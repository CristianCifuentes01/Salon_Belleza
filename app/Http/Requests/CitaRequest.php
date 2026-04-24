<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe tener un formato válido.',
            'fecha.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
            'hora.required' => 'La hora es obligatoria.',
            'hora.date_format' => 'La hora debe tener el formato HH:MM.',
            'servicios.required' => 'Debes seleccionar al menos un servicio.',
            'servicios.min' => 'Debes seleccionar al menos un servicio.',
            'servicios.*.exists' => 'Uno de los servicios seleccionados no es válido.',
        ];
    }
}
