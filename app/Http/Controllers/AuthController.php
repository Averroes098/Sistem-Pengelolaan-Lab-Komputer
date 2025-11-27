<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi pengguna.
     */
    public function authenticate(Request $request)
    {
        // Validasi form login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:1',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Regenerasi session ID untuk keamanan
            $request->session()->regenerate();

            $user = Auth::user();

            // Arahkan sesuai level user
            if ($user->level === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Login berhasil sebagai Admin.');
            } elseif ($user->level === 'staf') {
                return redirect()->route('staf.dashboard')
                    ->with('success', 'Login berhasil sebagai Staf.');
            } elseif ($user->level === 'kadep') {
                return redirect()->route('kadep.dashboard')
                    ->with('success', 'Login berhasil sebagai Kepala Departemen.');
            } else {
                return redirect()->route('dashboard.user')
                    ->with('success', 'Login berhasil sebagai Mahasiswa.');
            }
        }

        // Jika login gagal
        return back()->with('error', 'Email atau password salah.');
    }

    /**
     * Tampilkan form registrasi.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru.
     */
    public function create(Request $request)
    {
        // Validasi data
        $request->validate([
            'nim' => 'required|string|max:20|unique:users',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_telp' => 'nullable|string|min:10|max:21',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:1|confirmed',
        ]);

        // Simpan user baru
        $user = User::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'user', // default user baru
        ]);

        // Login user setelah registrasi
        Auth::login($user);

        return redirect()
            ->route('dashboard.user') // Arahkan ke dashboard user
            ->with('success', 'Registrasi berhasil dan Anda telah login.');
    }

    /**
     * Logout dari sistem.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda telah logout dengan aman.');
    }
}
