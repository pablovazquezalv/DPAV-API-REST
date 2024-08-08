<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas';

    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'radio',
        'gps_id',
    ];

    public function gps()
    {
        return $this->belongsTo(Gps::class);
    }


}
