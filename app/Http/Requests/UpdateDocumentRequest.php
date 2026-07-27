<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    /**
     * Tentukan apakah user diotorisasi untuk membuat request ini.
     * Otorisasi ditangani oleh middleware admin.auth.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk memperbarui Dokumen / Arsip.
     *
     * - document_type: wajib diisi
     * - file: opsional (nullable), jika diisi harus memenuhi kriteria tipe & ukuran
     */
    public function rules(): array
    {
        return [
            'document_type' => ['required', 'string', 'in:surat,notulen,dokumentasi'],
            'file'          => [
                'nullable',
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,jpg,jpeg,png,mp4',
            ],
        ];
    }

    /**
     * Pesan kesalahan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'document_type.required' => 'Jenis dokumen wajib dipilih.',
            'document_type.in'       => 'Jenis dokumen tidak valid. Pilih antara Surat, Notulen, atau Dokumentasi.',

            'file.file'              => 'Berkas pengganti tidak valid.',
            'file.max'               => 'Ukuran berkas tidak boleh melebihi 10 MB.',
            'file.mimes'             => 'Berkas pengganti harus berupa PDF, Word (doc/docx), gambar (jpg/png), atau video (mp4).',
        ];
    }
}
