<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'category_id', 'price', 'emoji', 'badge', 'is_active'];

    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean',
    ];

    public function categoryRel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
