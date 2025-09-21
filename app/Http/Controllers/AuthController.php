<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ================== TAMPILKAN FORM LOGIN ================== //
    public function showLogin()
    {
        return view('auth.login');
    }

    // ================== PROSES LOGIN ================== //
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Arahkan sesuai role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');   // admin ke dashboard admin
            } elseif ($user->role === 'staff') {
                return redirect()->route('staff.dashboard');   // staff ke dashboard staff
            } else {
                // kalau ada role lain, sementara kembalikan ke login
                Auth::logout();
                return redirect()->route('login.show')
                    ->withErrors(['email' => 'Role tidak dikenali.']);
            }
        }

        // Kalau gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ================== LOGOUT ================== //
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // setelah logout kembali ke halaman login
        return redirect()->route('login.show');
    }
}
