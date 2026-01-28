<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
     * Attempt to authenticate the user using user_nik and user_password.
     * Supports MD5 hashed passwords (legacy), plain text, and bcrypt passwords (new).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_nik' => ['required', 'string'],
            'user_password' => ['required'],
        ]);

        // Find user by NIK
        $user = User::where('user_nik', $credentials['user_nik'])->first();

        if (!$user) {
            return back()->withErrors([
                'user_nik' => 'NIK atau password yang Anda masukkan salah.',
            ])->onlyInput('user_nik');
        }

        // Check password - support MD5, plain text, and bcrypt
        $passwordMatches = false;

        // 1. Check if password is bcrypt hashed (for new passwords)
        // Use try-catch to suppress "This password does not use the Bcrypt algorithm" error
        try {
            if (Hash::check($credentials['user_password'], $user->user_password)) {
                $passwordMatches = true;
            }
        } catch (\Exception $e) {
            // Password is not bcrypt, continue to MD5/plain text check
        }

        // 2. Check MD5 hashed password (legacy support - most common)
        if (!$passwordMatches && $user->user_password === md5($credentials['user_password'])) {
            $passwordMatches = true;

            // Optionally: Auto-upgrade MD5 to bcrypt for better security
            // Uncomment the lines below if you want to automatically upgrade passwords
            // $user->user_password = Hash::make($credentials['user_password']);
            // $user->save();
        }

        // 3. Check plain text password (fallback for very old data)
        if (!$passwordMatches && $user->user_password === $credentials['user_password']) {
            $passwordMatches = true;

            // Optionally: Auto-upgrade plain text to bcrypt for security
            // Uncomment the lines below if you want to automatically upgrade passwords
            // $user->user_password = Hash::make($credentials['user_password']);
            // $user->save();
        }

        if ($passwordMatches) {
            // Manually log in the user
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'user_nik' => 'NIK atau password yang Anda masukkan salah.',
        ])->onlyInput('user_nik');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
