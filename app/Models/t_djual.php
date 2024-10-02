<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class t_djual extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 't_djuals';

    // Primary key gabungan
    protected $primaryKey = ['no_faktur', 'kode_barang'];
    public $incrementing = false; // Primary key bukan auto-increment
    protected $keyType = 'string'; // Tipe data primary key

    // Kolom yang bisa diisi
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
        return $this->belongsTo(barang::class, 'kode_barang', 'kode_barang');
    }

    // Menentukan kunci gabungan untuk operasi simpan (update/delete)
    protected function setKeysForSaveQuery($query)
    {
        return $query
            ->where('no_faktur', $this->getAttribute('no_faktur'))
            ->where('kode_barang', $this->getAttribute('kode_barang'));
    }
}
