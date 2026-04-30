@extends($layout ?? 'layouts.admin_premium')

@section('title', 'Edit Profil Admin')

@section('css')
<style>
    .edit-profile-container {
        max-width: 1000px;
    }

    .edit-title {
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
        background: #f8f8f8;
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

    .ubah-foto-btn:hover {
        background: #f5f5f5;
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

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 40px;
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
        display: inline-block;
    }

    /* Adjusting for the tall Alamat field */
    .alamat-group {
        grid-row: span 2;
    }
</style>
@stop

@section('content')
<div class="edit-profile-container">
    <h2 class="edit-title">Edit Admin</h2>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-wrapper">
            <div class="fields-grid">
                <!-- Username -->
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control readonly" value="{{ $user->username }}" readonly name="username">
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="Pria" {{ $user->gender == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ $user->gender == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>

                <!-- Nama -->
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" placeholder="Masukkan Nama">
                </div>

                <!-- Usia -->
                <div class="form-group">
                    <label>Usia</label>
                    <input type="number" name="usia" class="form-control" value="{{ $user->usia }}" placeholder="Masukkan Usia">
                </div>

                <!-- No Handphone -->
                <div class="form-group">
                    <label>No Handphone</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $user->no_hp }}" placeholder="Masukkan No Handphone">
                </div>

                <!-- Alamat -->
                <div class="form-group alamat-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" placeholder="Masukkan Alamat">{{ $user->alamat }}</textarea>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="Masukkan Email">
                </div>

                <!-- Status Admin (Readonly according to image) -->
                <div class="form-group">
                    <label>Status Admin</label>
                    <select class="form-control readonly" disabled>
                        <option>{{ $user->role == 'admin_utama' ? 'Admin Utama' : 'Admin' }}</option>
                    </select>
                </div>

                <!-- Password (Readonly placeholder according to image) -->
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control readonly" value="************" readonly>
                </div>
            </div>

            <div class="photo-section">
                <div class="profile-photo-wrapper">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile Photo" id="preview-foto">
                    @else
                        <img src="{{ asset('assets/images/admin-avatar.png') }}" alt="Default Profile" id="preview-foto">
                    @endif
                </div>
                <input type="file" name="foto" id="foto-input" style="display: none;" accept="image/*">
                <button type="button" class="ubah-foto-btn" onclick="document.getElementById('foto-input').click()">Ubah Foto</button>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn-save">Simpan Perubahan</button>
            <a href="{{ route('profile') }}" class="btn-cancel">Batal</a>
        </div>
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
