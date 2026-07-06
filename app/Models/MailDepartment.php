<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Mail;

class MailDepartment extends Model
{
    public function department() { return $this->belongsTo(Department::class); }
    public function mail() { return $this->belongsTo(Mail::class); }
}
