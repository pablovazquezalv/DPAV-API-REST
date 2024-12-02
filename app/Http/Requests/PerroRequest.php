<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerroRequest extends FormRequest
{
    public function authorize()
    {
        // Autorizar si el usuario está autenticado
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'edad' => 'required|int',
            'sexo' => 'required|in:Macho,Hembra',
            'peso' => 'required',
            'tamaño' => 'required|in:Pequeño,Mediano,Grande',
            'altura' => 'required',
            'estatus' => 'sometimes|in:0,1',
            'esterilizado' => 'required|in:Si,No',
            'fecha_nacimiento' => 'required|date',
            'id_raza' => 'required|int',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'color.required' => 'El color es requerido',
            'edad.required' => 'La edad es requerida',
            'sexo.required' => 'El sexo es requerido',
            'peso.required' => 'El peso es requerido',
            'tamaño.required' => 'El tamaño es requerido',
            'altura.required' => 'La altura es requerida',
            'esterilizado.required' => 'La esterilización es requerida',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'tamaño.in' => 'El tamaño no es válido',
        ];
    }
}
