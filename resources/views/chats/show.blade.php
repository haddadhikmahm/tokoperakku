@extends('guest.layouts.main')

@section('title', 'Chat dengan ' . $user->username)

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Chat dengan {{ $user->username }}</h5>
                </div>
                <div class="card-body chat-box" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
                    @foreach($messages as $msg)
                        <div class="mb-3 d-flex {{ $msg->sender_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="p-3 rounded {{ $msg->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-white border' }}" style="max-width: 70%;">
                                <p class="mb-0">{{ $msg->message }}</p>
                                <small class="{{ $msg->sender_id === Auth::id() ? 'text-light' : 'text-muted' }} d-block text-right">
                                    {{ $msg->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form action="{{ route('chats.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Ketik pesan..." required autofocus>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('chats.index') }}" class="btn btn-link"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Chat</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Scroll to bottom of chat box
    document.addEventListener('DOMContentLoaded', function() {
        const chatBox = document.querySelector('.chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>
@endsection
