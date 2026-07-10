<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Mail;

class MailOwner extends Model
{
    protected $fillable = [
    'mail_id',
    'user_id',  
];

public function user(){ return $this->belongTo(User::class);}
public function mail(){ return $this->belongTo(Mail::class);}
}
  