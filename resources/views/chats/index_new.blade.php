@extends($layout ?? 'layouts.user')

@section('title', 'Pesan')

@section('css')
<style>
    :root {
        --chat-bg: #f8f9fa;
        --chat-sidebar-bg: #ffffff;
        --chat-header-bg: #ffffff;
        --chat-contact-hover: #f8f9fa;
        --chat-contact-active: #f0f2f5;
        --chat-main-bg: #f8f9fa;
        --chat-text: #1a1a1a;
        --chat-text-muted: #71717a;
        --chat-border: #e4e4e7;
        --chat-header-text: #1a1a1a;
        --chat-accent: #ef4444;
    }

    body.dark-mode {
        --chat-bg: #0f0f0f;
        --chat-sidebar-bg: #1a1a1a;
        --chat-header-bg: #1a1a1a;
        --chat-contact-hover: #242424;
        --chat-contact-active: #2d2d2d;
        --chat-main-bg: #121212;
        --chat-text: #e4e4e7;
        --chat-text-muted: #a1a1aa;
        --chat-border: #3f3f46;
        --chat-header-text: #ffffff;
        --chat-accent: #f87171;
    }

    body { background-color: var(--chat-bg) !important; color: var(--chat-text) !important; overflow-x: hidden; }
    
    .main-container { margin-top: 15px !important; margin-bottom: 15px !important; min-height: auto !important; }

    .chat-container {
        display: flex;
        background: var(--chat-sidebar-bg);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--chat-border);
        height: calc(100vh - 210px);
        min-height: 500px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Contacts Sidebar */
    .contacts-sidebar {
        width: 320px;
        flex-shrink: 0;
        border-right: 1px solid var(--chat-border);
        display: flex;
        flex-direction: column;
        background: var(--chat-sidebar-bg);
    }

    .sidebar-header { 
        padding: 15px 20px; 
        background: var(--chat-header-bg); 
        display: flex; 
        flex-direction: column;
        gap: 12px;
        border-bottom: 1px solid var(--chat-border);
    }
    
    .sidebar-header h2 { font-size: 18px; font-weight: 700; margin: 0; color: var(--chat-header-text); }
    
    .search-box { position: relative; }
    .search-box input { 
        width: 100%; padding: 8px 15px 8px 40px; 
        border-radius: 10px; border: 1px solid var(--chat-border); 
        background: var(--chat-bg); color: var(--chat-text); 
        font-size: 13px; outline: none; transition: all 0.2s;
    }
    .search-box input:focus { border-color: var(--chat-accent); }
    .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--chat-text-muted); font-size: 13px;}

    .sort-by {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--chat-text-muted);
        margin-top: 5px;
    }

    .sort-by span {
        background: var(--chat-bg);
        padding: 3px 12px;
        border-radius: 15px;
        color: var(--chat-text-muted);
        cursor: pointer;
        border: 1px solid var(--chat-border);
    }

    .contact-list { flex: 1; overflow-y: auto; background: var(--chat-sidebar-bg); }
    .contact-item { 
        display: flex; align-items: center; gap: 12px; 
        padding: 12px 20px; text-decoration: none; color: inherit; 
        border-bottom: 1px solid var(--chat-border); transition: all 0.2s;
    }
    .contact-item:hover { background: var(--chat-contact-hover); }
    .contact-item.active { background: var(--chat-contact-active); border-left: 4px solid var(--chat-accent); }
    
    .avatar-wrapper { position: relative; flex-shrink: 0; }
    .avatar-img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 1px solid var(--chat-border); }
    
    .contact-info { flex: 1; min-width: 0; padding-left: 12px; }
    .contact-name-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px; }
    .contact-name { font-weight: 600; color: var(--chat-text); font-size: 15px; }
    .chat-time { font-size: 11px; color: var(--chat-text-muted); }
    .last-msg { display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100% !important; gap: 8px; }
    .last-msg-text { font-size: 12px; color: var(--chat-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }

    .typing-status {
        color: #22c55e;
        font-size: 11px;
        font-weight: 500;
    }

    /* Main Chat Area */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: var(--chat-main-bg);
        color: var(--chat-text-muted);
        text-align: center;
        padding: 40px;
        position: relative;
    }
    .chat-main::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: url('https://static.whatsapp.net/rsrc.php/v3/yl/r/rPj_wJ_Q4V0.png');
        background-repeat: repeat;
        opacity: 0.03;
        z-index: 0;
        pointer-events: none;
    }

    .chat-main h3 {
        color: var(--chat-text);
        font-weight: 700;
        margin-bottom: 15px;
        z-index: 1;
        font-size: 22px;
    }
    .chat-main p { z-index: 1; font-size: 14px; }

    .empty-chat-icon {
        font-size: 60px;
        margin-bottom: 20px;
        color: var(--chat-text-muted);
        opacity: 0.2;
        z-index: 1;
    }

    @media (max-width: 768px) {
        .contacts-sidebar { width: 100%; border-right: none; }
        .chat-main { display: none; }
    }
</style>
</style>
@endsection

@section('content')
<div class="chat-container">
    <div class="contacts-sidebar">
        <div class="sidebar-header">
            <h2>Pesan</h2>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="contact-search" placeholder="Cari">
            </div>
            <div class="sort-by">
                <!-- Sort by <span>Newest <i class="fas fa-chevron-down" style="font-size: 8px;"></i></span> -->
            </div>
        </div>

        <div class="contact-list" id="sidebar-contacts">
            @foreach($chatUsers as $chatUser)
            <a href="{{ route('chats.show', $chatUser->id) }}" class="contact-item" data-name="{{ strtolower($chatUser->display_name) }}">
                <div class="avatar-wrapper">
                    @if($chatUser->usaha && $chatUser->usaha->foto_usaha)
                        <img src="{{ asset('storage/' . $chatUser->usaha->foto_usaha) }}" class="avatar-img" alt="">
                    @elseif($chatUser->foto)
                        <img src="{{ asset('storage/' . $chatUser->foto) }}" class="avatar-img" alt="">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($chatUser->display_name) }}&background=random" class="avatar-img" alt="">
                    @endif
                    <div class="status-dot {{ $chatUser->isOnline() ? 'online' : '' }}" id="status-dot-{{ $chatUser->id }}"></div>
                </div>
                <div class="contact-info">
                    <div class="contact-name-row">
                        <span class="contact-name">{{ $chatUser->display_name }}</span>
                        <span class="chat-time">{{ $chatUser->last_chat_time }}</span>
                    </div>
                    <div class="last-msg">
                        <span class="last-msg-text">
                            @if($chatUser->unread_count > 0)
                                <strong style="color: var(--chat-text);">{{ $chatUser->last_message ?: 'Klik untuk memulai chat' }}</strong>
                            @else
                                {{ $chatUser->last_message ?: 'Klik untuk memulai chat' }}
                            @endif
                        </span>
                        @if($chatUser->unread_count > 0)
                            <span class="unread-badge" style="background: #25d366; color: #000; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">
                                {{ $chatUser->unread_count }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <div class="chat-main">
        <div class="empty-chat-icon">
            <i class="far fa-comments"></i>
        </div>
        <h3>TekoPerakku Chat</h3>
        <p>Pilih salah satu kontak di sebelah kiri untuk mulai mengobrol.</p>
    </div>
</div>
@endsection

@section('js')
<script>
    // Reset active chat user when on index page
    window.activeChatUserId = null;

    document.addEventListener('DOMContentLoaded', function() {
        const contactSearch = document.getElementById('contact-search');
        if (contactSearch) {
            contactSearch.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                document.querySelectorAll('.contact-item').forEach(item => {
                    const name = item.getAttribute('data-name');
                    if (name.includes(query)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        @if(Auth::check())
        window.addEventListener('load', () => {
            if (window.Echo) {
                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('.message.sent', (e) => {
                        console.log('RealTime: New message received from ' + e.message.sender_id + ' (Index Page)');
                        updateSidebar(e.message);
                    })
                    .listen('.message.read', (e) => {
                        if (e.chat) {
                            console.log('WS Event: Message ' + e.chat.id + ' READ by receiver (Index Page)');
                            const contactItem = document.querySelector(`.contact-item[href$="/chats/${e.chat.receiver_id}"]`);
                            if (contactItem) {
                                const badge = contactItem.querySelector('.unread-badge');
                                if (badge) badge.style.display = 'none';
                                const lastMsgTextStrong = contactItem.querySelector('.last-msg-text strong');
                                if (lastMsgTextStrong) {
                                    const span = lastMsgTextStrong.parentElement;
                                    span.innerText = lastMsgTextStrong.innerText;
                                }
                            }
                        }
                    });

                // Chat Module Presence (Status Online di Chat)
                window.Echo.join('chat-module')
                    .here((users) => { updateChatStatuses(users); })
                    .joining((user) => { updateChatStatus(user, true); })
                    .leaving((user) => { updateChatStatus(user, false); });
            }
        });
        @endif

        function updateSidebar(msg) {
            const contactItem = document.querySelector(`.contact-item[href$="/chats/${msg.sender_id}"]`);
            if (contactItem) {
                const sidebar = document.getElementById('sidebar-contacts');
                sidebar.prepend(contactItem);
                
                // Update last message preview
                const lastMsgText = contactItem.querySelector('.last-msg-text');
                if (lastMsgText) {
                    lastMsgText.innerHTML = `<strong>${msg.message || (msg.type === 'image' ? 'Gambar' : 'Berkas')}</strong>`;
                }

                // Update unread count bubble
                const lastMsgRow = contactItem.querySelector('.last-msg');
                let badge = lastMsgRow.querySelector('.unread-badge');
                if (!badge) {
                    const badgeHtml = `<span class="unread-badge" style="background: #25d366; color: #000; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">1</span>`;
                    lastMsgRow.insertAdjacentHTML('beforeend', badgeHtml);
                } else {
                    badge.innerText = parseInt(badge.innerText) + 1;
                    badge.style.display = 'flex';
                }

                // Update time
                const timeEl = contactItem.querySelector('.chat-time');
                if (timeEl) {
                    timeEl.innerText = new Date(msg.created_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });
                }
            }
        }

        function updateChatStatus(user, isOnline) {
            const dot = document.getElementById(`status-dot-${user.id}`);
            if (dot) dot.classList.toggle('online', isOnline);
        }

        function updateChatStatuses(users) {
            users.forEach(u => updateChatStatus(u, true));
        }
    });
</script>
@endsection
