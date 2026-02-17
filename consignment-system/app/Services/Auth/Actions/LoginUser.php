<?php

namespace App\Services\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginUser
{
    public function handle(string $email, string $password): array
    {
        if (! $token = Auth::guard('api')->attempt(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }



        $user = User::where('email', $email)->first();

        $refresh_token = Str::random(64);
        $refresh_token_expiration_at = now()->addDays(30);

        $user->refresh_token = Hash::make($refresh_token);
        $user->refresh_token_expiration_at = $refresh_token_expiration_at;
        $user->last_login = now();


        $user->save();

        Cookie::queue(
            'refreshToken',
            $refresh_token,
            60 * 24 * 30,
            '/',
            null,
            false,
            false,
            false,
            'Strict'
        );

        return ['token' => $token];
    }
}
