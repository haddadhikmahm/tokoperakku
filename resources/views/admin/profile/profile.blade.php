@extends('layouts.admin_premium')

@section('title', 'Profil Admin')

@section('css')
    <style>
        .page-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #000;
            letter-spacing: -0.5px;
        }

        .profile-card {
            background: linear-gradient(90deg, #f3f3f3 0%, #dcdcdc 100%);
            border-radius: 24px;
            padding: 50px 60px;
            display: flex;
            align-items: center;
            gap: 50px;
            position: relative;
            margin-bottom: 60px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }

        .profile-image-container {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            border: 8px solid #fff;
            overflow: hidden;
            background: #eee;
            flex-shrink: 0;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .edit-btn {
            position: absolute;
            top: 40px;
            right: 40px;
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 10px 35px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            color: #555;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            display: inline-block;
        }

        .edit-btn:hover {
            background: #f9f9f9;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
        }

        .profile-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px 60px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #999;
            text-transform: none;
            letter-spacing: 0.2px;
        }

        .form-control {
            background: #fff;
            border: 1px solid #f2f2f2;
            padding: 18px 24px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            outline: none;
            box-shadow: 0 2px 12px rgba(0,0,0,0.01);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #e0e0e0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .form-control[readonly] {
            cursor: default;
        }

        @media (max-width: 1200px) {
            .profile-form {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>
@stop

@section('content')
    <h1 class="page-title">Profil Admin</h1>

    <div class="profile-card">
        <div class="profile-image-container">
            @if($user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="Admin Profile">
            @else
                <img src="{{ asset('assets/images/admin-avatar.png') }}" alt="Admin Profile">
            @endif
        </div>
        <div class="welcome-text">
            <h2>Selamat Datang,<br>{{ $user->nama ?? $user->username }}</h2>
        </div>
        <a href="{{ route('profile.edit') }}" class="edit-btn">Ubah</a>
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
            <label>Nama Admin</label>
            <input type="text" class="form-control" value="{{ $user->nama ?? '-' }}" readonly>
        </div>
        <div class="form-group">
            <label>Nomor Handphone</label>
            <input type="text" class="form-control" value="{{ $user->no_hp ?? '-' }}" readonly>
        </div>
        <div class="form-group">
            <label>Gender</label>
            <input type="text" class="form-control" value="{{ $user->gender ?? '-' }}" readonly>
        </div>
        <div class="form-group">
            <label>Usia</label>
            <input type="text" class="form-control" value="{{ $user->usia ?? '-' }}" readonly>
        </div>
        <div class="form-group" style="grid-column: span 2;">
            <label>Alamat</label>
            <textarea class="form-control" readonly style="resize: none; height: 100px;">{{ $user->alamat ?? '-' }}</textarea>
        </div>
        <div class="form-group">
            <label>Status Admin</label>
            <input type="text" class="form-control" value="{{ $user->role == 'admin_utama' ? 'Admin Utama' : 'Admin' }}" readonly>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" value="************" readonly>
        </div>
    </div>
@stop
