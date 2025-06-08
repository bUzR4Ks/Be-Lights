<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfflineOrderController extends Controller
{
    public function index()
{
    $orders = OfflineOrder::latest()->get();
    return view('admin.offline_orders.index', compact('orders'));
}

public function create()
{
    $products = Product::where('stock', '>', 0)->get();
    return view('admin.offline_orders.create', compact('products'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'payment_proof' => 'required|image|max:2048',
    ]);

    $items = [];
    $total = 0;

    foreach ($data['items'] as $item) {
        $product = Product::find($item['product_id']);
        $subtotal = $product->price * $item['quantity'];
        $total += $subtotal;
        $items[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'quantity' => $item['quantity'],
            'price' => $product->price,
            'subtotal' => $subtotal,
        ];
    }

    $proofPath = $request->file('payment_proof')->store('offline_proofs', 'public');

    OfflineOrder::create([
        'items' => $items,
        'total_price' => $total,
        'payment_proof_path' => $proofPath,
        'status' => 'pending',
    ]);

    return redirect()->route('admin.offline_orders.index')->with('success', 'Pesanan offline berhasil dibuat');
}
}
