<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kendaraan',
        'pelayanan',
        'biaya',
        'durasi',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_service', 'id');
    }
}
