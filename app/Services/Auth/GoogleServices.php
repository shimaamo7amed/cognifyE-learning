<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

class GoogleServices
{
    public static function handleGoogleCallback(): array
    {
        try {
            $googleUser = self::getGoogleUser();
            self::validateGoogleUser($googleUser);
            $user = self::findOrCreateUser($googleUser);
            $token = self::generateToken($user);

            return self::formatResponse($token, $user);
        } catch (\Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected static function getGoogleUser()
    {
        try {
            return Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            Log::error('Google OAuth Failed: ' . $e->getMessage());
            throw new \Exception(__('messages.google.callback_failed'), 500);
        }
    }

    protected static function validateGoogleUser($googleUser): void
    {
        if (!$googleUser) {
            throw ValidationException::withMessages([
                'google' => __('messages.google.user_data_not_found')
            ]);
        }

        if (empty($googleUser->email)) {
            throw ValidationException::withMessages([
                'email' => __('messages.google.email_not_found')
            ]);
        }

        if (empty($googleUser->id)) {
            throw ValidationException::withMessages([
                'google_id' => __('messages.google.google_id_not_found')
            ]);
        }
    }

    protected static function findOrCreateUser($googleUser): User
    {
        $user = User::where('social_id', $googleUser->id)
                    ->where('social_type', 'Google')
                    ->first();

        if ($user) {
            return $user;
        }
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            self::updateUserWithSocialData($user, $googleUser);
            return $user;
        }
        return self::createNewUser($googleUser);
    }

    protected static function updateUserWithSocialData(User $user, $googleUser): void
    {
        $updateData = [];

        if (empty($user->social_id)) {
            $updateData['social_id'] = $googleUser->id;
        }

        if (empty($user->social_type)) {
            $updateData['social_type'] = 'Google';
        }

        // if (empty($user->image) && !empty($googleUser->avatar)) {
        //     $updateData['avatar'] = $googleUser->avatar;
        // }

        if (empty($user->email_verified_at)) {
            $updateData['email_verified_at'] = now();
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }
    }

    protected static function createNewUser($googleUser): User
    {
        $username = self::generateUniqueUsername(
            $googleUser->name ?? $googleUser->email
        );

        return User::create([
            'name' => $googleUser->name ?? 'Google User',
            'userName' => $username,
            'email' => $googleUser->email,
            'email_verified_at' => now(),
            'social_id' => $googleUser->id,
            'social_type' => 'Google',
            // 'image' => $googleUser->avatar ?? null,
            'password' => Hash::make(Str::random(40)),
        ]);
    }

    protected static function generateUniqueUsername(string $source): string
    {
        $baseUsername = Str::slug(Str::lower($source), '');

        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }

        $username = $baseUsername;
        $counter = 1;

        while (User::where('userName', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;

            // Safety check to prevent infinite loop
            if ($counter > 100) {
                $username = $baseUsername . Str::random(8);
                break;
            }
        }

        return $username;
    }

    protected static function generateToken(User $user): string
    {
        // Revoke existing tokens
        $user->tokens()->delete();

        Auth::login($user);

        return $user->createToken(
            'google_oauth_token',
            ['*'],
            now()->addDays(30)
        )->plainTextToken;
    }

    protected static function formatResponse(string $token, User $user): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => now()->addDays(30)->toISOString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'userName' => $user->userName,
                'avatar' => $user->avatar,
                'created_at' => $user->created_at->toISOString(),
            ],
        ];
    }
}
