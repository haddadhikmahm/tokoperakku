<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        
        if ($role == 'user') {
            return redirect()->route('profile');
        }

        $stats = [
            'total_pengerajin' => 0,
            'total_usaha' => 0,
            'total_produk' => 0,
            'total_pelaporan' => 0,
        ];

        if ($role == 'admin_utama') {
            $stats['total_pengerajin'] = \App\Models\Pengerajin::count();
            $stats['total_usaha'] = \App\Models\Usaha::count();
            $stats['total_produk'] = \App\Models\Produk::count();
            $stats['total_pelaporan'] = \App\Models\Pelaporan::count();
        } elseif ($role == 'admin_wilayah') {
            $stats['total_usaha'] = \App\Models\Usaha::where('wilayah_id', $user->wilayah_id)->count();
            $stats['total_pengerajin'] = \App\Models\Pengerajin::whereHas('usahaPengerajin.usaha', function($q) use ($user) {
                $q->where('wilayah_id', $user->wilayah_id);
            })->count();
            $stats['total_produk'] = \App\Models\Produk::whereHas('usaha', function($q) use ($user) {
                $q->where('wilayah_id', $user->wilayah_id);
            })->count();
            $stats['total_pelaporan'] = \App\Models\Pelaporan::whereHas('usaha', function($q) use ($user) {
                $q->where('wilayah_id', $user->wilayah_id);
            })->count();
        } elseif ($role == 'umkm') {
            $stats['total_usaha'] = \App\Models\Usaha::where('user_id', $user->id)->count();
            $stats['total_produk'] = \App\Models\Produk::whereHas('usaha', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
            $stats['total_pelaporan'] = \App\Models\Pelaporan::whereHas('usaha', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
        }

        $layout = $role == 'umkm' ? 'layouts.umkm' : 'layouts.admin_premium';
        return view('admin.dashboard.dashboard', compact('stats', 'layout'));
    }
}
