@extends($layout ?? 'layouts.user')

@section('title', 'Profil')

@section('css')
<style>
    .profile-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #18181b;
    }

    .welcome-banner {
        background: linear-gradient(90deg, #f4f4f5 0%, #e4e4e7 100%);
        border-radius: 20px;
        padding: 40px;
        display: flex;
        align-items: center;
        gap: 30px;
        margin-bottom: 40px;
        position: relative;
    }

    .profile-img-container {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 2px solid #fff;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .profile-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .welcome-text h2 {
        font-size: 28px;
        font-weight: 700;
        color: #18181b;
        line-height: 1.2;
    }

    .ubah-btn {
        position: absolute;
        top: 30px;
        right: 30px;
        background: #fff;
        border: 1px solid #e4e8eb;
        padding: 6px 20px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-decoration: none;
        transition: all 0.2s;
    }

    .ubah-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .info-grid {
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

    .form-control[readonly] {
        cursor: default;
    }

    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
        .welcome-banner { flex-direction: column; text-align: center; padding: 30px 20px; }
        .profile-img-container { width: 100px; height: 100px; }
        .welcome-text h2 { font-size: 22px; }
    }
</style>
@endsection

@section('content')
<h1 class="profile-title">Profil</h1>

<div class="welcome-banner">
    <div class="profile-img-container">
        <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->username).'&background=f4f4f5&color=71717a&size=200' }}" alt="">
    </div>
    <div class="welcome-text">
        <h2>Selamat Datang,<br>{{ Auth::user()->nama ?? Auth::user()->username }}</h2>
    </div>
    <a href="{{ route('user.profile.edit') }}" class="ubah-btn">Ubah</a>
</div>

<div class="info-grid">
    <div class="form-group">
        <label>Nama Depan</label>
        <input type="text" class="form-control" value="{{ explode(' ', Auth::user()->nama ?? '')[0] }}" readonly>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="text" class="form-control" value="{{ Auth::user()->email }}" readonly>
    </div>
    <div class="form-group">
        <label>Nama Belakang</label>
        <input type="text" class="form-control" value="{{ count(explode(' ', Auth::user()->nama ?? '')) > 1 ? implode(' ', array_slice(explode(' ', Auth::user()->nama ?? ''), 1)) : '-' }}" readonly>
    </div>
    <div class="form-group">
        <label>Nomor</label>
        <input type="text" class="form-control" value="{{ Auth::user()->no_hp ?? '-' }}" readonly>
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" value="{{ Auth::user()->username }}" readonly>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" value="************" readonly>
    </div>
</div>
@endsection
