<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get user by NIK for API lookup
     */
    public function getUserByNik($nik)
    {
        $user = User::where('user_nik', $nik)->first();
        
        if ($user) {
            return response()->json([
                'user_nik' => $user->user_nik,
                'user_nama' => $user->user_nama
            ]);
        }
        
        return response()->json(['error' => 'User not found'], 404);
    }

    /**
     * Search users by name for autocomplete/searchable dropdown
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');
        
        $users = User::where('user_nama', 'LIKE', "%{$query}%")
                    ->orWhere('user_nik', 'LIKE', "%{$query}%")
                    ->limit(10)
                    ->get(['user_nik', 'user_nama']);
        
        return response()->json($users);
    }

    /**
     * Get multiple users by NIKs (for bulk lookup)
     */
    public function getUsersByNiks(Request $request)
    {
        $niks = $request->input('niks', []);
        
        if (empty($niks)) {
            return response()->json([]);
        }
        
        $users = User::whereIn('user_nik', $niks)
                    ->get(['user_nik', 'user_nama']);
        
        return response()->json($users);
    }
}
