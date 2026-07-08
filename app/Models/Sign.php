<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mail;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;

class Sign extends Model
{

protected $fillable = [
    'name',  
    'file_path',
    'user_id',      
];
  

    /** @use HasFactory<\Database\Factories\SignFactory>  */
    use HasFactory, SoftDeletes, Searchable;

    public function user() { return $this->belongsTo(User::class); }


}
  