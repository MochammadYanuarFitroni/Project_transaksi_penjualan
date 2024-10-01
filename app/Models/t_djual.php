<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class t_djual extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_faktur',
        'kode_barang',
        'harga',
        'qty',
        'diskon',
        'bruto',
        'jumlah',
    ];

    // Relasi ke model T_Jual
    public function transaksi()
    {
        return $this->belongsTo(t_jual::class, 'no_faktur', 'no_faktur');
    }

    // Relasi ke model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}
