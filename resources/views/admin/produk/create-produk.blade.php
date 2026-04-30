@extends($layout ?? 'layouts.admin_premium')

@section('title', 'Tambah Produk')

@section('css')
<style>
    .form-container {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 40px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
        background: #fcfcfc;
    }

    .form-input:focus {
        border-color: #991b1b;
        background: #fff;
    }

    .form-input[readonly] {
        background: #f3f4f6;
        cursor: not-allowed;
    }

    .gallery-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 12px;
    }

    .gallery-item {
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-add-gallery {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        color: #9ca3af;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-add-gallery:hover {
        border-color: #991b1b;
        color: #991b1b;
    }

    .action-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .btn-submit {
        background: #991b1b;
        color: #fff;
        padding: 12px 32px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel {
        background: #fff;
        color: #4b5563;
        padding: 12px 32px;
        border-radius: 8px;
        font-weight: 600;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-submit:hover { opacity: 0.9; }
    .btn-cancel:hover { background: #f9fafb; }

    /* Tag/Category Style */
    .category-tag {
        display: inline-flex;
        align-items: center;
        background: #f3f4f6;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        color: #374151;
        margin-right: 8px;
        margin-bottom: 8px;
    }
</style>
@stop

@section('content')
<h2 style="font-size: 18px; font-weight: 700; margin-bottom: 30px;">Tambah Produk</h2>

<form action="{{ route('admin.produk-store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-container">
        <!-- Left Column -->
        <div class="form-column">
            <div class="form-group">
                <label>Kode Produk</label>
                <input type="text" name="kode_produk" class="form-input" value="{{ $autoKode }}" readonly>
            </div>
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" class="form-input" placeholder="Masukkan nama produk" required>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-input" placeholder="Rp. 0" required>
            </div>
            <div class="form-group">
                <label>Kategori Produk</label>
                <select name="kategori_produk_id" class="form-input" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoriProduks as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Pemilik (Usaha)</label>
                <select name="usaha_id" class="form-input" required>
                    <option value="">Pilih Usaha</option>
                    @foreach($usahas as $u)
                        <option value="{{ $u->id }}">{{ $u->nama_usaha }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Hidden stok field as it's not in UI but required in controller -->
            <input type="hidden" name="stok" value="0">
        </div>

        <!-- Right Column -->
        <div class="form-column">
            <div class="form-group">
                <label>Foto Produk</label>
                <div class="gallery-container" id="gallery-preview">
                    <div class="gallery-item">
                        <label class="btn-add-gallery">
                            <i class="fas fa-plus"></i>
                            <input type="file" name="foto_produk[]" multiple style="display: none;" onchange="previewGallery(this)">
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 40px;">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-input" rows="10" placeholder="Masukkan deskripsi produk"></textarea>
            </div>
        </div>
    </div>

    <div class="action-footer">
        <a href="{{ route('admin.produk-index') }}" class="btn-cancel">Batal</a>
        <button type="submit" class="btn-submit">Tambah Produk</button>
    </div>
</form>
@stop

@section('js')
<script>
    function previewGallery(input) {
        const container = document.getElementById('gallery-preview');
        const addButton = container.querySelector('.gallery-item:last-child');
        
        const oldPreviews = container.querySelectorAll('.gallery-item:not(:last-child)');
        oldPreviews.forEach(el => el.remove());

        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'gallery-item';
                    div.innerHTML = `<img src="${e.target.result}">`;
                    container.insertBefore(div, addButton);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@stop
