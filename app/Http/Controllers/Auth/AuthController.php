<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ValidateOTPRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\UpdateProfilePhotoRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgetPasswordRequest;

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

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $this->service->forgetPassword($request->validated());
        return apiResponse(true, [], __('messages.otp_sent'));
    }


    public function validateOtp(ValidateOTPRequest $request)
    {
        $result = $this->service->validateOtp($request->validated());
        return apiResponse($result['status'], $result['data'] ?? [], $result['message']);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->service->resetPassword($request->validated());
        return apiResponse($result['status'], $result['data'] ?? [], $result['message']);
    }
    public function updateProfile(UpdateProfileRequest $request)
    {

        $result = $this->service->updateProfile($request->validated());
        if ($result === null) {
            return apiResponse(false, [], __('messages.user_not_authenticated'));
        }

        if ($result === false) {
            return apiResponse(false, [], __('messages.access_denied'));
        }

        return apiResponse(true, $result, __('messages.profile_update_success'));
    }
    public function changeImage(UpdateProfilePhotoRequest $request)
    {
        $result = $this->service->changeImage($request->validated());
        if ($result === null) {
            return apiResponse(false, [], __('messages.user_not_authenticated'));
        }

        if ($result === false) {
            return apiResponse(false, [], __('messages.access_denied'));
        }

        return apiResponse(true, $result, __('messages.profile_image_update_success'));
    }


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
