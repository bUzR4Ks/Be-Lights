<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminOrderController extends Controller
{
    public function createOffline()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('admin.orders.create_offline', compact('products'));
    }

    public function storeOffline(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string',
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'payment_proof' => 'required|image',
    ]);

    $total = 0;
    foreach ($request->items as $item) {
        $product = Product::findOrFail($item['product_id']);
        $total += $product->price * $item['quantity'];
    }

    $path = $request->file('payment_proof')->store('payments', 'public');

    $order = Order::create([
        'customer_name' => $request->customer_name,
        'total_price' => $total,
        'is_offline' => true,
        'payment_status' => 'accepted',
        'order_status' => 'pending',
        'payment_proof_path' => $path,
        'order_code' => 'ORD-' . strtoupper(Str::random(6)),
    ]);

    foreach ($request->items as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $product->price,
        ]);
    }

    return redirect()->route('admin.dashboard')->with('success', 'Pesanan offline berhasil dibuat.');
}

public function index()
{
    $orders = Order::orderBy('created_at', 'desc')->paginate(10);
    return view('admin.orders.index', compact('orders'));
}

public function show($id)
{
    // Bisa diarahkan ke halaman detail order, atau redirect
    return redirect()->route('admin.orders.index');
}



}