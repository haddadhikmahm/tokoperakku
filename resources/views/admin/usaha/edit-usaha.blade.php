@extends('layouts.admin_premium')

@section('title', 'Edit Usaha')

@section('css')
<style>
    .form-container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
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

    .social-input-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .social-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f3f4f6;
        color: #4b5563;
        font-size: 16px;
        flex-shrink: 0;
    }

    .photo-preview-circle {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f9fafb;
        position: relative;
    }

    .photo-preview-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-upload {
        display: block;
        width: fit-content;
        margin: 0 auto;
        padding: 8px 16px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-upload:hover {
        background: #f9fafb;
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
        justify-content: space-between;
        align-items: center;
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

    .btn-delete {
        background: #fff;
        color: #dc2626;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        border: 1px solid #fee2e2;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover { opacity: 0.9; }
    .btn-cancel:hover { background: #f9fafb; }
    .btn-delete:hover { background: #fee2e2; }
</style>
@stop

@section('content')
<h2 style="font-size: 18px; font-weight: 700; margin-bottom: 30px;">Edit Usaha</h2>

<form action="{{ route('admin.usaha-update', $usaha->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-container">
        <!-- Left Column -->
        <div class="form-column">
            <div class="form-group">
                <label>Kode Usaha</label>
                <input type="text" name="kode_usaha" class="form-input" value="{{ $usaha->kode_usaha }}" readonly>
            </div>
            <div class="form-group">
                <label>Nama Usaha</label>
                <input type="text" name="nama_usaha" class="form-input" value="{{ $usaha->nama_usaha }}" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-input" value="{{ $usaha->user->username ?? '' }}" readonly>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-input" value="{{ $usaha->user->email ?? '' }}" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" placeholder="******** (Kosongkan jika tidak diubah)">
            </div>
            <div class="form-group">
                <label>Pemilik</label>
                <select name="user_id" class="form-input">
                    <option value="">Pilih Pemilik (Opsional)</option>
                    @foreach($pengerajins as $p)
                        <option value="{{ $p->id }}" {{ $usaha->user_id == $p->user_id ? 'selected' : '' }}>{{ $p->nama_pengerajin }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Wilayah</label>
                <select name="wilayah_id" class="form-input" required>
                    <option value="">Pilih Wilayah</option>
                    @foreach($wilayahs as $w)
                        <option value="{{ $w->id }}" {{ $usaha->wilayah_id == $w->id ? 'selected' : '' }}>{{ $w->nama_wilayah }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Middle Column -->
        <div class="form-column">
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-input" rows="4">{{ $usaha->user->alamat ?? '' }}</textarea>
            </div>
            
            <div class="social-input-group">
                <div class="social-icon"><i class="fas fa-link"></i></div>
                <input type="text" name="link_website_usaha" class="form-input" value="{{ $usaha->link_website_usaha }}" placeholder="https://toko-yanto.com">
            </div>
            <div class="social-input-group">
                <div class="social-icon"><i class="fas fa-map-marker-alt"></i></div>
                <input type="text" name="link_gmap_usaha" class="form-input" value="{{ $usaha->link_gmap_usaha }}" placeholder="Link Google Maps">
            </div>
            <div class="social-input-group">
                <div class="social-icon"><i class="fab fa-whatsapp"></i></div>
                <input type="text" name="link_wa_usaha" class="form-input" value="{{ $usaha->link_wa_usaha }}" placeholder="wa.me/nomor-wa">
            </div>
            <div class="social-input-group">
                <div class="social-icon"><i class="fab fa-instagram"></i></div>
                <input type="text" name="link_instagram_usaha" class="form-input" value="{{ $usaha->link_instagram_usaha }}" placeholder="instagram.com/username">
            </div>
            <div class="social-input-group">
                <div class="social-icon"><i class="fab fa-facebook"></i></div>
                <input type="text" name="link_facebook_usaha" class="form-input" value="{{ $usaha->link_facebook_usaha }}" placeholder="facebook.com/username">
            </div>
            <div class="social-input-group">
                <div class="social-icon"><i class="fab fa-tiktok"></i></div>
                <input type="text" name="link_tiktok_usaha" class="form-input" value="{{ $usaha->link_tiktok_usaha }}" placeholder="tiktok.com/@username">
            </div>
            <div class="social-input-group">
                <div class="social-icon"><i class="fas fa-shopping-bag"></i></div>
                <input type="text" name="link_shopee_usaha" class="form-input" value="{{ $usaha->link_shopee_usaha }}" placeholder="shopee.co.id/username">
            </div>
            <div class="social-input-group">
                <div class="social-icon"><i class="fas fa-store"></i></div>
                <input type="text" name="link_tokopedia_usaha" class="form-input" value="{{ $usaha->link_tokopedia_usaha }}" placeholder="tokopedia.com/username">
            </div>

            <div class="form-group" style="margin-top: 24px;">
                <label>Spesialisasi Usaha</label>
                <textarea name="spesialisasi_usaha" class="form-input" rows="3" placeholder="Kerajinan Perak, Kuningan, dsb">{{ $usaha->spesialisasi_usaha }}</textarea>
            </div>
        </div>

        <!-- Right Column -->
        <div class="form-column">
            <div class="photo-preview-circle" id="profile-preview">
                @if($usaha->foto_usaha)
                    <img src="{{ asset('storage/' . $usaha->foto_usaha) }}" alt="Profile">
                @else
                    <i class="fas fa-user" style="font-size: 60px; color: #e5e7eb;"></i>
                @endif
            </div>
            <input type="file" name="foto_usaha" id="foto_usaha" style="display: none;" accept="image/*">
            <button type="button" class="btn-upload" onclick="document.getElementById('foto_usaha').click()">Ubah Foto</button>

            <div class="form-group" style="margin-top: 40px;">
                <label>Deskripsi</label>
                <textarea name="deskripsi_usaha" class="form-input" rows="6">{{ $usaha->deskripsi_usaha }}</textarea>
            </div>

            <div class="form-group">
                <label>Foto Tempat</label>
                <div class="gallery-container" id="gallery-preview">
                    @php
                        $gallery = json_decode($usaha->foto_tempat, true) ?? [];
                    @endphp
                    @foreach($gallery as $img)
                        <div class="gallery-item">
                            <img src="{{ asset('storage/' . $img) }}">
                        </div>
                    @endforeach
                    <div class="gallery-item">
                        <label class="btn-add-gallery">
                            <i class="fas fa-plus"></i>
                            <input type="file" name="foto_tempat[]" multiple style="display: none;" onchange="previewGallery(this)">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-footer">
        <button type="button" class="btn-delete" onclick="if(confirm('Yakin ingin menghapus usaha ini?')) document.getElementById('delete-form').submit()">Hapus Usaha</button>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.usaha-index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </div>
</form>

<form id="delete-form" action="{{ route('admin.usaha-destroy', $usaha->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@stop

@section('js')
<script>
    // Profile photo preview
    document.getElementById('foto_usaha').onchange = function(evt) {
        const [file] = this.files;
        if (file) {
            const preview = document.getElementById('profile-preview');
            preview.innerHTML = `<img src="${URL.createObjectURL(file)}" alt="Preview">`;
        }
    };

    // Multiple gallery preview
    function previewGallery(input) {
        const container = document.getElementById('gallery-preview');
        // Keep the add button
        const addButton = container.querySelector('.gallery-item:last-child');
        
        // Remove old previews except the add button
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
