<?php

namespace App\Http\Requests\API\Auth;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.login.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
