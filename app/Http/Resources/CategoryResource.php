<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'en');

        return [
            'id'   => $this->id,
            'slug' => $this->slug,
            'name' => is_array($this->name)
                ? ($this->name[$lang] ?? $this->name['en'] ?? null)
                : $this->name,
        ];
    }
}
