<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        
        if ($role == 'umkm' || $role == 'user') {
            return redirect()->route('profile');
        }
        
        return view('admin.dashboard.dashboard');
    }
}
