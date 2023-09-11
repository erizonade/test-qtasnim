<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';

    protected $fillable = [
        'nomor_transaksi',
        'tanggal_transaksi',
        'total',
    ];

    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
