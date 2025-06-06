<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCatalog extends Model
{
    use HasFactory;

    protected $primaryKey = 'service_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'service_id', 'nama_layanan', 'deskripsi', 'harga', 'durasi', 'kategori'
    ];

    public $timestamps = false;
}
