@extends($layout ?? 'layouts.user')

@section('title', 'Pesan')

@section('css')
<style>
    body { background-color: #111b21 !important; color: #e9edef !important; }
    .chat-container {
        display: flex;
        background: #111b21;
        border-radius: 0;
        overflow: hidden;
        border: none;
        height: calc(100vh - 80px);
        min-height: 500px;
        margin-top: 0 !important;
        box-shadow: none;
    }

    /* Contacts Sidebar */
    .contacts-sidebar {
        width: 30%;
        min-width: 300px;
        border-right: 1px solid #222d34;
        display: flex;
        flex-direction: column;
        background: #111b21;
    }

    .sidebar-header { 
        padding: 10px 15px; 
        background: #202c33; 
        display: flex; 
        align-items: center; 
        height: 60px;
    }
    
    .sidebar-header h2 { font-size: 16px; font-weight: 600; margin: 0; color: #e9edef; }
    
    .search-box { position: relative; margin: 8px 12px; }
    .search-box input { 
        width: 100%; padding: 7px 15px 7px 40px; 
        border-radius: 8px; border: none; 
        background: #202c33; color: #e9edef; 
        font-size: 14px; outline: none; 
    }
    .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #8696a0; font-size: 12px;}

    .sort-by {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #8696a0;
        padding: 0 15px 10px;
        border-bottom: 1px solid #222d34;
    }

    .sort-by span {
        background: #202c33;
        padding: 4px 12px;
        border-radius: 16px;
        color: #8696a0;
        cursor: pointer;
    }

    .contact-list { flex: 1; overflow-y: auto; background: #111b21; }
    .contact-item { 
        display: flex; align-items: center; gap: 15px; 
        padding: 10px 15px; text-decoration: none; color: inherit; 
        border-bottom: 1px solid #222d34; transition: background 0.2s;
    }
    .contact-item:hover { background: #202c33; }
    .contact-item.active { background: #2a3942; }
    
    .avatar-wrapper { position: relative; flex-shrink: 0; }
    .avatar-img { width: 49px; height: 49px; border-radius: 50%; object-fit: cover; }
    .status-dot { display: none; }
    
    .contact-info { flex: 1; min-width: 0; }
    .contact-name-row { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 3px; }
    .contact-name { font-size: 16px; font-weight: 400; color: #e9edef; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .chat-time { font-size: 12px; color: #8696a0; }
    .last-msg { font-size: 13px; color: #8696a0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .typing-status {
        color: #00a884;
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
        background: #222e35;
        color: #8696a0;
        text-align: center;
        padding: 40px;
        border-bottom: 6px solid #00a884;
    }

    .chat-main h3 {
        color: #e9edef;
        font-weight: 300;
        margin-bottom: 15px;
    }

    .empty-chat-icon {
        font-size: 80px;
        margin-bottom: 30px;
        color: #8696a0;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .contacts-sidebar { width: 100%; border-right: none; }
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
                    <div class="last-msg d-flex justify-content-between align-items-center w-100">
                        <span style="flex:1; overflow:hidden; text-overflow:ellipsis;">
                            @if($chatUser->unread_count > 0)
                                <strong style="color: #e9edef;">{{ $chatUser->last_message ?: 'Klik untuk memulai chat' }}</strong>
                            @else
                                {{ $chatUser->last_message ?: 'Klik untuk memulai chat' }}
                            @endif
                        </span>
                        @if($chatUser->unread_count > 0)
                            <span style="background: #00a884; color: #111b21; border-radius: 50%; padding: 2px 6px; font-size: 11px; font-weight: 600; margin-left: 5px;">
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
