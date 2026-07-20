<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Tentukan apakah user diotorisasi untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk data Dokumen / Arsip.
     */
    public function rules(): array
    {
        return [
            'document_type' => ['required', 'in:surat,notulen,dokumentasi'],
            'file'          => ['required'],
        ];
    }

    /**
     * Pesan kesalahan dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'document_type.required' => 'Jenis dokumen wajib dipilih.',
            'document_type.in'       => 'Jenis dokumen harus berupa surat, notulen, atau dokumentasi.',
            'file.required'          => 'Berkas dokumen wajib diunggah.',
        ];
    }
}
