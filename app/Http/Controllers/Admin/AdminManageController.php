<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminManageController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin_utama') {
                abort(403, 'Akses terbatas hanya untuk Admin Utama.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\User::whereIn('role', ['admin_utama', 'admin_wilayah']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                  ->orWhere('nama', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $admins = $query->paginate(10);
        return view('admin.manage.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.manage.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin_utama,admin_wilayah',
            'nama' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Pria,Wanita',
            'usia' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['password', 'foto']);
        $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile_photos', 'public');
            $data['foto'] = $path;
        }

        \App\Models\User::create($data);

        return redirect()->route('admin.manage.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = \App\Models\User::findOrFail($id);
        return view('admin.manage.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = \App\Models\User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|unique:users,username,' . $admin->id,
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin_utama,admin_wilayah',
            'nama' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Pria,Wanita',
            'usia' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['password', 'foto']);
        
        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($admin->foto && file_exists(public_path('storage/' . $admin->foto))) {
                unlink(public_path('storage/' . $admin->foto));
            }
            $path = $request->file('foto')->store('profile_photos', 'public');
            $data['foto'] = $path;
        }

        $admin->update($data);

        return redirect()->route('admin.manage.index')->with('success', 'Admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = \App\Models\User::findOrFail($id);
        
        // Prevent deleting self
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($admin->foto && file_exists(public_path('storage/' . $admin->foto))) {
            unlink(public_path('storage/' . $admin->foto));
        }

        $admin->delete();

        return redirect()->route('admin.manage.index')->with('success', 'Admin berhasil dihapus.');
    }
}
