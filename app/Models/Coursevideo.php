<?php

namespace App\Models;

use App\Models\Course;
use App\Models\CourseModuleItem;
use Illuminate\Database\Eloquent\Model;

class Coursevideo extends Model
{
    public $timestamps = true;
    protected $table = 'coursevideos';
    protected $fillable = [
        'video_path',
        'title',
        'desc',
        'module_item_id',
        'course_id',
        'duration'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function moduleItem()
    {
        return $this->belongsTo(CourseModuleItem::class, 'module_item_id');
    }

}
