<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:5|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 5 karakter',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Check current password
        // Using MD5 as per system requirement
        if (md5($request->current_password) !== $user->user_password) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        // Update password (using the mutator in User model)
        // Using setAttribute to avoid IDE read-only property warnings
        $user->setAttribute('user_password', $request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah');
    }
}
