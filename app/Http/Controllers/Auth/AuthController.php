<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // arahkan ke view login
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/profile'); // sesuaikan dengan tujuan
        }

        // return back()->withErrors([
        //     'email' => 'Email atau password salah.',
        // ])->onlyInput('email');

        return back()->withErrors([
            'username' => 'Nama atau password salah.',
        ])->onlyInput('username');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // arahkan ke halaman login setelah logout
    }

    public function profile()
    {
        $user = Auth::user(); // ambil data user yang sedang login
        if (!$user) {
            return redirect()->route('login');
        }
        
        $layout = 'layouts.admin_premium';
        if ($user->role == 'umkm') {
            $layout = 'layouts.umkm';
        } elseif ($user->role == 'user') {
            $layout = 'layouts.user';
        }

        return view('auth.profile', compact('user', 'layout'));
    }

    public function changePassword()
    {
        $user = Auth::user(); // ambil data user yang sedang login
        if (!$user) {
            return redirect()->route('login'); // jika tidak ada user yang login, arahkan ke halaman login
        }
        return view('auth.change-password', compact('user')); // arahkan ke view ubah password
    }
    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // `confirmed` untuk validasi confirm_password
        ]);

        // Ambil data user yang sedang login
        $user = Auth::user();

        // Cek apakah password lama yang dimasukkan sesuai dengan password yang tersimpan
        if (!password_verify($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password jika valid
        $user->password = bcrypt($request->new_password);  // Menggunakan bcrypt untuk enkripsi password baru
        $user->save();  // Simpan perubahan password

        // Redirect dengan pesan sukses
        return redirect()->route('profile')->with('status', 'Password berhasil diubah.');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        // Don't allow deleting the main admin for safety in this demo
        if ($user->username === 'admin_utama') {
            return back()->withErrors(['error' => 'Admin utama tidak dapat dihapus.']);
        }

        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Akun anda telah dihapus.');
    }

}
