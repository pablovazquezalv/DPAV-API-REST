<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perro extends Model
{
    use HasFactory;

    // Definición de constantes para los atributos
    const NAME = 'nombre';
    const RAZA = 'raza';
    const EDAD = 'edad';
    const COLOR = 'color';
    const ALTURA = 'altura';
    const TAMANO = 'tamaño';
    const PESO = 'peso';
    const SEXO = 'sexo';
    const ESTERILIZADO = 'esterilizado';
    const VACUNADO = 'vacunado';
    const DESCRIPCION = 'descripcion';
    const FOTO = 'foto';
    const ESTATUS = 'estatus';
    const FECHA_NACIMIENTO = 'fecha_nacimiento';
    const USER_ID = 'user_id';
    const ID_RAZA = 'id_raza';

    /**
     * Los atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::NAME,
        self::RAZA,
        self::EDAD,
        self::COLOR,
        self::ALTURA,
        self::TAMANO,
        self::PESO,
        self::SEXO,
        self::ESTERILIZADO,
        self::VACUNADO,
        self::DESCRIPCION,
        self::FOTO,
        self::ESTATUS,
        self::FECHA_NACIMIENTO,
        self::USER_ID,
        self::ID_RAZA,
    ];

  
    /**
     * Relación con el modelo Raza (un perro pertenece a una raza).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function raza(): BelongsTo
    {
        return $this->belongsTo(Raza::class, 'id_raza');
    }

   
}
