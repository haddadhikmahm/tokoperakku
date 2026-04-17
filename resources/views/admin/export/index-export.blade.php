@extends('adminlte::page')

@section('title', 'Foto Produk')

@section('content_header')
    <h1
        style="
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 1px;
        ">
        <i class="fas fa-file-export"></i> Export Data
    </h1>
@stop

@section('content')
    <a href="{{ route('admin.export-pengerajin') }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel"></i> Export Data Pengerajin</a>
    {{-- tambah jarak dan garis --}}
    <br>
    {{-- tambah jarak dan garis --}}
    <hr color="#ccc">
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/custom.css"> --}}
@stop

@section('js')
    {{-- <script src="/js/custom.js"></script> --}}

@stop
