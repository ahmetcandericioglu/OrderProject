<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'original_price',
        'discount_amount',
        'campaign_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}