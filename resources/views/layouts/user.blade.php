<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TekoPerakku</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #333;
            --text-main: #1a1a1a;
            --text-muted: #71717a;
            --border-color: #e4e4e7;
            --bg-light: #fafafa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #fff;
            color: var(--text-main);
        }

        /* Header Styles */
        .main-header {
            border-bottom: 1px solid var(--border-color);
            padding: 20px 80px;
            background: #fff;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .logo {
            font-size: 26px;
            font-weight: 700;
            color: #000;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .search-container {
            flex: 1;
            max-width: 500px;
            margin: 0 40px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 14px;
            outline: none;
            background: #fff;
            color: #666;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a1a1aa;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .action-link {
            color: #3f3f46;
            text-decoration: none;
            font-size: 20px;
            transition: color 0.2s;
        }

        .user-profile-trigger {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-main);
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            background: #e4e4e7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        /* Nav Menu */
        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .nav-link {
            text-decoration: none;
            color: #18181b;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-link i {
            margin-left: 5px;
            font-size: 10px;
        }

        /* Main Content Layout */
        .main-container {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 40px;
            display: flex !important;
            align-items: flex-start;
            gap: 40px;
            min-height: 600px;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid transparent;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-item {
            margin-bottom: 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            text-decoration: none;
            color: #71717a;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .sidebar-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .sidebar-link:hover {
            background: #f4f4f5;
            color: #18181b;
        }

        .sidebar-link.active {
            background: #f4f4f5;
            color: #18181b;
        }

        /* Content Area */
        .content-area {
            flex: 1;
        }

        @media (max-width: 1024px) {
            .main-header { padding: 20px 40px; }
            .main-container { padding: 0 40px; flex-direction: column; }
            .sidebar { width: 100%; }
            .nav-menu { gap: 20px; }
        }
    </style>
    @yield('css')
</head>
<body>
    <header class="main-header">
        <div class="header-top">
            <a href="{{ route('guest-index') }}" class="logo">TekoPerakku</a>
            
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Cari Produk">
            </div>

            <div class="header-actions">
                <a href="#" class="action-link"><i class="far fa-heart"></i></a>
                <a href="{{ route('chats.index') }}" class="action-link"><i class="far fa-comment-dots"></i></a>
                
                <div class="user-profile-trigger">
                    <div class="avatar-circle">
                        <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->username).'&background=e4e4e7&color=71717a' }}" alt="">
                    </div>
                    <span class="user-name">{{ Auth::user()->username }}</span>
                    <i class="fas fa-chevron-down" style="font-size: 10px; color: #a1a1aa;"></i>
                </div>
            </div>
        </div>

        <nav class="nav-menu">
            <a href="{{ route('guest-index') }}" class="nav-link">Beranda</a>
            <a href="{{ route('guest-products') }}" class="nav-link">Katalog</a>
            <a href="#" class="nav-link">Kategori <i class="fas fa-chevron-down"></i></a>
            <a href="#" class="nav-link">Tentang Kami</a>
            <a href="#" class="nav-link">Kontak</a>
        </nav>
    </header>

    <main class="main-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('user.profile') }}" class="sidebar-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <i class="far fa-user"></i> Profil
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('chats.index') }}" class="sidebar-link {{ request()->routeIs('chats*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i> Chat
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('favorit') }}" class="sidebar-link {{ request()->routeIs('favorit') ? 'active' : '' }}">
                        <i class="far fa-heart"></i> Favorit
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('pengaturan') }}" class="sidebar-link {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                </li>
            </ul>
        </aside>

        <section class="content-area">
            @yield('content')
        </section>
    </main>

    @yield('js')
</body>
</html>
