<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class UserStatus extends Model
{
    public function User() { return $this->hasMany(User::class); }
}
