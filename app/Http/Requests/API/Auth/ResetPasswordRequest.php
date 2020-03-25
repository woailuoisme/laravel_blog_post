<?php

namespace App\Http\Requests\API\Auth;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class ResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.reset_password.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
