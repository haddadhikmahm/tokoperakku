@extends($layout ?? 'layouts.admin_premium')

@section('title', 'Edit Pelaporan')

@section('css')
<style>
    .form-container {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 40px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
        background: #fcfcfc;
    }

    .form-input:focus {
        border-color: #991b1b;
        background: #fff;
    }

    .form-input[readonly] {
        background: #f3f4f6;
        cursor: not-allowed;
    }

    .action-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .btn-submit {
        background: #991b1b;
        color: #fff;
        padding: 12px 32px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel {
        background: #fff;
        color: #4b5563;
        padding: 12px 32px;
        border-radius: 8px;
        font-weight: 600;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-submit:hover { opacity: 0.9; }
    .btn-cancel:hover { background: #f9fafb; }
</style>
@stop

@section('content')
<h2 style="font-size: 18px; font-weight: 700; margin-bottom: 30px;">Edit Pelaporan</h2>

<form action="{{ route('admin.pelaporan-update', $laporan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-container">
        <!-- Left Column -->
        <div class="form-column">
            <div class="form-group">
                <label>Usaha</label>
                <select name="usaha_id" class="form-input" required>
                    <option value="">Pilih Usaha</option>
                    @foreach($usahas as $u)
                        <option value="{{ $u->id }}" {{ $laporan->usaha_id == $u->id ? 'selected' : '' }}>{{ $u->nama_usaha }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Omset</label>
                <input type="number" name="omset" class="form-input" value="{{ (int)$laporan->omset }}" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-input" rows="8">{{ $laporan->deskripsi }}</textarea>
            </div>
        </div>

        <!-- Right Column -->
        <div class="form-column">
            <div class="form-group">
                <label>Bulan</label>
                <select name="bulan" class="form-input" required>
                    <option value="">Pilih Bulan</option>
                    @foreach($months as $m)
                        <option value="{{ $m }}" {{ $laporan->bulan == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tahun</label>
                <select name="tahun" class="form-input" required>
                    <option value="">Pilih Tahun</option>
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ $laporan->tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Kode</label>
                <input type="text" name="kode_laporan" class="form-input" value="{{ $laporan->kode_laporan }}" readonly>
            </div>
        </div>
    </div>

    <div class="action-footer">
        <a href="{{ route('admin.pelaporan-index') }}" class="btn-cancel">Batal</a>
        <button type="submit" class="btn-submit">Simpan Perubahan</button>
    </div>
</form>
@stop
