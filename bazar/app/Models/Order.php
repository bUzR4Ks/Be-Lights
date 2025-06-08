<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name', 'order_code', 'section', 'total_price',
        'payment_status', 'order_status', 'payment_proof_path'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
