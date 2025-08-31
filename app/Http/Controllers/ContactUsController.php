<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Forms\ContactUsServices;
use App\Http\Requests\Forms\ContactUsRequest;

class ContactUsController extends Controller
{
    public function contactUs(ContactUsRequest $request)
    {
        $data = ContactUsServices::ContactUsForm($request->validated());
        if (!$data) {
            return apiResponse(false, [], __('messages.contactus.failed'));
        }
        return apiResponse(true, [], __('messages.contactus.success'));

    }
}
