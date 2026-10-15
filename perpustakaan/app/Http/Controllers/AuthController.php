<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function formLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $kredensial = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $kredensial['username'])->first();

        if (! $user || ! Auth::attempt(['username' => $kredensial['username'], 'password' => $kredensial['password'], 'status' => 'aktif'])) {
            return back()->with('error', 'Username atau kata sandi salah, atau akun sudah dinonaktifkan.')->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}