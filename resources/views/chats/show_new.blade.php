@extends($layout ?? 'layouts.user')

@section('title', 'Chat with ' . $user->username)

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

    /* Contacts Sidebar (Same as index) */
    .contacts-sidebar {
        width: 350px;
        border-right: 1px solid #f1f1f4;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header { padding: 30px; }
    .sidebar-header h2 { font-size: 20px; font-weight: 700; margin-bottom: 20px; }
    .search-box { position: relative; margin-bottom: 15px; }
    .search-box input { width: 100%; padding: 12px 20px 12px 45px; border-radius: 12px; border: 1px solid #f1f1f4; background: #fafafa; font-size: 14px; outline: none; }
    .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a1a1aa; }
    .contact-list { flex: 1; overflow-y: auto; padding: 0 15px 30px; }
    .contact-item { display: flex; align-items: center; gap: 15px; padding: 15px; border-radius: 15px; text-decoration: none; color: inherit; margin-bottom: 5px; }
    .contact-item.active { background: #f1f5f9; }
    .avatar-wrapper { position: relative; flex-shrink: 0; }
    .avatar-img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
    .status-dot { position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff; background: #cbd5e1; }
    .status-dot.online { background: #22c55e; }
    .contact-info { flex: 1; min-width: 0; }
    .contact-name { font-size: 14px; font-weight: 700; color: #18181b; }

    /* Main Chat Window */
    .chat-window {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .chat-header {
        padding: 20px 30px;
        border-bottom: 1px solid #f1f1f4;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-info h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .header-info span {
        font-size: 12px;
        font-weight: 600;
        color: #71717a;
    }

    .header-info span.online {
        color: #ef4444; /* Image shows Online in red/pinkish? Wait, it says 'Online' in red? Actually, looking closer at image 1, 'Online' is red. Image 2, it's also red. */
    }

    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: #fff;
    }

    .message-row {
        display: flex;
        flex-direction: column;
        max-width: 80%;
    }

    .message-row.self {
        align-self: flex-end;
        align-items: flex-end;
    }

    .message-row.other {
        align-self: flex-start;
        align-items: flex-start;
    }

    .message-content {
        padding: 12px 18px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
    }

    .other .message-content {
        background: #f4f4f5;
        color: #18181b;
        border-bottom-left-radius: 4px;
    }

    .self .message-content {
        background: #fee2e2; /* Light red/pink like image */
        color: #18181b;
        border-bottom-right-radius: 4px;
    }

    .message-time {
        font-size: 11px;
        color: #a1a1aa;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .self .message-time { justify-content: flex-end; }

    .input-bar {
        padding: 20px 30px;
        border-top: 1px solid #f1f1f4;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .attachment-btn {
        color: #71717a;
        font-size: 20px;
        cursor: pointer;
    }

    .input-wrapper {
        flex: 1;
        position: relative;
    }

    .message-input {
        width: 100%;
        padding: 12px 20px;
        border-radius: 12px;
        border: 1px solid #f1f1f4;
        font-size: 14px;
        outline: none;
    }

    .send-btn {
        color: #ef4444;
        font-weight: 700;
        font-size: 14px;
        background: none;
        border: none;
        cursor: pointer;
        text-transform: lowercase;
    }

    .date-divider {
        text-align: center;
        position: relative;
        margin: 20px 0;
    }

    .date-divider::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #f1f1f4;
        z-index: 1;
    }

    .date-divider span {
        background: #fff;
        padding: 0 15px;
        font-size: 11px;
        color: #a1a1aa;
        position: relative;
        z-index: 2;
        text-transform: uppercase;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="chat-container">
    <div class="contacts-sidebar d-none d-md-flex">
        <div class="sidebar-header">
            <h2>Pesan</h2>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="contact-search" placeholder="Cari">
            </div>
        </div>

        <div class="contact-list">
            @foreach($chatUsers as $chatUser)
            <a href="{{ route('chats.show', $chatUser->id) }}" class="contact-item {{ $user->id == $chatUser->id ? 'active' : '' }}" data-name="{{ strtolower($chatUser->display_name) }}">
                <div class="avatar-wrapper">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($chatUser->username) }}&background=random" class="avatar-img" alt="">
                    <div class="status-dot" id="status-dot-{{ $chatUser->id }}"></div>
                </div>
                <div class="contact-info">
                    <div class="contact-name">{{ $chatUser->display_name }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <div class="chat-window">
        <div class="chat-header">
            <div class="avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=random" class="avatar-img" alt="">
                <div class="status-dot" id="header-status-dot"></div>
            </div>
            <div class="header-info">
                <h4>{{ $user->role === 'umkm' && $user->usaha ? $user->usaha->nama_usaha : $user->username }}</h4>
                <span id="user-status">Offline</span>
            </div>
        </div>

        <div class="messages-area" id="message-container">
            <div class="date-divider"><span>Today</span></div>
            
            @foreach($messages as $message)
                <div class="message-row {{ $message->sender_id == Auth::id() ? 'self' : 'other' }}">
                    <div class="message-content">
                        @if($message->type === 'image')
                            <img src="{{ asset('storage/' . $message->attachment) }}" style="max-width: 100%; border-radius: 12px; margin-bottom: 5px;"><br>
                        @elseif($message->type === 'file')
                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="text-dark">
                                <i class="fas fa-file-alt mr-2"></i> {{ $message->message ?: 'File' }}
                            </a>
                        @endif
                        
                        @if($message->type === 'text' || $message->message)
                            {!! nl2br(e($message->message)) !!}
                        @endif
                    </div>
                    <div class="message-time">
                        {{ $message->created_at->format('H:i') }}
                        @if($message->sender_id == Auth::id())
                            <i class="fas fa-check{{ $message->is_read ? '-double text-primary' : '' }}" style="font-size: 8px;"></i>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <form id="chat-form" class="input-bar">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
            <i class="fas fa-paperclip attachment-btn"></i>
            <div class="input-wrapper">
                <input type="text" name="message" id="message-input" placeholder="Ketik sesuatu" class="message-input" autocomplete="off">
            </div>
            <button type="submit" class="send-btn">kirim</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@vite(['resources/js/app.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.getElementById('message-container');
        const chatForm = document.getElementById('chat-form');

        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                if (!formData.get('message')) return;

                const input = document.getElementById('message-input');
                input.disabled = true;

                axios.post('{{ route("chats.store") }}', formData)
                    .then(response => {
                        input.value = '';
                        input.disabled = false;
                        input.focus();
                        // Real-time update handled by Echo
                    })
                    .catch(error => {
                        console.error(error);
                        input.disabled = false;
                    });
            });
        }

        // --- WEB SOCKET (LARAVEL ECHO) ---
        @if(Auth::check())
        window.addEventListener('load', () => {
            if (window.Echo) {
                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('.message.sent', (e) => {
                        if (e.message.sender_id == {{ $user->id }}) {
                            appendMessage(e.message, false);
                            axios.post(`/chats/${e.message.sender_id}/read`);
                        }
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
                const sidebar = document.querySelector('.contact-list');
                sidebar.prepend(contactItem);
                
                // Update last message if needed (optional since show view doesn't show preview in sidebar for now)
            }
        }

        function appendMessage(msg, isSelf) {
            const sideClass = isSelf ? 'self' : 'other';
            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const check = isSelf ? (msg.is_read ? '<i class="fas fa-check-double text-primary" style="font-size: 8px;"></i>' : '<i class="fas fa-check" style="font-size: 8px;"></i>') : '';

            let contentHtml = '';
            if (msg.type === 'image') {
                contentHtml = `<img src="/storage/${msg.attachment}" style="max-width: 100%; border-radius: 12px; margin-bottom: 5px;"><br>${msg.message || ''}`;
            } else if (msg.type === 'file') {
                contentHtml = `<a href="/storage/${msg.attachment}" target="_blank" class="text-dark"><i class="fas fa-file-alt mr-2"></i> ${msg.message || 'File'}</a>`;
            } else {
                contentHtml = msg.message.replace(/\n/g, '<br>');
            }

            const html = `
                <div class="message-row ${sideClass}">
                    <div class="message-content">${contentHtml}</div>
                    <div class="message-time">${time} ${check}</div>
                </div>
            `;
            messageContainer.insertAdjacentHTML('beforeend', html);
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        function updateStatus(user, isOnline) {
            const dot = document.getElementById(`status-dot-${user.id}`);
            if (dot) dot.classList.toggle('online', isOnline);
            
            if (user.id == {{ $user->id }}) {
                const statusText = document.getElementById('user-status');
                const headerDot = document.getElementById('header-status-dot');
                statusText.innerText = isOnline ? 'Online' : 'Offline';
                statusText.className = isOnline ? 'online' : '';
                headerDot.classList.toggle('online', isOnline);
            }
        }

        function updateStatuses(users) {
            users.forEach(u => updateStatus(u, true));
        }
    });
</script>
@endsection
