<?php

namespace App\Models;

use App\Models\Rate;
use App\Models\Tags;
use App\Models\User;
use App\Models\Category;
use App\Traits\SlugTrait;
use App\Models\Coursevideo;
use App\Models\CourseModule;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
        use SlugTrait;

    public $timestamps = true;
    protected $table = 'courses';
    protected $fillable = [
        'name',
        'desc',
        'image',
        'main_video',
        'free_video',
        'price',
        'old_price',
        'discount_percentage',
        'sale',
        'payment_status',
        'delivery_method',
        'course_goals',
        'target_audience',
        'learning_format',
        'instructor_id',
        'category_id',
    ];
    protected $casts = [
        'name' => 'array',
        'desc' => 'array',
        'course_goals' => 'array',
        'target_audience' => 'array',
        'learning_format' => 'array',
        'sale' => 'boolean',
        'old_price' => 'decimal:2',
        'price' => 'decimal:2',
        'discount_percentage' => 'integer',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    public function modules()
    {
        return $this->hasMany(CourseModule::class, 'course_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'course_tag', 'course_id', 'tag_id');
    }
    public function videos()
    {
        return $this->hasManyThrough(
            CourseModuleItem::class, // الفيديوهات موجودة هنا
            CourseModule::class,     // الموديولات الوسيطة
            'course_id',             // مفتاح خارجي في جدول الموديولات
            'module_id',             // مفتاح خارجي في جدول module_items
            'id',                    // المفتاح الأساسي للكورس
            'id'                     // المفتاح الأساسي للموديول
        );
    }

    public function rates()
    {
        return $this->hasMany(Rate::class, 'course_id');
    }

    // public function promoCodes()
    // {
    //     return $this->belongsToMany(PromoCodesM::class, 'course_promo_code');
    // }
   
}
