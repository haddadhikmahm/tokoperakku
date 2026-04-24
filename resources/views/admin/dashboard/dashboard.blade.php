@extends('layouts.admin_premium')

@section('title', 'Dashboard')

@section('css')
    <style>
        .page-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #000;
        }
        .welcome-card {
            background: #f9f9f9;
            border-radius: 20px;
            padding: 40px;
            border: 1px solid #f0f0f0;
        }
    </style>
@stop

@section('content')
    <h1 class="page-title">Dashboard</h1>
    
    <div class="welcome-card">
        <h3>Selamat Datang di Panel Admin TekoPerakku</h3>
        <p style="color: #666; margin-top: 10px;">Gunakan menu di sebelah kiri untuk mengelola data aplikasi.</p>
    </div>
@stop

@section('js')
    <script> console.log("Dashboard loaded"); </script>
@stop
