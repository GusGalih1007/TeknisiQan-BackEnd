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
        return in_array($this->user()?->role?->value, ['superadmin', 'admin'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unitName' => ['required', 'string', 'max:60'],
            'roomId' => ['required', 'uuid', 'exists:rooms,roomId'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'unitName.required' => 'Tolong tambahkan nama unit',
            'unitName.max' => 'Batas maksimal karakter adalah 60',
            'roomId.required' => 'Masukan nama ruangan penyimpanan unit',
            'roomId.exists' => 'Ruangan tidak ditemukan',
            'photo.image' => 'Foto unit harus berupa gambar',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP',
            'photo.max' => 'Ukuran foto maksimal 5MB',
        ];
    }
}
