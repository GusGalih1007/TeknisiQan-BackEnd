<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReportStoreRequest extends FormRequest
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
            'unitId' => 'required|exists:units,unitId',
            'problem' => 'required|string',
            'compId' => 'required|exists:companies,userId',
            'reportDate' => 'required',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,webp'
        ];
    }

    public function messages(): array
    {
        return [
            'unitId.required' => 'Tolong masukan unit yang mengalami kerusakan',
            'unitId.exists' => 'Unit tidak dapat ditemukan',
            'problem.required' => 'Tolong jelaskan kerusakan yang dialami',
            'compId.required' => 'Tolong isi instansi yang mengalami kerusakan',
            'compId.exists' => 'Instansi tidak dapat ditemukan',
            'reportDate.required' => 'masukan tanggal dan waktu',
            'photo.image' => 'Foto harus berupa gambar',
            'photo.mimes' => 'File harus berupa: png, jpg, jpeg, webp',
        ];
    }
}
