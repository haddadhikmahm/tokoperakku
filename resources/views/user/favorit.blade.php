@extends($layout ?? 'layouts.user')

@section('title', 'Favorit')

@section('css')
<style>
    .page-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 40px;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .favorit-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .favorit-item {
        background: #fff;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid rgba(241, 245, 249, 0.8);
        display: flex;
        align-items: center;
        gap: 32px;
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .favorit-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #e2e8f0;
    }

    .product-img-container {
        width: 160px;
        height: 160px;
        border-radius: 18px;
        overflow: hidden;
        background: #f1f5f9;
        flex-shrink: 0;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    }

    .product-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .favorit-item:hover .product-img-container img {
        transform: scale(1.08);
    }

    .product-details {
        flex: 1;
        min-width: 0;
    }

    .product-name {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        letter-spacing: -0.01em;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 4px;
        background: #f8fafc;
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .rating i { color: #f59e0b; font-size: 11px; }

    .store-link {
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .store-name {
        color: #980808;
        font-size: 14px;
        font-weight: 600;
        transition: color 0.2s;
    }

    .store-name:hover {
        color: #7f0606;
        text-decoration: underline;
    }

    .product-desc {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .added-date {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .added-date::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #cbd5e1;
    }

    .product-price {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-right: 20px;
    }

    .item-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-lihat {
        padding: 10px 24px;
        border-radius: 12px;
        background: #0f172a;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-lihat:hover {
        background: #1e293b;
        transform: translateX(4px);
    }

    .btn-chat-small {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-chat-small:hover {
        border-color: #94a3b8;
        color: #1e293b;
        background: #f8fafc;
    }

    .btn-remove {
        position: absolute;
        top: 24px;
        right: 24px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .btn-remove:hover {
        background: #fee2e2;
        color: #ef4444;
        transform: rotate(90deg);
    }

    /* Empty State Styling */
    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: #fff;
        border-radius: 32px;
        border: 2px dashed #e2e8f0;
    }

    .empty-icon-wrapper {
        width: 100px;
        height: 100px;
        background: #fff1f2;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        color: #f43f5e;
        font-size: 40px;
    }

    @media (max-width: 991px) {
        .favorit-item { flex-direction: column; align-items: flex-start; gap: 20px; }
        .product-img-container { width: 100%; height: 200px; }
        .product-price { margin-right: 0; }
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Favorit</h1>

<div class="favorit-list">
    @forelse ($favorites as $favorite)
    <div class="favorit-item">
        <div class="product-img-container">
            <img src="{{ asset('storage/' . optional($favorite->fotoProduk->first())->file_foto_produk) }}" 
                 alt="{{ $favorite->nama_produk }}"
                 onerror="this.onerror=null;this.src='{{ asset('assets/images/produk-default.jpg') }}';">
        </div>
        <div class="product-details">
            <h3 class="product-name">{{ $favorite->nama_produk }}</h3>
            <div class="product-meta">
                <div class="rating">
                    @php
                        $avgRating = $favorite->reviews->avg('rating') ?: 0;
                        $fullStars = floor($avgRating);
                        $hasHalf = ($avgRating - $fullStars) >= 0.5;
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $fullStars)
                            <i class="fa fa-star"></i>
                        @else
                            <i class="fa fa-star-o"></i>
                        @endif
                    @endfor
                </div>
                <a href="{{ route('guest-detail-usaha', $favorite->usaha->first()) }}" class="store-name">{{ $favorite->usaha->first()->nama_usaha ?? 'Toko Perak' }}</a>
            </div>
            <p class="product-desc">{{ \Illuminate\Support\Str::limit($favorite->deskripsi, 150) }}</p>
            <div class="added-date">Ditambahkan pada: {{ $favorite->pivot->created_at->format('d M Y') }}</div>
        </div>
        <div class="product-price">Rp {{ number_format($favorite->harga, 0, ',', '.') }}</div>
        <div class="item-actions">
            <a href="{{ route('guest-singleProduct', $favorite->slug) }}" class="btn-lihat">Lihat <i class="fa fa-arrow-right" style="font-size: 10px;"></i></a>
            <a href="{{ route('chats.index', ['user_id' => $favorite->usaha->first()?->user_id]) }}" class="btn-chat-small"><i class="fa fa-comment-o"></i></a>
        </div>
        
        <form action="{{ route('favorit.destroy', $favorite->id) }}" method="POST" class="m-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-remove border-0 bg-transparent" onclick="return confirm('Hapus dari favorit?')">
                <i class="fa fa-times"></i>
            </button>
        </form>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-icon-wrapper">
            <i class="fa fa-heart-o"></i>
        </div>
        <h3 class="product-name" style="font-size: 24px;">Belum ada produk favorit</h3>
        <p class="product-desc" style="max-width: 400px; margin: 10px auto 30px;">Cari produk menarik dan tambahkan ke favoritmu untuk menemukannya kembali dengan mudah!</p>
        <a href="{{ route('guest-katalog') }}" class="btn-lihat d-inline-flex" style="padding: 14px 40px;">Mulai Menjelajah</a>
    </div>
    @endforelse
</div>
@endsection
