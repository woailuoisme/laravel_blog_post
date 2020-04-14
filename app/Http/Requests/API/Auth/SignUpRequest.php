<?php

namespace App\Http\Requests\API\Auth;


use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class SignUpRequest extends FormRequest
{
    public function rules()
    {

        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
