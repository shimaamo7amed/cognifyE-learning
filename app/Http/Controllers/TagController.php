<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function allTags()
    {
        $tags = Tags::all();
        if (!$tags) {
            return apiResponse(null, [], __('messages.tag_not_found'));
        }

        return apiResponse(true, TagResource::collection($tags), __('messages.tag_retrieved_successfully'));
    }


}

