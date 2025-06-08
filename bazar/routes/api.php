<?php

use Illuminate\Http\Request;
use App\Models\Order;

Route::middleware('auth:sanctum')->get('/order-status', function (Request $request) {
    $user = $request->user();
    // Ambil order user terbaru yang mau dicek statusnya
    $order = Order::where('user_id', $user->id)
                  ->latest()
                  ->first();

    if ($order) {
        return response()->json([
            'status' => $order->status,
            'order_id' => $order->id,
        ]);
    }
    return response()->json(['status' => null]);
});
