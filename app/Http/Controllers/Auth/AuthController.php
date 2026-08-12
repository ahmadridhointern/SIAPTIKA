<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login Admin (menggunakan tabel users).
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('login_id', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('login_id'))
                ->withErrors([
                    'login_id' => 'ID Administrator atau password salah.',
                ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('just_logged_in', true);
    }

    /**
     * Proses logout Admin.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }
}
