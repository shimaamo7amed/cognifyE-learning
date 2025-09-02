<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
    $data = [];

    if ($this->user_type === 'user') {
        $data = [
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'government' => $this->government,
            'country'    => $this->country,
            'gender'     => $this->gender,
            'userName'   => $this->userName,
            'image'      => asset('storage/' . $this->image),
            'role'       => $this->user_type,
        ];
    }

    if ($this->user_type === 'instructor') {
        $data = [
            'name_en'    => $this->name_en,
            'name_ar'    => $this->name_ar,
            'email'      => $this->email,
            'image'      => $this->image,
            'role'      => $this->user_type,
        ];

    }

    if (!empty($this->access_token)) {
        $data['token'] = $this->access_token;
        $data['token_type'] = $this->token_type ?? 'Bearer';
    }

    return $data;

}
}
