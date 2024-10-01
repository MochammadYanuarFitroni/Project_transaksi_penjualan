<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'kode_customer';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_customer',
        'nama_customer',
    ];

    // Relasi ke model T_Jual
    public function customer()
    {
        return $this->hasMany(t_jual::class, 'kode_customer', 'kode_customer');
    }
}
