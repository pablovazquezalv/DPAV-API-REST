<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerroUser extends Model
{
    use HasFactory;

    // Definición de constantes para los atributos
    const PERRO_ID = 'perro_id';
    const USER_ID = 'user_id';

    /**
     * Los atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::PERRO_ID,
        self::USER_ID,
    ];

    /**
     * Relacion con Perro
     */
    public function perro()
    {
        return $this->belongsTo(Perro::class, self::PERRO_ID);
    }

    /**
     * Relacion con User
     */
    public function user()
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }
}
