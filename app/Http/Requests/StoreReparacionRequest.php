<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReparacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Datos del bien
            'numero_inventario' => ['required', 'string', 'max:255'],
            'tipo_bien' => ['required', 'string', 'max:255'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['nullable', 'string', 'max:255'],
            'numero_serie' => ['nullable', 'string', 'max:255'],
            'especificaciones' => ['nullable', 'string'],
            'plantel_id' => ['required', 'exists:planteles,id'],
            'entidad_id' => ['required', 'exists:entidades,id'],

            // Datos del cliente
            'nombre_cliente' => ['required', 'string', 'max:255'],
            'telefono_cliente' => ['nullable', 'string', 'max:20'],

            // Datos de la reparación
            'falla_reportada' => ['required', 'string'],
            'accesorios_incluidos' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_inventario.required' => 'El número de inventario es obligatorio.',
            'tipo_bien.required' => 'El tipo de bien es obligatorio.',
            'marca.required' => 'La marca es obligatoria.',
            'falla_reportada.required' => 'La falla reportada es obligatoria.',
            'plantel_id.required' => 'El plantel es obligatorio.',
            'plantel_id.exists' => 'El plantel seleccionado no existe.',
            'entidad_id.required' => 'La entidad es obligatoria.',
            'entidad_id.exists' => 'La entidad seleccionada no existe.',
            'nombre_cliente.required' => 'El nombre del cliente es obligatorio.',
        ];
    }
}
