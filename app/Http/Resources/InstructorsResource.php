<?php

namespace App\Http\Resources;

use App\Http\Resources\CourseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorsResource extends JsonResource
{
    protected $detailed;

    public function __construct($resource, $detailed = false)
    {
        parent::__construct($resource);
        $this->detailed = $detailed;
    }

    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'en');

        $data = [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'name'        => $lang === 'ar' ? $this->name_ar : $this->name_en,
            'description' => $lang === 'ar'
                ? ($this->desc['ar'] ?? null)
                : ($this->desc['en'] ?? null),
            'image'       => $this->image,
        ];

        if ($this->detailed) {
            $data['facebook'] = $this->facebook;
            $data['linkedIn'] = $this->linkedIn;
            $data['courses'] = CourseResource::collection($this->courses);

        }

        return $data;
    }
}
