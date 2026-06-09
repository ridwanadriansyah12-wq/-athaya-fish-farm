<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nomor_telepon' => 'required|regex:/^[0-9]+$/|max:15',
            'alamat' => 'required|string|min:30|max:500',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#])[a-zA-Z0-9!@#]+$/'
            ]
        ], [
            'email.unique' => 'Email sudah terdaftar',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung kombinasi huruf, angka, dan karakter spesial (!, @, #), serta hanya boleh terdiri dari karakter tersebut.',
            'nomor_telepon.regex' => 'Nomor telepon harus berupa angka dan tidak boleh mengandung huruf.',
            'alamat.min' => 'Alamat minimal harus terdiri dari 30 karakter.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'role' => 'customer',
            'saldo' => 0
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
    }

    /**
     * Show the login form
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->isCustomer()) {
                return redirect()->route('home')
                    ->with('login_success', 'Halo, ' . $user->name . '! Selamat datang kembali di Athaya Fish Farm.');
            }
            return redirect()->route('dashboard')
                ->with('login_success', 'Halo, ' . $user->name . '! Anda masuk sebagai ' . ucfirst($user->role) . '.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        $name = auth()->user()->name ?? 'Anda';
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('info', 'Sampai jumpa, ' . $name . '! Anda telah keluar.');
    }

    /**
     * Show profile edit form
     */
    public function editProfile(): View
    {
        return view('auth.edit-profile', ['user' => auth()->user()]);
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nomor_telepon' => 'required|regex:/^[0-9]+$/|max:15',
            'alamat' => 'required|string|min:30|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nomor_telepon.regex' => 'Nomor telepon harus berupa angka dan tidak boleh mengandung huruf.',
            'alamat.min' => 'Alamat minimal harus terdiri dari 30 karakter.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profil'), $filename);
            $user->foto_profil = $filename;
        }

        $user->update([
            'name' => $request->name,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Redirect ke Google OAuth
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            } else {
                // Buat user baru
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => Hash::make(Str::random(24)),
                    'role'      => 'customer',
                    'saldo'     => 0,
                ]);
            }

            Auth::login($user, true);
            // Customer → halaman utama; role lain → dashboard masing-masing
            if ($user->isCustomer()) {
                return redirect()->route('home')
                    ->with('login_success', 'Halo, ' . $user->name . '! Anda berhasil masuk dengan Google.');
            }
            return redirect()->route('dashboard')
                ->with('login_success', 'Halo, ' . $user->name . '! Anda masuk sebagai ' . ucfirst($user->role) . '.');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Login Google gagal. Silakan coba lagi.');
        }
    }
}
