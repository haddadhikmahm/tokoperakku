<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Usaha;
use App\Models\FotoProduk;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role == 'umkm') {
            $dataProduk = Produk::whereHas('usaha', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['kategoriProduk', 'usaha', 'fotoProduk'])->get();
        } elseif ($user->role == 'admin_wilayah') {
            $dataProduk = Produk::whereHas('usaha', function($query) use ($user) {
                $query->where('wilayah_id', $user->wilayah_id);
            })->with(['kategoriProduk', 'usaha', 'fotoProduk'])->get();
        } else {
            $dataProduk = Produk::with(['kategoriProduk', 'usaha', 'fotoProduk'])->get();
        }

        return view('admin.produk.index-produk', [
            'produks' => $dataProduk,
            'layout' => $user->role == 'umkm' ? 'layouts.umkm' : 'layouts.admin_premium'
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $kategoriProduks = KategoriProduk::all();
        
        if ($user->role == 'umkm') {
            $usahas = Usaha::where('user_id', $user->id)->get();
        } elseif ($user->role == 'admin_wilayah') {
            $usahas = Usaha::where('wilayah_id', $user->wilayah_id)->get();
        } else {
            $usahas = Usaha::all();
        }
        
        $lastProduk = Produk::orderBy('id', 'desc')->first();
        $nextId = $lastProduk ? $lastProduk->id + 1 : 1;
        $autoKode = 'PRD' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $layout = $user->role == 'umkm' ? 'layouts.umkm' : 'layouts.admin_premium';
        return view('admin.produk.create-produk', compact('kategoriProduks', 'usahas', 'autoKode', 'layout'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $produk = Produk::with(['fotoProduk', 'usaha'])->findOrFail($id);
        
        if ($user->role == 'umkm') {
            // Check if this product belongs to any of user's businesses
            $belongsToUser = $produk->usaha->where('user_id', $user->id)->count() > 0;
            if (!$belongsToUser) {
                abort(403, 'Unauthorized action.');
            }
            $usahas = Usaha::where('user_id', $user->id)->get();
        } elseif ($user->role == 'admin_wilayah') {
            // Check if this product belongs to any of businesses in user's wilayah
            $belongsToWilayah = $produk->usaha->where('wilayah_id', $user->wilayah_id)->count() > 0;
            if (!$belongsToWilayah) {
                abort(403, 'Unauthorized action.');
            }
            $usahas = Usaha::where('wilayah_id', $user->wilayah_id)->get();
        } else {
            $usahas = Usaha::all();
        }

        $kategoriProduks = KategoriProduk::all();
        $layout = $user->role == 'umkm' ? 'layouts.umkm' : 'layouts.admin_premium';
        return view('admin.produk.edit-produk', compact('kategoriProduks', 'usahas', 'produk', 'layout'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'kode_produk' => 'required|string|unique:produk',
            'kategori_produk_id' => 'required|exists:kategori_produk,id',
            'nama_produk' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'nullable|integer',
            'usaha_id' => 'required|exists:usaha,id',
            'foto_produk.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user->role == 'umkm') {
            $usaha = Usaha::where('id', $request->usaha_id)->where('user_id', $user->id)->first();
            if (!$usaha) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke usaha ini.');
            }
        }

        $produk = Produk::create($request->only([
            'kode_produk', 'kategori_produk_id', 'nama_produk', 'deskripsi', 'harga', 'stok'
        ]));

        // Sync with Usaha
        $produk->usaha()->sync($request->usaha_id);

        // Handle Gallery Photos
        if ($request->hasFile('foto_produk')) {
            foreach ($request->file('foto_produk') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('foto_produk', $name, 'public');
                
                FotoProduk::create([
                    'produk_id' => $produk->id,
                    'file_foto_produk' => $path,
                    'kode_foto_produk' => 'IMG-' . Str::upper(Str::random(8))
                ]);
            }
        }

        return redirect()->route('admin.produk-index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $produk = Produk::findOrFail($id);

        if ($user->role == 'umkm') {
            $belongsToUser = $produk->usaha->where('user_id', $user->id)->count() > 0;
            if (!$belongsToUser) {
                abort(403, 'Unauthorized action.');
            }
        }

        $request->validate([
            'kode_produk' => 'required|string|unique:produk,kode_produk,' . $id,
            'kategori_produk_id' => 'required|exists:kategori_produk,id',
            'nama_produk' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'nullable|integer',
            'usaha_id' => 'required|exists:usaha,id',
            'foto_produk.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user->role == 'umkm') {
            $usaha = Usaha::where('id', $request->usaha_id)->where('user_id', $user->id)->first();
            if (!$usaha) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke usaha ini.');
            }
        }

        $produk->update($request->only([
            'kode_produk', 'kategori_produk_id', 'nama_produk', 'deskripsi', 'harga', 'stok'
        ]));

        // Sync with Usaha
        $produk->usaha()->sync($request->usaha_id);

        // Handle Gallery Photos
        if ($request->hasFile('foto_produk')) {
            foreach ($request->file('foto_produk') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('foto_produk', $name, 'public');
                
                FotoProduk::create([
                    'produk_id' => $produk->id,
                    'file_foto_produk' => $path,
                    'kode_foto_produk' => 'IMG-' . Str::upper(Str::random(8))
                ]);
            }
        }

        return redirect()->route('admin.produk-index')
            ->with('success', 'Data Produk berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $produk = Produk::with(['fotoProduk', 'usaha'])->findOrFail($id);
        
        if ($user->role == 'umkm') {
            $belongsToUser = $produk->usaha->where('user_id', $user->id)->count() > 0;
            if (!$belongsToUser) {
                abort(403, 'Unauthorized action.');
            }
        }

        foreach ($produk->fotoProduk as $foto) {
            Storage::disk('public')->delete($foto->file_foto_produk);
            $foto->delete();
        }

        $produk->usaha()->detach();
        $produk->delete();

        return redirect()->route('admin.produk-index')
            ->with('success', 'Data Produk berhasil dihapus.');
    }
}
