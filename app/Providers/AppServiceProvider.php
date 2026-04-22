<?php

namespace App\Providers;
use App\Http\Middleware\RoleCheck; // pastikan ada use ini di atas
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; // pastikan ada use ini di atas
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Route::aliasMiddleware('role', RoleCheck::class);
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\Event::listen(\JeroenNoten\LaravelAdminLte\Events\BuildingMenu::class, function (\JeroenNoten\LaravelAdminLte\Events\BuildingMenu $event) {
            $user = \Illuminate\Support\Facades\Auth::user();

            if (!$user) {
                return;
            }

            // Dynamic User Menu in Top Navbar
            $event->menu->add([
                'text' => $user->username,
                'topnav_right' => true,
                'icon' => 'fas fa-user',
                'submenu' => [
                    [
                        'text' => 'Profile',
                        'url' => 'admin/profile',
                        'icon' => 'fas fa-id-badge',
                    ],
                    [
                        'text' => 'Change Password',
                        'url' => 'admin/change-password',
                        'icon' => 'fas fa-key',
                    ],
                    [
                        'text' => 'Logout',
                        'url' => '#',
                        'icon' => 'fas fa-sign-out-alt',
                        'id' => 'logout-button',
                    ]
                ],
            ]);

            // Common items for many roles
            $role = $user->role;

            if ($role === 'admin_utama') {
                $event->menu->add([
                    'text' => 'Profile',
                    'url'  => 'admin/profile',
                    'icon' => 'fas fa-fw fa-user',
                ]);
                $event->menu->add([
                    'text' => 'Kelola Admin',
                    'url'  => '#',
                    'icon' => 'fas fa-fw fa-users-cog',
                ]);
                $event->menu->add([
                    'text' => 'Pengrajin',
                    'url'  => 'admin/pengerajin',
                    'icon' => 'fas fa-fw fa-users',
                ]);
                $event->menu->add([
                    'text' => 'Usaha',
                    'url'  => 'admin/usaha',
                    'icon' => 'fas fa-fw fa-briefcase',
                ]);
                $event->menu->add([
                    'text' => 'Produk',
                    'url'  => 'admin/produk',
                    'icon' => 'fas fa-fw fa-box',
                ]);
                $event->menu->add([
                    'text' => 'Pelaporan',
                    'url'  => 'admin/export-data',
                    'icon' => 'fas fa-fw fa-file-export',
                ]);
            } elseif ($role === 'admin_wilayah') {
                $event->menu->add([
                    'text' => 'Profile',
                    'url'  => 'admin/profile',
                    'icon' => 'fas fa-fw fa-user',
                ]);
                $event->menu->add([
                    'text' => 'Pengrajin',
                    'url'  => 'admin/pengerajin',
                    'icon' => 'fas fa-fw fa-users',
                ]);
                $event->menu->add([
                    'text' => 'Usaha',
                    'url'  => 'admin/usaha',
                    'icon' => 'fas fa-fw fa-briefcase',
                ]);
                $event->menu->add([
                    'text' => 'Produk',
                    'url'  => 'admin/produk',
                    'icon' => 'fas fa-fw fa-box',
                ]);
                $event->menu->add([
                    'text' => 'Pelaporan',
                    'url'  => 'admin/export-data',
                    'icon' => 'fas fa-fw fa-file-export',
                ]);
            } elseif ($role === 'umkm') {
                $event->menu->add([
                    'text' => 'Profile',
                    'url'  => 'admin/profile',
                    'icon' => 'fas fa-fw fa-user',
                ]);
                $unreadCount = \App\Models\Chat::where('receiver_id', $user->id)->where('is_read', false)->count();
                $event->menu->add([
                    'text' => 'Chat',
                    'url'  => 'chats',
                    'icon' => 'fas fa-fw fa-comments',
                    'label' => $unreadCount > 0 ? $unreadCount : null,
                    'label_color' => 'primary',
                ]);
                $event->menu->add([
                    'text' => 'Produk',
                    'url'  => 'admin/produk',
                    'icon' => 'fas fa-fw fa-box',
                ]);
                $event->menu->add([
                    'text' => 'Pengaturan',
                    'url'  => '#',
                    'icon' => 'fas fa-fw fa-cogs',
                ]);
                $event->menu->add([
                    'text' => 'Pelaporan',
                    'url'  => 'admin/export-data',
                    'icon' => 'fas fa-fw fa-file-export',
                ]);
            } elseif ($role === 'user') {
                $event->menu->add([
                    'text' => 'Profile',
                    'url'  => 'admin/profile',
                    'icon' => 'fas fa-fw fa-user',
                ]);
                $unreadCount = \App\Models\Chat::where('receiver_id', $user->id)->where('is_read', false)->count();
                $event->menu->add([
                    'text' => 'Chat',
                    'url'  => 'chats',
                    'icon' => 'fas fa-fw fa-comments',
                    'label' => $unreadCount > 0 ? $unreadCount : null,
                    'label_color' => 'primary',
                ]);
                $event->menu->add([
                    'text' => 'Favorit',
                    'url'  => '#',
                    'icon' => 'fas fa-fw fa-heart',
                ]);
                $event->menu->add([
                    'text' => 'Pengaturan',
                    'url'  => '#',
                    'icon' => 'fas fa-fw fa-cogs',
                ]);
            }
        });
    }
}
