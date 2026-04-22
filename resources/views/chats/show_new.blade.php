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
                <a href="{{ route('chats.show', $chatUser->id) }}" class="text-decoration-none text-dark contact-item {{ isset($user) && $user->id == $chatUser->id ? 'active-chat' : '' }}" data-name="{{ strtolower($chatUser->display_name) }}" data-user-id="{{ $chatUser->id }}">
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

        <!-- Main Chat Area -->
        <div class="col-md-8 col-lg-9 d-flex flex-column h-100 position-relative">
            @if(isset($user))
            <!-- Chat Header -->
            <div class="p-2 bg-light d-flex align-items-center border-bottom border-left z-index-10">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=random" class="rounded-circle mr-3" style="width: 40px;" alt="">
                <div class="flex-grow-1">
                    <h6 class="mb-0 font-weight-bold">{{ $user->role === 'umkm' && $user->usaha ? $user->usaha->nama_usaha : $user->username }}</h6>
                    <small class="text-muted" id="user-status">Offline</small>
                </div>
                <div class="d-flex align-items-center text-muted pr-3">
                    <div id="search-chat-container" class="mr-2 d-none d-flex align-items-center">
                        <input type="text" id="message-search" class="form-control form-control-sm rounded-pill" placeholder="Cari pesan...">
                        <small id="search-count" class="ml-2 text-primary font-weight-bold" style="white-space: nowrap;"></small>
                    </div>
                    <i class="fas fa-search mr-3 cursor-pointer" id="btn-search-chat"></i>
                </div>
            </div>

            <!-- Chat Content (WhatsApp Style) -->
            <div class="flex-grow-1 overflow-auto p-4 chat-bg" id="message-container">
                @foreach($messages as $message)
                <div class="d-flex mb-2 {{ $message->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }} chat-message" data-content="{{ strtolower($message->message) }}">
                    <div class="message-bubble {{ $message->sender_id == Auth::id() ? 'bubble-right' : 'bubble-left' }}" id="message-{{ $message->id }}">
                        <div class="message-text">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                        <div class="d-flex justify-content-end align-items-center mt-1">
                            <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                            @if($message->sender_id == Auth::id())
                                <div class="ml-1 status-icon">
                                    @if($message->is_read)
                                        <i class="fas fa-check-double text-primary" style="font-size: 10px;"></i>
                                    @else
                                        <!-- Note: Initial server side check is simple, JS will update this based on presence -->
                                        <i class="fas fa-check text-muted check-single" style="font-size: 10px;"></i>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Input Bar -->
            <div class="p-3 bg-light border-top border-left position-relative">
                <form id="chat-form" class="d-flex align-items-center">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    <button type="button" class="btn btn-link text-muted px-2" id="emoji-trigger">
                        <i class="far fa-smile fa-lg"></i>
                    </button>
                    <input type="text" name="message" id="message-input" placeholder="Ketik pesan..." class="form-control mx-2 border-0 rounded-pill" style="padding: 10px 20px;" autocomplete="off">
                    <button type="submit" class="btn bg-success text-white rounded-circle" style="width: 45px; height: 45px;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
            @else
            <div class="h-100 d-flex flex-column justify-content-center align-items-center bg-light">
                <div class="text-center p-5 rounded-circle bg-white shadow-sm mb-4">
                    <i class="fab fa-whatsapp fa-7x text-success"></i>
                </div>
                <h4 class="text-muted">WhatsApp Web</h4>
                <p class="text-muted px-5 text-center">Kirim dan terima pesan seketika. <br>Pilih salah satu kontak di sebelah kiri untuk mulai mengobrol.</p>
                <hr class="w-25">
                <small class="text-muted mt-3"><i class="fas fa-lock"></i> Terenkripsi secara end-to-end</small>
            </div>
            @endif
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

    /* Custom WhatsApp Theme */
    .chat-bg {
        background-color: #e5ddd5;
        background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        background-blend-mode: overlay;
        background-size: contain;
    }

    .hover-light:hover { background-color: #f5f6f6 !important; }
    .active-chat { background-color: #ebebeb !important; border-left: 5px solid #00a884; }

    .message-bubble {
        position: relative;
        max-width: 65%;
        padding: 6px 7px 8px 9px;
        border-radius: 7.5px;
        box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
        word-wrap: break-word;
    }

    .bubble-left {
        background-color: #ffffff;
        color: #111b21;
        border-top-left-radius: 0;
    }

    .bubble-left::before {
        content: "";
        position: absolute;
        top: 0;
        left: -8px;
        width: 0;
        height: 0;
        border-top: 10px solid #ffffff;
        border-left: 10px solid transparent;
    }

    .bubble-right {
        background-color: #dcf8c6; /* WhatsApp Greenish Bubble */
        color: #111b21;
        border-top-right-radius: 0;
    }

    .bubble-right::before {
        content: "";
        position: absolute;
        top: 0;
        right: -8px;
        width: 0;
        height: 0;
        border-top: 10px solid #dcf8c6;
        border-right: 10px solid transparent;
    }

    .message-text { font-size: 14.2px; line-height: 19px; }
    .message-time { font-size: 11px; color: #667781; margin-top: 4px; }
    .cursor-pointer { cursor: pointer; }

    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 15px;
        width: 12px;
        height: 12px;
        background-color: #25d366;
        border: 2px solid white;
        border-radius: 50%;
    }

    .search-highlight {
        background-color: #fff3cd !important; /* Yellow highlight */
        border: 2px solid #ffc107 !important;
        transition: all 0.3s ease;
        transform: scale(1.02);
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@vite(['resources/js/app.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.getElementById('message-container');
        const chatForm = document.getElementById('chat-form');
        const attachmentInput = document.getElementById('attachment');
        const contactSearch = document.getElementById('contact-search');
        const btnSearchChat = document.getElementById('btn-search-chat');
        const messageSearch = document.getElementById('message-search');
        const searchChatContainer = document.getElementById('search-chat-container');

        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        // --- SEARCH CONTACTS ---
        if (contactSearch) {
            contactSearch.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                document.querySelectorAll('.contact-item').forEach(item => {
                    const name = item.getAttribute('data-name');
                    if (name.includes(query)) {
                        item.classList.remove('d-none');
                    } else {
                        item.classList.add('d-none');
                    }
                });
            });
        }

        // --- SEARCH MESSAGES ---
        if (btnSearchChat) {
            btnSearchChat.addEventListener('click', () => {
                searchChatContainer.classList.toggle('d-none');
                if (!searchChatContainer.classList.contains('d-none')) {
                    messageSearch.focus();
                } else {
                    // Reset search highlight
                    messageSearch.value = '';
                    document.querySelectorAll('.message-bubble').forEach(b => {
                        b.classList.remove('search-highlight');
                        b.style.opacity = '1';
                    });
                }
            });
        }

        if (messageSearch) {
            const searchCount = document.getElementById('search-count');
            messageSearch.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const bubbles = document.querySelectorAll('.message-bubble');
                let matches = 0;
                
                bubbles.forEach(b => {
                    b.classList.remove('search-highlight');
                    b.style.opacity = query ? '0.3' : '1';
                });

                if (!query) {
                    searchCount.innerText = '';
                    return;
                }

                let firstMatch = null;
                bubbles.forEach(bubble => {
                    const text = bubble.querySelector('.message-text').innerText.toLowerCase();
                    if (text.includes(query)) {
                        matches++;
                        bubble.classList.add('search-highlight');
                        bubble.style.opacity = '1';
                        if (!firstMatch) firstMatch = bubble;
                    }
                });

                searchCount.innerText = matches > 0 ? `${matches} ditemukan` : 'Tidak ada';

                if (firstMatch) {
                    firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }

        // --- SEND MESSAGE ---
        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                if (!formData.get('message')) return;

                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                axios.post('{{ route("chats.store") }}', formData)
                    .then(response => {
                        const msg = response.data;
                        this.reset();
                        appendMessage(msg, true);
                        submitBtn.disabled = false;
                    })
                    .catch(error => {
                        console.error(error);
                        submitBtn.disabled = false;
                    });
            });
        }

        let onlineUsers = [];

        function appendMessage(msg, isSelf) {
            if (!messageContainer) return;
            
            const sideClass = isSelf ? 'justify-content-end' : 'justify-content-start';
            const bubbleClass = isSelf ? 'bubble-right' : 'bubble-left';
            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            // Determine initial checkmark for sender
            let checkHtml = '';
            if (isSelf) {
                if (msg.is_read) {
                    checkHtml = '<i class="fas fa-check-double text-primary" style="font-size: 10px;"></i>';
                } else {
                    // Check if receiver is online
                    @if(isset($user))
                    const isOnline = onlineUsers.some(u => u.id == {{ $user->id }});
                    if (isOnline) {
                        checkHtml = '<i class="fas fa-check-double text-muted" style="font-size: 10px;"></i>';
                    } else {
                        checkHtml = '<i class="fas fa-check text-muted" style="font-size: 10px;"></i>';
                    }
                    @else
                    checkHtml = '<i class="fas fa-check text-muted" style="font-size: 10px;"></i>';
                    @endif
                }
            }

            const msgHtml = `
                <div class="d-flex mb-2 ${sideClass} chat-message" data-content="${msg.message ? msg.message.toLowerCase() : ''}">
                    <div class="message-bubble ${bubbleClass}" id="message-${msg.id}">
                        <div class="message-text">${msg.message.replace(/\n/g, '<br>')}</div>
                        <div class="d-flex justify-content-end align-items-center mt-1">
                            <span class="message-time">${time}</span>
                            <div class="ml-1 status-icon">${checkHtml}</div>
                        </div>
                    </div>
                </div>
            `;
            
            messageContainer.insertAdjacentHTML('beforeend', msgHtml);
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        // --- WEB SOCKET (LARAVEL ECHO) ---
        @if(Auth::check())
        window.addEventListener('load', () => {
            if (window.Echo) {
                // Listen for private messages
                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('.message.sent', (e) => {
                        console.log('New message received:', e.message);
                        @if(isset($user))
                            if (e.message.sender_id == {{ $user->id }}) {
                                appendMessage(e.message, false);
                                // Notify sender that we've read it (if currently active in this chat)
                                axios.post(`/chats/${e.message.sender_id}/read`);
                            }
                        @endif
                        updateSidebar(e.message);
                    })
                    .listen('.message.read', (e) => {
                        console.log('Message read by recipient:', e.chat.id);
                        const msgBubble = document.getElementById(`message-${e.chat.id}`);
                        if (msgBubble) {
                            const iconContainer = msgBubble.querySelector('.status-icon');
                            if (iconContainer) {
                                iconContainer.innerHTML = '<i class="fas fa-check-double text-primary" style="font-size: 10px;"></i>';
                            }
                        }
                    });

                // Listen for online presence
                window.Echo.join('online')
                    .here((users) => {
                        onlineUsers = users;
                        updateAllStatuses();
                    })
                    .joining((user) => {
                        onlineUsers.push(user);
                        updateUserStatus(user, true);
                    })
                    .leaving((user) => {
                        onlineUsers = onlineUsers.filter(u => u.id != user.id);
                        updateUserStatus(user, false);
                    });
            }
        });
        @endif

        function updateUserStatus(user, isOnline) {
            const dot = document.getElementById(`status-dot-${user.id}`);
            if (dot) {
                if (isOnline) dot.classList.remove('d-none');
                else dot.classList.add('d-none');
            }
            
            @if(isset($user))
            if (user.id == {{ $user->id }}) {
                const statusText = document.getElementById('user-status');
                if (isOnline) {
                    statusText.innerText = 'Online';
                    statusText.classList.replace('text-muted', 'text-success');
                    // Update all single checks to double checks (grey)
                    document.querySelectorAll('.fa-check.text-muted').forEach(el => {
                        el.className = 'fas fa-check-double text-muted';
                    });
                } else {
                    statusText.innerText = 'Offline';
                    statusText.classList.replace('text-success', 'text-muted');
                }
            }
            @endif
        }

        function updateAllStatuses() {
            onlineUsers.forEach(u => {
                const dot = document.getElementById(`status-dot-${u.id}`);
                if (dot) dot.classList.remove('d-none');
                
                @if(isset($user))
                if (u.id == {{ $user->id }}) {
                    const statusText = document.getElementById('user-status');
                    statusText.innerText = 'Online';
                    statusText.classList.replace('text-muted', 'text-success');
                    // Update all single checks to double checks
                    document.querySelectorAll('.fa-check.text-muted').forEach(el => {
                        el.className = 'fas fa-check-double text-muted';
                    });
                }
                @endif
            });
        }

        function updateSidebar(msg) {
            const contactId = msg.sender_id == {{ Auth::id() }} ? msg.receiver_id : msg.sender_id;
            const contactLink = document.querySelector(`a[data-user-id="${contactId}"]`);
            if (contactLink) {
                const sidebar = document.getElementById('sidebar-contacts');
                sidebar.prepend(contactLink);
                const container = contactLink.querySelector('.d-flex.align-items-center.text-truncate.w-75');
                if (container) {
                    const isSelf = msg.sender_id == {{ Auth::id() }};
                    let checkHtml = '';
                    if (isSelf) {
                        const isOnline = onlineUsers.some(u => u.id == msg.receiver_id);
                        checkHtml = isOnline 
                            ? `<i class="fas fa-check-double text-muted mr-1 sidebar-check-${msg.receiver_id}" style="font-size: 10px;"></i>` 
                            : `<i class="fas fa-check text-muted mr-1 sidebar-check-${msg.receiver_id}" style="font-size: 10px;"></i>`;
                    }
                    container.innerHTML = `${checkHtml}<p class="mb-0 text-sm text-muted text-truncate">${msg.message}</p>`;
                }
                const timeEl = contactLink.querySelector('small.text-muted');
                if (timeEl) timeEl.innerText = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                @if(isset($user))
                if (msg.sender_id != {{ $user->id }}) {
                    updateBadge(contactLink);
                }
                @else
                updateBadge(contactLink);
                @endif
            }
        }

        function updateBadge(contactLink) {
            let badge = contactLink.querySelector('.badge-success');
            if (!badge) {
                const container = contactLink.querySelector('.d-flex.justify-content-between.align-items-center');
                container.insertAdjacentHTML('beforeend', '<span class="badge badge-success badge-pill">1</span>');
            } else {
                badge.innerText = parseInt(badge.innerText) + 1;
            }
        }
    });
</script>
@stop
