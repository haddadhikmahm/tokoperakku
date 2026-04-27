@extends('layouts.admin_premium')

@section('title', 'Edit Admin')

@section('css')
<style>
    .manage-container {
        max-width: 1000px;
    }

    .manage-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #1a1a1a;
    }

    .form-wrapper {
        display: flex;
        gap: 60px;
        align-items: flex-start;
    }

    .fields-grid {
        flex: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 40px;
    }

    .photo-section {
        width: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    .profile-photo-wrapper {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        overflow: hidden;
        border: 1px solid #ddd;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-photo-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ubah-foto-btn {
        background: #fff;
        border: 1px solid #e0e0e0;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        transition: all 0.2s;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 500;
        color: #666;
    }

    .form-control {
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
        background: #fff;
        font-size: 14px;
        color: #333;
        outline: none;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #ddd;
    }

    .form-control.readonly {
        background: #e9e9e9;
        color: #888;
        border-color: #e9e9e9;
    }

    textarea.form-control {
        resize: none;
        height: 120px;
    }

    .actions-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 60px;
    }

    .btn-delete {
        background: #fff;
        color: #dc2626;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 500;
        border: 1px solid #dc2626;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
    }

    .right-actions {
        display: flex;
        gap: 15px;
    }

    .btn-save {
        background: #991b1b;
        color: #fff;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-cancel {
        background: #fff;
        color: #666;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 500;
        border: 1px solid #e0e0e0;
        text-decoration: none;
        font-size: 14px;
    }

    .alamat-group {
        grid-row: span 2;
    }
</style>
@stop

@section('content')
<div class="manage-container">
    <h2 class="manage-title">Edit Admin</h2>

    <form action="{{ route('admin.manage.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-wrapper">
            <div class="fields-grid">
                <!-- Username -->
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control readonly" value="{{ $admin->username }}" readonly>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="Pria" {{ $admin->gender == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ $admin->gender == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>

                <!-- Nama -->
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" value="{{ $admin->nama }}">
                </div>

                <!-- Usia -->
                <div class="form-group">
                    <label>Usia</label>
                    <input type="number" name="usia" class="form-control" placeholder="Masukkan Usia" value="{{ $admin->usia }}">
                </div>

                <!-- No Handphone -->
                <div class="form-group">
                    <label>No Handphone</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Masukkan No Handphone" value="{{ $admin->no_hp }}">
                </div>

                <!-- Alamat -->
                <div class="form-group alamat-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" placeholder="Masukkan Alamat">{{ $admin->alamat }}</textarea>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan Email" value="{{ $admin->email }}" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control readonly" placeholder="************" readonly>
                </div>

                <!-- Status Admin -->
                <div class="form-group">
                    <label>Status Admin</label>
                    <select name="role" class="form-control" required>
                        <option value="admin_utama" {{ $admin->role == 'admin_utama' ? 'selected' : '' }}>Admin Utama</option>
                        <option value="admin_wilayah" {{ $admin->role == 'admin_wilayah' ? 'selected' : '' }}>Admin Wilayah</option>
                    </select>
                </div>
            </div>

            <div class="photo-section">
                <div class="profile-photo-wrapper">
                    @if($admin->foto)
                        <img src="{{ asset('storage/' . $admin->foto) }}" alt="Preview" id="preview-foto">
                    @else
                        <img src="{{ asset('assets/images/admin-avatar.png') }}" alt="Preview" id="preview-foto">
                    @endif
                </div>
                <input type="file" name="foto" id="foto-input" style="display: none;" accept="image/*">
                <button type="button" class="ubah-foto-btn" onclick="document.getElementById('foto-input').click()">Ubah Foto</button>
            </div>
        </div>

        <div class="actions-footer">
            <button type="button" class="btn-delete" onclick="document.getElementById('delete-form').submit()">Hapus Admin</button>
            <div class="right-actions">
                <button type="submit" class="btn-save">Simpan Perubahan</button>
                <a href="{{ route('admin.manage.index') }}" class="btn-cancel">Batal</a>
            </div>
        </div>
    </form>

    <form id="delete-form" action="{{ route('admin.manage.destroy', $admin->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@stop

@section('js')
<script>
    document.getElementById('foto-input').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@stop
