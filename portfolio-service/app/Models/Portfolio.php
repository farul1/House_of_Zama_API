<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = ['photography_id', 'judul', 'deskripsi'];

    public function photography()
    {
        return $this->belongsTo(Photography::class, 'photography_id');
    }
}

