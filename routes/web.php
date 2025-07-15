<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'home' )->name('app_home');
    Route::get('/about', 'about' )->name('app_about');
    Route::match(['get', 'post'], '/dashboard', 'dashboard' )
    ->middleware('auth')//on accède uniquement à cette page lorsqu'on est authentifié
    ->name('app_dashboard');
});

Route::controller(LoginController::class)->group(function(){
    Route::get('/logout', 'logout')->name('app_logout');

    Route::post('/exist_email', 'existEmail')->name('app_exist_email');

    Route::match(['get', 'post'], '/activation_code/{token}', 'activationCode')->name('app_activation_code');

    Route::get('/user_checker', 'userChecker')->name('app_user_checker');

    Route::get('/resend_activation_code/{token}', 'resendActivationCode')->name('app_resend_activation_code');

    Route::match(['get', 'post'], '/change_email_address/{token}', 'changeEmailAddress')->name('app_change_email_address');

    Route::get('/activation_account_link/{token}', 'activationAccountLink')->name('app_activation_account_link');

    Route::match(['get', 'post'], '/forgot_password', 'forgotPassword')->name('app_forgot_password');


});














/*Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])
->name('app_login');

Route::match(['get', 'post'], '/register', [LoginController::class, 'register'])
->name('app_register');*/
