<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role == 'user' || $user->role == 'umkm') {
            return view('user.profile', compact('user'))->with('layout', $user->role == 'umkm' ? 'layouts.umkm' : 'layouts.user');
        }

        return view('admin.profile.profile', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        
        if ($user->role == 'umkm' || $user->role == 'user') {
            return view('user.edit-profile', compact('user'))->with('layout', $user->role == 'umkm' ? 'layouts.umkm' : 'layouts.user');
        }

        return view('admin.profile.edit-profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'nama' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Pria,Wanita',
            'usia' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['nama', 'no_hp', 'gender', 'usia', 'alamat', 'email']);

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto && file_exists(public_path('storage/' . $user->foto))) {
                unlink(public_path('storage/' . $user->foto));
            }
            $path = $request->file('foto')->store('profile_photos', 'public');
            $data['foto'] = $path;
        }

        $user->update($data);

        $route = 'admin.profile';
        if ($user->role == 'umkm') $route = 'umkm.profile';
        if ($user->role == 'user') $route = 'user.profile';

        return redirect()->route($route)
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
