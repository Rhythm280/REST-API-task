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

    protected $hidden = [
        'user_id',
    ];

    public function products()
    {
        return $this->hasMany(Products::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
