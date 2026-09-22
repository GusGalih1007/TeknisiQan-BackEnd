<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoomStoreRequest extends FormRequest
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
            'roomName' => 'required|string|max:20',
            'compId' => 'nullable|exists:companies,compId'
        ];
    }

    public function messages(): array
    {
        return [
            'roomName.required' => 'Tolong isi nama ruangan',
            'roomName.max' => 'Maksimal karakter 20',
            'compId.exists' => 'Instansi tidak ditemukan'
        ];
    }
}
