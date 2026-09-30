<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NonUserReportStoreRequest extends FormRequest
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
            'unitId' => ['required', 'uuid', 'exists:units,unitId'],
            'compId' => ['required', 'uuid', 'exists:companies,compId'],
            'problem' => ['required', 'string', 'min:10'],
            'reportByName' => ['required', 'string', 'min:3', 'max:60'],
            'contact_phone' => ['required', 'string', 'min:10', 'max:20'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'department' => ['nullable', 'string', 'max:100'],
            'reportDate' => ['required', 'date'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'unitId.required' => 'Silakan pilih unit/barang terlebih dahulu',
            'unitId.exists' => 'Unit tidak dapat ditemukan',
            'compId.required' => 'Perusahaan tidak dapat ditemukan',
            'compId.exists' => 'Perusahaan tidak valid',
            'problem.required' => 'Deskripsi kerusakan wajib diisi',
            'problem.min' => 'Deskripsi kerusakan minimal 10 karakter',
            'reportByName.required' => 'Nama lengkap wajib diisi',
            'reportByName.min' => 'Nama minimal 3 karakter',
            'reportByName.max' => 'Nama maksimal 60 karakter',
            'contact_phone.required' => 'Nomor telepon/WhatsApp wajib diisi',
            'contact_phone.min' => 'Nomor telepon minimal 10 digit',
            'contact_phone.max' => 'Nomor telepon maksimal 20 karakter',
            'contact_email.email' => 'Format email tidak valid',
            'contact_email.max' => 'Email maksimal 255 karakter',
            'department.max' => 'Divisi/departemen maksimal 100 karakter',
            'reportDate.required' => 'Tanggal laporan wajib diisi',
            'reportDate.date' => 'Format tanggal tidak valid',
            'photos.array' => 'File harus berupa array',
            'photos.max' => 'Maksimal 5 file foto dapat diupload',
            'photos.*.image' => 'Setiap file harus berupa gambar',
            'photos.*.mimes' => 'Format file harus: png, jpg, jpeg, webp',
            'photos.*.max' => 'Ukuran setiap file maksimal 5MB',
        ];
    }
}
