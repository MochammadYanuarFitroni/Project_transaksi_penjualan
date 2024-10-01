<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    use HasFactory;

    protected $primaryKey = 'kode_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'harga_barang',
    ];

    // Relasi ke model T_DJual
    public function barang()
    {
        return $this->hasMany(t_djual::class, 'kode_barang', 'kode_barang');
    }
}
