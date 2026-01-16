<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collections extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'user_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'collection_product', 'collection_id', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
