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
        'id_service',
        'invoice_number',
        'qr_code',
        'nama_pemesan',
        'no_antrian',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(service::class, 'id_service', 'id');
    }
}
