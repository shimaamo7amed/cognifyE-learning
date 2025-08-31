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
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return null;
        }

        $otp = $data['otp'] ?? rand(100000, 999999);
        
        $user->update(['otp' => $otp]);
        
        Mail::to($user->email)->send(new ResetPasswordCode($otp, $user->name, $user->email));

        return $user;
    }

    public static function validateOtp(array $data)
    {
        $user = User::where('otp', $data['otp'])->first();
        
        return $user ? true : false;
    }

    public static function resetPassword(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if ($user && $user->otp == $data['otp']) {
            $user->update([
                'password' => Hash::make($data['password']),
                'otp' => null
            ]);
            
            // حذف جميع الـ tokens الحالية لإجبار المستخدم على تسجيل دخول جديد
            $user->tokens()->delete();
            
            return $user;
        }

        return null;
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