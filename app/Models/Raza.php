<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Raza extends Model
{
    use HasFactory;

    // Definición de constantes para los atributos
    const NAME = 'nombre';
    const ESTADO = 'estado';

    /**
     * Los atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::NAME,
        self::ESTADO,
    ];

    /**
     * Relación con el modelo Perro (una raza tiene muchos perros).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function perros(): HasMany
    {
        return $this->hasMany(Perro::class, Perro::ID_RAZA);
    }

    
}
