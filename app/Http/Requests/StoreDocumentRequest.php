<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Tentukan apakah user diotorisasi untuk membuat request ini.
     * Otorisasi sesungguhnya ditangani oleh middleware admin.auth.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk upload Dokumen / Arsip.
     *
     * Jenis file yang diizinkan:
     *   - Dokumen  : pdf, doc, docx
     *   - Gambar   : jpg, jpeg, png
     *   - Video    : mp4
     *
     * Batas ukuran: 10 MB (10 240 KB)
     */
    public function rules(): array
    {
        return [
            'document_type' => ['required', 'string', 'in:surat,notulen,dokumentasi'],
            'file'          => [
                'required',
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

            'file.required'          => 'Berkas dokumen wajib diunggah.',
            'file.file'              => 'Berkas yang diunggah tidak valid.',
            'file.max'               => 'Ukuran berkas tidak boleh melebihi 10 MB.',
            'file.mimes'             => 'Berkas harus berupa PDF, Word (doc/docx), gambar (jpg/png), atau video (mp4).',
        ];
    }
}
