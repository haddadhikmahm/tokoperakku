<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $fillable = ['nama_wilayah', 'keterangan'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function usahas()
    {
        return $this->hasMany(Usaha::class);
    }
}
