<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\Auth\GoogleServices;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle(): JsonResponse
    {
        try {
            $url = Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl();

            return response()->json([
                'success' => true,
                'message' => __('messages.google.redirect_success'),
                'data' => ['redirect_url' => $url]
            ]);

        } catch (\Throwable $th) {
            \Log::error('Google Redirect Error: ' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('messages.google.redirect_failed'),
                'error' => config('app.debug') ? $th->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function handleGoogleCallback(): JsonResponse
    {
        try {
            $data = GoogleServices::handleGoogleCallback();

            return response()->json([
                'success' => true,
                'message' => __('messages.google.login_success'),
                'data' => $data
            ]);

        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'errors' => $th->errors()
            ], 422);

        } catch (\Throwable $th) {
            \Log::error('Google Callback Error: ' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('messages.google.login_failed'),
                'error' => config('app.debug') ? $th->getMessage() : 'Authentication failed'
            ], 400);
        }
    }
}
