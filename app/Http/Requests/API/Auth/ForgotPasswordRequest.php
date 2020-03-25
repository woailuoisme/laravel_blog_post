<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class ForgotPasswordRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.forgot_password.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
