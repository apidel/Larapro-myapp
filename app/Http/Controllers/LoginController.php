<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Http\Request;
use App\Services\EmailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
   /* public function login()
    {
        return view('auth.login');
    }

     public function register()
    {
        return view('auth.register');
    }*/
    protected $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');

    }

//vérifier que le mail existe avant de créer un autre utilisateur
    public function existEmail()
    {
        $email = $this->request->input('email');

        $user = User::where('email', $email)
                    ->first();

        $response = "";
        ($user) ? $response = "exist" : $response = "not_exist";//condition ternaire comme le if...else
        return response()->json([
            'code' => 200,
            'response' => $response
        ]);
    }

    public function activationCode($token)
    {
        $user = User::where('activation_token', $token)->first();

        if(!$user)
        {
            return redirect()->route('login')->with('danger', 'This token doen\'t not match any user\'s');
        }

        if($this->request->isMethod('post'))
        {

            $code = $user->activation_code;

            $activation_code = $this->request->input('activation_code');

            if ($activation_code != $code)
                {
                    return back()->with([
                        'danger' => 'This activation code is invalid!',
                        'activation_code' => $activation_code
                    ]);
                }
            else
                {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'is_verified' => 1,
                            'activation_code' => "",
                            'activation_token' =>"",
                            'email_verified_at' => new DateTimeImmutable,
                            'updated_at' => new DateTimeImmutable()
                        ]);

                    return redirect()->route('login')->with ('success', 'Your email address has been verified');
                }


        }
        return view('auth.activation_code', [
            'token'=>$token
        ]);
    }
/**
 * vérifier si l'utilisateur a déjà activé son compte
 * ou pas avant de s'authentifier
 */
    public function userChecker()
    {
        $activation_token = auth::user()->activation_token;
        $is_verified = Auth::user()->is_verified;

        if($is_verified != 1)
            {
                Auth::logout();
                return redirect()->route('app_activation_code', ['token'=>$activation_token])
                                ->with('warning', 'your account is not activated yet. Please check your mail-box
                                and activate your account or resend the confirmation message.');
            }
        else
            {
            return redirect()->route('app_dashboard');
            }

    }

    public function resendActivationCode($token)
    {
        $user = User::where('activation_token', $token)->first();
        $email = $user->email;
        $name = $user->name;
        $activation_token = $user->activation_token;
        $activation_code = $user->activation_code;

         $emailsend = new EmailService;
        $subject ="Activate your account";
        $message = view('mail.confirmation_email')
                        ->with([
                            'name'=>$name,
                            'activation_code'=>$activation_code,
                            'activation_token'=>$activation_token,
                        ]);
        $emailsend ->sendEmail($subject, $email, $name, true, $message);

        return redirect()->route('app_activation_code',
                ['token'=>$token])
                 ->with('success', 'We have just sent a new activation code');
    }

    public function activationAccountLink($token)
    {
        $user = User::where('activation_token', $token)->first();

        if(!$user)
        {
            return redirect()->route('login')->with('danger', 'This token doen\'t not match any user\'s');
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
            'is_verified' => 1,
            'activation_code' => "",
            'activation_token' =>"",
            'email_verified_at' => new DateTimeImmutable,
            'updated_at' => new DateTimeImmutable
            ]);

        return redirect()->route('login')->with ('success', 'Your email address has been verified');

    }

    public function changeEmailAddress($token)
    {
        $user = User::where('activation_token', $token)->first();


        if ($this->request->isMethod('post'))
            {
               $new_email = $this->request->input('new-email');
               $user_exist = User::where('email', $new_email)->first();

               if ($user_exist)
                {
                    return back()->with([
                        'danger' => 'This email is already used. Please enter another email address!',
                        'new_email' => $new_email
                    ]);
                }
               else
                {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'email' => $new_email,
                            'updated_at' => new DateTimeImmutable
                        ]);

                    $activation_code = $user->activation_code;
                    $activation_token = $user->activation_token;
                    $name =$user->name;

                    $emailsend = new EmailService;
                    $subject ="Activate your account";
                    $message = view('mail.confirmation_email')
                        ->with([
                            'name'=>$name,
                            'activation_code'=>$activation_code,
                            'activation_token'=>$activation_token,
                        ]);
        $emailsend ->sendEmail($subject, $new_email, $name, true, $message);

        return redirect()->route('app_activation_code',
                ['token'=>$token])
                 ->with('success', 'We have just sent a new activation code');
                }


            }
        else
            {
                return view('auth.change_email_address',[
                'token' => $token
                ]);
            }
        }

        public function forgotPassword()
        {
            //si la requête est de type post
            if ($this->request->isMethod('post'))
                {
                    $email = $this->request->input('email-send');
                    $message = null;
                    $user = DB::table('users')->where('email', $email)->first();

                    if ($user)
                        {
                        $full_name = $user->name;
                        //on va générer un token pour la réinitialisation du mot de passe de l'utilisateur
                        $activation_token = md5(uniqid()) . $email . sha1($email);
                        $emailresetpwd = new EmailService;
                        $subject = "Reset your password";
                        $emailresetpwd->resetPassword($subject, $email, $full_name, true, $activation_token);

                        DB::table('users')
                            ->where('email', $email)
                            ->update(['activation_token' => $activation_token]);

                        $message = "we have successfully sent the request to reset your password. please check your email box";
                        return back()->withErrors([
                            'email-success'=> $message
                        ])
                                    ->with('old_email', $email)
                                    ->with('success', $message);
                        }
                    else
                        {
                            $message = 'the email address you enterred doesn\'t exists!';
                        return back()->withErrors([
                            'email-error'=> $message
                        ])
                                    ->with('old_email', $email)
                                    ->with('danger', $message);
                        }

                }

            return view('auth.forgot_password');
        }

        public function changePassword($token)
        {
            if ($this->request->isMethod('post'))
                {
                $new_password = $this->request->input('new-password');
                $new_password_confirm = $this->request->input('new-password-confirm');
                $password_lenght = strlen($new_password);
                $message = null;
                    if ($password_lenght >= 8)
                        {
                        $message = "Your password must be identical!";
                        if ($new_password == $new_password_confirm)
                        {
                            $user = DB::table('users')->where('Activation_token', $token)->first();

                            if ($user)
                            {
                                $id_user = $user->id;
                                DB::table('users')
                            ->where('id', $id_user)
                            ->update([
                                'password' => Hash::make($new_password),
                                'updated_at' => new DateTimeImmutable(),
                                'activation_token' => "",
                            ]);
                            $message = "Your pasword has successfully changed";
                            return redirect()->route('login')
                                            ->with('success', $message);
                            }
                            else
                            {
                                return back()->with('danger', 'this token doesn\'t match any token');
                            }
                                                    }
                        else
                        {
                            return back()->withErrors([
                                'password-confirm-error' =>$message,
                                'password-success' =>"success"
                            ])
                                ->with('old_password_confirm', $new_password_confirm)
                                ->with('danger', $message)
                                ->with('old_new_password', $new_password);
                        }


                        }
                    else
                        {
                            $message = "Your password must be at least 8 characters";
                            return back()->withErrors([
                                'password-error' =>$message,
                            ])
                                ->with('old_new_password', $new_password)
                                ->with('danger', $message);
                        }


                }
                 else
                {
                # code...
                }

            return view('auth.change_password', [
                'activation_token'=> $token
            ]);
        }
}

