<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackUp extends Model
{
    /** @use HasFactory<\Database\Factories\BackUpFactory> */
protected $fillable = [
   'name',
   'file_path',
   'saved',
];

    use HasFactory;
}
