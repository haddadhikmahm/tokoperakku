<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TekoPerakku</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        @yield('css')
    </style>
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
                <a href="{{ route('profile') }}" class="menu-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="far fa-user"></i> Profil Admin
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i> Kelola Admin
                </a>
                <a href="{{ route('admin.pengerajin-index') }}" class="menu-item">
                    <i class="fas fa-users"></i> Pengrajin
                </a>
                <a href="{{ route('admin.usaha-index') }}" class="menu-item">
                    <i class="fas fa-briefcase"></i> Usaha
                </a>
                <a href="{{ route('admin.produk-index') }}" class="menu-item">
                    <i class="fas fa-box"></i> Produk
                </a>
                <a href="{{ route('admin.export-data') }}" class="menu-item">
                    <i class="fas fa-box"></i> Pelaporan
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <div class="user-nav">
                    <div class="user-avatar">
                        <img src="{{ asset('assets/images/admin-avatar.png') }}" alt="User">
                    </div>
                    <span class="user-name">{{ Auth::user()->username }} <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px; color: #888;"></i></span>
                </div>
            </header>

            <div class="content-body">
                @yield('content')
            </div>
        </main>
    </div>
    @yield('js')
</body>
</html>
