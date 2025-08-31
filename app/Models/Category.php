<?php

namespace App\Models;

use App\Models\Course;
use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use SlugTrait;
    public $timestamps = true;
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'image',
    ];
    protected $casts = [
        'name' => 'array',
    ];
    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
