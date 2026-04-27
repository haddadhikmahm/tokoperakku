<?php

namespace App\Http\Controllers\Admin;
use App\Models\Pengerajin;
use App\Models\Usaha;
use App\Models\UsahaPengerajin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengerajinController extends Controller
{
    public function index()
    {
        $dataPengerajin = Pengerajin::all();
        return view('admin.pengerajin.index-pengerajin', [
            'pengerajins' => $dataPengerajin
        ]);
    }

    public function create()
    {
        $usahas = Usaha::all();
        // Generate automatic code
        $lastPengerajin = Pengerajin::orderBy('id', 'desc')->first();
        $nextId = $lastPengerajin ? $lastPengerajin->id + 1 : 1;
        $autoKode = 'PRJ' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        
        return view('admin.pengerajin.create-pengerajin', compact('usahas', 'autoKode'));
    }

    public function edit($id)
    {
        $pengerajin = Pengerajin::findOrFail($id);
        $usahas = Usaha::all();
        $selectedUsaha = UsahaPengerajin::where('pengerajin_id', $id)->pluck('usaha_id')->toArray();
        return view('admin.pengerajin.edit-pengerajin', compact('pengerajin', 'usahas', 'selectedUsaha'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pengerajin' => 'required|string|max:255|unique:pengerajin',
            'nama_pengerajin' => 'required|string|max:255',
            'jk_pengerajin' => 'required|string|max:10',
            'usia_pengerajin' => 'required|integer',
            'telp_pengerajin' => 'required|string|max:15',
            'email_pengerajin' => 'required|email|max:255',
            'alamat_pengerajin' => 'required|string|max:255',
            'foto_pengerajin' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'usaha_ids' => 'nullable|array',
            'usaha_ids.*' => 'exists:usaha,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_pengerajin')) {
            $originalName = $request->file('foto_pengerajin')->getClientOriginalName();
            $path = $request->file('foto_pengerajin')->storeAs('foto_pengerajin', $originalName, 'public');
            $data['foto_pengerajin'] = $path;
        }

        $pengerajin = Pengerajin::create($data);

        if ($request->has('usaha_ids')) {
            foreach ($request->usaha_ids as $usahaId) {
                UsahaPengerajin::create([
                    'usaha_id' => $usahaId,
                    'pengerajin_id' => $pengerajin->id,
                ]);
            }
        }

        return redirect()->route('admin.pengerajin-index')
            ->with('success', 'Pengerajin berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pengerajin = Pengerajin::findOrFail($id);

        $request->validate([
            'kode_pengerajin' => 'required|string|max:255|unique:pengerajin,kode_pengerajin,' . $id,
            'nama_pengerajin' => 'required|string|max:255',
            'jk_pengerajin' => 'required|string|max:10',
            'usia_pengerajin' => 'required|integer',
            'telp_pengerajin' => 'required|string|max:15',
            'email_pengerajin' => 'required|email|max:255',
            'alamat_pengerajin' => 'required|string|max:255',
            'foto_pengerajin' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'usaha_ids' => 'nullable|array',
            'usaha_ids.*' => 'exists:usaha,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_pengerajin')) {
            if ($pengerajin->foto_pengerajin) {
                Storage::disk('public')->delete($pengerajin->foto_pengerajin);
            }
            $originalName = $request->file('foto_pengerajin')->getClientOriginalName();
            $path = $request->file('foto_pengerajin')->storeAs('foto_pengerajin', $originalName, 'public');
            $data['foto_pengerajin'] = $path;
        }

        $pengerajin->update($data);

        // Update relations
        UsahaPengerajin::where('pengerajin_id', $id)->delete();
        if ($request->has('usaha_ids')) {
            foreach ($request->usaha_ids as $usahaId) {
                UsahaPengerajin::create([
                    'usaha_id' => $usahaId,
                    'pengerajin_id' => $pengerajin->id,
                ]);
            }
        }

        return redirect()->route('admin.pengerajin-index')
            ->with('success', 'Data Pengerajin berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pengerajin = Pengerajin::findOrFail($id);
        if ($pengerajin->foto_pengerajin) {
            Storage::disk('public')->delete($pengerajin->foto_pengerajin);
        }
        UsahaPengerajin::where('pengerajin_id', $id)->delete();
        $pengerajin->delete();

        return redirect()->route('admin.pengerajin-index')
            ->with('success', 'Pengerajin berhasil dihapus.');
    }
}
