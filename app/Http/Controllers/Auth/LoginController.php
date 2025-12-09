<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show simple login form (blade not provided here).
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Attempt to authenticate the user using user_email and user_password.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_email' => ['required','email'],
            'user_password' => ['required'],
        ]);

        // Attempt login — because our User model uses getAuthPassword(), we can pass 'password' key
        if (Auth::attempt(['user_email' => $credentials['user_email'], 'password' => $credentials['user_password']], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'user_email' => 'The provided credentials do not match our records.',
        ])->onlyInput('user_email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
