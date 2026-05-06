<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    use HasFactory;

    protected $table = 'pelaporan';
    protected $fillable = [
        'kode_laporan',
        'usaha_id',
        'bulan',
        'tahun',
        'omset',
        'deskripsi',
    ];

    public function usaha()
    {
        return $this->belongsTo(Usaha::class, 'usaha_id');
    }
}
