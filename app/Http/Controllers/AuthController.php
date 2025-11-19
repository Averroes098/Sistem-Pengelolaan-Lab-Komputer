<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }
    public function create(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telp' => 'required|string|min:10|max:21',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login.index')->with(['success' => 'Registrasi berhasil! Anda dapat melakukan login.']);
    }
    public function login()
    {
        return view('auth.login');
    }
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $request->email)->first();
        if($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            if($user->level === 'admin') {
                return redirect()->route('admin.dashboard')->with(['success'=>'Anda berhasil login']);
            }
            return redirect()->route('user.index')->with(['success'=>'Anda berhasil login']);
        }
        return redirect()->route('login.index')->with(['error'=>'Kesalahan login! Harap periksa email dan password anda kembali.']);
    }
    public function logout(Request $request) 
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.index')->with(['success'=>'Anda berhasil logout!']);
    }
}
