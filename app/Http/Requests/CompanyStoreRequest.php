<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'leaderId' => 'nullable|exists:user,userId',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5024',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama instansi wajib diisi',
            'name.max' => 'Nama instansi tidak boleh lebih dari 50 karakter',
            'address.required' => 'Alamat instansi wajib diisi',
            'logo.image' => 'Logo harus berbentuk foto',
            'logo.mimes' => 'File harus berupa: png, jpg, jpeg, webp',
            'logo.max' => 'Ukuran file terlalu besar. Maksimal 5MB'
        ];
    }
}
