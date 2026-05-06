<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TekoPerakku</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <a href="{{ route('favorit') }}" class="action-link"><i class="far fa-heart"></i></a>
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
                // Mark all received messages as delivered when connecting
                axios.post('{{ route("chats.delivered.all") }}');

                // Helper to clear UI badges
                const clearUnreadBadge = (userId, usahaId) => {
                    const selector = `.contact-item[data-user-id="${userId}"][data-usaha-id="${usahaId || 'null'}"]`;
                    const contactItem = document.querySelector(selector);
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
                                               (window.activeUsahaId == e.message.usaha_id) &&
                                               document.visibilityState === 'visible' &&
                                               document.hasFocus();

                        if (isStrictlyInRoom) {
                            clearUnreadBadge(e.message.sender_id, e.message.usaha_id);
                            axios.post(`{{ url('chats') }}/${e.message.sender_id}/read?usaha_id=${e.message.usaha_id || ''}`);
                        } else {
                            axios.post(`{{ url('chats') }}/${e.message.sender_id}/delivered?usaha_id=${e.message.usaha_id || ''}`);
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
