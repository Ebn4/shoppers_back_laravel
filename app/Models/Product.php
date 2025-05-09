<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    Use HasFactory;
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function cart()
{
    return $this->belongsTo(Cart::class);
}
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
    ];
}
