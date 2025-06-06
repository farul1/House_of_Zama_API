<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'client_id',
        'fotografer',
        'tempat',
        'waktu',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

}
