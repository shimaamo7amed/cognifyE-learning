<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function allCategories()
    {
        $categories = Category::all();
        if (!$categories) {
            return apiResponse(null, [], __('messages.category_not_found'));
        }

        return apiResponse(true, CategoryResource::collection($categories), __('messages.category_retrieved_successfully'));
    }
}
