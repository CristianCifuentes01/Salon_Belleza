<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:0|max:999.99',
            'descripcion' => 'nullable|string|max:500',
            'duracion' => 'required|integer|min:15|max:240',
            'activo' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 60 caracteres.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.min' => 'El precio no puede ser negativo.',
            'precio.max' => 'El precio no puede superar $999.99.',
            'duracion.required' => 'La duración es obligatoria.',
            'duracion.integer' => 'La duración debe ser un número entero.',
            'duracion.min' => 'La duración mínima es de 15 minutos.',
            'duracion.max' => 'La duración máxima es de 240 minutos.',
        ];
    }
}
