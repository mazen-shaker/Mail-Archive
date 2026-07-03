<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mail;
use Illuminate\Database\Eloquent\SoftDeletes;
class Entity extends Model
{

protected $fillable = [    
    'name',
];

    /** @use HasFactory<\Database\Factories\EntityFactory> */
    use HasFactory, SoftDeletes;


  public function mail() { return $this->hasMany(Mail::class); }

}
        