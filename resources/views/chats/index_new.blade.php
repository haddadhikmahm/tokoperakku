@extends('adminlte::page')

@section('title', 'WhatsApp Chat')

@section('content')
<div class="container-fluid p-0" style="height: calc(100vh - 120px); overflow: hidden;">
    <div class="row h-100 no-gutters">
        <!-- Sidebar Contacts -->
        <div class="col-md-4 col-lg-3 d-flex flex-column bg-white border-right h-100">
            <div class="p-3 bg-light d-flex align-items-center justify-content-between border-bottom">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random" class="rounded-circle" style="width: 40px;" alt="">
            </div>
            
            <div class="p-2 border-bottom bg-white">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="contact-search" class="form-control bg-light border-left-0" placeholder="Cari atau mulai chat baru">
                </div>
            </div>

            <div class="flex-grow-1 overflow-auto bg-white" id="sidebar-contacts">
                @foreach($chatUsers as $chatUser)
                <a href="{{ route('chats.show', $chatUser->id) }}" class="text-decoration-none text-dark contact-item" data-name="{{ strtolower($chatUser->display_name) }}" data-user-id="{{ $chatUser->id }}">
                    <div class="d-flex align-items-center p-3 border-bottom hover-light">
                        <div class="position-relative">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($chatUser->username) }}&background=random" class="rounded-circle mr-3" style="width: 49px;" alt="">
                            <span class="online-indicator d-none" id="status-dot-{{ $chatUser->id }}"></span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="mb-0 text-truncate font-weight-bold">{{ $chatUser->display_name }}</h6>
                                <small class="text-muted text-xs">{{ $chatUser->last_chat_time }}</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center text-truncate w-75">
                                    @if($chatUser->last_message_sender_id == Auth::id())
                                        @if($chatUser->last_message_is_read)
                                            <i class="fas fa-check-double text-primary mr-1" style="font-size: 10px;"></i>
                                        @else
                                            <i class="fas fa-check text-muted mr-1 sidebar-check-{{ $chatUser->id }}" style="font-size: 10px;"></i>
                                        @endif
                                    @endif
                                    <p class="mb-0 text-sm text-muted text-truncate">{{ $chatUser->last_message ?: 'Klik untuk memulai chat' }}</p>
                                </div>
                                @if($chatUser->unread_count > 0)
                                    <span class="badge badge-success badge-pill">{{ $chatUser->unread_count }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Empty State (WhatsApp Style) -->
        <div class="col-md-8 col-lg-9 d-flex flex-column h-100">
            <div class="h-100 d-flex flex-column justify-content-center align-items-center bg-light border-left">
                <div class="text-center p-5 rounded-circle bg-white shadow-sm mb-4">
                    <i class="fab fa-whatsapp fa-7x text-success"></i>
                </div>
                <h4 class="text-muted">WhatsApp Web</h4>
                <p class="text-muted px-5 text-center">Kirim dan terima pesan seketika. <br>Pilih salah satu kontak di sebelah kiri untuk mulai mengobrol.</p>
                <hr class="w-25">
                <small class="text-muted mt-3"><i class="fas fa-lock"></i> Terenkripsi secara end-to-end</small>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* AdminLTE content adjustment */
    .content-wrapper { background: #f0f2f5 !important; }
    .content-header { display: none !important; }
    .content { padding: 0 !important; }
    .main-sidebar { background-color: #ffffff !important; }
    .main-sidebar .brand-link { 
        background-color: #ffffff !important; 
        border-bottom: 1px solid #dee2e6;
        pointer-events: none; /* Disable clicking */
        cursor: default;
    }
    .main-sidebar .brand-link .brand-text {
        color: #343a40 !important; /* Ensure text is visible (dark) */
        opacity: 1 !important;
    }

    .hover-light:hover { background-color: #f5f6f6 !important; }
    
    .online-indicator {
        position: absolute;
        bottom: 5px;
        right: 15px;
        width: 12px;
        height: 12px;
        background-color: #25d366;
        border: 2px solid white;
        border-radius: 50%;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@vite(['resources/js/app.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactSearch = document.getElementById('contact-search');
        
        // --- CONTACT SEARCH ---
        if (contactSearch) {
            contactSearch.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                document.querySelectorAll('.contact-item').forEach(item => {
                    const name = item.getAttribute('data-name');
                    if (name.includes(query)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        @if(Auth::check())
        window.addEventListener('load', () => {
            if (window.Echo) {
                // Listen for private messages
                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('.message.sent', (e) => {
                        updateSidebar(e.message);
                    });

                // Listen for online presence
                window.Echo.join('online')
                    .here((users) => {
                        users.forEach(u => {
                            const dot = document.getElementById(`status-dot-${u.id}`);
                            if (dot) dot.classList.remove('d-none');
                        });
                    })
                    .joining((user) => {
                        const dot = document.getElementById(`status-dot-${user.id}`);
                        if (dot) dot.classList.remove('d-none');
                    })
                    .leaving((user) => {
                        const dot = document.getElementById(`status-dot-${user.id}`);
                        if (dot) dot.classList.add('d-none');
                    });
            }
        });
        @endif

        function updateSidebar(msg) {
            const contactLink = document.querySelector(`a[href$="/chats/${msg.sender_id}"]`);
            if (contactLink) {
                const sidebar = document.getElementById('sidebar-contacts');
                sidebar.prepend(contactLink);
                const preview = contactLink.querySelector('p.text-muted');
                if (preview) preview.innerText = msg.message;
                const timeEl = contactLink.querySelector('small.text-muted');
                if (timeEl) timeEl.innerText = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                let badge = contactLink.querySelector('.badge-success');
                if (!badge) {
                    const container = contactLink.querySelector('.d-flex.justify-content-between.align-items-center');
                    container.insertAdjacentHTML('beforeend', '<span class="badge badge-success badge-pill">1</span>');
                } else {
                    badge.innerText = parseInt(badge.innerText) + 1;
                }
            }
        }
    });
</script>
@stop
