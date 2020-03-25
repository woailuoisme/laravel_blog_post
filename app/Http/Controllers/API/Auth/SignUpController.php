<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\Auth\SignUpRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
//        User::where('email',$request->input('email'));
        $user = new User($request->all());
        if(!$user->save()) {
            throw new HttpException(500);
        }
//        if(!Config::get('boilerplate.sign_up.release_token')) {
//            return response()->json([
//                'status' => 'ok'
//            ], 201);
//        }
        $token = $JWTAuth->fromUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token,
            'expires_in' =>auth('api')->factory()->getTTL() * 60
        ], 201);
    }
}