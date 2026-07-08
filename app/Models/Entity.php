<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mail;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;


class Entity extends Model
{

protected $fillable = [    
    'name',
];

    /** @use HasFactory<\Database\Factories\EntityFactory> */
    use HasFactory, SoftDeletes, Searchable;


  public function mail() { return $this->hasMany(Mail::class); }

}
        