<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mail;

class MailPrivacy extends Model
{
    public function mail() { return $this->hasMany(Mail::class); }
    
}
