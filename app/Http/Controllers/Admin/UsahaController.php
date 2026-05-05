<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\Pengerajin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsahaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Usaha::with(['wilayah', 'user']);

        if ($user->role === 'admin_wilayah') {
            $query->where('wilayah_id', $user->wilayah_id);
        } elseif ($user->role === 'umkm') {
            $query->where('user_id', $user->id);
        }

        $dataUsaha = $query->get();

        return view('admin.usaha.index-usaha', [
            'usahas' => $dataUsaha
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role === 'admin_wilayah') {
            $wilayahs = Wilayah::where('id', $user->wilayah_id)->get();
        } else {
            $wilayahs = Wilayah::all();
        }
        $pengerajins = Pengerajin::all();
        
        $lastUsaha = Usaha::orderBy('id', 'desc')->first();
        $nextId = $lastUsaha ? $lastUsaha->id + 1 : 1;
        $autoKode = 'PR' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('admin.usaha.create-usaha', compact('wilayahs', 'pengerajins', 'autoKode'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $usaha = Usaha::with('user')->findOrFail($id);
        
        if ($user->role === 'admin_wilayah') {
            if ($usaha->wilayah_id != $user->wilayah_id) {
                abort(403, 'Unauthorized action.');
            }
            $wilayahs = Wilayah::where('id', $user->wilayah_id)->get();
        } else {
            $wilayahs = Wilayah::all();
        }
        
        $pengerajins = Pengerajin::all();
        return view('admin.usaha.edit-usaha', compact('usaha', 'wilayahs', 'pengerajins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_usaha' => 'required|string|max:255|unique:usaha',
            'nama_usaha' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'wilayah_id' => 'required|exists:wilayahs,id',
            'telp_usaha' => 'nullable|string|max:15',
            'deskripsi_usaha' => 'nullable|string',
            'spesialisasi_usaha' => 'nullable|string',
            'foto_usaha' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_tempat.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 1. Create User
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'umkm',
            'wilayah_id' => $request->wilayah_id,
            'nama' => $request->nama_usaha,
        ]);

        $data = $request->except(['password', 'username', 'email']);
        $data['user_id'] = $user->id;
        $data['status_usaha'] = 'aktif';

        // 2. Handle Profile Photo
        if ($request->hasFile('foto_usaha')) {
            $originalName = $request->file('foto_usaha')->getClientOriginalName();
            $path = $request->file('foto_usaha')->storeAs('foto_usaha', $originalName, 'public');
            $data['foto_usaha'] = $path;
        }

        // 3. Handle Gallery Photos (foto_tempat)
        if ($request->hasFile('foto_tempat')) {
            $gallery = [];
            foreach ($request->file('foto_tempat') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('foto_tempat', $name, 'public');
                $gallery[] = $path;
            }
            $data['foto_tempat'] = json_encode($gallery);
        }

        Usaha::create($data);

        return redirect()->route('admin.usaha-index')
            ->with('success', 'Usaha berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $usaha = Usaha::findOrFail($id);
        $user = $usaha->user;

        $request->validate([
            'kode_usaha' => 'required|string|max:255|unique:usaha,kode_usaha,' . $id,
            'nama_usaha' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . ($user->id ?? 0),
            'email' => 'required|email|max:255|unique:users,email,' . ($user->id ?? 0),
            'password' => 'nullable|string|min:8',
            'wilayah_id' => 'required|exists:wilayahs,id',
            'telp_usaha' => 'nullable|string|max:15',
            'deskripsi_usaha' => 'nullable|string',
            'spesialisasi_usaha' => 'nullable|string',
            'foto_usaha' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_tempat.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 1. Update User
        if ($user) {
            $userData = [
                'username' => $request->username,
                'email' => $request->email,
                'wilayah_id' => $request->wilayah_id,
                'nama' => $request->nama_usaha,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
        }

        $data = $request->except(['password', 'username', 'email']);

        // 2. Handle Profile Photo
        if ($request->hasFile('foto_usaha')) {
            if ($usaha->foto_usaha) {
                Storage::disk('public')->delete($usaha->foto_usaha);
            }
            $originalName = $request->file('foto_usaha')->getClientOriginalName();
            $path = $request->file('foto_usaha')->storeAs('foto_usaha', $originalName, 'public');
            $data['foto_usaha'] = $path;
        }

        // 3. Handle Gallery Photos (foto_tempat)
        if ($request->hasFile('foto_tempat')) {
            $oldGallery = json_decode($usaha->foto_tempat, true) ?? [];
            foreach ($oldGallery as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $gallery = [];
            foreach ($request->file('foto_tempat') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('foto_tempat', $name, 'public');
                $gallery[] = $path;
            }
            $data['foto_tempat'] = json_encode($gallery);
        }

        $usaha->update($data);

        return redirect()->route('admin.usaha-index')
            ->with('success', 'Usaha berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $usaha = Usaha::findOrFail($id);
        if ($usaha->foto_usaha) {
            Storage::disk('public')->delete($usaha->foto_usaha);
        }
        $gallery = json_decode($usaha->foto_tempat, true) ?? [];
        foreach ($gallery as $path) {
            Storage::disk('public')->delete($path);
        }
        
        $user = $usaha->user;
        $usaha->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.usaha-index')
            ->with('success', 'Usaha berhasil dihapus.');
    }
}
