<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    public $timestamps = true;
    protected $table = 'tags';
    protected $fillable = [
        'name',
    ];
    protected $casts = [
        'name' => 'array',
    ];
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_tag', 'tag_id', 'course_id');
    }
}
