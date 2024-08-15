<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'conditions',
        'discount_rate'
    ];

    protected $casts = [
        'conditions' => 'array'
    ];

}
