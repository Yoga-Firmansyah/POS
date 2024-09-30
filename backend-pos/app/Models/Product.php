<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image',
        'category_id',
        'purchase_price',
        'sale_price',
        'discount',
        'qty',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => url('/storage/categories/' . $value),
        );
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
