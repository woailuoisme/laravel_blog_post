<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\Auth\ForgotPasswordRequest;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\ResetPasswordRequest;
use App\Http\Requests\API\Auth\SignUpRequest;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
Use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;

/**
 * @group Auth management
 * APIs for 用户认证管理
 */
class AuthController extends AppBaseController
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['me', 'refresh']);
    }

    /**
     * signin
     * 用户登录
     * @bodyParam email string required
     * @bodyParam password string required
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            $this->sendError('Unauthorized', 401);
        }
        return $this->sendResponse($this->_tokenData($token),
            'User login successfully');
    }

    /**
     * me
     * 获取用户信息
     * @authenticated  认证类型 bearer
     *
     * @response {
     *  "id": 4,
     *  "name": "Jessica Jones",
     *  "roles": ["admin"]
     * }
     *
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
//        dd(auth('api')->user()->profile);
        return $this->sendResponse(new UserResource(auth('api')->user()), '');
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return $this->sendSuccess('Successfully logged out');
    }

    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth): JsonResponse
    {
        $user = new User($request->only(['name', 'email', 'password']));
        if (!$user->save()) {
            throw new HttpException(500);
        }
        $token = $JWTAuth->fromUser($user);
        return $this->sendResponse($this->_tokenData($token), 201);

    }

    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();
        \Tymon\JWTAuth\Facades\JWTAuth::factory()->getDefaultClaims();
        return $this->sendResponse($this->_tokenData($token),
            'User token successfully', 201);
    }

    public function forgetPassword(ForgotPasswordRequest $request): JsonResponse
    {

        $email = $request->input('email');
        $user =User::where('email', $email)->get()->first();
        if (!$user) {
            throw new ModelNotFoundException('user not found');
        }
        $user->password_token = Uuid::uuid4()->toString();
        $user->password_token_expired_at = Carbon::now()->addHours(1);
        $user->save();
        return $this->sendResponse(['token' => $user->password_token], 'reset_token sent');
    }

    public function resetPassword(ResetPasswordRequest $request): ?JsonResponse
    {
        $reset_token = $request->input('token');
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('password_token', $reset_token)
            ->where('email', $email)->get()->first();

        if (!$user) {
            throw new ModelNotFoundException('user not found');
        }
        $now = Carbon::now();
        if (Carbon::now()->isBefore($user->reset_token_expired_at)) {
            return $this->sendError('reset_token has been expired');
        }
        $user->password = $password;
        $user->password_token = null;
        $user->password_token_expired_at = null;
        $user->save();
        $token = auth('api')->login($user);
        return $this->sendResponse($this->_tokenData($token));

    }


    private function _tokenData($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer ',
            'expires_in' => auth('api')->factory()->getTTL() . ' minutes',
            'create_at' => Carbon::createFromTimestamp(auth('api')->getClaim('iat'))->toDateTimeString(),
            'expires_at' => Carbon::createFromTimestamp(auth('api')->getClaim('exp'))->toDateTimeString()
//            'expires' => \Tymon\JWTAuth\Facades\JWTAuth::getClaim('exp')
        ];
    }
}
