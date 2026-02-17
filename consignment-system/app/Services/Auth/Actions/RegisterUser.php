<?php

namespace App\Services\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;


class RegisterUser
{
    public function handle(array $data): array
    {
        $refreshToken = Str::random(64);

        $refresh_token = Hash::make($refreshToken);
        $refresh_token_expiration_at = now()->addDays(30);

        Cookie::queue(
            'refreshToken',
            $refreshToken,
            60 * 24 * 30,
            '/',
            null,
            false,
            false,
            false,
            'Strict'
        );
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'refresh_token' => $refresh_token,
            'refresh_token_expiration_at' => $refresh_token_expiration_at
        ]);

        // Automatically login after registration
        $token = Auth::guard('api')->login($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
