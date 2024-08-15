<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'title', 
        'category_id', 
        'category_title', 
        'author', 
        'list_price', 
        'stock_quantity'
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            $category = Category::find($product->category_id);
            if ($category) {
                $product->category_title = $category->title;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'product_id');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }
}
