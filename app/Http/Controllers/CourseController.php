<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;
use App\Services\Courses\CoursesServices;
use App\Http\Resources\CourseDetailsResource;

class CourseController extends Controller
{
    protected $courseService;
    public function __construct(CoursesServices $courseService)
    {
        $this->courseService = $courseService;
    }

    public function allCourses()
    {
        $courses = $this->courseService::GetAllCourses(10);

        if (!$courses) {
            return apiResponse(false, __('messages.no_courses_found'), null);
        }

        return apiResponse(true, CourseResource::collection($courses), __('messages.courses_retrieved_successfully'));
    }
    public function courseBySlug($slug)
    {
        $course = $this->courseService::GetBySlug($slug);

        if (!$course) {
            return apiResponse(false, __('messages.course_not_found'), null);
        }

        return apiResponse(true, new CourseDetailsResource($course), __('messages.course_retrieved_successfully'));
    }

}
