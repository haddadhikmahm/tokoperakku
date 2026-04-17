@extends('guest.layouts.main')

@section('title', 'Daftar Chat')

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-12">
            <h3>Daftar Pesan</h3>
            <hr>
            <div class="list-group">
                @forelse($chatUsers as $chatUser)
                    <a href="{{ route('chats.show', $chatUser->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $chatUser->username }}</strong>
                            <p class="mb-0 text-muted small">Klik untuk melihat percakapan</p>
                        </div>
                        @php
                            $unreadCount = $chatUser->chatsSent()->where('receiver_id', Auth::id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="badge badge-primary badge-pill">{{ $unreadCount }}</span>
                        @endif
                    </a>
                @empty
                    <p class="text-muted">Belum ada percakapan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
