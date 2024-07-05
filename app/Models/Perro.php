<?php

namespace App\Models;

use App\Enums\SexoPerro;
use App\Enums\TamañoPerro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Perro extends Model
{
    use HasFactory;

    protected $table = 'perros';

   


    protected $fillable = [
        'nombre',
        'raza',
        'edad',
        'color',
        'altura',
        'tamano',
        'peso',

        'sexo',
        'esterilizado',
        'vacunado',
        'descripcion',
        'foto',
        'estatus',
        'esterilizado',
        'fecha_nacimiento',
        'chip',
        'tipo',
        'user_id',
        'id_raza',
        'padre_id',
        'madre_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function raza()
    {
        return $this->belongsTo(Raza::class);
    }


    
}
