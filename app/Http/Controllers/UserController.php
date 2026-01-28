<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('unit')->get();
        $units = Unit::all();
        return view('master.user.index', compact('users', 'units'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_nik' => 'required|string|max:50|unique:tb_users,user_nik',
            'user_nama' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:tb_users,user_email',
            'user_password' => 'required|string|min:6',
            'user_tlp' => 'nullable|string|max:20',
            'user_level' => 'required|string',
            'unit_id' => 'nullable|exists:tb_unit,unit_id',
        ]);

        $validated['user_aktif'] = 'Y';
        $validated['user_count'] = 0;

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::with('unit')->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'user_nik' => 'required|string|max:50|unique:tb_users,user_nik,' . $id . ',id_users',
            'user_nama' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:tb_users,user_email,' . $id . ',id_users',
            'user_password' => 'nullable|string|min:6',
            'user_tlp' => 'nullable|string|max:20',
            'user_level' => 'required|string',
            'unit_id' => 'nullable|exists:tb_unit,unit_id',
            'user_aktif' => 'required|in:Y,N',
        ]);

        // Only update password if provided
        if (empty($validated['user_password'])) {
            unset($validated['user_password']);
        }

        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }

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
