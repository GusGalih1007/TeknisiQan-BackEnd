<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:superadmin,admin,technician',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'compId' => 'nullable|exists:companies,compId'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tolong masukan nama pengguna',
            'name.max' => 'Maksimal karakter tidak boleh lebih dari 40',
            'email.required' => 'Tolong masukan email pengguna',
            'email.email' => 'Gunakan alamat email yang valid',
            'phone.required' => 'Tolong masukan nomor pengguna',
            'role.required' => 'Tolong pilih jabatan pengguna',
            'role.in' => 'Jabatan pengguna tidak valid',
            'photo.images' => 'Foto pengguna harus berupa gambar',
            'photo.mimes' => 'Format tidak valid. Format yang diizinkan: png, jpg, jpeg, webp',
            'photo.max' => 'Maksimal ukuran foto: 5MB',
            'compId.exists' => 'Perushaan/Instansi tidak valid' 
        ];
    }
}
