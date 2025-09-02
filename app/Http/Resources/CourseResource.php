<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale() ?? 'en';
        $totalSeconds = $this->videos->sum(function ($video) {
            $timeParts = explode(':', $video->duration);

            if (count($timeParts) === 3) {
                return ($timeParts[0] * 3600) + ($timeParts[1] * 60) + $timeParts[2];
            } elseif (count($timeParts) === 2) {
                return ($timeParts[0] * 60) + $timeParts[1];
            }
            return 0;
        });
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);

        if ($locale === 'ar') {
            if ($hours > 0 && $minutes > 0) {
                $totalTime = "{$hours} ساعة و {$minutes} دقيقة";
            } elseif ($hours > 0) {
                $totalTime = "{$hours} ساعة";
            } elseif ($minutes > 0) {
                $totalTime = "{$minutes} دقيقة";
            } else {
                $totalTime = "0 دقيقة";
            }
        } else {
            if ($hours > 0 && $minutes > 0) {
                $totalTime = "{$hours} hr. {$minutes} min.";
            } elseif ($hours > 0) {
                $totalTime = "{$hours} hr.";
            } elseif ($minutes > 0) {
                $totalTime = "{$minutes} min.";
            } else {
                $totalTime = "0 min.";
            }
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name[$locale] ?? $this->name['en'],
            'desc' => $this->desc[$locale] ?? $this->desc['en'],
            'price' => $this->price,
            'old_price' => $this->old_price,
            'discount_percentage' => $this->discount_percentage,
            'sale'=>$this->sale,
            'delivery_method' => $this->delivery_method,
            'payment_status' => $this->payment_status,
            'image' => asset('storage/' . $this->image),
            'category' => $this->category
                ? [
                    'name' => $locale === 'ar'
                        ? ($this->category->name['ar'] ?? null) 
                        : ($this->category->name['en'] ?? null),
                    'slug' => $this->category->slug ?? null,
                ]
                : null,

            'tags' => $this->tags->map(function($tag) use ($locale) {
                    return [
                        'name' => $locale === 'ar' ? ($tag->name['ar'] ?? '') : ($tag->name['en'] ?? ''),
                    ];
            }),
            'instructor' => $this->instructor
            ? [
                'name' => $locale === 'ar'
                    ? $this->instructor->name_ar
                    : $this->instructor->name_en,
                'slug' => $this->instructor->slug,
            ]
            : null,

            'lecture' => $this->videos->count(),
            'time' => $totalTime,
        ];
    }
}
