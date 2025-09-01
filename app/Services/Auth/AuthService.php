<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Mail\SendOTP;
use Illuminate\Support\Str;

use App\Mail\VerifyCodeEmail;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class AuthService
{

    protected static function sendVerificationEmail($email, $otp, $name)
    {
        Mail::to($email)->send(new SendOTP($otp, $name, $email));
    }

    public static function register(array $data)
    {
        $data['otp'] = rand(100000, 999999);
        Cache::put('register_'.$data['email'], $data, now()->addMinutes(10));
        self::sendVerificationEmail($data['email'], $data['otp'], $data['name']);
        return true;
    }

    public static function verifyEmail(array $data)
    {
        $key = 'register_' . strtolower(trim($data['email']));
        $cachedData = Cache::get($key);
        if (!$cachedData) {
            return null;
        }
        if ((string)$cachedData['otp'] === (string)$data['otp']) {
            $existingUser = User::where('email', $cachedData['email'])->first();
            if ($existingUser) {
                $existingUser->tokens()->delete();
                Cache::forget($key);
                return $existingUser;
            }
            $cachedData['is_active'] = true;
            $user = User::create($cachedData);
            Cache::forget($key);
            return $user;
        }
        return null;
    }

    public static function login(array $data)
    {
        $field = filter_var($data['data'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($field, $data['data'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }
            $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->access_token = $token;
        $user->token_type = 'Bearer';
        return new UserResource($user);
    }

    public static function logout($user)
    {
        $user->currentAccessToken()->delete();
        return true;
    }

    public static function logoutFromAllDevices($user)
    {
        $user->tokens()->delete();
        return true;
    }


    public static function forgetPassword(array $data)
    {
        if (empty($data['email'])) {
            throw new \InvalidArgumentException('Email is required');
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            // ما نكشفش أن الإيميل مش موجود لأسباب أمان
            return true;
        }

        $otp = random_int(100000, 999999);
        $otpExpiresAt = now()->addMinutes(10);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
            'otp_attempts' => 0
        ]);

        try {
            self::sendVerificationEmail($user->email, $otp, $user->name);
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            throw new \RuntimeException('Failed to send reset code');
        }

        return true;
    }

    public static function validateOtp(array $data)
    {
        if (empty($data['email']) || empty($data['otp'])) {
            return ['status' => false, 'message' => __('messages.email_and_otp_required')];
        }

        $user = User::where('email', $data['email'])
                    ->where('otp', $data['otp'])
                    ->first();

        if (!$user) {
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                $user->increment('otp_attempts');
                if ($user->otp_attempts >= 10) {
                    $user->update(['otp' => null, 'otp_expires_at' => null]);
                }
            }
            return ['status' => false, 'message' => __('messages.invalid_otp')];
        }

        if ($user->otp_expires_at && $user->otp_expires_at->isPast()) {
            $user->update(['otp' => null, 'otp_expires_at' => null]);
            return ['status' => false, 'message' => __('messages.otp_expired')];
        }

        return ['status' => true, 'message' => __('messages.otp_valid')];
    }

    public static function resetPassword(array $data)
    {

        $user = User::where('email', $data['email'])
                    ->where('otp', $data['otp'])
                    ->first();

        if (!$user) {
            return [
                'status' => false,
                'message' => __('messages.invalid_otp')
            ];
        }

        if ($user->otp_expires_at && $user->otp_expires_at->isPast()) {
            $user->update(['otp' => null, 'otp_expires_at' => null]);
            return ['status' => false, 'message' => __('messages.otp_expired')];
        }

        $user->update([
            'password' => $data['password'],
            'otp' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0
        ]);

        $user->tokens()->delete();

        // try {
        //     Mail::to($user->email)->send(new \App\Mail\PasswordChangedNotification($user->name));
        // } catch (\Exception $e) {
        //     \Log::error('Failed to send password changed notification: ' . $e->getMessage());
        // }

        return ['status' => true, 'message' => __('messages.password_reset_success'), 'user' => $user];
    }


    public static function changePassword( array $data)
    {
        $user = auth()->user();
        if (!Hash::check($data['currentPassword'], $user->password)) {
            return false;
        }

        $user->update([
            'password' => $data['newPassword']
        ]);
        $currentToken = $user->currentAccessToken();
        $user->tokens()->where('id', '!=', $currentToken->id)->delete();

        return true;
    }
    
    public static function deleteMyAccount($slug)
    {
        $user = User::where('slug', $slug)->first();

        if ($user) {
            // حذف جميع الـ tokens قبل حذف المستخدم
            $user->tokens()->delete();
            
            // حذف المستخدم (soft delete إذا كان متاح)
            $user->delete();
            
            return true;
        }

        return false;
    }

    public static function checkPermission($user, $permission)
    {
        return $user->can($permission);
    }


    public static function checkRole($user, $role)
    {
        return $user->hasRole($role);
    }
    public static function getCurrentUser()
    {
        $user = auth('sanctum')->user();

        if ($user) {
            return [
                'user' => $user,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ];
        }

        return null;
    }

    public static function updateProfile($user, array $data)
    {
        unset($data['password'], $data['email'], $data['user_type']);

        $user->update($data);

        return $user->fresh();
    }

}