<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelaporan;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PelaporanController extends Controller
{
    public function index()
    {
        $laporans = Pelaporan::with('usaha')->latest()->get();
        return view('admin.pelaporan.index-pelaporan', compact('laporans'));
    }

    public function create()
    {
        $usahas = Usaha::all();
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $lastLaporan = Pelaporan::orderBy('id', 'desc')->first();
        $nextId = $lastLaporan ? $lastLaporan->id + 1 : 1;
        $autoKode = 'LAP' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('admin.pelaporan.create-pelaporan', compact('usahas', 'months', 'autoKode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_laporan' => 'required|string|unique:pelaporan',
            'usaha_id' => 'required|exists:usaha,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
            'omset' => 'required|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        Pelaporan::create($request->all());

        return redirect()->route('admin.pelaporan-index')
            ->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $laporan = Pelaporan::findOrFail($id);
        $usahas = Usaha::all();
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return view('admin.pelaporan.edit-pelaporan', compact('laporan', 'usahas', 'months'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Pelaporan::findOrFail($id);
        $request->validate([
            'kode_laporan' => 'required|string|unique:pelaporan,kode_laporan,' . $id,
            'usaha_id' => 'required|exists:usaha,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
            'omset' => 'required|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        $laporan->update($request->all());

        return redirect()->route('admin.pelaporan-index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = Pelaporan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('admin.pelaporan-index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function chart()
    {
        // Simple mock data for chart or actual calculation
        $data = Pelaporan::select('bulan', 'omset', 'tahun')
            ->orderBy('tahun', 'asc')
            ->get();
            
        return view('admin.pelaporan.chart-pelaporan', compact('data'));
    }
}
