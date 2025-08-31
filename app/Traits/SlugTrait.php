<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SlugTrait
{
    public static function bootSlugTrait()
    {
        static::saving(function ($model) {
            $nameEn = is_array($model->name) ? ($model->name['en'] ?? '') : $model->name;

            $model->slug = Str::slug($nameEn);
        });
    }
}
