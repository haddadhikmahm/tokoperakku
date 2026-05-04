@extends($layout ?? 'layouts.user')

@section('title', 'Chat with ' . $user->username)

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

    /* Main Chat Window */
    .chat-window {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #0b141a;
        position: relative;
    }
    .chat-window::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: url('https://static.whatsapp.net/rsrc.php/v3/yl/r/rPj_wJ_Q4V0.png');
        background-repeat: repeat;
        opacity: 0.06;
        z-index: 0;
    }

    .chat-header {
        padding: 10px 15px;
        background: #202c33;
        display: flex;
        align-items: center;
        gap: 15px;
        height: 60px;
        z-index: 1;
    }

    .header-info h4 { margin: 0; font-size: 16px; font-weight: 500; color: #e9edef; }
    .header-info span { font-size: 13px; color: #8696a0; }

    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px 6%;
        display: flex;
        flex-direction: column;
        gap: 6px;
        z-index: 1;
    }

    .message-row {
        display: flex;
        flex-direction: column;
        max-width: 65%;
    }

    .message-row.self { align-self: flex-end; }
    .message-row.other { align-self: flex-start; }

    .message-content {
        padding: 6px 9px 8px 9px;
        border-radius: 7.5px;
        font-size: 14.2px;
        line-height: 19px;
        position: relative;
        box-shadow: 0 1px 0.5px rgba(11,20,26,.13);
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .other .message-content {
        background: #202c33;
        color: #e9edef;
        border-top-left-radius: 0;
    }

    .self .message-content {
        background: #005c4b;
        color: #e9edef;
        border-top-right-radius: 0;
    }
    
    .message-text {
        flex: 1 1 auto;
        word-break: break-word;
    }

    .message-time {
        font-size: 11px;
        color: rgba(255,255,255,0.6);
        margin-left: 12px;
        margin-bottom: -4px;
        display: flex;
        align-items: center;
        gap: 4px;
        float: right;
    }

    .input-bar {
        padding: 10px 15px;
        background: #202c33;
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 1;
        border-top: none;
    }

    .attachment-btn {
        color: #8696a0;
        font-size: 22px;
        cursor: pointer;
        padding: 5px;
    }

    .input-wrapper { flex: 1; position: relative; }

    .message-input {
        width: 100%;
        padding: 9px 15px;
        border-radius: 8px;
        border: none;
        background: #2a3942;
        color: #e9edef;
        font-size: 15px;
        outline: none;
    }

    .send-btn {
        color: #8696a0;
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
    }

    .date-divider { text-align: center; margin: 12px 0; z-index: 1;}
    .date-divider::before { display: none; }
    .date-divider span {
        background: #182229;
        padding: 5px 12px;
        font-size: 12.5px;
        color: #8696a0;
        border-radius: 8px;
        box-shadow: 0 1px 0.5px rgba(11,20,26,.13);
        text-transform: capitalize;
        font-weight: 500;
    }
    
    .text-primary { color: #53bdeb !important; } /* Blue ticks */
    .text-secondary { color: #8696a0 !important; } /* Gray ticks */
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
                <span id="user-status">Offline</span>
            </div>
        </div>

        <div class="messages-area" id="message-container">
            @if($messages->isEmpty())
                <div class="d-flex flex-column align-items-center justify-content-center h-100 w-100 text-muted" id="empty-chat-state">
                    <i class="far fa-comments mb-3" style="font-size: 48px; opacity: 0.2;"></i>
                    <p>Mulai percakapan dengan {{ $user->username }}</p>
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
            <button type="submit" class="send-btn"><i class="fas fa-paper-plane"></i></button>
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
            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
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
