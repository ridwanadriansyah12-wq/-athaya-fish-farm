<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form "Lupa Password" — input email
     */
    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Cek apakah email terdaftar.
     * Jika ya → simpan ke session lalu ke form reset.
     * Jika tidak → tolak dengan pesan error.
     */
    public function checkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'Email tidak ditemukan. Silakan hubungi admin.'])
                ->withInput();
        }

        // Simpan email ke session, dipakai di form reset
        session(['reset_email' => $request->email]);

        return redirect()->route('password.reset.form')
            ->with('info', 'Email ditemukan. Silakan buat password baru Anda.');
    }

    /**
     * Tampilkan form ganti password baru.
     * Hanya bisa diakses jika ada session 'reset_email'.
     */
    public function showResetForm(): View|RedirectResponse
    {
        if (! session('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi habis. Silakan masukkan email kembali.']);
        }

        return view('auth.reset-password', [
            'email' => session('reset_email'),
        ]);
    }

    /**
     * Simpan password baru ke database.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $email = session('reset_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi habis. Silakan mulai ulang proses.']);
        }

        $request->validate([
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.min'               => 'Password minimal 8 karakter.',
            'password.confirmed'         => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Akun tidak ditemukan. Silakan mulai ulang.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus session setelah berhasil
        session()->forget('reset_email');

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
    }
}
