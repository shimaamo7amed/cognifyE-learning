<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    public $timestamps = true;
    protected $table = 'contact_us';
    protected $fillable = ['fullName', 'email', 'phone', 'message'];
}
