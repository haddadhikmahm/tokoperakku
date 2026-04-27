@extends('layouts.admin_premium')

@section('title', 'Kelola Admin')

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

    .search-input::placeholder {
        color: #9ca3af;
    }

    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 16px;
    }

    /* Table Styles */
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

    /* Pagination Styling */
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

    .pagination-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-link {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-link:hover {
        background-color: #f3f4f6;
    }

    .page-link.active {
        background-color: #991b1b;
        color: #ffffff;
    }

    .page-link.dots {
        cursor: default;
    }

    .page-link.dots:hover {
        background: none;
    }

    .page-link.arrow {
        color: #9ca3af;
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

    .pagination {
        display: flex;
        gap: 10px;
        list-style: none;
    }

    .pagination li a, .pagination li span {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        text-decoration: none;
        color: #666;
        transition: all 0.2s;
    }

    .pagination li.active span {
        background: #991b1b;
        color: #fff;
    }

    .pagination li a:hover {
        background: #f0f0f0;
    }

    /* Action Dropdown */
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
        z-index: 1;
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
</style>
@stop

@section('content')
<div class="manage-header">
    <h2 class="manage-title">Kelola Admin</h2>
    <div class="manage-actions">
        <a href="{{ route('admin.manage.create') }}" class="btn-add">Add product</a>
        <form action="{{ route('admin.manage.index') }}" method="GET" class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" class="search-input" placeholder="Search products" value="{{ request('search') }}">
        </form>
    </div>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>username</th>
            <th>Nama</th>
            <th>Status Admin</th>
            <th>No Telepon</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($admins as $admin)
        <tr>
            <td>{{ $admin->username }}</td>
            <td>{{ $admin->nama ?? '-' }}</td>
            <td>{{ $admin->role == 'admin_utama' ? 'Admin Utama' : 'Admin Wilayah' }}</td>
            <td>{{ $admin->no_hp ?? '-' }}</td>
            <td>{{ $admin->email }}</td>
            <td>
                <div class="dropdown">
                    <button class="action-btn">•••</button>
                    <div class="dropdown-content">
                        <a href="{{ route('admin.manage.edit', $admin->id) }}"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('admin.manage.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
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
    <div class="results-text">Showing {{ $admins->firstItem() ?? 0 }}-{{ $admins->lastItem() ?? 0 }} Of {{ $admins->total() }} Results.</div>
    <div class="pagination-wrapper">
        @if ($admins->onFirstPage())
            <span class="page-link arrow"><i class="fas fa-chevron-left"></i></span>
        @else
            <a href="{{ $admins->previousPageUrl() }}" class="page-link arrow"><i class="fas fa-chevron-left"></i></a>
        @endif

        @foreach ($admins->getUrlRange(1, $admins->lastPage()) as $page => $url)
            @if ($page == $admins->currentPage())
                <span class="page-link active">{{ $page }}</span>
            @elseif ($page == 1 || $page == 2 || $page == $admins->lastPage() || ($page >= $admins->currentPage() - 1 && $page <= $admins->currentPage() + 1))
                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
            @elseif ($page == 3 && $admins->currentPage() > 4)
                <span class="page-link dots">...</span>
            @elseif ($page == $admins->lastPage() - 1 && $admins->currentPage() < $admins->lastPage() - 3)
                <span class="page-link dots">...</span>
            @endif
        @endforeach

        @if ($admins->hasMorePages())
            <a href="{{ $admins->nextPageUrl() }}" class="page-link arrow"><i class="fas fa-chevron-right"></i></a>
        @else
            <span class="page-link arrow"><i class="fas fa-chevron-right"></i></span>
        @endif
    </div>
</div>
@stop
