<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItems extends Model
{
    use HasFactory;

    protected $fillable =  [
        'sales_id',
        'product_id',
        'product_name',
        'price_per_unit',
        'total_price',
        'discount',
        'quantity'
    ];

}
