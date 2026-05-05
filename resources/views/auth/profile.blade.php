@extends($layout ?? 'layouts.admin_premium')

@section('title', 'Profil Saya')

@section('css')
    <style>
        .page-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .profile-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 32px;
            padding: 40px;
            display: flex;
            align-items: center;
            gap: 40px;
            position: relative;
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid #fff;
        }

        .profile-image-container {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 6px solid #fff;
            overflow: hidden;
            background: #fff;
            flex-shrink: 0;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .welcome-text h2 {
            font-size: 36px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .edit-btn {
            position: absolute;
            top: 40px;
            right: 40px;
            background: #0f172a;
            border: none;
            padding: 12px 30px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            color: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }

        .edit-btn:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
        }

        .profile-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px 50px;
            background: #fff;
            padding: 40px;
            border-radius: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-control {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 16px 20px;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control[readonly] {
            background: #f8fafc;
            color: #475569;
        }

        @media (max-width: 991px) {
            .profile-card { flex-direction: column; text-align: center; padding: 50px 30px; }
            .profile-form { grid-template-columns: 1fr; padding: 30px; }
            .edit-btn { position: relative; top: 0; right: 0; margin-top: 20px; }
        }
    </style>
@stop

@section('content')
    <h1 class="page-title">Informasi Profil</h1>

    <div class="profile-card">
        <div class="profile-image-container">
            <img src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username) . '&background=e2e8f0&color=475569&size=200' }}" 
                 alt="{{ $user->username }}">
        </div>
        <div class="welcome-text">
            <h2>Halo,<br>{{ $user->nama ?? $user->username }}</h2>
        </div>
        <a href="{{ route('profile.edit') }}" class="edit-btn">Edit Profil</a>
    </div>

    <div class="profile-form">
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" value="{{ $user->username }}" readonly>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" value="{{ $user->email }}" readonly>
        </div>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" value="{{ $user->nama ?? '-' }}" readonly>
        </div>
        <div class="form-group">
            <label>Nomor HP</label>
            <input type="text" class="form-control" value="{{ $user->no_hp ?? '-' }}" readonly>
        </div>
        <div class="form-group">
            <label>Peran / Role</label>
            <input type="text" class="form-control" 
                   value="{{ $user->role == 'admin_utama' ? 'Admin Utama' : ($user->role == 'admin_wilayah' ? 'Admin Wilayah' : ($user->role == 'umkm' ? 'Pemilik UMKM' : 'Pelanggan')) }}" 
                   readonly>
        </div>
        <div class="form-group">
            <label>Wilayah</label>
            <input type="text" class="form-control" value="{{ $user->wilayah->nama_wilayah ?? 'Nasional' }}" readonly>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Any specific JS if needed
    </script>
@stop