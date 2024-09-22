<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class OffStoreSale extends Model
{
    protected $fillable = ['products_id', 'quantity', 'total_price'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}