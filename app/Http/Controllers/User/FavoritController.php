<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favoritProduks()->with(['usaha', 'fotoProduk', 'reviews'])->get();
        
        $layout = 'layouts.user';
        if ($user->role == 'umkm') {
            $layout = 'layouts.umkm';
        } elseif (in_array($user->role, ['admin_utama', 'admin_wilayah'])) {
            $layout = 'layouts.admin_premium';
        }
        
        return view('user.favorit', compact('favorites', 'layout'));
    }

    public function toggle(Request $request, $id)
    {
        $user = Auth::user();
        $user->favoritProduks()->toggle($id);
        
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $user->favoritProduks()->detach($id);
        
        return redirect()->back()->with('success', 'Produk dihapus dari favorit.');
    }
}
