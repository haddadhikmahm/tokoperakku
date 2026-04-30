@extends($layout ?? 'layouts.user')

@section('title', 'Favorit')

@section('css')
<style>
    .page-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #18181b;
    }

    .favorit-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .favorit-item {
        background: #fff;
        border-radius: 20px;
        padding: 25px;
        border: 1px solid #f1f1f4;
        display: flex;
        align-items: center;
        gap: 30px;
        position: relative;
    }

    .product-img-container {
        width: 140px;
        height: 140px;
        border-radius: 15px;
        overflow: hidden;
        background: #f8f8f8;
        flex-shrink: 0;
    }

    .product-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details {
        flex: 1;
        min-width: 0;
    }

    .product-name {
        font-size: 18px;
        font-weight: 700;
        color: #18181b;
        margin-bottom: 8px;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 12px;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        font-weight: 700;
    }

    .rating i { color: #000; font-size: 10px; }

    .store-name {
        color: #ef4444;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .product-desc {
        font-size: 13px;
        color: #71717a;
        line-height: 1.5;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .added-date {
        font-size: 12px;
        color: #a1a1aa;
        font-style: italic;
    }

    .product-price {
        font-size: 16px;
        font-weight: 700;
        color: #18181b;
        margin-right: 40px;
    }

    .item-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-lihat {
        padding: 8px 25px;
        border-radius: 8px;
        border: 1px solid #18181b;
        background: #fff;
        color: #18181b;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-chat-small {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: 1px solid #e4e4e7;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #71717a;
        text-decoration: none;
    }

    .btn-remove {
        position: absolute;
        top: 25px;
        right: 25px;
        color: #a1a1aa;
        cursor: pointer;
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .favorit-item { flex-direction: column; align-items: flex-start; gap: 20px; }
        .product-price { margin-right: 0; }
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Favorit</h1>

<div class="favorit-list">
    <!-- Static Examples matching image -->
    <div class="favorit-item">
        <div class="product-img-container">
            <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&q=80&w=200" alt="">
        </div>
        <div class="product-details">
            <h3 class="product-name">Raw Black T-Shirt Lineup</h3>
            <div class="product-meta">
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <a href="#" class="store-name">Toko Pak Yanto</a>
            </div>
            <p class="product-desc">Elevate Your Everyday Style With Our Men's Black T-Shirts, The Ultimate Wardrobe Essential For Modern Men. Crafted With Meticulous Attention T...</p>
            <div class="added-date">Added On: 27 July 2023</div>
        </div>
        <div class="product-price">Rp. 500.000</div>
        <div class="item-actions">
            <a href="#" class="btn-lihat">Lihat <i class="fas fa-arrow-right" style="font-size: 10px;"></i></a>
            <a href="#" class="btn-chat-small"><i class="far fa-comment-dots"></i></a>
        </div>
        <i class="fas fa-times btn-remove"></i>
    </div>

    <div class="favorit-item">
        <div class="product-img-container">
            <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&q=80&w=200" alt="">
        </div>
        <div class="product-details">
            <h3 class="product-name">Raw Black T-Shirt Lineup</h3>
            <div class="product-meta">
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <a href="#" class="store-name">Unique Silver</a>
            </div>
            <p class="product-desc">Elevate Your Everyday Style With Our Men's Black T-Shirts, The Ultimate Wardrobe Essential For Modern Men. Crafted With Meticulous Attention T...</p>
            <div class="added-date">Added On: 27 July 2023</div>
        </div>
        <div class="product-price">Rp. 500.000</div>
        <div class="item-actions">
            <a href="#" class="btn-lihat">Lihat <i class="fas fa-arrow-right" style="font-size: 10px;"></i></a>
            <a href="#" class="btn-chat-small"><i class="far fa-comment-dots"></i></a>
        </div>
        <i class="fas fa-times btn-remove"></i>
    </div>
</div>
@endsection
