@extends($layout ?? 'layouts.admin_premium')

@section('title', 'Grafik Pelaporan')

@section('css')
<style>
    .chart-card {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
    }
    .filter-dropdown {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 14px;
        outline: none;
        color: #4b5563;
    }
</style>
@stop

@section('content')
<div class="manage-header">
    <h2 class="manage-title">Pelaporan</h2>
</div>

<div class="chart-card">
    <div class="chart-header">
        <div class="chart-title">Omset Toko</div>
        <select class="filter-dropdown">
            <option>1 tahun</option>
            <option>6 bulan</option>
            <option>3 bulan</option>
        </select>
    </div>
    
    <div style="height: 400px;">
        <canvas id="omsetChart"></canvas>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('omsetChart').getContext('2d');
    
    // Map Indonesian months to Chart.js labels
    const monthMap = {
        'Januari': 0, 'Februari': 1, 'Maret': 2, 'April': 3, 'Mei': 4, 'Juni': 5,
        'Juli': 6, 'Agustus': 7, 'September': 8, 'Oktober': 9, 'November': 10, 'Desember': 11
    };
    
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const values = new Array(12).fill(0);
    
    // Fill values from database data
    @foreach($data as $d)
        if (monthMap['{{ $d->bulan }}'] !== undefined) {
            values[monthMap['{{ $d->bulan }}']] += {{ (float)$d->omset }};
        }
    @endforeach
    
    // Find index for highlight (e.g. current month or highest)
    const currentMonthIndex = new Date().getMonth();

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Omset',
                data: values,
                backgroundColor: (context) => {
                    const index = context.dataIndex;
                    return index === currentMonthIndex ? '#fecaca' : '#f3f4f6';
                },
                borderRadius: 8,
                borderSkipped: false,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: '#f3f4f6'
                    },
                    ticks: {
                        color: '#9ca3af',
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#9ca3af' }
                }
            }
        }
    });
</script>
@stop
