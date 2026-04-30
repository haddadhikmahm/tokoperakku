@extends($layout ?? 'layouts.user')

@section('title', 'Pesan')

@section('css')
<style>
    .chat-container {
        display: flex;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #f1f1f4;
        height: calc(100vh - 250px);
        min-height: 500px;
    }

    /* Contacts Sidebar */
    .contacts-sidebar {
        width: 350px;
        border-right: 1px solid #f1f1f4;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 30px;
    }

    .sidebar-header h2 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .search-box {
        position: relative;
        margin-bottom: 15px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border-radius: 12px;
        border: 1px solid #f1f1f4;
        background: #fafafa;
        font-size: 14px;
        outline: none;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a1a1aa;
    }

    .sort-by {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #71717a;
    }

    .sort-by span {
        color: #3b82f6;
        font-weight: 600;
        cursor: pointer;
    }

    .contact-list {
        flex: 1;
        overflow-y: auto;
        padding: 0 15px 30px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-radius: 15px;
        text-decoration: none;
        color: inherit;
        transition: background 0.2s;
        margin-bottom: 5px;
    }

    .contact-item:hover {
        background: #f8fafc;
    }

    .contact-item.active {
        background: #f1f5f9;
    }

    .avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .avatar-img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
    }

    .status-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        background: #cbd5e1;
    }

    .status-dot.online {
        background: #22c55e;
    }

    .contact-info {
        flex: 1;
        min-width: 0;
    }

    .contact-name-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .contact-name {
        font-size: 14px;
        font-weight: 700;
        color: #18181b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-time {
        font-size: 11px;
        color: #a1a1aa;
    }

    .last-msg {
        font-size: 13px;
        color: #71717a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .typing-status {
        color: #ef4444;
        font-size: 12px;
        font-weight: 500;
    }

    /* Main Chat Area */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: #fafafa;
        color: #a1a1aa;
        text-align: center;
        padding: 40px;
    }

    .empty-chat-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.2;
    }

    @media (max-width: 768px) {
        .contacts-sidebar { width: 100%; }
        .chat-main { display: none; }
    }
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
                Sort by <span>Newest <i class="fas fa-chevron-down" style="font-size: 8px;"></i></span>
            </div>
        </div>

        <div class="contact-list" id="sidebar-contacts">
            @foreach($chatUsers as $chatUser)
            <a href="{{ route('chats.show', $chatUser->id) }}" class="contact-item" data-name="{{ strtolower($chatUser->display_name) }}">
                <div class="avatar-wrapper">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($chatUser->username) }}&background=random" class="avatar-img" alt="">
                    <div class="status-dot" id="status-dot-{{ $chatUser->id }}"></div>
                </div>
                <div class="contact-info">
                    <div class="contact-name-row">
                        <span class="contact-name">{{ $chatUser->display_name }}</span>
                        <span class="chat-time">{{ $chatUser->last_chat_time }}</span>
                    </div>
                    <div class="last-msg">
                        @if($chatUser->unread_count > 0)
                            <strong>{{ $chatUser->last_message ?: 'Klik untuk memulai chat' }}</strong>
                        @else
                            {{ $chatUser->last_message ?: 'Klik untuk memulai chat' }}
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
                        updateSidebar(e.message);
                    });

                window.Echo.join('online')
                    .here((users) => { updateStatuses(users); })
                    .joining((user) => { updateStatus(user, true); })
                    .leaving((user) => { updateStatus(user, false); });
            }
        });
        @endif

        function updateSidebar(msg) {
            const contactItem = document.querySelector(`.contact-item[href$="/chats/${msg.sender_id}"]`);
            if (contactItem) {
                const sidebar = document.getElementById('sidebar-contacts');
                sidebar.prepend(contactItem);
                
                const lastMsgEl = contactItem.querySelector('.last-msg');
                if (lastMsgEl) {
                    lastMsgEl.innerHTML = `<strong>${msg.message}</strong>`;
                }
                const timeEl = contactItem.querySelector('.chat-time');
                if (timeEl) {
                    timeEl.innerText = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                }
            }
        }

        function updateStatus(user, isOnline) {
            const dot = document.getElementById(`status-dot-${user.id}`);
            if (dot) dot.classList.toggle('online', isOnline);
        }

        function updateStatuses(users) {
            users.forEach(u => updateStatus(u, true));
        }
    });
</script>
@endsection
