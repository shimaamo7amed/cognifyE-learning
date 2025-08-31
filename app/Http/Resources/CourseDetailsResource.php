<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'en');

        return [
            'id'                => $this->id,
            'slug'              => $this->slug,
            'name'              => $this->name[$lang] ?? $this->name['en'],
            'desc'              => $this->desc[$lang] ?? $this->desc['en'],
            'image'             => $this->image,
            'main_video'        => $this->main_video,
            'free_video'        => $this->free_video,
            'price'             => $this->price,
            'old_price'         => $this->old_price,
            'discount_percentage' => $this->discount_percentage,
            'sale'              => $this->sale,
            'payment_status'    => $this->payment_status,
            'delivery_method'   => $this->delivery_method,

            'course_goals' => $this->course_goals
                ? collect($this->course_goals)->map(function ($goal) use ($lang) {
                    return $goal[$lang] ?? $goal['en'];
                })
                : [],

            'target_audience' => $this->target_audience
                ? collect($this->target_audience)->map(function ($audience) use ($lang) {
                    return $audience[$lang] ?? $audience['en'];
                })
                : [],

            'category' => $this->category
                ? [
                    'id'   => $this->category->id,
                    'name' => $this->category->name[$lang] ?? $this->category->name['en'],
                    'slug' => $this->category->slug
                ]
                : null,

            'tags' => $this->tags
                ? $this->tags->map(function ($tag) use ($lang) {
                    return [
                        'id'   => $tag->id,
                        'name' => $tag->name[$lang] ?? $tag->name['en'],
                    ];
                })
                : [],

            'instructor' => $this->instructor
                ? [
                    'slug'  => $this->instructor->slug,
                    'name'  => $lang === 'ar' ? $this->instructor->name_ar : $this->instructor->name_en,
                    'desc'  => $this->instructor->desc[$lang] ?? $this->instructor->desc['en'],
                    'image' => $this->instructor->image,
                ]
                : null,

            'modules' => $this->modules
            ? $this->modules->map(function ($module) use ($lang) {
                return [
                    'id'          => $module->id,
                    'title'       => $module->title[$lang] ?? $module->title['en'],
                    'module_item' => $module->moduleItem
                        ? $module->moduleItem->map(function ($item) use ($lang) {
                            return [
                                'id'        => $item->id,
                                'content'   => $item->content[$lang] ?? $item->content['en'],
                            ];
                        })
                        : [],
                ];
            })
            : [],

        ];
    }
}
