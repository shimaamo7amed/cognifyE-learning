<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Forms\InstructorsService;
use App\Http\Resources\InstructorsResource;
use App\Http\Requests\Forms\InstructorRequest;

class InstructorController extends Controller
{
    protected $instructorService;

    public function __construct(InstructorsService $instructorService)
    {
        $this->instructorService = $instructorService;
    }

    public function instructor(InstructorRequest $request)
    {
        $instructor = $this->instructorService->InstructorsForm($request->validated());
        if ($instructor) {
            return apiResponse(true, [], __('messages.request_received'));
        }

        return apiResponse(false,[],__('messages.request_error'));
    }
    public function getInstructors()
    {
        $instructors = $this->instructorService->getInstructors();
        if (!$instructors) {
            return apiResponse(false, [], __('messages.instructors_failed'));
        }

        return apiResponse(true, [
            'instructors' => $instructors->map(function($instructor) {
                return new InstructorsResource($instructor, false);
            })
        ], __('messages.instructors_success'));
    }


    public function getInstructor($slug)
    {
        $instructor = $this->instructorService->getInstructorBySlug($slug);
        if (!$instructor) {
            return apiResponse(false, [], __('messages.instructor_failed'));
        }

        return apiResponse(true, [
            'instructor' => new InstructorsResource($instructor, true)
        ], __('messages.instructor_success'));
    }

}
