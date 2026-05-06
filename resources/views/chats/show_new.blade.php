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
    
    .contact-info { flex: 1; min-width: 0; padding-left: 12px; }
    .contact-name-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px; }
    .last-msg { display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100% !important; gap: 8px; }
    .last-msg-text { font-size: 12px; color: var(--chat-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
    .contact-name { font-weight: 600; color: var(--chat-text); font-size: 15px; }
    .chat-time { font-size: 11px; color: #8696a0; }

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

    .tick-gray { color: #8696a0 !important; }
    .tick-blue { color: #53bdeb !important; }

    /* Message Actions */
    .message-content { position: relative; overflow: visible !important; }
    .message-actions {
        position: absolute;
        top: 5px;
        right: 5px;
        opacity: 0;
        transition: opacity 0.2s;
        cursor: pointer;
        background: rgba(255,255,255,0.8);
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 10;
        color: #667781;
    }
    .message-row:hover .message-actions { opacity: 1; }
    
    .action-dropdown {
        display: none;
        position: absolute;
        top: 30px;
        background: var(--chat-sidebar-bg);
        border: 1px solid var(--chat-border);
        border-radius: 8px;
        z-index: 9999;
        min-width: 170px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        padding: 5px 0;
    }

    /* Directional alignment */
    .other .action-dropdown {
        left: 0;
        right: auto;
    }

    .self .action-dropdown {
        right: 0;
        left: auto;
    }

    .action-dropdown div {
        padding: 10px 15px;
        font-size: 13px;
        color: var(--chat-text);
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .action-dropdown div:hover { background: var(--chat-bg); color: var(--chat-accent); }
    .action-dropdown div i { width: 16px; font-size: 14px; opacity: 0.7; }

    /* Reply Style */
    .reply-content {
        background: rgba(0,0,0,0.05);
        border-left: 4px solid var(--chat-accent);
        padding: 5px 8px;
        border-radius: 4px;
        margin-bottom: 5px;
        font-size: 12px;
        color: var(--chat-text-muted);
    }
    .reply-preview-container, .file-preview-container {
        padding: 10px 15px;
        background: var(--chat-sidebar-bg);
        border-top: 1px solid var(--chat-border);
        display: none;
        align-items: center;
        justify-content: space-between;
    }
    .reply-preview-content, .file-preview-content {
        border-left: 4px solid var(--chat-accent);
        padding-left: 10px;
        font-size: 13px;
        flex: 1;
    }
    .close-preview { cursor: pointer; color: var(--chat-text-muted); transition: color 0.2s; }
    .close-preview:hover { color: #ef4444; }

    .edited-label { font-size: 10px; font-style: italic; color: var(--chat-text-muted); margin-right: 5px; }

    /* File Attachment UI */
    .attachment-btn { position: relative; overflow: hidden; }
    #file-input { position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    
    .typing-status {
        color: #22c55e;
        font-size: 12px;
        font-weight: 500;
        margin-top: 2px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
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

    <div class="chat-window">
        <div class="chat-header">
            <a href="{{ route('chats.index') }}" class="btn btn-link text-dark d-md-none mr-2 p-0">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="avatar-wrapper">
                @if(isset($user->specific_usaha) && $user->specific_usaha->foto_usaha)
                    <img src="{{ asset('storage/' . $user->specific_usaha->foto_usaha) }}" class="avatar-img" alt="">
                @elseif($user->usaha && $user->usaha->foto_usaha)
                    <img src="{{ asset('storage/' . $user->usaha->foto_usaha) }}" class="avatar-img" alt="">
                @elseif($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" class="avatar-img" alt="">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->display_name) }}&background=random" class="avatar-img" alt="">
                @endif
                <div class="status-dot {{ $user->isOnline() ? 'online' : '' }}" id="header-status-dot"></div>
            </div>
            <div class="header-info">
                <h4>{{ $user->display_name }}</h4>
                <div id="user-status-container">
                    @if($user->isOnline())
                        <span id="user-status" class="online">Online</span>
                    @else
                        <span id="user-status" class="offline">
                            @if($user->last_seen_at)
                                Terakhir dilihat {{ $user->last_seen_at->diffForHumans() }}
                            @else
                                Offline
                            @endif
                        </span>
                    @endif
                </div>
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
                <div class="message-row {{ $message->sender_id == Auth::id() ? 'self' : 'other' }}" id="message-{{ $message->id }}">
                    <div class="message-content">
                        @if(!$message->deleted_by_sender && !$message->deleted_by_receiver)
                            <div class="message-actions" onclick="toggleActionMenu({{ $message->id }})">
                                <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
                                <div class="action-dropdown" id="dropdown-{{ $message->id }}">
                                    <div onclick="handleReply({{ $message->id }}, '{{ $message->sender_id == Auth::id() ? 'Anda' : $user->display_name }}', '{{ str_replace(["'", "\n"], ["\\'", ' '], $message->message ?: 'Berkas') }}')"><i class="fas fa-reply"></i> Balas</div>
                                    @if($message->sender_id == Auth::id() && $message->type === 'text')
                                        <div onclick="handleEdit({{ $message->id }}, '{{ str_replace(["'", "\n"], ["\\'", ' '], $message->message ?: '') }}')"><i class="fas fa-edit"></i> Edit</div>
                                    @endif
                                    @if($message->sender_id == Auth::id())
                                        <div onclick="handleDelete({{ $message->id }}, 'everyone')"><i class="fas fa-trash-alt"></i> Hapus untuk semua</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($message->replyTo)
                            <div class="reply-content">
                                <strong>{{ $message->replyTo->sender_id == Auth::id() ? 'Anda' : $user->display_name }}</strong><br>
                                {{ $message->replyTo->message ?: 'Berkas' }}
                            </div>
                        @endif

                        <div class="message-text">
                            @if($message->type === 'image')
                                <img src="{{ asset('storage/' . $message->attachment) }}" style="max-width: 250px; max-height: 300px; border-radius: 8px; margin-bottom: 5px; object-fit: contain; display: block; background: #eee;">
                                @if($message->message)
                                    <br>{!! nl2br(e($message->message)) !!}
                                @endif
                            @elseif($message->type === 'file')
                                @php
                                    $ext = pathinfo($message->attachment, PATHINFO_EXTENSION);
                                    $icon = 'fa-file-alt';
                                    if ($ext === 'pdf') $icon = 'fa-file-pdf';
                                    elseif (in_array($ext, ['doc', 'docx'])) $icon = 'fa-file-word';
                                    elseif (in_array($ext, ['xls', 'xlsx'])) $icon = 'fa-file-excel';
                                    elseif (in_array($ext, ['ppt', 'pptx'])) $icon = 'fa-file-powerpoint';
                                    elseif ($ext === 'zip') $icon = 'fa-file-archive';
                                    elseif ($ext === 'txt') $icon = 'fa-file-lines';
                                @endphp
                                <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.05); padding: 10px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); margin-bottom: 5px;">
                                    <i class="fas {{ $icon }}" style="font-size: 24px; color: var(--chat-accent);"></i>
                                    <div style="flex: 1; overflow: hidden;">
                                        <div class="text-truncate" style="font-weight: 500;">{{ $message->message ?: 'Berkas' }}</div>
                                        <div style="font-size: 10px; opacity: 0.7;">Klik untuk membuka</div>
                                    </div>
                                    <i class="fas fa-download" style="font-size: 14px; opacity: 0.5;"></i>
                                </a>
                            @else
                                {!! nl2br(e($message->message)) !!}
                            @endif

                            @if($message->is_edited)
                                <span class="edited-label">(diedit)</span>
                            @endif
                        </div>
                        <div class="message-time">
                            {{ $message->created_at->format('H:i') }}
                            @if($message->sender_id == Auth::id())
                                @if($message->is_read)
                                    <i class="fas fa-check-double tick-blue" style="font-size: 10px; margin-left: 4px;" id="msg-tick-{{ $message->id }}"></i>
                                @elseif($message->is_delivered)
                                    <i class="fas fa-check-double tick-gray" style="font-size: 10px; margin-left: 4px;" id="msg-tick-{{ $message->id }}"></i>
                                @else
                                    <i class="fas fa-check tick-gray" style="font-size: 10px; margin-left: 4px;" id="msg-tick-{{ $message->id }}"></i>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="file-preview-container" id="file-preview">
            <div class="file-preview-content">
                <small style="color: var(--chat-accent); font-weight: 600;">Unggahan Berkas</small>
                <div id="file-name-preview" class="text-truncate" style="max-width: 300px; color: var(--chat-text-muted);"></div>
            </div>
            <i class="fas fa-times close-preview" onclick="cancelFile()"></i>
        </div>

        <div class="reply-preview-container" id="reply-preview">
            <div class="reply-preview-content">
                <small id="reply-user-name" style="color: var(--chat-accent); font-weight: 600;"></small>
                <div id="reply-text-preview" class="text-truncate" style="max-width: 300px; color: var(--chat-text-muted);"></div>
            </div>
            <i class="fas fa-times close-preview" onclick="cancelReply()"></i>
        </div>

        <form id="chat-form" class="input-bar" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
            <input type="hidden" name="reply_to_id" id="reply-id-input">
            <input type="hidden" id="edit-id-input">
            <input type="file" id="attachment-input" name="attachment" style="display: none;">
            <i class="fas fa-paperclip attachment-btn" onclick="document.getElementById('attachment-input').click()"></i>
            <div class="input-wrapper">
                <input type="text" name="message" id="message-input" placeholder="Ketik sesuatu" class="message-input" autocomplete="off">
            </div>
            <button type="submit" id="send-btn" class="send-btn">kirim</button>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script>
        // Global identifier for this specific chat room
        window.activeChatUserId = {{ $user->id }};
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@vite(['resources/js/app.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Axios CSRF configuration
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }

        const messageContainer = document.getElementById('message-container');
        const chatForm = document.getElementById('chat-form');

        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const editId = document.getElementById('edit-id-input').value;
                const url = editId ? `{{ url('chats') }}/${editId}` : '{{ route("chats.store") }}';
                
                const input = document.getElementById('message-input');
                const sendBtn = document.getElementById('send-btn');
                
                if (!input.value.trim() && !document.getElementById('attachment-input').files.length) return;

                const formData = new FormData(this);
                
                input.disabled = true;
                if(sendBtn) {
                    sendBtn.disabled = true;
                    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                }

                if (editId) {
                    // Use JSON for updates
                    axios.put(url, {
                        message: input.value
                    })
                    .then(response => {
                        handleSuccess(response.data, true);
                    })
                    .catch(handleError);
                } else {
                    // Use FormData for new messages (support attachments)
                    axios.post(url, formData)
                    .then(response => {
                        handleSuccess(response.data, false);
                    })
                    .catch(handleError);
                }

                function handleSuccess(data, isEdit) {
                    input.value = '';
                    input.disabled = false;
                    if(sendBtn) {
                        sendBtn.disabled = false;
                        sendBtn.innerText = 'kirim';
                    }
                    document.getElementById('attachment-input').value = '';
                    cancelReply();
                    cancelFile();
                    if (isEdit) {
                        document.getElementById('edit-id-input').value = '';
                        updateMessageInUI(data);
                    } else {
                        appendMessage(data, true);
                        updateSidebar(data);
                    }
                    input.focus();
                }

                function handleError(error) {
                    console.error(error);
                    input.disabled = false;
                    if(sendBtn) {
                        sendBtn.disabled = false;
                        sendBtn.innerText = 'kirim';
                    }
                    const msg = error.response && error.response.data && error.response.data.error ? error.response.data.error : 'Gagal memproses pesan';
                    alert(msg);
                }
            });
        }
        
        const pendingDelivered = {};
        const pendingRead = {};

        // --- WEB SOCKET (LARAVEL ECHO) ---
        @if(Auth::check())
        window.addEventListener('load', () => {
            if (window.Echo) {
                const chatChannel = window.Echo.private('chat.{{ Auth::id() }}');
                
                chatChannel.listen('.message.sent', (e) => {
                        // Double check if we are truly in the right room
                        if (e.message.sender_id == window.activeChatUserId) {
                            appendMessage(e.message, false);
                        }
                        updateSidebar(e.message);
                    })
                    .listen('.message.delivered', (e) => {
                        if (e.chat) {
                            const tick = document.getElementById('msg-tick-' + e.chat.id);
                            if (tick) {
                                if (!tick.classList.contains('tick-blue')) {
                                    tick.className = 'fas fa-check-double tick-gray';
                                }
                            } else {
                                pendingDelivered[e.chat.id] = true;
                            }
                        }
                    })
                    .listen('.message.read', (e) => {
                        if (e.chat) {
                            console.log('WS Event: Message ' + e.chat.id + ' READ by receiver');
                            const tick = document.getElementById('msg-tick-' + e.chat.id);
                            if (tick) {
                                tick.className = 'fas fa-check-double tick-blue';
                            } else {
                                pendingRead[e.chat.id] = true;
                            }

                            // Remove unread badge from sidebar for this contact
                            // e.chat.sender_id is the person who sent the message (ME in this listener)
                            // e.chat.receiver_id is the person who READ the message
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
                    })
                    .listen('.message.updated', (e) => {
                        updateMessageInUI(e.message);
                    })
                    .listen('.message.deleted', (e) => {
                        removeMessageFromUI(e.chat_id, e.delete_type);
                    })
                    .listenForWhisper('typing', (e) => {
                        if (e.user_id == {{ $user->id }}) {
                            showTypingIndicator(e.typing);
                        }
                    });

                // Chat Module Presence (Status Online di Chat)
                window.Echo.join('chat-module')
                    .here((users) => { updateChatStatuses(users); })
                    .joining((user) => { updateChatStatus(user, true); })
                    .leaving((user) => { updateChatStatus(user, false); });

                // Global Site Presence (Untuk Tick 2 Abu)
                window.Echo.join('online')
                    .here((users) => { /* Online globally */ })
                    .joining((user) => { /* User joined globally */ })
                    .leaving((user) => { /* User left globally */ });
                
                // Typing Whisper logic
                const msgInput = document.getElementById('message-input');
                let typingTimer;
                msgInput.addEventListener('input', () => {
                    window.Echo.private('chat.{{ $user->id }}').whisper('typing', {
                        user_id: {{ Auth::id() }},
                        typing: true
                    });
                    
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => {
                        window.Echo.private('chat.{{ $user->id }}').whisper('typing', {
                            user_id: {{ Auth::id() }},
                            typing: false
                        });
                    }, 3000);
                });
            }
        });
        @endif

        function showTypingIndicator(isTyping) {
            const container = document.getElementById('user-status-container');
            const statusSpan = document.getElementById('user-status');
            
            if (isTyping) {
                if (!document.getElementById('typing-text')) {
                    statusSpan.style.display = 'none';
                    const typingText = document.createElement('span');
                    typingText.id = 'typing-text';
                    typingText.className = 'typing-status';
                    typingText.innerText = 'Sedang mengetik...';
                    container.appendChild(typingText);
                }
            } else {
                const typingText = document.getElementById('typing-text');
                if (typingText) {
                    typingText.remove();
                    statusSpan.style.display = 'inline';
                }
            }
        }

        function updateSidebar(msg) {
            const contactItem = document.querySelector(`.contact-item[href$="/chats/${msg.sender_id}"]`);
            if (contactItem) {
                const sidebar = document.querySelector('.contact-list');
                sidebar.prepend(contactItem);
                
                // Update last message preview
                const lastMsgText = contactItem.querySelector('.last-msg-text');
                if (lastMsgText) {
                    if ({{ Auth::id() }} != msg.sender_id) {
                        lastMsgText.innerHTML = `<strong>${msg.message || (msg.type === 'image' ? 'Gambar' : 'Berkas')}</strong>`;
                    } else {
                        lastMsgText.innerText = msg.message || (msg.type === 'image' ? 'Gambar' : 'Berkas');
                    }
                }

                // Update unread count bubble if not in current room
                if (typeof user !== 'undefined' && user.id != msg.sender_id) {
                    const lastMsgRow = contactItem.querySelector('.last-msg');
                    let badge = lastMsgRow.querySelector('.unread-badge');
                    if (!badge) {
                        const badgeHtml = `<span class="unread-badge" style="background: #25d366; color: #000; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">1</span>`;
                        lastMsgRow.insertAdjacentHTML('beforeend', badgeHtml);
                    } else {
                        badge.innerText = parseInt(badge.innerText) + 1;
                        badge.style.display = 'flex';
                    }
                }
            }
        }

        window.toggleActionMenu = function(id) {
            const dropdown = document.getElementById(`dropdown-${id}`);
            const allDropdowns = document.querySelectorAll('.action-dropdown');
            
            const wasOpen = dropdown && dropdown.style.display === 'block';
            
            allDropdowns.forEach(d => d.style.display = 'none');
            
            if (dropdown && !wasOpen) {
                dropdown.style.display = 'block';
            }
        };

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.message-actions')) {
                document.querySelectorAll('.action-dropdown').forEach(d => d.style.display = 'none');
            }
        });

        window.handleReply = function(id, name, text) {
            document.getElementById('reply-id-input').value = id;
            document.getElementById('reply-user-name').innerText = name;
            document.getElementById('reply-text-preview').innerText = text;
            document.getElementById('reply-preview').style.display = 'flex';
            document.getElementById('message-input').focus();
            document.querySelectorAll('.action-dropdown').forEach(d => d.style.display = 'none');
        };

        window.cancelReply = function() {
            const input = document.getElementById('reply-id-input');
            if(input) input.value = '';
            const preview = document.getElementById('reply-preview');
            if(preview) preview.style.display = 'none';
        };

        window.cancelFile = function() {
            const input = document.getElementById('attachment-input');
            if(input) input.value = '';
            const preview = document.getElementById('file-preview');
            if(preview) preview.style.display = 'none';
        };

        document.getElementById('attachment-input').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                document.getElementById('file-name-preview').innerText = this.files[0].name;
                document.getElementById('file-preview').style.display = 'flex';
            }
        });

        window.handleEdit = function(id, text) {
            document.getElementById('edit-id-input').value = id;
            document.getElementById('message-input').value = text;
            const sendBtn = document.getElementById('send-btn');
            if(sendBtn) sendBtn.innerText = 'Simpan';
            document.getElementById('message-input').focus();
            document.querySelectorAll('.action-dropdown').forEach(d => d.style.display = 'none');
        };

        window.handleDelete = function(id, type) {
            if (!confirm('Hapus pesan ini?')) return;
            const url = type === 'me' ? `{{ url('chats') }}/${id}/me` : `{{ url('chats') }}/${id}/everyone`;
            axios.delete(url).then(() => {
                removeMessageFromUI(id, type);
            });
        };

        function updateMessageInUI(msg) {
            const msgEl = document.getElementById(`message-${msg.id}`);
            if (msgEl) {
                const textEl = msgEl.querySelector('.message-text');
                textEl.innerHTML = (msg.message || '').replace(/\n/g, '<br>');
                if (!textEl.querySelector('.edited-label')) {
                    textEl.insertAdjacentHTML('beforeend', ' <span class="edited-label">(diedit)</span>');
                }
            }
        }

        function removeMessageFromUI(id, type) {
            const msgEl = document.getElementById(`message-${id}`);
            if (msgEl) {
                if (type === 'everyone') {
                    const textEl = msgEl.querySelector('.message-text');
                    if(textEl) {
                        textEl.innerHTML = '<i class="fas fa-ban mr-1"></i> Pesan telah dihapus';
                        textEl.style.fontStyle = 'italic';
                        textEl.style.opacity = '0.7';
                    }
                    const actions = msgEl.querySelector('.message-actions');
                    if (actions) actions.remove();
                } else {
                    msgEl.remove();
                }
            }
        }

        function appendMessage(msg, isSelf) {
            if (document.getElementById(`message-${msg.id}`)) return;
            
            const emptyState = document.getElementById('empty-chat-state');
            if (emptyState) emptyState.remove();

            const sideClass = isSelf ? 'self' : 'other';
            const time = new Date(msg.created_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });
            
            let check = '';
            if (isSelf) {
                if (msg.is_read || pendingRead[msg.id]) {
                    check = `<i class="fas fa-check-double tick-blue" style="font-size: 10px; margin-left: 4px;" id="msg-tick-${msg.id}"></i>`;
                    delete pendingRead[msg.id];
                    delete pendingDelivered[msg.id];
                } else if (msg.is_delivered || pendingDelivered[msg.id]) {
                    check = `<i class="fas fa-check-double tick-gray" style="font-size: 10px; margin-left: 4px;" id="msg-tick-${msg.id}"></i>`;
                    delete pendingDelivered[msg.id];
                } else {
                    check = `<i class="fas fa-check tick-gray" style="font-size: 10px; margin-left: 4px;" id="msg-tick-${msg.id}"></i>`;
                }
            }

            let replyHtml = '';
            if (msg.reply_to) {
                replyHtml = `
                    <div class="reply-content">
                        <strong>${msg.reply_to.sender_id == {{ Auth::id() }} ? 'Anda' : '{{ $user->display_name }}'}</strong><br>
                        ${msg.reply_to.message || 'Berkas'}
                    </div>
                `;
            }

            let contentHtml = '';
            if (msg.type === 'image') {
                contentHtml = `<img src="/storage/${msg.attachment}" style="max-width: 250px; max-height: 300px; border-radius: 8px; margin-bottom: 5px; object-fit: contain; display: block; background: #eee;">`;
                if (msg.message) {
                    contentHtml += `<br>${msg.message.replace(/\n/g, '<br>')}`;
                }
            } else if (msg.type === 'file') {
                const ext = msg.attachment.split('.').pop().toLowerCase();
                let icon = 'fa-file-alt';
                if (ext === 'pdf') icon = 'fa-file-pdf';
                else if (['doc', 'docx'].includes(ext)) icon = 'fa-file-word';
                else if (['xls', 'xlsx'].includes(ext)) icon = 'fa-file-excel';
                else if (['ppt', 'pptx'].includes(ext)) icon = 'fa-file-powerpoint';
                else if (ext === 'zip') icon = 'fa-file-archive';
                else if (ext === 'txt') icon = 'fa-file-lines';

                contentHtml = `<a href="/storage/${msg.attachment}" target="_blank" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.05); padding: 10px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1);">
                                    <i class="fas ${icon}" style="font-size: 24px; color: var(--chat-accent);"></i>
                                    <div style="flex: 1; overflow: hidden;">
                                        <div class="text-truncate" style="font-weight: 500;">${msg.message || 'Berkas'}</div>
                                        <div style="font-size: 10px; opacity: 0.7;">Klik untuk membuka</div>
                                    </div>
                                    <i class="fas fa-download" style="font-size: 14px; opacity: 0.5;"></i>
                                </a>`;
            } else {
                contentHtml = (msg.message || '').replace(/\n/g, '<br>');
            }

            if (msg.is_edited) {
                contentHtml += ' <span class="edited-label">(diedit)</span>';
            }

            const actionsHtml = `
                <div class="message-actions" onclick="toggleActionMenu(${msg.id})">
                    <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
                    <div class="action-dropdown" id="dropdown-${msg.id}">
                        <div onclick="handleReply(${msg.id}, '${isSelf ? 'Anda' : '{{ $user->display_name }}'}', '${(msg.message || 'Berkas').replace(/'/g, "\\'").replace(/\n/g, ' ')}')"><i class="fas fa-reply"></i> Balas</div>
                        ${isSelf && msg.type === 'text' ? `<div onclick="handleEdit(${msg.id}, '${(msg.message || '').replace(/'/g, "\\'").replace(/\n/g, ' ')}')"><i class="fas fa-edit"></i> Edit</div>` : ''}
                        ${isSelf ? `<div onclick="handleDelete(${msg.id}, 'everyone')"><i class="fas fa-trash-alt"></i> Hapus untuk semua</div>` : ''}
                    </div>
                </div>
            `;

            const html = `
                <div class="message-row ${sideClass}" id="message-${msg.id}">
                    <div class="message-content">
                        ${actionsHtml}
                        ${replyHtml}
                        <div class="message-text">${contentHtml}</div>
                        <div class="message-time">${time} ${check}</div>
                    </div>
                </div>
            `;
            messageContainer.insertAdjacentHTML('beforeend', html);
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        function updateChatStatus(user, isOnline) {
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
                    // Update to last seen or just "Offline"
                    statusText.innerText = 'Terakhir online baru saja';
                    statusText.className = 'offline';
                    headerDot.classList.remove('online');
                }
            }
        }

        function updateChatStatuses(users) {
            users.forEach(u => updateChatStatus(u, true));
        }
    });
</script>
@endsection
