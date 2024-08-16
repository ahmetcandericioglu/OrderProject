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
        'campaign_id',
        'products',
        'total_price',
        'discount_amount',
        'final_price',
    ];

    protected $casts = [
        'products' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'product_id', 'product_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}