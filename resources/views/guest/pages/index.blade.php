@extends('guest.layouts.main')
@section('title', 'Index')
@section('content')

<div class="main-banner" id="top">
    <!-- Background dengan gambar asli Anda -->
    <div class="banner-background">
        <img src="{{ asset('assets/images/malioboro2.jpg') }}" alt="Keraton Yogyakarta"
             style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: -1;">
    </div>

    <!-- Banner Content di sebelah kanan -->
    <div class="banner-content">
        <h1>Perak Asli Kotagede – Warisan Seni dari Jogja</h1>
        <p>
            Karya seni perak dari Kotagede, Yogyakarta yang menggabungkan tradisi, ketelitian, dan keanggunan. Setiap detail menyimpan cerita, setiap ukiran merekam sejarah. Dibuat oleh tangan pengrajin lokal dengan teknik turun-temurun, menghadirkan keindahan otentik dengan kualitas terbaik.
        </p>
        <a href="javascript:void(0);" class="btn btn-danger btn-lg mt-3 scroll-to-produk">Beli Sekarang</a>
    </div>
</div>

<!-- Kategori Produk Section -->
<section class="categories">
    <div class="container">
        <div class="section-heading">
            <h2>Kategori Produk</h2>
            <p class="text-muted">Temukan berbagai koleksi produk perak terbaik kami</p>
        </div>

        <div id="categoryCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @php
                    // Bagi array kategori menjadi grup-grup 3
                    $chunks = array_chunk($kategoris->toArray(), 3);
                @endphp

                @foreach ($chunks as $key => $chunk)
                    <div class="carousel-item @if($key == 0) active @endif">
                        <div class="row">
                            @foreach ($chunk as $kategori)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <a href="{{ route('guest-katalog', array_merge(request()->except('page'), ['kategori' => $kategori['slug']])) }}">
                                        <div class="card category-card h-100">
                                            <img src="{{ asset('assets/images/' . $kategori['slug'] . '.jpg') }}"
                                                 alt="{{ $kategori['nama_kategori_produk'] }}"
                                                 class="card-img-top category-img"
                                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/kategori-default.jpg') }}';">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $kategori['nama_kategori_produk'] }}</h5>
                                                <p class="text-muted subtitle">Pesona Perak</p>
                                                <a href="{{ route('guest-katalog', array_merge(request()->except('page'), ['kategori' => $kategori['slug']])) }}" class="btn btn-outline-dark btn-sm">Lihat Produk</a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"></span>
            </button>
        </div>
    </div>
</section>
    <!-- ***** Main Banner Area End ***** -->

    <!-- ***** Produk Area Starts ***** -->
<section class="products">
    <div class="container">
        <div class="section-heading">
            <h2>Produk Terbaru Kami!</h2>
            <span>Temukan Produk Terfavoritmu!</span>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @foreach ($randomProduks as $produk)
                {{-- Menggunakan col-lg-3 untuk 4 item per baris --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-item">
                        <a href="{{ route('guest-singleProduct', $produk->slug) }}">
                            <div class="thumb">
                                <img src="{{ asset('storage/' . optional($produk->fotoProduk->first())->file_foto_produk) }}"
                                    alt="{{ $produk->nama_produk }}"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/produk-default.jpg') }}';">
                            </div>
                            <div class="down-content">
                                <h4>{{ $produk->nama_produk }}</h4>
                                <span class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                <ul class="stars">
                                    @php
                                        $avgRating = $produk->reviews->avg('rating') ?: 0;
                                        $fullStars = floor($avgRating);
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li><i class="fa fa-star{{ $i <= $fullStars ? '' : '-o' }}"></i></li>
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
    <!-- ***** Produk Area Ends ***** -->

    <!-- ***** Pengrajin's Area Starts ***** -->
<section class="pengrajins-section">
    <div class="container">
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Temukan Pengrajin Kesukaanmu!</h2>
                    <p class="text-muted">Kenali tangan-tangan terampil di balik keindahan perak Kotagede</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            @foreach ($pengerajins as $pengerajin)
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="pengrajin-card">
                    <div class="pengrajin-thumb">
                        <img src="{{ $pengerajin->foto_pengerajin ? asset('storage/' . $pengerajin->foto_pengerajin) : asset('assets/images/kategori-default.jpg') }}" 
                             alt="{{ $pengerajin->nama_pengerajin }}"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/kategori-default.jpg') }}';">
                    </div>
                    <div class="pengrajin-info">
                        <h5 class="pengrajin-name">{{ $pengerajin->nama_pengerajin }}</h5>
                        <p class="pengrajin-spesialisasi">{{ $pengerajin->jk_pengerajin == 'P' ? 'Pria' : 'Wanita' }}, {{ $pengerajin->usia_pengerajin }} Tahun</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="col-lg-12">
            <div class="text-center mt-2">
                <a href="{{ route('guest-katalog') }}" class="see-all-button btn">Lihat Katalog Produk</a>
            </div>
        </div>
    </div>
</section>

<!-- ***** Usaha/Toko Section ***** -->
<section class="products" style="background-color: #fff; padding-top: 0;">
    <div class="container">
        <div class="section-heading">
            <h2>Toko & Usaha Perak</h2>
            <span>Kunjungi toko-toko perak terbaik di Kotagede</span>
        </div>
        <div class="row">
            @foreach ($usahas as $usaha)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="product-item">
                    <a href="{{ route('guest-detail-usaha', $usaha->id) }}">
                        <div class="thumb">
                            <img src="{{ $usaha->foto_usaha ? asset('storage/' . $usaha->foto_usaha) : asset('assets/images/kategori-default.jpg') }}" 
                                 alt="{{ $usaha->nama_usaha }}"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/kategori-default.jpg') }}';">
                        </div>
                        <div class="down-content">
                            <h4>{{ $usaha->nama_usaha }}</h4>
                            <span class="product-price">{{ $usaha->wilayah->nama_wilayah ?? 'Kotagede' }}</span>
                            <p class="product-reviews">{{ $usaha->produks_count ?? $usaha->produks->count() }} Produk Tersedia</p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="about-us">
    <div class="container">
        <div class="row align-items-center">
            {{-- Gambar akan mengambil 7 kolom di layar besar (lebih besar) --}}
            <div class="col-lg-7 col-md-12">
                <div class="about-image">
                    <img src="{{ asset('assets/images/kerajinan-perak-kota-ged.png') }}" alt="Sentra Kerajinan Perak Kotagede" class="img-fluid">
                </div>
            </div>
            {{-- Kotak teks akan mengambil 5 kolom di layar besar (lebih kecil) --}}
            <div class="col-lg-5 col-md-12">
                <div class="about-content">
                    <h3>TekoPerakku</h3>
                    <p>TekoPerakku menghadirkan kerajinan perak asli Kotagede dengan kualitas terbaik. Setiap karya diproses secara teliti oleh pengrajin berpengalaman untuk menjaga keaslian dan keindahan tradisi. Kami berkomitmen memberikan produk yang elegan, otentik, dan bernilai seni tinggi bagi setiap pelanggan.</p>
                    <a href="{{ route('guest-about') }}" class="btn btn-primary about-btn">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.querySelector('.scroll-to-produk').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('.products').scrollIntoView({
            behavior: 'smooth'
        });
    });
</script>
@endpush

