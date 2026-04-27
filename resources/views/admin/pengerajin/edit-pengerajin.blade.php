@extends('layouts.admin_premium')

@section('title', 'Edit Pengrajin')

@section('css')
<style>
    .form-container {
        background: #fff;
        padding: 0;
        border-radius: 12px;
    }

    .form-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
        color: #666;
        margin-bottom: 8px;
        display: block;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s;
        background-color: #fff;
    }

    .form-input:focus {
        border-color: #991b1b;
    }

    .form-input:disabled, .form-input[readonly] {
        background-color: #f3f4f6;
        color: #9ca3af;
    }

    .photo-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    .photo-preview {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #fff;
    }

    .photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-change-photo {
        background: #fff;
        border: 1px solid #e5e7eb;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
    }

    .btn-submit {
        background: #991b1b;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .btn-cancel {
        background: #fff;
        color: #666;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        display: inline-block;
    }

    .btn-delete {
        background: #fff;
        color: #dc2626;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: 1px solid #dc2626;
        cursor: pointer;
        text-decoration: none;
    }

    .row {
        display: flex;
        gap: 40px;
    }

    .col-form {
        flex: 1;
    }

    .col-photo {
        width: 250px;
        flex-shrink: 0;
    }

    .footer-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
    }

    .right-actions {
        display: flex;
        gap: 12px;
    }
</style>
@stop

@section('content')
<div class="form-container">
    <h2 class="form-title">Edit Pengrajin</h2>

    <form action="{{ route('admin.pengerajin-update', $pengerajin->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Column -->
            <div class="col-form">
                <div class="form-group">
                    <label class="form-label">Kode Pengrajin</label>
                    <input type="text" name="kode_pengerajin" class="form-input" value="{{ $pengerajin->kode_pengerajin }}" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama_pengerajin" class="form-input" value="{{ $pengerajin->nama_pengerajin }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">No Handphone</label>
                    <input type="text" name="telp_pengerajin" class="form-input" value="{{ $pengerajin->telp_pengerajin }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email_pengerajin" class="form-input" value="{{ $pengerajin->email_pengerajin }}" required>
                </div>
            </div>

            <!-- Middle Column -->
            <div class="col-form">
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="jk_pengerajin" class="form-input" required>
                        <option value="P" {{ $pengerajin->jk_pengerajin == 'P' ? 'selected' : '' }}>Pria</option>
                        <option value="W" {{ $pengerajin->jk_pengerajin == 'W' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Usia</label>
                    <input type="number" name="usia_pengerajin" class="form-input" value="{{ $pengerajin->usia_pengerajin }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat_pengerajin" class="form-input" rows="4" required>{{ $pengerajin->alamat_pengerajin }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Tempat Usaha</label>
                    <select name="usaha_ids[]" class="form-input" multiple style="height: 100px;">
                        @foreach($usahas as $usaha)
                            <option value="{{ $usaha->id }}" {{ in_array($usaha->id, $selectedUsaha) ? 'selected' : '' }}>
                                {{ $usaha->nama_usaha }}
                            </option>
                        @endforeach
                    </select>
                    <p style="font-size: 11px; color: #888; margin-top: 5px;">* Tahan Ctrl untuk memilih lebih dari satu</p>
                </div>
            </div>

            <!-- Right Column (Photo) -->
            <div class="col-photo">
                <div class="photo-section">
                    <div class="photo-preview" id="photoPreview">
                        @if($pengerajin->foto_pengerajin)
                            <img src="{{ asset('storage/' . $pengerajin->foto_pengerajin) }}" alt="Foto">
                        @else
                            <i class="fas fa-user" style="font-size: 60px; color: #e5e7eb;"></i>
                        @endif
                    </div>
                    <label class="btn-change-photo">
                        Ubah Foto
                        <input type="file" name="foto_pengerajin" id="photoInput" style="display: none;" accept="image/*">
                    </label>
                </div>
            </div>
        </div>

        <div class="footer-actions">
            <div>
                <button type="button" class="btn-delete" onclick="if(confirm('Yakin ingin menghapus pengrajin ini?')) document.getElementById('delete-form').submit();">Hapus Pengrajin</button>
            </div>
            <div class="right-actions">
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
                <a href="{{ route('admin.pengerajin-index') }}" class="btn-cancel">Batal</a>
            </div>
        </div>
    </form>

    <form id="delete-form" action="{{ route('admin.pengerajin-destroy', $pengerajin->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@stop

@section('js')
<script>
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const preview = document.getElementById('photoPreview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@stop

