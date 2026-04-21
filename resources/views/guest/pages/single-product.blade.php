@extends('guest.layouts.main')

@section('title', $produk->nama_produk)

@push('styles')
    {{-- Memuat file CSS khusus untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('assets/css/detail-product.css') }}">
@endpush

@section('content')
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
                                    <img src="{{ asset('storage/' . $produk->fotoProduk->first()->file_foto_produk) }}" alt="{{ $produk->nama_produk }}" id="mainProductImage" class="img-fluid">
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
                                        <img src="{{ asset('storage/' . $foto->file_foto_produk) }}" alt="Thumbnail" class="img-fluid" onclick="changeMainImage(this)">
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
                            <div class="action-icons">
                                <button class="btn btn-icon" id="copyLinkBtn" title="Bagikan Tautan">
                                    <i class="fa fa-share-alt"></i>
                                </button>
                            </div>
                        </div>

                        <div class="rating-stock-wrapper">
                            <div class="rating-wrapper">
                                <div class="stars-custom">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                </div>
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
                                            <a href="{{ route('chats.show', $usaha->user_id) }}" class="btn btn-sm btn-primary mb-2 shadow-sm">
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

            {{-- Review Section --}}
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="review-section">
                        <h4>Ulasan Pelanggan</h4>
                        <hr>
                        
                        @if($produk->reviews->isEmpty())
                            <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
                        @else
                            <div class="reviews-list mb-4">
                                @foreach($produk->reviews as $review)
                                    <div class="review-item mb-3">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $review->user->username }}</strong>
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </span>
                                        </div>
                                        <p class="mb-1">{{ $review->comment }}</p>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        <hr>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @auth
                            @if(Auth::user()->role === 'user')
                                <div class="add-review-form card p-4">
                                    <h5>Tambah Ulasan</h5>
                                    <form action="{{ route('reviews.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                        <div class="form-group mb-3">
                                            <label>Rating</label>
                                            <select name="rating" class="form-control" required>
                                                <option value="5">5 Bintang</option>
                                                <option value="4">4 Bintang</option>
                                                <option value="3">3 Bintang</option>
                                                <option value="2">2 Bintang</option>
                                                <option value="1">1 Bintang</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Komentar</label>
                                            <textarea name="comment" class="form-control" rows="3" placeholder="Tulis ulasan Anda..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <p class="text-muted">Silakan <a href="{{ route('login') }}">login</a> untuk memberikan ulasan.</p>
                        @endauth
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
            @foreach ($randomProduks as $produk)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-item">
                        <a href="{{ route('guest-singleProduct', $produk->slug) }}">
                            <div class="thumb">
                                <img src="{{ asset('storage/' . optional($produk->fotoProduk->first())->file_foto_produk) }}"
                                    alt="{{ $produk->nama_produk }}"
                                    onerror="this.onerror=null;this.src='{{ asset('images/produk-default.jpg') }}';">
                            </div>
                            <div class="down-content">
                                <h4>{{ $produk->nama_produk }}</h4>
                                <span class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                <ul class="stars">
                                    @for ($i = 0; $i < 5; $i++)
                                        <li><i class="fa fa-star"></i></li>
                                    @endfor
                                </ul>
                                <p class="product-reviews">20 Reviews</p>
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
@endsection

@push('scripts')
<script>
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
    });
</script>
@endpush