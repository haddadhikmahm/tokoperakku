@extends('layouts.admin_premium')

@section('title', 'Pelaporan')

@section('content')
<div class="manage-header">
    <h2 class="manage-title">Pelaporan</h2>
    <div class="manage-actions">
        <a href="{{ route('admin.pelaporan-chart') }}" class="btn-chart" title="Lihat Grafik">
            <i class="fas fa-chart-line"></i>
        </a>
        <a href="{{ route('admin.pelaporan-create') }}" class="btn-add">Tambah laporan</a>
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="custom-search" class="search-input" placeholder="Search products">
        </div>
    </div>
</div>

@section('css')
<style>
    .btn-chart {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-chart:hover {
        background: #f9fafb;
        color: #991b1b;
        border-color: #991b1b;
    }
</style>
@stop

<table id="pelaporan-table" class="admin-table">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Toko</th>
            <th>Bulan</th>
            <th>Omset</th>
            <th>Keterangan</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporans as $laporan)
            <tr>
                <td>{{ $laporan->kode_laporan }}</td>
                <td style="font-weight: 600; color: #1a1a1a;">{{ $laporan->usaha->nama_usaha ?? '-' }}</td>
                <td>{{ $laporan->bulan }} {{ $laporan->tahun }}</td>
                <td>Rp {{ number_format($laporan->omset, 0, ',', '.') }}</td>
                <td style="color: #6b7280; font-size: 13px;">{{ \Illuminate\Support\Str::limit($laporan->deskripsi, 50) }}</td>
                <td>
                    <div class="dropdown">
                        <button class="action-btn">•••</button>
                        <div class="dropdown-content">
                            <a href="{{ route('admin.pelaporan-edit', $laporan->id) }}"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.pelaporan-destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #dc2626;"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination-container">
    <div class="results-text">Showing 1-{{ count($laporans) }} Of {{ count($laporans) }} Results.</div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#pelaporan-table').DataTable({
            paging: true,
            searching: true,
            info: true,
            dom: 'tp',
            language: {
                zeroRecords: "No data available in table",
                paginate: {
                    previous: '<i class="fas fa-chevron-left"></i>',
                    next: '<i class="fas fa-chevron-right"></i>'
                }
            },
            drawCallback: function(settings) {
                const api = this.api();
                const info = api.page.info();
                
                let resultsText = '';
                if (info.recordsTotal > 0) {
                    resultsText = `Showing ${info.start + 1}-${info.end} Of ${info.recordsTotal} Results.`;
                } else {
                    resultsText = 'Showing 0-0 Of 0 Results.';
                }
                $('.results-text').html(resultsText);

                // Move pagination buttons to the container
                $('#pelaporan-table_paginate').appendTo('.pagination-container');
            }
        });

        $('#custom-search').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>
@stop
