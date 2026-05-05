<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PengerajinController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\KategoriProdukController;
use App\Http\Controllers\Admin\FotoProdukController;
use App\Http\Controllers\Admin\UsahaController;
use App\Http\Controllers\Admin\JenisUsahaController;
use App\Http\Controllers\Admin\UsahaPengerajinController;
use App\Http\Controllers\Admin\UsahaJenisController;
use App\Http\Controllers\Admin\UsahaProdukController;
use App\Http\Controllers\Admin\AdminManageController;
use App\Http\Controllers\Guest\PageController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\PelaporanController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('loginForm');
Route::post('login', [AuthController::class, 'login'])->name('login');

//Guest
Route::get('', [PageController::class, 'index'])->name('guest-index');
Route::get('about', [PageController::class, 'about'])->name('guest-about');
Route::get('contact', [PageController::class, 'contact'])->name('guest-contact');
Route::get('products', [PageController::class, 'products'])->name('guest-products');
Route::get('single-product', [PageController::class, 'singleProduct'])->name('guest-single-product');
Route::get('katalog', [PageController::class, 'katalog'])->name('guest-katalog');
Route::get('detail-usaha/{usaha}', [PageController::class, 'detailUsaha'])->name('guest-detail-usaha');
Route::get('produk/kategori/{slug}', [PageController::class, 'productsByCategory'])->name('guest-productsByCategory');
Route::get('produk/{slug}', [PageController::class, 'singleProduct'])->name('guest-singleProduct');


// Dynamic Middleware for all logged in users (Auth)
Route::middleware(['auth'])->group(function () {
    Route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('admin/profile', [ProfileController::class, 'index'])->name('admin.profile');
    Route::get('umkm/profile', [ProfileController::class, 'index'])->name('umkm.profile');
    Route::get('user/profile', [ProfileController::class, 'index'])->name('user.profile');
    
    Route::get('profile', function() {
        $role = auth()->user()->role;
        if ($role == 'umkm') return redirect()->route('umkm.profile');
        if ($role == 'user') return redirect()->route('user.profile');
        return redirect()->route('admin.profile');
    })->name('profile');

    Route::get('admin/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::get('umkm/profile/edit', [ProfileController::class, 'edit'])->name('umkm.profile.edit');
    Route::get('user/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');

    Route::get('profile/edit', function() {
        $role = auth()->user()->role;
        if ($role == 'umkm') return redirect()->route('umkm.profile.edit');
        if ($role == 'user') return redirect()->route('user.profile.edit');
        return redirect()->route('admin.profile.edit');
    })->name('profile.edit');

    Route::put('admin/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('umkm/profile/update', [ProfileController::class, 'update'])->name('umkm.profile.update');
    Route::put('user/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');

    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('admin/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::post('update-password', [AuthController::class, 'updatePassword'])->name('update-password');
    
    // Favorit
    Route::get('favorit', [\App\Http\Controllers\User\FavoritController::class, 'index'])->name('favorit');
    Route::post('favorit/toggle/{id}', [\App\Http\Controllers\User\FavoritController::class, 'toggle'])->name('favorit.toggle');
    Route::delete('favorit/{id}', [\App\Http\Controllers\User\FavoritController::class, 'destroy'])->name('favorit.destroy');

    Route::get('pengaturan', function() {
        $layout = auth()->user()->role == 'umkm' ? 'layouts.umkm' : 'layouts.user';
        return view('user.pengaturan', compact('layout'));
    })->name('pengaturan');

    // Chat Routes (Shared)
    Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('chats/{user}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('chats', [ChatController::class, 'store'])->name('chats.store');
    Route::post('chats/{user}/read', [ChatController::class, 'markAsRead'])->name('chats.read');
    Route::post('chats/{user}/delivered', [ChatController::class, 'markAsDelivered'])->name('chats.delivered');
    Route::post('chats/delivered/all', [ChatController::class, 'markAllAsDelivered'])->name('chats.delivered.all');

    // Account Deletion
    Route::delete('account/delete', [AuthController::class, 'deleteAccount'])->name('account.delete');
});

// Admin & UMKM shared routes (Produk & Pelaporan)
Route::middleware(['role:admin_utama,admin_wilayah,umkm'])->group(function () {
    // Produk
    Route::get('admin/produk', [ProdukController::class, 'index'])->name('admin.produk-index');
    Route::get('admin/produk/create', [ProdukController::class, 'create'])->name('admin.produk-create');
    Route::post('admin/produk/store', [ProdukController::class, 'store'])->name('admin.produk-store');
    Route::get('admin/produk/edit/{id}', [ProdukController::class, 'edit'])->name('admin.produk-edit');
    Route::put('admin/produk/update/{id}', [ProdukController::class, 'update'])->name('admin.produk-update');
    Route::delete('admin/produk/destroy/{id}', [ProdukController::class, 'destroy'])->name('admin.produk-destroy');

    // Export / Pelaporan
    Route::get('admin/export-data', [ExportController::class, 'index'])->name('admin.export-data');
    Route::get('admin/export-pengerajin', [ExportController::class, 'exportPengerajin'])->name('admin.export-pengerajin');

    // Dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('umkm/dashboard', [DashboardController::class, 'index'])->name('umkm.dashboard');

    // Pelaporan CRUD
    Route::get('admin/pelaporan', [PelaporanController::class, 'index'])->name('admin.pelaporan-index');
    Route::get('admin/pelaporan/create', [PelaporanController::class, 'create'])->name('admin.pelaporan-create');
    Route::post('admin/pelaporan/store', [PelaporanController::class, 'store'])->name('admin.pelaporan-store');
    Route::get('admin/pelaporan/edit/{id}', [PelaporanController::class, 'edit'])->name('admin.pelaporan-edit');
    Route::put('admin/pelaporan/update/{id}', [PelaporanController::class, 'update'])->name('admin.pelaporan-update');
    Route::delete('admin/pelaporan/destroy/{id}', [PelaporanController::class, 'destroy'])->name('admin.pelaporan-destroy');
    Route::get('admin/pelaporan/chart', [PelaporanController::class, 'chart'])->name('admin.pelaporan-chart');
});

// Admin only routes
Route::middleware(['role:admin_utama,admin_wilayah'])->group(function () {
    // Pengerajin
    Route::get('admin/pengerajin', [PengerajinController::class, 'index'])->name('admin.pengerajin-index');
    Route::get('admin/pengerajin/create', [PengerajinController::class, 'create'])->name('admin.pengerajin-create');
    Route::post('admin/pengerajin/store', [PengerajinController::class, 'store'])->name('admin.pengerajin-store');
    Route::get('admin/pengerajin/edit/{id}', [PengerajinController::class, 'edit'])->name('admin.pengerajin-edit');
    Route::put('admin/pengerajin/update/{id}', [PengerajinController::class, 'update'])->name('admin.pengerajin-update');
    Route::delete('admin/pengerajin/destroy/{id}', [PengerajinController::class, 'destroy'])->name('admin.pengerajin-destroy');

    // Usaha
    Route::get('admin/usaha', [UsahaController::class, 'index'])->name('admin.usaha-index');
    Route::get('admin/usaha/create', [UsahaController::class, 'create'])->name('admin.usaha-create');
    Route::post('admin/usaha/store', [UsahaController::class, 'store'])->name('admin.usaha-store');
    Route::get('admin/usaha/edit/{id}', [UsahaController::class, 'edit'])->name('admin.usaha-edit');
    Route::put('admin/usaha/update/{id}', [UsahaController::class, 'update'])->name('admin.usaha-update');
    Route::delete('admin/usaha/destroy/{id}', [UsahaController::class, 'destroy'])->name('admin.usaha-destroy');

    // Jenis Usaha
    Route::get('admin/jenis-usaha', [JenisUsahaController::class, 'index'])->name('admin.jenis_usaha-index');
    Route::get('admin/jenis-usaha/create', [JenisUsahaController::class, 'create'])->name('admin.jenis_usaha-create');
    Route::post('admin/jenis-usaha/store', [JenisUsahaController::class, 'store'])->name('admin.jenis_usaha-store');
    Route::get('admin/jenis-usaha/edit/{id}', [JenisUsahaController::class, 'edit'])->name('admin.jenis_usaha-edit');
    Route::put('admin/jenis-usaha/update/{id}', [JenisUsahaController::class, 'update'])->name('admin.jenis_usaha-update');
    Route::delete('admin/jenis-usaha/destroy/{id}', [JenisUsahaController::class, 'destroy'])->name('admin.jenis_usaha-destroy');

    // Kategori Produk
    Route::get('admin/kategori-produk', [KategoriProdukController::class, 'index'])->name('admin.kategori_produk-index');
    Route::get('admin/kategori-produk/create', [KategoriProdukController::class, 'create'])->name('admin.kategori_produk-create');
    Route::post('admin/kategori-produk/store', [KategoriProdukController::class, 'store'])->name('admin.kategori_produk-store');
    Route::get('admin/kategori-produk/edit/{id}', [KategoriProdukController::class, 'edit'])->name('admin.kategori_produk-edit');
    Route::put('admin/kategori-produk/update/{id}', [KategoriProdukController::class, 'update'])->name('admin.kategori_produk-update');
    Route::delete('admin/kategori-produk/destroy/{id}', [KategoriProdukController::class, 'destroy'])->name('admin.kategori_produk-destroy');

    // Foto Produk
    Route::get('admin/foto-produk', [FotoProdukController::class, 'index'])->name('admin.foto_produk-index');
    Route::get('admin/foto-produk/create', [FotoProdukController::class, 'create'])->name('admin.foto_produk-create');
    Route::post('admin/foto-produk/store', [FotoProdukController::class, 'store'])->name('admin.foto_produk-store');
    Route::get('admin/foto-produk/edit/{id}', [FotoProdukController::class, 'edit'])->name('admin.foto_produk-edit');
    Route::put('admin/foto-produk/update/{id}', [FotoProdukController::class, 'update'])->name('admin.foto_produk-update');
    Route::delete('admin/foto-produk/destroy/{id}', [FotoProdukController::class, 'destroy'])->name('admin.foto_produk-destroy');

    // Usaha Pengerajin
    Route::get('admin/usaha-pengerajin', [UsahaPengerajinController::class, 'index'])->name('admin.usaha_pengerajin-index');
    Route::get('admin/usaha-pengerajin/create', [UsahaPengerajinController::class, 'create'])->name('admin.usaha_pengerajin-create');
    Route::post('admin/usaha-pengerajin/store', [UsahaPengerajinController::class, 'store'])->name('admin.usaha_pengerajin-store');
    Route::get('admin/usaha-pengerajin/edit/{id}', [UsahaPengerajinController::class, 'edit'])->name('admin.usaha_pengerajin-edit');
    Route::put('admin/usaha-pengerajin/update/{id}', [UsahaPengerajinController::class, 'update'])->name('admin.usaha_pengerajin-update');
    Route::delete('admin/usaha-pengerajin/destroy/{id}', [UsahaPengerajinController::class, 'destroy'])->name('admin.usaha_pengerajin-destroy');

    // Usaha Jenis
    Route::get('admin/usaha-jenis', [UsahaJenisController::class, 'index'])->name('admin.usaha_jenis-index');
    Route::get('admin/usaha-jenis/create', [UsahaJenisController::class, 'create'])->name('admin.usaha_jenis-create');
    Route::post('admin/usaha-jenis/store', [UsahaJenisController::class, 'store'])->name('admin.usaha_jenis-store');
    Route::get('admin/usaha-jenis/edit/{id}', [UsahaJenisController::class, 'edit'])->name('admin.usaha_jenis-edit');
    Route::put('admin/usaha-jenis/update/{id}', [UsahaJenisController::class, 'update'])->name('admin.usaha_jenis-update');
    Route::delete('admin/usaha-jenis/destroy/{id}', [UsahaJenisController::class, 'destroy'])->name('admin.usaha_jenis-destroy');

    // Usaha Produk
    Route::get('admin/usaha-produk', [UsahaProdukController::class, 'index'])->name('admin.usaha_produk-index');
    Route::get('admin/usaha-produk/create', [UsahaProdukController::class, 'create'])->name('admin.usaha_produk-create');
    Route::post('admin/usaha-produk/store', [UsahaProdukController::class, 'store'])->name('admin.usaha_produk-store');
    Route::get('admin/usaha-produk/edit/{id}', [UsahaProdukController::class, 'edit'])->name('admin.usaha_produk-edit');
    Route::put('admin/usaha-produk/update/{id}', [UsahaProdukController::class, 'update'])->name('admin.usaha_produk-update');
    Route::delete('admin/usaha-produk/destroy/{id}', [UsahaProdukController::class, 'destroy'])->name('admin.usaha_produk-destroy');

    // Admin Management
    Route::resource('admin/manage', AdminManageController::class)->names([
        'index' => 'admin.manage.index',
        'create' => 'admin.manage.create',
        'store' => 'admin.manage.store',
        'edit' => 'admin.manage.edit',
        'update' => 'admin.manage.update',
        'destroy' => 'admin.manage.destroy',
    ]);
});

// UMKM Routes
Route::middleware(['role:umkm'])->group(function () {
    Route::get('umkm/dashboard', [DashboardController::class, 'index'])->name('umkm.dashboard');
});

// Review Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
