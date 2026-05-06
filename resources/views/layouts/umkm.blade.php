<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TekoPerakku</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
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

        /* Category Dropdown */
        .category-dropdown-wrapper {
            position: relative;
        }
        .category-dropdown {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 220px;
            display: none;
            z-index: 1000;
            margin-top: 15px;
            padding: 8px;
            animation: slideDown 0.2s ease-out;
        }
        .category-dropdown-wrapper:hover .category-dropdown {
            display: block;
        }
        .category-dropdown::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 0;
            right: 0;
            height: 20px;
        }
        .category-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            text-decoration: none;
            color: var(--text-main);
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .category-item:hover {
            background: #f4f4f5;
        }

        /* Main Content Layout */
        .main-container {
            width: 100%;
            margin: 40px 0;
            padding: 0 80px;
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

        /* Global Settings Classes */
        body.font-small { --base-font-size: 12px; }
        body.font-medium { --base-font-size: 15px; }
        body.font-large { --base-font-size: 19px; }

        body.font-small .nav-link, body.font-small .sidebar-link { font-size: 11px !important; }
        body.font-medium .nav-link, body.font-medium .sidebar-link { font-size: 14px !important; }
        body.font-large .nav-link, body.font-large .sidebar-link { font-size: 18px !important; }

        body.font-small .logo { font-size: 20px !important; }
        body.font-large .logo { font-size: 32px !important; }

        body.dark-mode {
            background-color: #121212 !important;
            color: #e4e4e7 !important;
        }
        body.dark-mode .main-header,
        body.dark-mode .sidebar {
            background: #1e1e1e !important;
            border-color: #333 !important;
        }
        body.dark-mode .logo,
        body.dark-mode .user-name {
            color: #fff !important;
        }
        body.dark-mode .nav-link { color: #e4e4e7 !important; }
        body.dark-mode .sidebar-link { color: #a1a1aa !important; }
        body.dark-mode .sidebar-link:hover, 
        body.dark-mode .sidebar-link.active {
            background: #2a2a2a !important;
            color: #fff !important;
        }
        body.dark-mode .search-input {
            background: #2a2a2a !important;
            border-color: #444 !important;
            color: #fff !important;
        }

        /* Profile Dropdown Styles */
        .user-profile-trigger {
            position: relative;
        }
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 180px;
            display: none;
            z-index: 1000;
            margin-top: 15px;
            padding: 8px;
            animation: slideDown 0.2s ease-out;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .profile-dropdown.show {
            display: block;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 15px;
            text-decoration: none !important;
            color: var(--text-main) !important;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .dropdown-item:hover {
            background: #f4f4f5;
        }
        .dropdown-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        
        body.dark-mode .profile-dropdown {
            background: #1e1e1e;
            border-color: #333;
        }
        body.dark-mode .dropdown-item:hover {
            background: #2a2a2a;
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

        .btn-add:hover {
            opacity: 0.9;
            color: #fff;
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

        body.dark-mode .manage-title { color: #fff !important; }
        body.dark-mode .admin-table th { border-color: #333 !important; }
        body.dark-mode .admin-table td { border-color: #222 !important; color: #a1a1aa !important; }
        body.dark-mode .admin-table tr:hover td { background-color: #2a2a2a !important; }
        body.dark-mode .dropdown-content { background: #1e1e1e; border-color: #333; }
        body.dark-mode .dropdown-content a, body.dark-mode .dropdown-content button { color: #e4e4e7; }
        body.dark-mode .search-input { background: #2a2a2a; border-color: #444; color: #fff; }
    </style>
    @yield('css')
</head>
<body>
    <header class="main-header">
        <div class="header-top">
            <a href="{{ route('profile') }}" class="logo">TekoPerakku</a>
            
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Cari Produk">
            </div>

            <div class="header-actions">
                <a href="{{ route('chats.index') }}" class="action-link"><i class="far fa-comment-dots"></i></a>
                
                <div class="user-profile-trigger" id="profileTrigger">
                    <div class="avatar-circle">
                        <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama ?? Auth::user()->username).'&background=e4e4e7&color=71717a' }}" alt="">
                    </div>
                    <span class="user-name">{{ Auth::user()->nama ?? Auth::user()->username }}</span>
                    <i class="fas fa-chevron-down" style="font-size: 10px; color: #a1a1aa;"></i>

                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="far fa-user"></i> Profil Saya
                        </a>
                        <a href="{{ route('pengaturan') }}" class="dropdown-item">
                            <i class="fas fa-cog"></i> Pengaturan
                        </a>
                        <hr style="margin: 8px 0; border: 0; border-top: 1px solid var(--border-color);">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-header">
                            @csrf
                            <button type="submit" class="dropdown-item w-100 border-0 bg-transparent text-left" style="color: #ef4444 !important; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <nav class="nav-menu">
            <a href="{{ route('guest-index') }}" class="nav-link">Beranda</a>
            <a href="{{ route('guest-katalog') }}" class="nav-link">Katalog</a>
            <div class="category-dropdown-wrapper">
                <a href="#" class="nav-link">Kategori <i class="fas fa-chevron-down"></i></a>
                <div class="category-dropdown">
                    @foreach($kategoris as $kategori)
                        <a href="{{ route('guest-katalog', ['kategori' => $kategori->slug]) }}" class="category-item">
                            {{ $kategori->nama_kategori_produk }}
                        </a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('guest-about') }}" class="nav-link">Tentang Kami</a>
            <a href="{{ route('guest-contact') }}" class="nav-link">Kontak</a>
        </nav>
    </header>

    <main class="main-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('umkm.profile') }}" class="sidebar-link {{ request()->routeIs('umkm.profile') ? 'active' : '' }}">
                        <i class="far fa-user"></i> Profil
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('chats.index') }}" class="sidebar-link {{ request()->routeIs('chats*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i> Chat
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.produk-index') }}" class="sidebar-link {{ request()->routeIs('admin.produk*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> Produk
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('pengaturan') }}" class="sidebar-link {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.pelaporan-index') }}" class="sidebar-link {{ request()->routeIs('admin.pelaporan*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Pelaporan
                    </a>
                </li>
            </ul>
        </aside>

        <section class="content-area">
            @yield('content')
        </section>
    </main>

    <script>
        // Apply settings immediately to prevent flicker
        (function() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) document.body.classList.add('dark-mode');
            
            const savedFont = localStorage.getItem('fontSize') || 'medium';
            document.body.classList.add('font-' + savedFont);

            // Translation Dictionary
            const translations = {
                en: {
                    "Beranda": "Home",
                    "Katalog": "Catalog",
                    "Kategori": "Categories",
                    "Tentang Kami": "About Us",
                    "Kontak": "Contact",
                    "Profil": "Profile",
                    "Chat": "Chat",
                    "Favorit": "Favorites",
                    "Pengaturan": "Settings",
                    "Cari Produk": "Search Products",
                    "Ukuran Font": "Font Size",
                    "Mode Gelap": "Dark Mode",
                    "Notifikasi Email": "Email Notifications",
                    "Bahasa": "Language",
                    "Hapus Akun": "Delete Account",
                    "Kecil": "Small",
                    "Sedang": "Medium",
                    "Besar": "Large",
                    "Apakah anda yakin untuk menghapus akun?": "Are you sure you want to delete your account?",
                    "Tidak": "No",
                    "Iya": "Yes"
                }
            };

            const savedLang = localStorage.getItem('language') || 'id';
            if (savedLang === 'en') {
                document.addEventListener('DOMContentLoaded', () => {
                    const dict = translations.en;
                    const walk = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
                    let node;
                    while (node = walk.nextNode()) {
                        const trimmed = node.nodeValue.trim();
                        if (dict[trimmed]) {
                            node.nodeValue = node.nodeValue.replace(trimmed, dict[trimmed]);
                        }
                    }
                    // Special case for placeholders
                    document.querySelectorAll('[placeholder]').forEach(el => {
                        if (dict[el.placeholder]) el.placeholder = dict[el.placeholder];
                    });
                });
            }

            // Profile Dropdown Toggle
            document.addEventListener('DOMContentLoaded', () => {
                const trigger = document.getElementById('profileTrigger');
                const dropdown = document.getElementById('profileDropdown');

                if (trigger && dropdown) {
                    trigger.addEventListener('click', (e) => {
                        e.stopPropagation();
                        dropdown.classList.toggle('show');
                    });

                    document.addEventListener('click', () => {
                        dropdown.classList.remove('show');
                    });
                }
            });
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite(['resources/js/app.js'])
    <script>
        @if(Auth::check())
        window.addEventListener('load', () => {
            if (window.Echo) {
                // Helper to clear UI badges
                const clearUnreadBadge = (userId) => {
                    const contactItem = document.querySelector(`.contact-item[href$="/chats/${userId}"]`);
                    if (contactItem) {
                        const badge = contactItem.querySelector('.unread-badge');
                        if (badge) badge.style.display = 'none';
                        const lastMsgTextStrong = contactItem.querySelector('.last-msg-text strong');
                        if (lastMsgTextStrong) {
                            const span = lastMsgTextStrong.parentElement;
                            span.innerText = lastMsgTextStrong.innerText;
                        }
                    }
                };

                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('.message.sent', (e) => {
                        const pathRegex = new RegExp('/chats/' + e.message.sender_id + '$');
                        const isStrictlyInRoom = window.activeChatUserId && 
                                               window.activeChatUserId == e.message.sender_id && 
                                               pathRegex.test(window.location.pathname) &&
                                               document.visibilityState === 'visible' &&
                                               document.hasFocus();

                        if (isStrictlyInRoom) {
                            clearUnreadBadge(e.message.sender_id);
                            axios.post(`{{ url('chats') }}/${e.message.sender_id}/read`);
                        } else {
                            axios.post(`{{ url('chats') }}/${e.message.sender_id}/delivered`);
                        }
                    });

                // Auto-mark as read when switching back to the tab
                const markCurrentAsRead = () => {
                    const pathRegex = new RegExp('/chats/' + window.activeChatUserId + '$');
                    if (window.activeChatUserId && document.visibilityState === 'visible' && document.hasFocus() && pathRegex.test(window.location.pathname)) {
                        clearUnreadBadge(window.activeChatUserId);
                        axios.post(`{{ url('chats') }}/${window.activeChatUserId}/read`);
                    }
                };

                // Clear badge on initial page load if we are already in a chat room
                if (window.activeChatUserId) {
                    clearUnreadBadge(window.activeChatUserId);
                }
                window.addEventListener('visibilitychange', markCurrentAsRead);
                window.addEventListener('focus', markCurrentAsRead);

                // Global online presence
                window.Echo.join('online')
                    .here((users) => { /* Online globally */ })
                    .joining((user) => { /* User joined globally */ })
                    .leaving((user) => { /* User left globally */ });
            }
        });
        @endif
    </script>
    @yield('js')
</body>
</html>
