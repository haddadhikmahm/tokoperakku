<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UsahaProduk;
use App\Models\Usaha;
use App\Models\Produk;
use Illuminate\Http\Request;

class UsahaProdukController extends Controller
{
    public function index()
    {
        return view('admin.usaha_produk.index-usaha_produk', [
            'usahaProduks' => UsahaProduk::all()
        ]);
    }

    public function create()
    {
        return view('admin.usaha_produk.create-usaha_produk', [
            'usahas' => Usaha::all(),
            'produks' => Produk::all()
        ]);
    }

    public function edit($id)
    {
        $usahaProduk = UsahaProduk::findOrFail($id);
        return view('admin.usaha_produk.edit-usaha_produk', [
            'usahaProduk' => $usahaProduk,
            'usahas' => Usaha::all(),
            'produks' => Produk::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'usaha_id' => 'required|exists:usaha,id',
            'produk_id' => 'required|exists:produk,id',
        ]);

        UsahaProduk::create($request->all());

        return redirect()->route('admin.usaha_produk-index')
            ->with('success', 'Usaha Produk berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'usaha_id' => 'required|exists:usaha,id',
            'produk_id' => 'required|exists:produk,id',
        ]);

        $usahaProduk = UsahaProduk::findOrFail($request->id);
        $usahaProduk->update($request->all());

        return redirect()->route('admin.usaha_produk-index')
            ->with('success', 'Usaha Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $usahaProduk = UsahaProduk::findOrFail($id);
        $usahaProduk->delete();

        return redirect()->route('admin.usaha_produk-index')
            ->with('success', 'Usaha Produk berhasil dihapus.');
    }
}
