<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Mail;

class Inbox extends Model
{

protected $fillable = [
    'mail_id',
    'department_id',  
];


    public function department() { return $this->belongsTo(Department::class); }
    public function mail() { return $this->belongsTo(Mail::class); }

}
 