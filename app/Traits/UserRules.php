<?php


namespace App\Traits;


trait UserRules
{

    public static array $resetPasswordRules = [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed'
    ];

    public static array $forgetPasswordRules = [
        'email' => 'required|email'
    ];

    public static array $loginRules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    public static array $signUpRules = [
        'name' => 'required|unique:users',
        'email' => 'required|unique:users',
        'password' => 'required'
    ];



}
