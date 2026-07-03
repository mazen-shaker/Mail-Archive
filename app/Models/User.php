<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Department;
use App\Models\UserStatus;
use App\Models\Mail;
use App\Models\Sign;
use Illuminate\Database\Eloquent\SoftDeletes;


#[Fillable(['name', 'email', 'password',

    'role_id',  
    'department_id',
    'user_status_id',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role() { return $this->belongsTo(Role::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function status() { return $this->belongsTo(UserStatus::class, 'user_status_id'); }
    public function mail() { return $this->hasMany(Mail::class); }
    public function sign() { return $this->hasMany(Sign::class); }


}
