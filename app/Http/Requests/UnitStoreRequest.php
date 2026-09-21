<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UnitStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unitName' => 'required|string|max:60',
            'compId' => 'required|exists:companies,compId',
            'roomId' => 'required|exists:rooms,roomId'
        ];
    }

    public function messages(): array
    {
        return [
            'unitName.required' => 'Tolong tambahkan nama unit',
            'unitName.max' => 'Batas maksimal karakter adalah 60',
            'compId.required' => 'Tolong masukan nama instansi',
            'compId.exists' => 'Instansi tidak ditemukan',
            'roomId.required' => 'Masukan nama ruangan penyimpanan unit',
            'roomId.exists' => 'Ruangan tidak ditemukan',
        ];
    }
}
