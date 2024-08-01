<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camada extends Model
{
    use HasFactory;

    protected $fillable = [
        'cruce_id',
        'fecha',
        'numero_cachorros',
        'numero_machos',
        'numero_hembras',
        'numero_total',
    ];
}
