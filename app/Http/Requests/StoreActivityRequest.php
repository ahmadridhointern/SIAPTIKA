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
     * Arahkan redirect validasi gagal ke halaman daftar kegiatan (modal ada di sana).
     */
    protected function redirectTo(): string
    {
        return route('admin.activities.index');
    }


    /**
     * Aturan validasi untuk data Kegiatan.
     */
    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'min:5', 'max:255'],
            'activity_date' => ['required', 'date', 'date_format:Y-m-d'],
            'time'          => ['required', 'date_format:H:i'],
            'location'      => ['required', 'string', 'min:3', 'max:255'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'status'        => ['required', 'in:scheduled,completed'],
        ];
    }

    /**
     * Pesan kesalahan dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            // Judul
            'title.required'         => 'Judul kegiatan wajib diisi.',
            'title.string'           => 'Judul kegiatan harus berupa teks.',
            'title.min'              => 'Judul kegiatan minimal :min karakter.',
            'title.max'              => 'Judul kegiatan maksimal :max karakter.',

            // Tanggal
            'activity_date.required'    => 'Tanggal pelaksanaan wajib diisi.',
            'activity_date.date'        => 'Format tanggal pelaksanaan tidak valid.',
            'activity_date.date_format' => 'Format tanggal pelaksanaan harus berupa YYYY-MM-DD.',

            // Waktu
            'time.required'     => 'Waktu pelaksanaan wajib diisi.',
            'time.date_format'  => 'Format waktu harus berupa HH:MM (contoh: 08:00).',

            // Tempat
            'location.required' => 'Tempat pelaksanaan wajib diisi.',
            'location.string'   => 'Tempat pelaksanaan harus berupa teks.',
            'location.min'      => 'Tempat pelaksanaan minimal :min karakter.',
            'location.max'      => 'Tempat pelaksanaan maksimal :max karakter.',

            // Deskripsi
            'description.string' => 'Deskripsi kegiatan harus berupa teks.',
            'description.max'    => 'Deskripsi kegiatan maksimal :max karakter.',

            // Status
            'status.required' => 'Status kegiatan wajib dipilih.',
            'status.in'       => 'Status kegiatan hanya boleh berupa "scheduled" atau "completed".',
        ];
    }

    /**
     * Label nama atribut dalam Bahasa Indonesia.
     */
    public function attributes(): array
    {
        return [
            'title'         => 'Judul Kegiatan',
            'activity_date' => 'Tanggal Pelaksanaan',
            'time'          => 'Waktu Pelaksanaan',
            'location'      => 'Tempat Pelaksanaan',
            'description'   => 'Deskripsi Kegiatan',
            'status'        => 'Status Kegiatan',
        ];
    }
}
