<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class t_jual extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_faktur';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_faktur',
        'kode_customer',
        'kode_tjen',
        'tgl_faktur',
        'total_bruto',
        'total_diskon',
        'total_jumlah',
    ];

    // Relasi ke model Customer
    public function customer()
    {
        return $this->belongsTo(customer::class, 'kode_customer', 'kode_customer');
    }

    // Relasi ke model T_Djual
    public function detailPenjualan()
    {
        return $this->hasMany(t_djual::class, 'no_faktur', 'no_faktur');
    }

    // Relasi ke model T_Jenis
    public function jenisTransaksi()
    {
        return $this->belongsTo(tjenis::class, 'kode_tjen', 'kode_tjen');
    }
}
