@extends('guest.layouts.main')

@section('title', $produk->nama_produk)

@push('styles')
    {{-- Memuat file CSS khusus untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('assets/css/detail-product.css') }}">
    <style>
        .btn-icon.active i {
            color: #ef4444;
        }
        #favoritBtn i {
            transition: all 0.3s ease;
        }

        /* Review Section Styling */
        .review-section-title {
            text-align: center;
            margin: 60px 0 40px;
            position: relative;
        }
        .review-section-title h2 {
            font-size: 28px;
            font-weight: 700;
            display: inline-block;
            padding-bottom: 10px;
        }
        .review-section-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #ddd;
        }

        .review-summary-card {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .rating-avg-box {
            text-align: center;
            flex: 0.7;
        }
        .rating-avg-value {
            font-size: 48px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        .rating-avg-stars {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .rating-avg-count {
            color: #666;
            font-size: 14px;
        }

        .rating-bars-box {
            flex: 2.5;
            padding: 0 60px;
            border-right: 1px solid #ddd;
        }
        .rating-bar-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }
        .rating-bar-label {
            font-size: 14px;
            width: 30px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .rating-bar-progress {
            flex-grow: 1;
            height: 8px;
            background-color: #eee;
            border-radius: 4px;
            overflow: hidden;
        }
        .rating-bar-fill {
            height: 100%;
            background-color: #333;
            border-radius: 4px;
        }

        .write-review-box {
            flex: 0.8;
            display: flex;
            justify-content: center;
        }
        .btn-write-review {
            border: 1px solid #333;
            background: transparent;
            padding: 12px 25px;
            font-weight: 600;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .btn-write-review:hover {
            background-color: #333;
            color: #fff;
        }

        .review-filter-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .review-filter-select {
            border: 1px solid #ddd;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .review-list-item {
            border-bottom: 1px solid #eee;
            padding: 30px 0;
            display: flex;
            gap: 20px;
        }
        .review-user-avatar {
            width: 45px;
            height: 45px;
            background-color: #e3f2fd;
            color: #2196f3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }
        .review-content {
            flex-grow: 1;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .review-user-name {
            font-weight: 700;
            font-size: 16px;
            color: #333;
        }
        .review-meta {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .review-date {
            color: #999;
            font-size: 13px;
        }
        .review-stars {
            color: #333;
            font-size: 14px;
        }
        .review-text {
            color: #666;
            line-height: 1.6;
            font-size: 14px;
        }

        .load-more-container {
            text-align: center;
            margin-top: 40px;
        }
        .btn-load-more {
            border: 1px solid #ddd;
            background: #fff;
            padding: 10px 30px;
            border-radius: 5px;
            color: #666;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-load-more:hover {
            border-color: #333;
            color: #333;
        }

        /* Modal Review Form */
        #reviewFormWrapper {
            display: none;
            margin-bottom: 40px;
        }
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            cursor: pointer;
            width: 30px;
            height: 30px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ddd'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }
        .rating-input input:checked ~ label,
        .rating-input label:hover,
        .rating-input label:hover ~ label {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23333'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
        }
    </style>
@endpush

@section('content')
    @php
        // Mengambil data review secara langsung dari database untuk memastikan sinkronisasi data
        $actualReviews = \App\Models\Review::where('produk_id', $produk->id)->get();
        $actualReviewsCount = $actualReviews->count();
        $actualAverageRating = $actualReviewsCount > 0 ? $actualReviews->avg('rating') : 0;
        
        $actualRatingStats = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach($actualReviews as $r) {
            if(isset($actualRatingStats[$r->rating])) {
                $actualRatingStats[$r->rating]++;
            }
        }

        $hasReviewed = false;
        if(Auth::check() && Auth::user()->role === 'user') {
            $hasReviewed = $actualReviews->where('user_id', Auth::id())->count() > 0;
        }
    @endphp
    {{-- Breadcrumb Navigation --}}
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb" class="product-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('guest-katalog') }}">Katalog</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $produk->nama_produk }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Product Detail Section --}}
    <section class="section" id="product">
        <div class="container">
            <div class="row">
                {{-- Kolom Kiri: Galeri Gambar Produk --}}
                <div class="col-lg-6">
                    <div class="gallery-wrapper">
                        {{-- Gambar Utama --}}
                        {{-- Gambar Utama yang Bisa Diklik --}}
                        <div class="main-image-container mb-3">
                            @if($produk->fotoProduk->isNotEmpty())
                                {{-- Bungkus <img> dengan <a> --}}
                                <a href="{{ asset('storage/' . $produk->fotoProduk->first()->file_foto_produk) }}" data-lightbox="product-gallery">
                                    <img src="{{ asset('storage/' . $produk->fotoProduk->first()->file_foto_produk) }}" alt="{{ $produk->nama_produk }}" id="mainProductImage" class="img-fluid"
                                     onerror="this.onerror=null;this.src='{{ asset('assets/images/produk-default.jpg') }}';">
                                </a>
                            @else
                                <a href="{{ asset('assets/images/produk-default.jpg') }}" data-lightbox="product-gallery">
                                    <img src="{{ asset('assets/images/produk-default.jpg') }}" alt="Produk Default" id="mainProductImage" class="img-fluid">
                                </a>
                            @endif
                        </div>
                        
                        {{-- Thumbnail Gambar Scroller --}}
                        <div class="thumbnail-scroller-wrapper">
                            <button class="thumb-nav-btn prev" id="thumbPrevBtn"><i class="fa fa-chevron-left"></i></button>
                            <div class="thumbnail-container" id="thumbnailContainer">
                                @foreach ($produk->fotoProduk as $index => $foto)
                                    <div class="thumbnail-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $foto->file_foto_produk) }}" alt="Thumbnail" class="img-fluid" 
                                         onerror="this.onerror=null;this.src='{{ asset('assets/images/produk-default.jpg') }}';" onclick="changeMainImage(this)">
                                    </div>
                                @endforeach
                            </div>
                            <button class="thumb-nav-btn next" id="thumbNextBtn"><i class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Informasi Produk --}}
                <div class="col-lg-6">
                    {{-- GANTI SELURUH ISI DARI <div class="right-content"> DENGAN KODE INI --}}
                    <div class="right-content">

                        {{-- BARIS 1: JUDUL PRODUK DAN IKON AKSI --}}
                        <div class="product-header">
                            <h2 class="product-title">{{ $produk->nama_produk }}</h2>
                            <div class="action-icons d-flex gap-2">
                                @auth
                                    <button class="btn btn-icon {{ Auth::user()->favoritProduks->contains($produk->id) ? 'active' : '' }}" 
                                            id="favoritBtn" 
                                            data-id="{{ $produk->id }}" 
                                            title="Tambah ke Favorit">
                                        <i class="fa {{ Auth::user()->favoritProduks->contains($produk->id) ? 'fa-heart' : 'fa-heart-o' }}"></i>
                                    </button>
                                @else
                                    <a href="{{ route('loginForm') }}" class="btn btn-icon" title="Login untuk Favorit">
                                        <i class="fa fa-heart-o"></i>
                                    </a>
                                @endauth
                                <button class="btn btn-icon" id="copyLinkBtn" title="Bagikan Tautan">
                                    <i class="fa fa-share-alt"></i>
                                </button>
                            </div>
                        </div>

                        <div class="rating-stock-wrapper">
                            <div class="rating-wrapper">
                                <div class="stars-custom">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star{{ $i <= floor($actualAverageRating) ? '' : ($i - $actualAverageRating < 1 && $i - $actualAverageRating > 0 ? '-half-o' : '-o') }}"></i>
                                    @endfor
                                </div>
                                <span class="ms-2 text-muted" style="font-size: 13px;">({{ $actualReviewsCount }} Ulasan)</span>
                            </div>
                            <span class="stock-status">IN STOCK</span>
                        </div>
                        <span class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                        @php
                            $usaha = $produk->usaha->first();
                        @endphp

                        <div class="usaha-info">
                            @if ($usaha)
                                <a href="{{ route('guest-detail-usaha', ['usaha' => $usaha, 'from_product' => $produk->slug]) }}" class="usaha-link">
                                    <img src="{{ asset('assets/images/kategori-default.jpg') }}" alt="Logo Usaha" class="usaha-avatar">
                                    <div class="usaha-details">
                                        <span class="usaha-name">{{ $usaha->nama_usaha }}</span>
                                        <span class="usaha-spesialisasi">{{ $usaha->deskripsi_usaha ?? 'Kerajinan Perak Kotagede' }}</span>
                                    </div>
                                </a>
                                <div class="social-links mt-2">
                                    @auth
                                        @if(Auth::id() !== $usaha->user_id)
                                            <a href="{{ route('chats.show', ['user' => $usaha->user_id, 'usaha_id' => $usaha->id]) }}" class="btn btn-sm btn-primary mb-2 shadow-sm">
                                                <i class="fa fa-comments"></i> Chat Penjual
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('loginForm') }}" class="btn btn-sm btn-outline-primary mb-2">
                                            <i class="fa fa-sign-in"></i> Login untuk Chat
                                        </a>
                                    @endauth
                                    <div class="d-flex gap-2">
                                        <a href="#" target="_blank" class="social-icon email" title="Email"><i class="fa fa-envelope"></i></a>
                                        <a href="https://wa.me/" target="_blank" class="social-icon whatsapp" title="WhatsApp"><i class="fa fa-phone"></i></a>
                                        <a href="#" target="_blank" class="social-icon instagram" title="Instagram"><i class="fa fa-instagram"></i></a>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">Informasi usaha tidak tersedia.</p>
                            @endif
                        </div>

                        {{-- DESKRIPSI DAN DETAIL PRODUK --}}
                        <div class="product-details">
                            <h5>Detail</h5>
                            <p>{{ $produk->deskripsi }}</p>
                        </div>

                    </div>
                </div>
            </div>

    </section>

    <section class="products">
        <div class="container">
            <div class="section-heading">
                <h2>Produk Terkait</h2>
            </div>
        </div>
        <div class="container">
            <div class="row">
                @foreach ($randomProduks as $relatedProduct)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="product-item">
                            <a href="{{ route('guest-singleProduct', $relatedProduct->slug) }}">
                                <div class="thumb">
                                    <img src="{{ asset('storage/' . optional($relatedProduct->fotoProduk->first())->file_foto_produk) }}"
                                        alt="{{ $relatedProduct->nama_produk }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/produk-default.jpg') }}';">
                                </div>
                                <div class="down-content">
                                    <h4>{{ $relatedProduct->nama_produk }}</h4>
                                    <span class="product-price">Rp {{ number_format($relatedProduct->harga, 0, ',', '.') }}</span>
                                    <ul class="stars">
                                        @php
                                            $avg = $relatedProduct->reviews->avg('rating') ?: 0;
                                            $full = floor($avg);
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <li><i class="fa fa-star{{ $i <= $full ? '' : ($i - $avg < 1 && $i - $avg > 0 ? '-half-o' : '-o') }}"></i></li>
                                        @endfor
                                    </ul>
                                    <p class="product-reviews">
                                        @if($relatedProduct->reviews->count() > 0)
                                            {{ $relatedProduct->reviews->count() }} Reviews
                                        @else
                                            Belum ada review
                                        @endif
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-12">
                <div class="text-center mt-5">
                    <a href="{{ route('guest-katalog') }}" class="see-all-button btn">Lihat Semua</a>
                </div>
            </div>
        </div>
    </section>

    {{-- High Fidelity Review Section --}}
    <section class="section" id="reviews-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="review-section-title">
                        <h2>Reviews</h2>
                    </div>

                    {{-- Summary Card --}}
                    <div class="review-summary-card">
                        <div class="rating-avg-box">
                            <div class="rating-avg-value">{{ number_format($actualAverageRating, 1) }}</div>
                            <div class="rating-avg-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star{{ $i <= floor($actualAverageRating) ? '' : ($i - $actualAverageRating < 1 && $i - $actualAverageRating > 0 ? '-half-o' : '-o') }}"></i>
                                @endfor
                            </div>
                            <div class="rating-avg-count">{{ number_format($actualAverageRating, 1) }} dari 5 Bintang</div>
                            <div class="text-muted" style="font-size: 12px; margin-top: 4px;">({{ $actualReviewsCount }} Ulasan)</div>
                        </div>

                        <div class="rating-bars-box" style="{{ $hasReviewed ? 'border-right: none;' : '' }}">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php 
                                    $sCount = $actualRatingStats[$star];
                                    $percent = $actualReviewsCount > 0 ? ($sCount / $actualReviewsCount) * 100 : 0;
                                @endphp
                                <div class="rating-bar-item">
                                    <div class="rating-bar-label">{{ $star }} <i class="fa fa-star"></i></div>
                                    <div class="rating-bar-progress">
                                        <div class="rating-bar-fill" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(!$hasReviewed)
                        <div class="write-review-box">
                            @auth
                                @if(Auth::user()->role === 'user')
                                    <button class="btn btn-write-review" id="btnShowReviewForm" onclick="toggleReviewForm()">Tulis Ulasan Anda</button>
                                @else
                                    <span class="text-muted text-center">Hanya Pembeli yang dapat mengulas</span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-write-review">Tulis Ulasan Anda</a>
                            @endauth
                        </div>
                        @endif
                    </div>

                    {{-- Write Review Form (Hidden by Default) --}}
                    <div id="reviewFormWrapper" class="card p-4 shadow-sm" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Tulis Ulasan Anda</h5>
                            <button type="button" class="btn-close" onclick="toggleReviewForm()"></button>
                        </div>
                        <form id="reviewForm" action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <div class="mb-3">
                                <label class="form-label">Berikan Rating</label>
                                <div class="rating-input">
                                    <input type="radio" name="rating" value="5" id="star5" required><label for="star5"></label>
                                    <input type="radio" name="rating" value="4" id="star4"><label for="star4"></label>
                                    <input type="radio" name="rating" value="3" id="star3"><label for="star3"></label>
                                    <input type="radio" name="rating" value="2" id="star2"><label for="star2"></label>
                                    <input type="radio" name="rating" value="1" id="star1"><label for="star1"></label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ulasan Anda</label>
                                <textarea name="comment" class="form-control" rows="4" placeholder="Ceritakan pengalaman Anda menggunakan produk ini..." required></textarea>
                            </div>
                            <button type="submit" id="btnSubmitReview" class="btn btn-dark w-100 py-2">Kirim Ulasan</button>
                        </form>
                    </div>

                    {{-- Review Filter --}}
                    @if($actualReviewsCount > 0)
                    <div class="review-filter-box">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted fw-bold">Urut Berdasarkan:</span>
                            <select class="review-filter-select" id="reviewSortSelect" onchange="sortReviews()">
                                <option value="newest">Produk Terbaru</option>
                                <option value="highest">Rating Tertinggi</option>
                                <option value="lowest">Rating Terendah</option>
                            </select>
                        </div>
                    </div>
                    @endif

                    {{-- Review List --}}
                    <div class="reviews-list" id="reviewsListContainer">
                        @forelse($actualReviews->sortByDesc('created_at') as $review)
                            <div class="review-list-item {{ $loop->iteration > 3 ? 'd-none hidden-review' : '' }}" 
                                 data-rating="{{ $review->rating }}" 
                                 data-date="{{ $review->created_at->timestamp }}">
                                <div class="review-user-avatar">
                                    {{ strtoupper(substr($review->user->username, 0, 2)) }}
                                </div>
                                <div class="review-content">
                                    <div class="review-header">
                                        <div class="review-user-name">{{ $review->user->username }}</div>
                                        <div class="review-meta">
                                            <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                            <div class="review-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-text">
                                        {{ $review->comment }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fa fa-comments-o fa-3x text-light mb-3"></i>
                                <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($actualReviewsCount > 3)
                        <div class="text-center mt-5" id="loadMoreContainer">
                            <button class="btn btn-outline-dark px-5 py-2" style="border-radius: 25px; font-weight: 600;" onclick="loadMoreReviews()">Tampilkan Ulasan Lainnya</button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Fungsi untuk toggle form ulasan
    function toggleReviewForm() {
        const form = document.getElementById('reviewFormWrapper');
        if (form.style.display === 'none') {
            form.style.display = 'block';
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            form.style.display = 'none';
        }
    }

    // Fungsi untuk mengubah gambar utama, sekarang bisa diakses secara global
    function changeMainImage(thumbnailElement) {
        const index = Array.from(document.querySelectorAll('.thumbnail-item')).indexOf(thumbnailElement.parentElement);
        if (index > -1) {
            updateGallery(index);
        }
    }

    // Fungsi utama untuk mengupdate galeri
    function updateGallery(index) {
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        const mainImage = document.getElementById('mainProductImage');
        const mainImageLink = mainImage ? mainImage.parentElement : null;

        if (thumbnails.length === 0 || !mainImage) return;

        const newImageSrc = thumbnails[index].querySelector('img').src;

        mainImage.src = newImageSrc;
        if (mainImageLink) {
            mainImageLink.href = newImageSrc;
        }

        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        thumbnails[index].classList.add('active');

        // Simpan index saat ini untuk tombol prev/next
        window.currentImageIndex = index;
    }

    // Jalankan semua event listener setelah halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        window.currentImageIndex = 0; // Inisialisasi index gambar
        
        // --- Logika untuk Tombol Navigasi Thumbnail ---
        const thumbContainer = document.getElementById('thumbnailContainer');
        const thumbPrevBtn = document.getElementById('thumbPrevBtn');
        const thumbNextBtn = document.getElementById('thumbNextBtn');

        if (thumbContainer) {
            const scrollAmount = 300;
            thumbNextBtn.addEventListener('click', () => {
                let nextIndex = window.currentImageIndex + 1;
                if (nextIndex >= thumbContainer.children.length) {
                    nextIndex = 0;
                }
                updateGallery(nextIndex);
                // Scroll to the active thumbnail
                thumbContainer.children[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            });
            thumbPrevBtn.addEventListener('click', () => {
                let prevIndex = window.currentImageIndex - 1;
                if (prevIndex < 0) {
                    prevIndex = thumbContainer.children.length - 1;
                }
                updateGallery(prevIndex);
                // Scroll to the active thumbnail
                thumbContainer.children[prevIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            });
        }

        // --- Logika untuk Tombol Copy Link ---
        const copyBtn = document.getElementById('copyLinkBtn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function() {
                const urlToCopy = window.location.href;
                navigator.clipboard.writeText(urlToCopy).then(() => {
                    const icon = copyBtn.querySelector('i');
                    const originalIconClass = icon.className;
                    icon.className = 'fa fa-check';
                    copyBtn.disabled = true;
                    setTimeout(() => {
                        icon.className = originalIconClass;
                        copyBtn.disabled = false;
                    }, 2000);
                }).catch(err => console.error('Gagal menyalin:', err));
            });
        }

        // --- Logika untuk Tombol Favorit ---
        const favoritBtn = document.getElementById('favoritBtn');
        if (favoritBtn) {
            favoritBtn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const icon = this.querySelector('i');
                
                axios.post(`/favorit/toggle/${id}`)
                    .then(response => {
                        if (response.data.success) {
                            this.classList.toggle('active');
                            if (this.classList.contains('active')) {
                                icon.className = 'fa fa-heart';
                            } else {
                                icon.className = 'fa fa-heart-o';
                            }
                        }
                    })
                    .catch(err => console.error('Gagal toggle favorit:', err));
            });
        }
    });

    // --- Logika AJAX untuk Form Ulasan ---
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btnSubmit = document.getElementById('btnSubmitReview');
            const originalBtnText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengirim...';
            btnSubmit.disabled = true;

            const formData = new FormData(this);
            
            axios.post(this.action, formData)
                .then(response => {
                    if (response.data.success) {
                        // Sembunyikan form
                        toggleReviewForm();
                        
                        // Sembunyikan tombol "Tulis Ulasan" dan kotaknya
                        const writeReviewBox = document.querySelector('.write-review-box');
                        if (writeReviewBox) writeReviewBox.remove();

                        // Buat elemen ulasan baru
                        const review = response.data.review;
                        const reviewHtml = `
                            <div class="review-list-item" style="background-color: #fff9f9; transition: background 2s;">
                                <div class="review-user-avatar">
                                    ${review.initials}
                                </div>
                                <div class="review-content">
                                    <div class="review-header">
                                        <div class="review-user-name">${review.username}</div>
                                        <div class="review-meta">
                                            <div class="review-date">${review.date}</div>
                                            <div class="review-stars">
                                                ${generateStarsHtml(review.rating)}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-text">
                                        ${review.comment}
                                    </div>
                                </div>
                            </div>
                        `;

                        // Tambahkan ke daftar (di paling atas)
                        const container = document.getElementById('reviewsListContainer');
                        const emptyState = container.querySelector('.text-center.py-5');
                        if (emptyState) emptyState.remove();
                        
                        // Buat HTML dengan atribut data untuk sorting
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = reviewHtml;
                        const newReviewElement = tempDiv.firstElementChild;
                        newReviewElement.setAttribute('data-rating', review.rating);
                        newReviewElement.setAttribute('data-date', Math.floor(Date.now() / 1000));
                        
                        container.insertAdjacentElement('afterbegin', newReviewElement);
                        
                        // Scroll ke ulasan baru
                        newReviewElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                })
                .catch(error => {
                    const message = error.response?.data?.error || 'Terjadi kesalahan saat mengirim ulasan.';
                    alert(message);
                    btnSubmit.innerHTML = originalBtnText;
                    btnSubmit.disabled = false;
                });
        });
    }

    function generateStarsHtml(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fa fa-star${i <= rating ? '' : '-o'}"></i> `;
        }
        return stars;
    }

    function loadMoreReviews() {
        const hiddenReviews = document.querySelectorAll('.hidden-review');
        hiddenReviews.forEach(r => r.classList.remove('d-none', 'hidden-review'));
        
        const container = document.getElementById('loadMoreContainer');
        if (container) container.remove();
    }

    function sortReviews() {
        const select = document.getElementById('reviewSortSelect');
        const criteria = select.value;
        const container = document.getElementById('reviewsListContainer');
        const items = Array.from(container.querySelectorAll('.review-list-item'));
        
        items.sort((a, b) => {
            if (criteria === 'newest') {
                return b.dataset.date - a.dataset.date;
            } else if (criteria === 'highest') {
                return b.dataset.rating - a.dataset.rating;
            } else if (criteria === 'lowest') {
                return a.dataset.rating - b.dataset.rating;
            }
            return 0;
        });
        
        // Re-append items in new order
        items.forEach(item => container.appendChild(item));
        
        // Tetap terapkan logic "Load More" jika tombolnya masih ada
        const loadMoreBtn = document.getElementById('loadMoreContainer');
        if (loadMoreBtn) {
            items.forEach((item, index) => {
                if (index >= 3) {
                    item.classList.add('d-none', 'hidden-review');
                } else {
                    item.classList.remove('d-none', 'hidden-review');
                }
            });
        }
    }
</script>
@endpush