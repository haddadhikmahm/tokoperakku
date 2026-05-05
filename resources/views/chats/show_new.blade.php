@extends($layout ?? 'layouts.user')

@section('title', 'Chat with ' . $user->username)

@section('css')
<style>
    :root {
        --chat-bg: #f8f9fa;
        --chat-sidebar-bg: #ffffff;
        --chat-header-bg: #ffffff;
        --chat-contact-hover: #f8f9fa;
        --chat-contact-active: #f0f2f5;
        --chat-window-bg: #fdfdfd;
        --chat-msg-other: #f0f2f5;
        --chat-msg-self: #fff0f0;
        --chat-text: #1a1a1a;
        --chat-text-muted: #71717a;
        --chat-border: #e4e4e7; /* More distinct border */
        --chat-input-bg: #ffffff;
        --chat-header-text: #1a1a1a;
        --chat-divider-bg: #ffffff;
        --chat-accent: #ef4444;
    }

    body.dark-mode {
        --chat-bg: #0f0f0f;
        --chat-sidebar-bg: #1a1a1a;
        --chat-header-bg: #1a1a1a;
        --chat-contact-hover: #242424;
        --chat-contact-active: #2d2d2d;
        --chat-window-bg: #121212;
        --chat-msg-other: #2d2d2d;
        --chat-msg-self: #451a1a;
        --chat-text: #e4e4e7;
        --chat-text-muted: #a1a1aa;
        --chat-border: #3f3f46;
        --chat-input-bg: #242424;
        --chat-header-text: #ffffff;
        --chat-divider-bg: #1a1a1a;
        --chat-accent: #f87171;
    }

    body { background-color: var(--chat-bg) !important; color: var(--chat-text) !important; overflow-x: hidden; }
    
    /* Override layout margins to lift chat higher */
    .main-container { margin-top: 15px !important; margin-bottom: 15px !important; min-height: auto !important; }

    .chat-container {
        display: flex;
        background: var(--chat-sidebar-bg);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--chat-border);
        height: calc(100vh - 210px); /* Adjusted to prevent page scroll */
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
    
    .contact-info { flex: 1; min-width: 0; }
    .contact-name-row { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2px; }
    .contact-name { font-size: 14px; font-weight: 600; color: var(--chat-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .chat-time { font-size: 11px; color: var(--chat-text-muted); }
    .last-msg { font-size: 12px; color: var(--chat-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Main Chat Window */
    .chat-window {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--chat-window-bg);
        position: relative;
    }
    .chat-window::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: url('https://static.whatsapp.net/rsrc.php/v3/yl/r/rPj_wJ_Q4V0.png');
        background-repeat: repeat;
        opacity: 0.03;
        z-index: 0;
        pointer-events: none;
    }

    .chat-header {
        padding: 10px 25px;
        background: var(--chat-header-bg);
        display: flex;
        align-items: center;
        gap: 15px;
        height: 60px;
        z-index: 1;
        border-bottom: 1px solid var(--chat-border);
    }

    .header-info h4 { margin: 0; font-size: 15px; font-weight: 700; color: var(--chat-header-text); }
    .header-info span { font-size: 12px; font-weight: 600; }
    .header-info span.online { color: #22c55e; }
    .header-info span.offline { color: var(--chat-text-muted); }

    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px 4%;
        display: flex;
        flex-direction: column;
        gap: 10px;
        z-index: 1;
    }

    .message-row {
        display: flex;
        flex-direction: column;
        max-width: 75%;
    }

    .message-row.self { align-self: flex-end; }
    .message-row.other { align-self: flex-start; }

    .message-content {
        padding: 8px 12px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .other .message-content {
        background: var(--chat-msg-other);
        color: var(--chat-text);
        border-bottom-left-radius: 2px;
    }

    .self .message-content {
        background: var(--chat-msg-self);
        color: var(--chat-text);
        border-bottom-right-radius: 2px;
        border: 1px solid rgba(239, 68, 68, 0.1);
    }
    
    .message-text { word-break: break-word; }

    .message-time {
        font-size: 10px;
        color: var(--chat-text-muted);
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 3px;
        justify-content: flex-end;
    }

    .input-bar {
        padding: 12px 20px;
        background: var(--chat-header-bg);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1;
        border-top: 1px solid var(--chat-border);
    }

    .attachment-btn {
        color: var(--chat-text-muted);
        font-size: 18px;
        cursor: pointer;
    }

    .input-wrapper { flex: 1; }

    .message-input {
        width: 100%;
        padding: 10px 18px;
        border-radius: 20px;
        border: 1px solid var(--chat-border);
        background: var(--chat-bg);
        color: var(--chat-text);
        font-size: 14px;
        outline: none;
    }
    .message-input:focus { border-color: var(--chat-accent); background: var(--chat-sidebar-bg); }

    .send-btn {
        color: var(--chat-accent);
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 700;
        font-size: 13px;
        padding: 5px 8px;
        text-transform: lowercase;
    }

    .date-divider { text-align: center; margin: 15px 0; z-index: 1; }
    .date-divider span {
        background: var(--chat-divider-bg);
        padding: 3px 12px;
        font-size: 11px;
        color: var(--chat-text-muted);
        border-radius: 15px;
        border: 1px solid var(--chat-border);
        font-weight: 600;
    }
</style>
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

    <div class="chat-window">
        <div class="chat-header">
            <a href="{{ route('chats.index') }}" class="btn btn-link text-dark d-md-none mr-2 p-0">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=random" class="avatar-img" alt="">
                <div class="status-dot" id="header-status-dot"></div>
            </div>
            <div class="header-info">
                <h4>{{ $user->role === 'umkm' && $user->usaha ? $user->usaha->nama_usaha : $user->username }}</h4>
                <span id="user-status" class="offline">
                    @if($user->last_seen_at)
                        Terakhir dilihat {{ $user->last_seen_at->diffForHumans() }}
                    @else
                        Offline
                    @endif
                </span>
            </div>
        </div>

        <div class="messages-area" id="message-container" style="display: flex; flex-direction: column; height: 100%;">
            @if($messages->isEmpty())
                <div class="text-muted" id="empty-chat-state" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
                    <div class="text-center">
                        <i class="far fa-comments mb-3" style="font-size: 64px; opacity: 0.1;"></i>
                        <h5 class="mb-2" style="font-weight: 600; color: var(--chat-text);">Mulai Percakapan</h5>
                    </div>
                </div>
            @endif

            @php
                $currentDate = null;
            @endphp
            @foreach($messages as $message)
                @php
                    $msgDate = $message->created_at->format('Y-m-d');
                    $displayDate = $message->created_at->isToday() ? 'Today' : ($message->created_at->isYesterday() ? 'Yesterday' : $message->created_at->format('d M Y'));
                @endphp
                
                @if($currentDate !== $msgDate)
                    <div class="date-divider"><span>{{ $displayDate }}</span></div>
                    @php $currentDate = $msgDate; @endphp
                @endif
                <div class="message-row {{ $message->sender_id == Auth::id() ? 'self' : 'other' }}">
                    <div class="message-content">
                        @if($message->type === 'image')
                            <img src="{{ asset('storage/' . $message->attachment) }}" style="max-width: 100%; border-radius: 8px; margin-bottom: 5px;"><br>
                        @elseif($message->type === 'file')
                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" style="color: #e9edef;">
                                <i class="fas fa-file-alt mr-2"></i> {{ $message->message ?: 'File' }}
                            </a>
                        @endif
                        
                        <div class="message-text">
                            @if($message->type === 'text' || ($message->type !== 'file' && $message->message))
                                {!! nl2br(e($message->message)) !!}
                            @endif
                        </div>
                        
                        <div class="message-time">
                            {{ $message->created_at->format('H:i') }}
                            @if($message->sender_id == Auth::id())
                                @if($message->is_read)
                                    <i class="fas fa-check-double text-primary" style="font-size: 10px; margin-left: 4px;" id="msg-tick-{{ $message->id }}"></i>
                                @elseif($message->is_delivered)
                                    <i class="fas fa-check-double text-secondary" style="font-size: 10px; margin-left: 4px;" id="msg-tick-{{ $message->id }}"></i>
                                @else
                                    <i class="fas fa-check text-secondary" style="font-size: 10px; margin-left: 4px;" id="msg-tick-{{ $message->id }}"></i>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <form id="chat-form" class="input-bar" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
            <input type="file" id="attachment-input" name="attachment" style="display: none;" accept=".jpg,.jpeg,.png,.pdf,.docx">
            <i class="fas fa-paperclip attachment-btn" onclick="document.getElementById('attachment-input').click()"></i>
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
                if (!formData.get('message') && !document.getElementById('attachment-input').files.length) return;

                const input = document.getElementById('message-input');
                input.disabled = true;

                axios.post('{{ route("chats.store") }}', formData)
                    .then(response => {
                        input.value = '';
                        input.disabled = false;
                        document.getElementById('attachment-input').value = '';
                        input.focus();
                        
                        // Append locally since toOthers() won't broadcast back to sender
                        appendMessage(response.data, true);
                        updateSidebar(response.data);
                    })
                    .catch(error => {
                        console.error(error);
                        input.disabled = false;
                    });
            });


        }
        
        const pendingDelivered = {};
        const pendingRead = {};

        // --- WEB SOCKET (LARAVEL ECHO) ---
        @if(Auth::check())
        window.addEventListener('load', () => {
            if (window.Echo) {
                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('.message.sent', (e) => {
                        if (e.message.sender_id == {{ $user->id }}) {
                            appendMessage(e.message, false);
                            axios.post(`/chats/${e.message.sender_id}/read`);
                        } else {
                            axios.post(`/chats/${e.message.sender_id}/delivered`);
                        }
                        updateSidebar(e.message);
                    })
                    .listen('.message.delivered', (e) => {
                        if (e.chat) {
                            const tick = document.getElementById('msg-tick-' + e.chat.id);
                            if (tick) {
                                if (!tick.classList.contains('text-primary')) {
                                    tick.className = 'fas fa-check-double text-secondary';
                                }
                            } else {
                                pendingDelivered[e.chat.id] = true;
                            }
                        }
                    })
                    .listen('.message.read', (e) => {
                        if (e.chat) {
                            const tick = document.getElementById('msg-tick-' + e.chat.id);
                            if (tick) {
                                tick.className = 'fas fa-check-double text-primary';
                            } else {
                                pendingRead[e.chat.id] = true;
                            }
                        }
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
            const emptyState = document.getElementById('empty-chat-state');
            if (emptyState) emptyState.remove();

            const sideClass = isSelf ? 'self' : 'other';
            const time = new Date(msg.created_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });
            
            let check = '';
            if (isSelf) {
                if (msg.is_read || pendingRead[msg.id]) {
                    check = `<i class="fas fa-check-double text-primary" style="font-size: 10px; margin-left: 4px;" id="msg-tick-${msg.id}"></i>`;
                    delete pendingRead[msg.id];
                    delete pendingDelivered[msg.id];
                } else if (msg.is_delivered || pendingDelivered[msg.id]) {
                    check = `<i class="fas fa-check-double text-secondary" style="font-size: 10px; margin-left: 4px;" id="msg-tick-${msg.id}"></i>`;
                    delete pendingDelivered[msg.id];
                } else {
                    check = `<i class="fas fa-check text-secondary" style="font-size: 10px; margin-left: 4px;" id="msg-tick-${msg.id}"></i>`;
                }
            }

            let contentHtml = '';
            if (msg.type === 'image') {
                contentHtml = `<img src="/storage/${msg.attachment}" style="max-width: 100%; border-radius: 8px; margin-bottom: 5px;"><br>${msg.message || ''}`;
            } else if (msg.type === 'file') {
                contentHtml = `<a href="/storage/${msg.attachment}" target="_blank" style="color: #e9edef;"><i class="fas fa-file-alt mr-2"></i> ${msg.message || 'File'}</a>`;
            } else {
                contentHtml = msg.message ? msg.message.replace(/\n/g, '<br>') : '';
            }

            const html = `
                <div class="message-row ${sideClass}">
                    <div class="message-content">
                        <div class="message-text">${contentHtml}</div>
                        <div class="message-time">${time} ${check}</div>
                    </div>
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
                if (isOnline) {
                    statusText.innerText = 'Online';
                    statusText.className = 'online';
                    headerDot.classList.add('online');
                } else {
                    statusText.innerText = 'Terakhir dilihat baru saja';
                    statusText.className = 'offline';
                    headerDot.classList.remove('online');
                }
            }
        }

        function updateStatuses(users) {
            users.forEach(u => updateStatus(u, true));
        }
    });
</script>
@endsection
