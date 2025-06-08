<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineOrder extends Model
{
    use HasFactory;

    protected $fillable = ['items', 'total_price', 'payment_proof_path', 'status'];

    protected $casts = [
        'items' => 'array',
    ];
}
