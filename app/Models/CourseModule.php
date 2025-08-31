<?php

namespace App\Models;

use App\Models\Course;
use App\Models\CourseModuleItem;
use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    protected $table = "course_modules";
    public $timestamps = true;

    protected $fillable = [
        'code',
        'title',
        'course_id',
    ];
    protected $casts =
    [
    'title' => 'array',
    ];
    public function course()
    {
    return $this->belongsTo(Course::class, 'course_id');
    }
    public function moduleItem()
    {
    return $this->hasMany(CourseModuleItem::class, 'module_id');
    }

}
