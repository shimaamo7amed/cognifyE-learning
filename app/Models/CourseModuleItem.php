<?php

namespace App\Models;

use App\Models\Coursevideo;
use App\Models\CourseModule;
use Illuminate\Database\Eloquent\Model;

class CourseModuleItem extends Model
{
    public $timsestamps = true;
    protected $table = 'course_module_items';
    protected $fillable = [
        'content','module_id','video_path','duration'
    ];

    protected $casts = [
        'content' => 'array',
        'duration' => 'string'
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }
    // public function video()
    // {
    //     return $this->hasOne(Coursevideo::class, 'module_item_id');
    // }

}
