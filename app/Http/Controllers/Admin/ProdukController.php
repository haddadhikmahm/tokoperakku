<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index()
    {
        $dataProduk = Produk::all(); // atau bisa juga pakai paginate()

        return view('admin.produk.index-produk', [
            'produks' => $dataProduk
        ]);
    }

    public function create()
    {
        // Ambil data kategori produk dari database
        $kategoriProduks = KategoriProduk::all();
        // Kirim data kategori produk ke view
        return view('admin.produk.create-produk', [
            'kategoriProduks' => $kategoriProduks
        ]);
    }

    public function edit($id)
    {
        $kategoriProduks = KategoriProduk::all();
        $produk = Produk::findOrFail($id);
        // Kirim data produk dan kategori produk ke view
        return view('admin.produk.edit-produk', [
            'kategoriProduks' => $kategoriProduks,
            'produk' => $produk
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|string',
            'kategori_produk_id' => 'required|exists:kategori_produk,id',
            'nama_produk' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        // Simpan data produk ke database
        Produk::create($request->all());

        return redirect()->route('admin.produk-index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'kode_produk' => 'required|string',
            'kategori_produk_id' => 'required|exists:kategori_produk,id',
            'nama_produk' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        // Generate slug dari nama kategori
        $data['slug'] = Str::slug($data['nama_produk']);

        // Update data produk di database
        Produk::where('id', $id)->update($data);

        return redirect()->route('admin.produk-index')
            ->with('success', 'Data Produk berhasil diupdate.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('admin.produk-index')
            ->with('success', 'Data Produk berhasil dihapus.');
    }
}
