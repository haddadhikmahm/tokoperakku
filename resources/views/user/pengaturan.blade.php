@extends($layout ?? 'layouts.user')

@section('title', 'Pengaturan')

@section('css')
<style>
    .page-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #18181b;
    }

    .settings-card {
        background: #fff;
        border-radius: 20px;
        padding: 10px 30px;
        border: 1px solid #f1f1f4;
        margin-bottom: 30px;
    }

    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 25px 0;
        border-bottom: 1px solid #f4f4f5;
    }

    .setting-item:last-child { border-bottom: none; }

    .setting-info {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #18181b;
        font-weight: 600;
        font-size: 15px;
    }

    .setting-info i {
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    .setting-control select {
        padding: 8px 15px;
        border-radius: 10px;
        border: 1px solid #e4e4e7;
        font-size: 13px;
        font-weight: 500;
        outline: none;
        background: #fff;
        min-width: 120px;
    }

    /* Switch styling */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .switch input { opacity: 0; width: 0; height: 0; }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #e4e4e7;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px; width: 18px;
        left: 3px; bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider { background-color: #18181b; }
    input:checked + .slider:before { transform: translateX(20px); }

    /* Delete Account Area */
    .delete-account-card {
        background: #fff;
        border-radius: 20px;
        padding: 25px 30px;
        border: 1px solid #f1f1f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background 0.2s;
    }

    .delete-account-card:hover {
        background: #fffafa;
    }

    .delete-label {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #ef4444;
        font-weight: 700;
        font-size: 15px;
    }

    /* Modal Styling */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: #fff;
        width: 100%;
        max-width: 600px;
        border-radius: 24px;
        padding: 50px;
        text-align: center;
        position: relative;
    }

    .close-modal {
        position: absolute;
        top: 30px;
        right: 30px;
        font-size: 24px;
        color: #a1a1aa;
        cursor: pointer;
    }

    .warning-icon {
        font-size: 120px;
        color: #991b1b;
        margin-bottom: 30px;
    }

    .modal-text {
        font-size: 18px;
        font-weight: 600;
        color: #18181b;
        margin-bottom: 40px;
        font-style: italic;
    }

    .modal-actions {
        display: flex;
        gap: 20px;
        justify-content: center;
    }

    .btn-modal-no {
        background: #991b1b;
        color: #fff;
        border: none;
        padding: 15px 60px;
        border-radius: 12px;
        font-size: 20px;
        font-weight: 700;
        cursor: pointer;
        flex: 1;
    }

    .btn-modal-yes {
        background: #fff;
        color: #18181b;
        border: 1px solid #e4e4e7;
        padding: 15px 60px;
        border-radius: 12px;
        font-size: 20px;
        font-weight: 700;
        cursor: pointer;
        flex: 1;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    body.dark-mode select,
    body.dark-mode .slider {
        background-color: #2a2a2a !important;
        border-color: #444 !important;
        color: #fff !important;
    }
    body.dark-mode .main-header {
        background: #1e1e1e !important;
        border-color: #333 !important;
    }
    body.dark-mode .logo { color: #fff !important; }
    body.dark-mode .nav-link { color: #e4e4e7 !important; }
    body.dark-mode .sidebar-link { color: #a1a1aa !important; }
    body.dark-mode .sidebar-link:hover, 
    body.dark-mode .sidebar-link.active {
        background: #2a2a2a !important;
        color: #fff !important;
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Pengaturan</h1>

<div class="settings-card">
    <div class="setting-item">
        <div class="setting-info">
            <i class="far fa-eye"></i> Ukuran Font
        </div>
        <div class="setting-control">
            <select id="font-size-select">
                <option value="small">Kecil</option>
                <option value="medium" selected>Sedang</option>
                <option value="large">Besar</option>
            </select>
        </div>
    </div>
    <div class="setting-item">
        <div class="setting-info">
            <i class="far fa-moon"></i> Mode Gelap
        </div>
        <div class="setting-control">
            <label class="switch">
                <input type="checkbox" id="dark-mode-toggle">
                <span class="slider"></span>
            </label>
        </div>
    </div>
    <div class="setting-item">
        <div class="setting-info">
            <i class="far fa-bell"></i> Notifikasi Email
        </div>
        <div class="setting-control">
            <label class="switch">
                <input type="checkbox" id="email-notif-toggle" checked>
                <span class="slider"></span>
            </label>
        </div>
    </div>
    <div class="setting-item">
        <div class="setting-info">
            <i class="fas fa-globe"></i> Bahasa
        </div>
        <div class="setting-control">
            <select id="language-select">
                <option value="id" selected>Indonesia</option>
                <option value="en">English</option>
            </select>
        </div>
    </div>
</div>

<div class="delete-account-card" id="btn-open-delete">
    <div class="delete-label">
        <i class="fas fa-biohazard"></i> Hapus Akun
    </div>
    <i class="fas fa-chevron-right" style="color: #ef4444;"></i>
</div>

<!-- Modal Hapus Akun -->
<div class="modal-overlay" id="delete-modal">
    <div class="modal-content">
        <i class="fas fa-times close-modal" id="btn-close-modal"></i>
        <div class="warning-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <p class="modal-text">Apakah anda yakin untuk menghapus akun?</p>
        <div class="modal-actions">
            <button class="btn-modal-no" id="btn-no">Tidak</button>
            <form action="{{ route('account.delete') }}" method="POST" style="flex: 1; display: flex;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal-yes">Iya</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Modal Logic ---
        const modal = document.getElementById('delete-modal');
        const btnOpen = document.getElementById('btn-open-delete');
        const btnClose = document.getElementById('btn-close-modal');
        const btnNo = document.getElementById('btn-no');

        if(btnOpen) btnOpen.onclick = () => modal.style.display = 'flex';
        if(btnClose) btnClose.onclick = () => modal.style.display = 'none';
        if(btnNo) btnNo.onclick = () => modal.style.display = 'none';

        window.onclick = (event) => {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // --- Settings Logic ---
        
        // 1. Dark Mode
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        
        const applyDarkMode = (enabled) => {
            if (enabled) {
                document.body.classList.add('dark-mode');
                darkModeToggle.checked = true;
            } else {
                document.body.classList.remove('dark-mode');
                darkModeToggle.checked = false;
            }
        };

        applyDarkMode(isDarkMode);

        darkModeToggle.onchange = (e) => {
            const enabled = e.target.checked;
            localStorage.setItem('darkMode', enabled);
            applyDarkMode(enabled);
        };

        // 2. Font Size
        const fontSelect = document.getElementById('font-size-select');
        const savedFont = localStorage.getItem('fontSize') || 'medium';

        const applyFontSize = (size) => {
            document.body.classList.remove('font-small', 'font-medium', 'font-large');
            document.body.classList.add('font-' + size);
            fontSelect.value = size;
        };

        applyFontSize(savedFont);

        fontSelect.onchange = (e) => {
            const size = e.target.value;
            localStorage.setItem('fontSize', size);
            applyFontSize(size);
            window.location.reload();
        };

        // 3. Email Notifications
        const notifToggle = document.getElementById('email-notif-toggle');
        const isNotifOn = localStorage.getItem('emailNotif') !== 'false'; // default true
        
        notifToggle.checked = isNotifOn;
        notifToggle.onchange = (e) => {
            localStorage.setItem('emailNotif', e.target.checked);
            alert('Pengaturan notifikasi berhasil disimpan!');
        };

        // 4. Language
        const langSelect = document.getElementById('language-select');
        const savedLang = localStorage.getItem('language') || 'id';

        langSelect.value = savedLang;
        langSelect.onchange = (e) => {
            localStorage.setItem('language', e.target.value);
            alert('Bahasa berhasil diubah ke ' + (e.target.value === 'id' ? 'Indonesia' : 'English'));
            window.location.reload();
        };
    });
</script>
@endsection
