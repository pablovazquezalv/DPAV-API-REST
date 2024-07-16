<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cruce extends Model
{
    use HasFactory;

    protected $fillable = [
        'perro_macho_id',
        'perro_hembra_id',
        'fecha',
        'estado',
        'cita_id',
        'observaciones',
    ];
}
