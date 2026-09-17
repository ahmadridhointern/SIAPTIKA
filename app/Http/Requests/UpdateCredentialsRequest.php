<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCredentialsRequest extends FormRequest
{
    /**
     * Hanya admin yang terautentikasi yang boleh mengubah kredensial.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Aturan validasi berdasarkan mode (type) yang dipilih.
     */
    public function rules(): array
    {
        $userId = Auth::id();

        $baseRules = [
            'type'             => ['required', 'in:login_id,password'],
            'current_password' => ['required', 'string'],
        ];

        if ($this->input('type') === 'login_id') {
            return array_merge($baseRules, [
                'new_login_id' => [
                    'required',
                    'string',
                    'min:3',
                    'max:50',
                    Rule::unique('users', 'login_id')->ignore($userId),
                ],
            ]);
        }

        return array_merge($baseRules, [
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'type.required'             => 'Mode perubahan wajib dipilih.',
            'type.in'                   => 'Mode perubahan tidak valid.',
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_login_id.required'     => 'ID Administrator baru wajib diisi.',
            'new_login_id.string'       => 'ID Administrator harus berupa teks.',
            'new_login_id.min'          => 'ID Administrator minimal :min karakter.',
            'new_login_id.max'          => 'ID Administrator maksimal :max karakter.',
            'new_login_id.unique'       => 'ID Administrator tersebut sudah digunakan.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal :min karakter.',
            'new_password.confirmed'    => 'Konfirmasi password baru tidak sesuai.',
        ];
    }

    /**
     * Label atribut dalam Bahasa Indonesia.
     */
    public function attributes(): array
    {
        return [
            'type'             => 'Mode perubahan',
            'current_password' => 'Password saat ini',
            'new_login_id'     => 'ID Administrator baru',
            'new_password'     => 'Password baru',
        ];
    }
}
