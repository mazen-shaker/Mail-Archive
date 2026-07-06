<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department;
use App\Models\MailStatus;
use App\Models\MailPrivacy;
use App\Models\MailDepartment;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mail extends Model
{

protected $fillable = [
    'title',
    'description',
    'file_type',
    'file_path',
    'mail_status_id',    
    'mail_privacy_id',
    'user_id',
    'department_id',  
    'entity_id',
    'writed_by'
];


    /** @use HasFactory<\Database\Factories\MailFactory> */
    use HasFactory, SoftDeletes;

    public function user() { return $this->belongsTo(User::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function entity() { return $this->belongsTo(Entity::class); }
    public function status() { return $this->belongsTo(MailStatus::class, 'mail_status_id'); }
    public function privacy() { return $this->belongsTo(MailPrivacy::class, 'mail_privacy_id'); }
    public function mailDepartment() { return $this->hasMany(MailDepartment::class); }


}
