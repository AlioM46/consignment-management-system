<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\Actions\LoginUser;
use App\Services\Auth\Actions\RefreshTokenUser;
use App\Services\Auth\Actions\RegisterUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    public function register(RegisterRequest $request, RegisterUser $action)
    {
        $result = $action->handle($request->validated());

        return response()->json([
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
            ],
        ], 201);
    }

    public function login(LoginRequest $request, LoginUser $action)
    {
        $data = $request->validated();
        $result = $action->handle($data['email'], $data['password']);

        return response()->json([
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
        ]);
    }

    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    public function logout()
    {
        $userId = Auth::guard('api')->id();

        if ($userId) {
            User::whereKey($userId)->update([
                'refresh_token' => null,
                'refresh_token_expiration_at' => null,
            ]);
        }

        Cookie::queue(Cookie::forget('refreshToken'));
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Logged out']);
    }

    public function refresh(RefreshTokenUser $action)
    {
        $refreshToken = request()->cookie('refreshToken');

        if (! $refreshToken) {
            return response()->json([
                'isSuccess' => false,
                'information' => 'No refresh token'
            ], 401);
        }

        $result = $action->handle($refreshToken);

        if (! ($result['isSuccess'] ?? false)) {
            Cookie::queue(Cookie::forget('refreshToken'));
            return response()->json([
                'isSuccess' => false,
                'information' => $result['message'] ?? 'Invalid refresh token'
            ], 401);
        }

        return response()->json([
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'expires_in' => (int) config('jwt.ttl')  * 60,
        ]);
    }
}
