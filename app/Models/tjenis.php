<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tjenis extends Model
{
    use HasFactory;

    // protected $connection = 'mysql';
    // protected $table = 'tjenis';
    protected $primaryKey = 'kode_tjen';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_tjen',
        'nama_tjen',
    ];

    // Relasi ke model T_Jual
    public function jual()
    {
        return $this->hasMany(t_jual::class, 'kode_tjen', 'kode_tjen');
    }
}
