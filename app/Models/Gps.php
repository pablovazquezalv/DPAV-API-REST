<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gps extends Model
{
    use HasFactory;

    protected $table = 'gps';

    protected $fillable = [
        'id',
        'device_id',
        'perro_id',
    ];

    public function perro()
    {
        return $this->belongsTo(Perro::class);
    }

    public function zonas()
    {
        return $this->hasMany(Zona::class);
    }
}
