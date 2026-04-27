@extends('layouts.admin_premium')

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
    
    // Mock data for display - in real app would use $data passed from controller
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const values = [700, 900, 500, 1200, 1400, 350, 200, 1700, 1000, 1500, 350, 700];
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Omset',
                data: values,
                backgroundColor: (context) => {
                    const index = context.dataIndex;
                    return index === 7 ? '#fecaca' : '#f3f4f6'; // Aug is red in screenshot
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
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: '#f3f4f6'
                    },
                    ticks: {
                        stepSize: 200,
                        color: '#9ca3af'
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
