<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;

class BackUp extends Model
{
    /** @use HasFactory<\Database\Factories\BackUpFactory> */
protected $fillable = [
   'name',
   'file_path',
   'saved',
];

    use HasFactory,SoftDeletes;
}
