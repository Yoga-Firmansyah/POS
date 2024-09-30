<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_item',
        'total_price',
        'discount',
        'grand_total',
        'receive',
        'return',
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class)->with('product');
    }
}
