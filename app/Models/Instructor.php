<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{

    public $timestamps = true;
    protected $table = "instructors";

    protected $fillable = [
    'name_en',
    'name_ar',
    'email',
    'phone',
    'message',
    'linkedIn',
    'facebook',
    'experince',
    'cv',
    'image',
    'status'
    ];
    protected $hidden = [
        'id',
    ];
}
