<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    /**
     * Tentukan apakah user diotorisasi untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk data Kegiatan.
     */
    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'activity_date' => ['required', 'date'],
            'time'          => ['required'],
            'location'      => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'status'        => ['required', 'in:scheduled,completed'],
        ];
    }

    /**
     * Pesan kesalahan dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'title.required'         => 'Judul kegiatan wajib diisi.',
            'title.string'           => 'Judul kegiatan harus berupa teks.',
            'title.max'              => 'Judul kegiatan maksimal 255 karakter.',
            'activity_date.required' => 'Tanggal kegiatan wajib diisi.',
            'activity_date.date'     => 'Format tanggal kegiatan tidak valid.',
            'time.required'          => 'Waktu kegiatan wajib diisi.',
            'location.required'      => 'Tempat kegiatan wajib diisi.',
            'location.string'        => 'Tempat kegiatan harus berupa teks.',
            'location.max'           => 'Tempat kegiatan maksimal 255 karakter.',
            'description.string'     => 'Deskripsi kegiatan harus berupa teks.',
            'status.required'        => 'Status kegiatan wajib diisi.',
            'status.in'              => 'Status kegiatan harus berupa scheduled atau completed.',
        ];
    }
}
