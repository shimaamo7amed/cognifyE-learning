<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());
        
        if ($user) {
            return apiResponse(true, [], __('messages.otp_success'));
        }
        return apiResponse(false, [], __('messages.otp_failed'));
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $user = $this->service->verifyEmail($request->validated());
        if ($user) {
            return apiResponse(true, $user, __('messages.register_success'));
        }
        return apiResponse(false, [], __('messages.otp_invalid'));
    }

    public function login(LoginRequest $request)
    {
        $result = $this->service->login($request->validated());
        if ($result) {
            return apiResponse(true, $result, __('messages.login_success'));
        }
        return apiResponse(false, [], __('messages.login_failed'));
    }

    public function logout(Request $request)
    {
        $result = $this->service->logout($request->user());
        if ($result) {
            return apiResponse(true, [], __('messages.logout_success'));
        }
        return apiResponse(false, [], __('messages.logout_failed'));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $result = $this->service->changePassword($request->validated());

        if (!$result) {
            return apiResponse(false, [], __('messages.change_password_failed'));
        }
        return apiResponse(true, [], __('messages.change_password_success'));
    }

    // public function forgetPassword(Request $request): JsonResponse
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email'
    //     ]);

    //     $otp = rand(100000, 999999);
    //     $user = AuthService::forgetPassword([
    //         'email' => $request->email,
    //         'otp' => $otp
    //     ]);

    //     if ($user) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'تم إرسال كود إعادة التعيين إلى بريدك الإلكتروني'
    //         ], 200);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'البريد الإلكتروني غير موجود'
    //     ], 404);
    // }

    // public function resetPassword(Request $request): JsonResponse
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'otp' => 'required|string|size:6',
    //         'password' => 'required|string|min:8|confirmed'
    //     ]);

    //     $user = AuthService::resetPassword($request->all());

    //     if ($user) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'تم تغيير كلمة المرور بنجاح'
    //         ], 200);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'البيانات غير صحيحة'
    //     ], 400);
    // }


    // public function me(Request $request): JsonResponse
    // {
    //     $userData = AuthService::getCurrentUser();

    //     if ($userData) {
    //         return response()->json([
    //             'success' => true,
    //             'data' => $userData
    //         ], 200);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'غير مصرح'
    //     ], 401);
    // }
}
