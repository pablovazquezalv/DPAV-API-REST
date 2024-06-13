<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerroUser extends Model
{
    use HasFactory;

    protected $table = 'perros_usuarios';

    protected $fillable = [
        'perro_id',
        'user_id',
    ];
}
