@extends('adminlte::page')

@section('title', 'WhatsApp Chat')

@section('content')
<div class="container-fluid p-0" style="height: calc(100vh - 120px); overflow: hidden;">
    <div class="row h-100 no-gutters">
        <!-- Sidebar Contacts -->
        <div class="col-md-4 col-lg-3 d-flex flex-column bg-white border-right h-100">
            <div class="p-3 bg-light d-flex align-items-center justify-content-between border-bottom">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random" class="rounded-circle" style="width: 40px;" alt="">
                <div class="text-muted">
                    <i class="fas fa-circle-notch mr-3"></i>
                    <i class="fas fa-comment-alt mr-3"></i>
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            
            <div class="p-2 border-bottom bg-white">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" class="form-control bg-light border-left-0" placeholder="Cari atau mulai chat baru">
                </div>
            </div>

            <div class="flex-grow-1 overflow-auto bg-white" id="sidebar-contacts">
                @foreach($chatUsers as $chatUser)
                <a href="{{ route('chats.show', $chatUser->id) }}" class="text-decoration-none text-dark contact-item {{ isset($user) && $user->id == $chatUser->id ? 'active-chat' : '' }}">
                    <div class="d-flex align-items-center p-3 border-bottom hover-light">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($chatUser->username) }}&background=random" class="rounded-circle mr-3" style="width: 49px;" alt="">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="mb-0 text-truncate font-weight-bold">{{ $chatUser->display_name }}</h6>
                                <small class="text-muted text-xs">{{ $chatUser->last_chat_time }}</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0 text-sm text-muted text-truncate w-75">{{ $chatUser->last_message ?: 'Klik untuk memulai chat' }}</p>
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
                    <small class="text-muted">Online</small>
                </div>
                <div class="text-muted pr-3">
                    <i class="fas fa-search mr-4"></i>
                    <i class="fas fa-paperclip mr-4"></i>
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>

            <!-- Chat Content (WhatsApp Style) -->
            <div class="flex-grow-1 overflow-auto p-4 chat-bg" id="message-container">
                @foreach($messages as $message)
                <div class="d-flex mb-2 {{ $message->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="message-bubble {{ $message->sender_id == Auth::id() ? 'bubble-right' : 'bubble-left' }}">
                        @if($message->type == 'image')
                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank">
                                <img src="{{ asset('storage/' . $message->attachment) }}" class="img-fluid rounded mb-1" style="max-width: 250px;">
                            </a>
                        @elseif($message->type == 'file')
                            <div class="bg-light p-2 rounded mb-1 text-sm border">
                                <i class="fas fa-file"></i> <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="text-primary font-weight-bold">Unduh File</a>
                            </div>
                        @endif
                        <div class="message-text">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                        <div class="d-flex justify-content-end align-items-center mt-1">
                            <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                            @if($message->sender_id == Auth::id())
                                <i class="fas fa-check-double text-primary ml-1" style="font-size: 10px;"></i>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Input Bar -->
            <div class="p-3 bg-light border-top border-left">
                <form id="chat-form" class="d-flex align-items-center">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    <button type="button" class="btn btn-link text-muted px-2" id="attachment-trigger">
                        <i class="far fa-smile fa-lg"></i>
                    </button>
                    <label for="attachment" class="btn btn-link text-muted px-2 mb-0 cursor-pointer">
                        <i class="fas fa-plus fa-lg"></i>
                    </label>
                    <input type="file" name="attachment" id="attachment" class="d-none">
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

        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        if (attachmentInput) {
            attachmentInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    $('#message-input').attr('placeholder', 'File: ' + this.files[0].name);
                }
            });
        }

        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                if (!formData.get('message') && !formData.get('attachment').name) return;

                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                axios.post('{{ route("chats.store") }}', formData)
                    .then(response => {
                        const msg = response.data;
                        this.reset();
                        $('#message-input').attr('placeholder', 'Ketik pesan...');
                        appendMessage(msg, true);
                        submitBtn.disabled = false;
                    })
                    .catch(error => {
                        console.error(error);
                        submitBtn.disabled = false;
                    });
            });
        }

        function appendMessage(msg, isSelf) {
            if (!messageContainer) return;
            
            const sideClass = isSelf ? 'justify-content-end' : 'justify-content-start';
            const bubbleClass = isSelf ? 'bubble-right' : 'bubble-left';
            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            let attachmentHtml = '';
            if(msg.type == 'image') {
                attachmentHtml = `<a href="/storage/${msg.attachment}" target="_blank"><img src="/storage/${msg.attachment}" class="img-fluid rounded mb-1" style="max-width: 250px;"></a>`;
            } else if(msg.type == 'file') {
                attachmentHtml = `<div class="bg-light p-2 rounded mb-1 text-sm border"><i class="fas fa-file"></i> <a href="/storage/${msg.attachment}" target="_blank" class="text-primary font-weight-bold">Unduh File</a></div>`;
            }

            const checkHtml = isSelf ? '<i class="fas fa-check-double text-primary ml-1" style="font-size: 10px;"></i>' : '';

            const msgHtml = `
                <div class="d-flex mb-2 ${sideClass}">
                    <div class="message-bubble ${bubbleClass}">
                        ${attachmentHtml}
                        <div class="message-text">${msg.message.replace(/\n/g, '<br>')}</div>
                        <div class="d-flex justify-content-end align-items-center mt-1">
                            <span class="message-time">${time}</span>
                            ${checkHtml}
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
                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('.message.sent', (e) => {
                        console.log('New message received via WebSocket:', e.message);
                        
                        // If it's a message from the person we are currently chatting with, append it
                        @if(isset($user))
                            if (e.message.sender_id == {{ $user->id }}) {
                                appendMessage(e.message, false);
                                // Logic to mark as read would go here via AJAX
                            }
                        @endif
                        
                        // Logic to update unread badge in sidebar would go here
                        updateSidebar(e.message);
                    });
            }
        });
        @endif

        function updateSidebar(msg) {
            // Finding the contact item for the sender
            const contactLink = document.querySelector(`a[href$="/chats/${msg.sender_id}"]`);
            if (contactLink) {
                // Move it to top
                const sidebar = document.getElementById('sidebar-contacts');
                sidebar.prepend(contactLink);
                
                // Update last message preview
                const preview = contactLink.querySelector('p.text-muted');
                if (preview) preview.innerText = msg.message;
                
                // Update time
                const timeEl = contactLink.querySelector('small.text-muted');
                if (timeEl) timeEl.innerText = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                // Update badge if not active
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
