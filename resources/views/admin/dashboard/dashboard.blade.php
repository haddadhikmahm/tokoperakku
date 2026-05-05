@extends($layout ?? 'layouts.admin_premium')

@section('title', 'Dashboard')

@section('css')
    <style>
        .page-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #1a1a1a;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: #fff;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .icon-pengerajin { background: #fee2e2; color: #dc2626; }
        .icon-usaha { background: #fef3c7; color: #d97706; }
        .icon-produk { background: #dcfce7; color: #16a34a; }
        .icon-pelaporan { background: #dbeafe; color: #2563eb; }
        
        .stat-info h4 {
            font-size: 14px;
            color: #71717a;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .stat-info p {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
        }
        .welcome-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            border: 1px solid #f0f0f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .stat-card, body.dark-mode .welcome-card { background: #1e1e1e; border-color: #333; }
        body.dark-mode .page-title, body.dark-mode .stat-info p, body.dark-mode .welcome-card h3 { color: #fff !important; }
    </style>
@stop

@section('content')
    <h1 class="page-title">Dashboard</h1>
    
    <div class="stats-grid">
        @if(auth()->user()->role != 'umkm')
        <div class="stat-card">
            <div class="stat-icon icon-pengerajin">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h4>Total Pengerajin</h4>
                <p>{{ $stats['total_pengerajin'] }}</p>
            </div>
        </div>
        @endif

        <div class="stat-card">
            <div class="stat-icon icon-usaha">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-info">
                <h4>Total Usaha</h4>
                <p>{{ $stats['total_usaha'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-produk">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <h4>Total Produk</h4>
                <p>{{ $stats['total_produk'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-pelaporan">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h4>Total Laporan</h4>
                <p>{{ $stats['total_pelaporan'] }}</p>
            </div>
        </div>
    </div>

    <div class="welcome-card">
        <h3>Selamat Datang, {{ auth()->user()->nama ?? auth()->user()->username }}!</h3>
        <p style="color: #71717a; margin-top: 10px;">Panel kendali TekoPerakku telah disesuaikan dengan peran Anda sebagai <strong>{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</strong>.</p>
    </div>
@stop

@section('js')
    <script> console.log("Dashboard loaded"); </script>
@stop
