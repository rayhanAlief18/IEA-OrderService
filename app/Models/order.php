<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing  = true; 

    protected $fillable = [
        'id_user',
        'id_kendaraan',
        'pelayanan',
        'biaya',
        'durasi_pengerjaan',
        'no_antrian'
    ];
}
