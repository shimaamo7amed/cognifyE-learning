<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'en');

        return [
            'id'   => $this->id,
            'name' => is_array($this->name)
                ? ($this->name[$lang] ?? $this->name['en'] ?? null)
                : $this->name,
        ];
    }
}
