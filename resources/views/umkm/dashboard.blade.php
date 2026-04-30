@extends('layouts.umkm')

@section('title', 'Dashboard UMKM')

@section('css')
<style>
    .page-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #18181b;
    }

    .welcome-banner {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 20px;
        padding: 40px;
        display: flex;
        align-items: center;
        gap: 30px;
        margin-bottom: 40px;
        border: 1px solid #e2e8f0;
    }

    .shop-logo-container {
        width: 120px;
        height: 120px;
        border-radius: 20px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: #4f46e5;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .welcome-text h2 {
        font-size: 26px;
        font-weight: 700;
        color: #18181b;
        margin-bottom: 5px;
    }

    .welcome-text p {
        color: #64748b;
        font-size: 15px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: #fff;
        padding: 25px;
        border-radius: 16px;
        border: 1px solid #f1f1f4;
        transition: all 0.2s;
    }

    .stat-card:hover {
        border-color: #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #71717a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        display: block;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #18181b;
    }

    .content-card {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #f1f1f4;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
    }

    .view-all {
        font-size: 13px;
        font-weight: 600;
        color: #4f46e5;
        text-decoration: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        color: #a1a1aa;
        padding: 12px 0;
        border-bottom: 1px solid #f4f4f5;
    }

    td {
        padding: 15px 0;
        font-size: 14px;
        border-bottom: 1px solid #f8fafc;
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Dashboard</h1>

<div class="welcome-banner">
    <div class="shop-logo-container">
        <i class="fas fa-store"></i>
    </div>
    <div class="welcome-text">
        <h2>Selamat Datang di Panel Penjual</h2>
        <p>Kelola produk dan pantau laporan penjualan toko Anda di sini.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-label">Total Produk</span>
        <div class="stat-value">12</div>
    </div>
    <div class="stat-card">
        <span class="stat-label">Terjual</span>
        <div class="stat-value">48</div>
    </div>
    <div class="stat-card">
        <span class="stat-label">Rating</span>
        <div class="stat-value">4.9</div>
    </div>
    <div class="stat-card">
        <span class="stat-label">Pesan Baru</span>
        <div class="stat-value">5</div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">Penjualan Terakhir</h3>
        <a href="#" class="view-all">Lihat Laporan</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 600;">Cincin Perak Ukir</td>
                <td>30 Apr 2026</td>
                <td><span style="color: #16a34a; font-weight: 600;">Selesai</span></td>
                <td style="font-weight: 700;">Rp 250.000</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Kalung Motif Bunga</td>
                <td>29 Apr 2026</td>
                <td><span style="color: #16a34a; font-weight: 600;">Selesai</span></td>
                <td style="font-weight: 700;">Rp 450.000</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
