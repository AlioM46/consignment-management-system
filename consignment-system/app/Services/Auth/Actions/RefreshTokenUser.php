<?php

namespace App\Services\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class RefreshTokenUser
{
    public function handle(string $userRefreshToken): array
    {
        $user = User::whereNotNull('refresh_token')->get()->first(function ($item) use ($userRefreshToken) {
            $storedToken = (string) $item->refresh_token;

            if ($storedToken === '') {
                return false;
            }

            if ($this->isBcryptHash($storedToken)) {
                try {
                    return Hash::check($userRefreshToken, $storedToken);
                } catch (\RuntimeException $e) {
                    return false;
                }
            }

            return hash_equals($userRefreshToken, $storedToken);
        });

        if (! $user || $user->refresh_token_expiration_at < now()) {
            return ['isSuccess' => false, 'message' => 'incorrect refresh token'];
        }

        $refreshToken = Str::random(64);
        $refresh_token_expiration_at = now()->addDays(30);

        $user->refresh_token = Hash::make($refreshToken);
        $user->refresh_token_expiration_at = $refresh_token_expiration_at;
        
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


        // Automatically login after registration
        $token = Auth::guard('api')->login($user);

        return [
            'isSuccess' => true,
            'token' => $token,
            'user' => $user,
        ];
    }

    private function isBcryptHash(string $value): bool
    {
        return str_starts_with($value, '$2y$')
            || str_starts_with($value, '$2a$')
            || str_starts_with($value, '$2b$');
    }
}
