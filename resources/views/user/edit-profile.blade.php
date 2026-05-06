@extends($layout ?? 'layouts.user')

@section('title', 'Edit Profil')

@section('css')
<style>
    .profile-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #18181b;
    }

    .edit-card {
        background: #fff;
        border-radius: 20px;
        padding: 40px;
        border: 1px solid #f1f1f4;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px 40px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #71717a;
    }

    .form-control {
        width: 100%;
        padding: 14px 20px;
        border-radius: 10px;
        border: 1px solid #f1f1f4;
        background: #fff;
        font-size: 14px;
        font-weight: 500;
        color: #18181b;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: #d4d4d8;
    }

    .photo-upload {
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .current-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f4f4f5;
    }

    .upload-btn {
        background: #f4f4f5;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #18181b;
        cursor: pointer;
    }

    .actions {
        margin-top: 40px;
        display: flex;
        gap: 15px;
    }

    .btn-save {
        background: #18181b;
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-cancel {
        background: #fff;
        color: #71717a;
        border: 1px solid #e4e4e7;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
    }
</style>
@endsection

@section('content')
<h1 class="profile-title">Edit Profil</h1>

<div class="edit-card">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="photo-upload">
            <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->username).'&background=f4f4f5&color=71717a' }}" class="current-photo" id="preview-foto">
            <input type="file" name="foto" id="foto-input" style="display: none;" accept="image/*">
            <button type="button" class="upload-btn" onclick="document.getElementById('foto-input').click()">Ubah Foto</button>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ Auth::user()->nama }}" placeholder="Masukkan nama lengkap">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" placeholder="nama@email.com">
            </div>
            <div class="form-group">
                <label>Nomor HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ Auth::user()->no_hp }}" placeholder="0812xxxx">
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="gender" class="form-control">
                    <option value="" disabled {{ !Auth::user()->gender ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                    <option value="Pria" {{ Auth::user()->gender == 'Pria' ? 'selected' : '' }}>Pria</option>
                    <option value="Wanita" {{ Auth::user()->gender == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                </select>
            </div>
            <div class="form-group">
                <label>Usia</label>
                <input type="number" name="usia" class="form-control" value="{{ Auth::user()->usia }}" placeholder="Contoh: 25">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" value="{{ Auth::user()->username }}" readonly style="background: #fafafa; color: #a1a1aa;">
            </div>
        </div>

        <div class="form-group mt-4">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap">{{ Auth::user()->alamat }}</textarea>
        </div>

        <div class="actions">
            <button type="submit" class="btn-save">Simpan Perubahan</button>
            <a href="{{ route('profile') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection

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
@endsection
