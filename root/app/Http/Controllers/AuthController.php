<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Halaman register
    public function showRegister() {
        return view('register');
    }

    // Proses register
    public function register(Request $request) {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telp' => 'required|max:13',
            'nomor_kendaraan' => 'required'
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'nomor_kendaraan' => $request->nomor_kendaraan
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat.');
    }

    // Halaman login
    public function showLogin() {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Ambil username & password
        $credentials = $request->only('username', 'password');

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // keamanan, regenerasi session
            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role == 'admin') {
                return redirect('/admin');
            } else {
                return redirect('/'); // user biasa
            }
        }

        // Jika gagal login
        return back()->with('error', 'Username atau password salah');
    }

    // Logout
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}