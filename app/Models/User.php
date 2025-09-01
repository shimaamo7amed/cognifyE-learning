<?php
namespace App\Models;
use Filament\Panel;
use App\Models\Rate;
use App\Models\Course;
use App\Traits\SlugTrait;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles , SlugTrait;

    public $timestamps = true;
    protected $table = 'users';
    protected $fillable = [
        'name',
        'userName',
        'email',
        'password',
        'user_type',
        'is_active',
        'phone',
        'image',
        'otp',
        'gender',
        'country',
        'government',
        'social_id',
        'social_type',
        'name_en',
        'name_ar',
        'desc',
        'linkedIn',
        'facebook',
        'website',
        'otp_expires_at',
        'otp_attempts',
        'experience',
        'cv',
        'status',
    ];
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'otp',
        'otp_attempts',
        'otp_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'otp_attempts' => 'integer',
            'desc' => 'array',
        ];
    }
    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }


    public function rates()
    {
        return $this->hasMany(Rate::class, 'user_id');
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->user_type === 'admin' || $this->hasRole('admin');
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin' || $this->hasRole('admin');
    }
    public function isInstructor(): bool
    {
        return $this->user_type === 'instructor' || $this->hasRole('instructor');
    }
    public function isUser(): bool
    {
        return $this->user_type === 'user' || $this->hasRole('user');
    }
}