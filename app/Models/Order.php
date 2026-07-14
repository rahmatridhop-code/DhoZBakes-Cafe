<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'cafe_table_id', 'order_type', 'customer_name', 'customer_phone', 'subtotal', 'tax', 'service_fee', 'total', 'payment_method', 'cash_received', 'change_amount', 'status'];

    protected $casts = [
        'subtotal' => 'integer',
        'tax' => 'integer',
        'service_fee' => 'integer',
        'total' => 'integer',
        'cash_received' => 'integer',
        'change_amount' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cafeTable()
    {
        return $this->belongsTo(CafeTable::class);
    }

    public function orderNumber(): string
    {
        return str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
