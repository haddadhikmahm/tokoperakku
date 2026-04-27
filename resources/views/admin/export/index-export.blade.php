@extends('layouts.admin_premium')

@section('title', 'Pelaporan & Export')

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

    .export-card {
        background: #fff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f3f4f6;
    }

    .btn-export {
        background: #10b981;
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
        gap: 10px;
        transition: all 0.2s;
    }

    .btn-export:hover {
        background: #059669;
        transform: translateY(-1px);
    }
</style>
@stop

@section('content')
<div class="manage-header">
    <h2 class="manage-title">Pelaporan & Export</h2>
</div>

<div class="export-card">
    <h3 style="font-size: 18px; margin-bottom: 20px; color: #374151;">Download Data dalam format Excel</h3>
    <a href="{{ route('admin.export-pengerajin') }}" class="btn-export">
        <i class="fas fa-file-excel"></i> Export Data Pengerajin
    </a>
</div>
@stop

@section('js')
@stop
