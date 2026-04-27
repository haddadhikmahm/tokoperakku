@extends('layouts.admin_premium')

@section('title', 'Data Produk')

@section('css')
<style>
    .manage-header {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        margin-bottom: 40px !important;
        padding-top: 10px !important;
        flex-wrap: nowrap !important;
    }

    .manage-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        flex-shrink: 0;
    }

    .manage-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: auto;
    }

    .btn-add {
        background: #991b1b;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 45px;
    }

    .search-container {
        position: relative;
    }

    .search-input {
        padding: 0 15px 0 45px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 14px;
        width: 320px;
        height: 45px;
        outline: none;
        background-color: #fcfcfc;
        color: #666;
    }

    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 16px;
    }

    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 10px;
    }

    .admin-table th {
        text-align: left;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        color: #888;
        border-bottom: 1px solid #f3f4f6;
    }

    .admin-table td {
        padding: 18px 20px;
        font-size: 14px;
        color: #4b5563;
        border-bottom: 1px solid #f9fafb;
        vertical-align: middle;
    }

    .admin-table tr:hover td {
        background-color: #fcfcfc;
    }

    .action-btn {
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 20px;
        padding: 0;
        letter-spacing: 1px;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: #fff;
        min-width: 120px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1);
        z-index: 10;
        border-radius: 8px;
        overflow: hidden;
    }

    .dropdown-content a, .dropdown-content button {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 13px;
        text-align: left;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
    }

    .dropdown-content a:hover, .dropdown-content button:hover {
        background-color: #f5f5f5;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-bottom: 20px;
    }

    .results-text {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
    }

    /* DataTables Overrides */
    .dataTables_empty {
        text-align: center !important;
        padding: 40px !important;
        color: #9ca3af !important;
        font-style: italic;
    }

    .dataTables_info {
        display: none !important;
    }

    .dataTables_paginate {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .paginate_button {
        width: 36px;
        height: 36px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280 !important;
        text-decoration: none !important;
        transition: all 0.2s;
        cursor: pointer;
        background: transparent !important;
        border: none !important;
    }

    .paginate_button:hover:not(.disabled):not(.current) {
        background-color: #f3f4f6 !important;
    }

    .paginate_button.current {
        background-color: #991b1b !important;
        color: #ffffff !important;
    }

    .paginate_button.disabled {
        color: #d1d5db !important;
        cursor: default;
    }

    .paginate_button.previous, .paginate_button.next {
        color: #9ca3af !important;
    }
</style>
@stop

@section('content')
<div class="manage-header">
    <h2 class="manage-title">Produk</h2>
    <div class="manage-actions">
        <a href="{{ route('admin.produk-create') }}" class="btn-add">Tambah Produk</a>
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="custom-search" class="search-input" placeholder="Search products">
        </div>
    </div>
</div>

<table id="produk-table" class="admin-table">
    <thead>
        <tr>
            <th style="width: 80px;"></th>
            <th>Nama Produk</th>
            <th>Kode</th>
            <th>Usaha</th>
            <th>Harga</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produks as $produk)
            <tr>
                <td>
                    <div style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: #f3f4f6;">
                        @if ($produk->fotoProduk->first())
                            <img src="{{ asset('storage/' . $produk->fotoProduk->first()->file_foto_produk) }}" alt="Foto Produk" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #9ca3af;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                </td>
                <td style="font-weight: 600; color: #1a1a1a;">{{ $produk->nama_produk }}</td>
                <td>{{ $produk->kode_produk }}</td>
                <td>{{ $produk->usaha->first()->nama_usaha ?? '-' }}</td>
                <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                <td>
                    <div class="dropdown">
                        <button class="action-btn">•••</button>
                        <div class="dropdown-content">
                            <a href="{{ route('admin.produk-edit', $produk->id) }}"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.produk-destroy', $produk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
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
    <div class="results-text">Showing 1-{{ count($produks) }} Of {{ count($produks) }} Results.</div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#produk-table').DataTable({
            paging: true,
            searching: true,
            info: true,
            dom: 'tp',
            language: {
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

                $('#produk-table_paginate').appendTo('.pagination-container');
            }
        });

        $('#custom-search').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>
@stop
