<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCredentialsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Perbarui kredensial (login_id atau password) Administrator.
     *
     * Dikonsumsi via AJAX (fetch) dari modal pengaturan.
     */
    public function updateCredentials(UpdateCredentialsRequest $request): JsonResponse
    {
        $user = Auth::user();

        // Verifikasi password saat ini
        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'field'   => 'current_password',
                'message' => 'Password saat ini tidak sesuai.',
            ], 422);
        }

        if ($request->type === 'login_id') {
            $user->update(['login_id' => $request->new_login_id]);

            return response()->json([
                'success' => true,
                'message' => 'ID Administrator berhasil diperbarui.',
            ]);
        }

        // type === 'password'
        $user->update(['password' => $request->new_password]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}
