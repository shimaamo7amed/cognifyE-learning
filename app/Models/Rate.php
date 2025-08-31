<?php

namespace App\Models;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public $timestamps = false;
    protected $table = 'rates';
    protected $fillable = [
        'course_id', 'user_id', 'rate', 'review'
    ];


    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
