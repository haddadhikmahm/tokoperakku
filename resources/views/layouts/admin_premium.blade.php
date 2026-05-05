<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TekoPerakku</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #333;
            --bg-light: #fdfdfd;
            --border-color: #f0f0f0;
            --text-main: #1a1a1a;
            --text-muted: #8e8e8e;
            --active-bg: #f5f5f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #fff;
            color: var(--text-main);
            overflow-x: hidden;
        }

        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            padding-top: 20px;
            border-right: 1px solid var(--border-color);
        }

        .sidebar-header {
            padding: 20px 40px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #000;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.5px;
            line-height: 1;
            font-family: 'Inter', sans-serif;
        }

        .logo span {
            font-size: 14px;
            font-weight: 400;
            color: #1a1a1a;
            margin-top: 0px;
            letter-spacing: 0px;
        }

        .sidebar-menu {
            flex: 1;
            padding: 0 20px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            margin-bottom: 8px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .menu-item i {
            margin-right: 15px;
            width: 22px;
            text-align: center;
            font-size: 18px;
        }

        .menu-item:hover {
            background-color: var(--active-bg);
            color: var(--text-main);
        }

        .menu-item.active {
            background-color: var(--active-bg);
            color: var(--text-main);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }

        .header {
            height: 90px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 60px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            background: #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-weight: 500;
            font-size: 14px;
            color: #333;
        }

        .content-body {
            padding: 20px 60px 60px 60px;
        }

        /* DataTables Overrides */
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 0 !important;
            padding-top: 0 !important;
            display: flex !important;
            gap: 8px !important;
            align-items: center !important;
        }

        .dataTables_wrapper .dataTables_info {
            display: none !important;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none !important;
        }

        .dataTables_wrapper .dataTables_length {
            display: none !important;
        }

        .paginate_button {
            width: 36px !important;
            height: 36px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #6b7280 !important;
            text-decoration: none !important;
            transition: all 0.2s !important;
            cursor: pointer !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .paginate_button:hover:not(.disabled):not(.current) {
            background-color: #f3f4f6 !important;
            color: #1a1a1a !important;
        }

        .paginate_button.current {
            background-color: #991b1b !important;
            color: #ffffff !important;
        }

        .paginate_button.disabled {
            color: #d1d5db !important;
            cursor: default !important;
        }

        .dataTables_empty {
            text-align: center !important;
            padding: 40px !important;
            color: #9ca3af !important;
            font-style: italic !important;
            background-color: #fff !important;
        }

        /* Common Management Styles */
        .manage-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 40px;
            padding-top: 10px;
        }

        .manage-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .manage-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-add {
            background: #991b1b;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 45px;
        }

        .search-container {
            position: relative;
        }

        .search-input {
            padding: 0 15px 0 45px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            width: 320px;
            height: 45px;
            outline: none;
            background-color: #fcfcfc;
            color: #666;
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }

        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
        }

        .admin-table th {
            text-align: left;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            color: #888;
            border-bottom: 1px solid #f3f4f6;
        }

        .admin-table td {
            padding: 18px 20px;
            font-size: 14px;
            color: #4b5563;
            border-bottom: 1px solid #f9fafb;
            vertical-align: middle;
        }

        .admin-table tr:hover td {
            background-color: #fcfcfc;
        }

        /* Action Buttons & Dropdowns */
        .action-btn {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .action-btn:hover {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 120px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 10;
            border: 1px solid #f0f0f0;
            padding: 8px 0;
        }

        .dropdown-content a, .dropdown-content button {
            color: #4b5563;
            padding: 10px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
        }

        .dropdown-content a:hover, .dropdown-content button:hover {
            background-color: #f9fafb;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Pagination Alignment */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }

        .results-text {
            color: #6b7280;
            font-size: 14px;
        }

        .pagination-wrapper {
            display: flex;
            gap: 8px;
        }

        .page-link {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 14px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
        }

        .page-link.active {
            background-color: #991b1b;
            color: #fff;
        }

        .page-link:hover:not(.active) {
            background-color: #f3f4f6;
        }
    </style>
    @yield('css')
</head>
<body>
    <div class="layout-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    ADMIN
                    <span>TekoPerakku</span>
                </div>
            </div>
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('profile') }}" class="menu-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
                    <i class="far fa-user"></i> Profil
                </a>
@if(auth()->user()->role == 'admin_utama')
                <a href="{{ route('admin.manage.index') }}" class="menu-item {{ request()->routeIs('admin.manage.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Kelola Admin
                </a>
@endif
                <a href="{{ route('admin.pengerajin-index') }}" class="menu-item {{ request()->routeIs('admin.pengerajin*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Pengrajin
                </a>
                <a href="{{ route('admin.usaha-index') }}" class="menu-item {{ request()->routeIs('admin.usaha*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i> Usaha
                </a>
                <a href="{{ route('admin.produk-index') }}" class="menu-item {{ request()->routeIs('admin.produk*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Produk
                </a>
                <a href="{{ route('admin.pelaporan-index') }}" class="menu-item {{ request()->routeIs('admin.pelaporan*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Pelaporan
                </a>
                <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #991b1b; margin-top: 20px;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <div class="user-nav">
                    <div class="user-avatar">
                        <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->username).'&background=e4e4e7&color=71717a' }}" alt="User">
                    </div>
                    <span class="user-name">{{ Auth::user()->username }} <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px; color: #888;"></i></span>
                </div>
            </header>

            <div class="content-body">
                @yield('content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite(['resources/js/app.js'])
    <script>
        @if(Auth::check())
        window.addEventListener('load', () => {
            if (window.Echo) {
                // Mark all received messages as delivered when connecting
                axios.post('{{ route("chats.delivered.all") }}');

                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('.message.sent', (e) => {
                        // If we are not actively reading this chat window
                        if (!window.location.pathname.includes('/chats/' + e.message.sender_id)) {
                            axios.post(`/chats/${e.message.sender_id}/delivered`);
                        }
                    });
            }
        });
        @endif
    </script>
    @yield('js')
</body>
</html>
