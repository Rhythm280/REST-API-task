<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sku',
        'price',
        'description',
        'status',
        'category_id',
        'collection_id',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collections::class);
    }

    public function assets()
    {
        return $this->hasMany(Assets::class);
    }
}
