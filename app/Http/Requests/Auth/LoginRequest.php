<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak melakukan request ini.
     * Login selalu diizinkan (belum terautentikasi).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk form login.
     */
    public function rules(): array
    {
        return [
            'login_id' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'login_id.required' => 'ID Administrator wajib diisi.',
            'login_id.string'   => 'ID Administrator harus berupa teks.',
            'login_id.max'      => 'ID Administrator maksimal :max karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'password.max'      => 'Password maksimal :max karakter.',
        ];
    }

    /**
     * Label atribut dalam Bahasa Indonesia.
     */
    public function attributes(): array
    {
        return [
            'login_id' => 'ID Administrator',
            'password' => 'Password',
        ];
    }
}
