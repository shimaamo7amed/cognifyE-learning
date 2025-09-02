<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\InstructorController;

// Auth
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('verify', 'verifyEmail');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::get('user', 'user')->middleware('auth:sanctum');
    Route::post('change-password', 'changePassword')->middleware('auth:sanctum');
    Route::post('forget-password', 'forgetPassword');
    Route::post('validate-otp', 'validateOtp');
    Route::post('reset-password', 'resetPassword');
    Route::post('edit-personal-information', 'updateProfile')->middleware('auth:sanctum');
    Route::post('edit-profile-photo', 'changeImage')->middleware('auth:sanctum');
});


// social
Route::prefix('auth/google')->name('auth.google.')->group(function () {
    Route::get('redirect', [SocialController::class, 'redirectToGoogle'])->name('redirect');
    Route::get('callback', [SocialController::class, 'handleGoogleCallback'])->name('callback');
});

// instructors
Route::controller(InstructorController::class)->group(function(){
    Route::post('instructor','instructor' );
    Route::get('/instructors','getInstructors');
    Route::get('/instructor/{slug}','getInstructor');
});

// Forms

Route::post('/contact-us',[ContactUsController::class,'contactUs']);

// Categories
Route::get('/categories',[CategoryController::class,'allCategories']);

// Tags
Route::get('/tags',[TagController::class,'allTags']);


Route::controller(CourseController::class)->group(function(){
    Route::get('/courses','allCourses');
    Route::get('/course/{slug}','courseBySlug');
});

