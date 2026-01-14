<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    protected $fillable = [
        'product_id',
        'file_path',
        'type',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
