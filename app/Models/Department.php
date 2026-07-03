<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Mail;
use App\Models\MailDepartment;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{

protected $fillable = [
    'name',
    'code',
];


    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;
    use SoftDeletes, SoftDeletes;


    public function user() { return $this->hasMany(User::class); }
    public function mail() { return $this->hasMany(Mail::class); }

    public function mailDepartment() { return $this->hasMany(MailDepartment::class); }


}
      