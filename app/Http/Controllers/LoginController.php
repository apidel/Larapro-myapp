<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Http\Request;
use App\Services\EmailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            return view('auth.forgot_password');
        }

}

