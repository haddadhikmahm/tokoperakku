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
        $dataProduk = Produk::with(['kategoriProduk', 'usaha', 'fotoProduk'])->get();

        return view('admin.produk.index-produk', [
            'produks' => $dataProduk,
            'layout' => auth()->user()->role == 'umkm' ? 'layouts.umkm' : 'layouts.admin_premium'
        ]);
    }

    public function create()
    {
        $kategoriProduks = KategoriProduk::all();
        $usahas = Usaha::all();
        
        $lastProduk = Produk::orderBy('id', 'desc')->first();
        $nextId = $lastProduk ? $lastProduk->id + 1 : 1;
        $autoKode = 'PRD' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $layout = auth()->user()->role == 'umkm' ? 'layouts.umkm' : 'layouts.admin_premium';
        return view('admin.produk.create-produk', compact('kategoriProduks', 'usahas', 'autoKode', 'layout'));
    }

    public function edit($id)
    {
        $kategoriProduks = KategoriProduk::all();
        $usahas = Usaha::all();
        $produk = Produk::with(['fotoProduk', 'usaha'])->findOrFail($id);
        $layout = auth()->user()->role == 'umkm' ? 'layouts.umkm' : 'layouts.admin_premium';
        return view('admin.produk.edit-produk', compact('kategoriProduks', 'usahas', 'produk', 'layout'));
    }

    public function store(Request $request)
    {
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
        $produk = Produk::findOrFail($id);

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

        $produk->update($request->only([
            'kode_produk', 'kategori_produk_id', 'nama_produk', 'deskripsi', 'harga', 'stok'
        ]));

        // Sync with Usaha
        $produk->usaha()->sync($request->usaha_id);

        // Handle Gallery Photos
        if ($request->hasFile('foto_produk')) {
            // Delete old photos if needed? Usually we keep them and allow adding more or specific deletions.
            // But based on the UI "+" button, it looks like adding more.
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
        $produk = Produk::with('fotoProduk')->findOrFail($id);
        
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
