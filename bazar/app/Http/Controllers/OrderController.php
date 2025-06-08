<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // Tampilkan form konfirmasi pesanan (checkout)
    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'Keranjang kosong!');
        }

        $flatCart = [];
        foreach ($cart as $sectionItems) {
            foreach ($sectionItems as $productId => $item) {
                $flatCart[$productId] = $item;
            }
        }

        $total = collect($flatCart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Ambil semua section yang ada (bisa lebih dari satu)
        $sections = collect($flatCart)->pluck('section')->unique()->toArray();

        return view('order.checkout', [
            'cart' => $flatCart,
            'total' => $total,
            'sections' => $sections,
        ]);
    }

    // Proses simpan order
    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'payment_proof' => 'required|image|max:2048',
        ]);

        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'Keranjang kosong!');
        }

        // Flatten cart
        $flatCart = [];
        foreach ($cart as $sectionItems) {
            foreach ($sectionItems as $productId => $item) {
                $flatCart[$productId] = $item;
            }
        }

        // Hitung total dari flatCart
        $total = collect($flatCart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Simpan file bukti pembayaran
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        $filename = basename($path);

        // Tentukan section: jika lebih dari satu → 'multiple'
        $sections = collect($flatCart)->pluck('section')->unique();
        $section = $sections->count() > 1 ? 'multiple' : $sections->first();

        // Generate kode unik order
        $orderCode = strtoupper(Str::random(8));

        // Simpan order
        $order = Order::create([
            'customer_name'      => $request->customer_name,
            'order_code'         => $orderCode,
            'section'            => $section,
            'total_price'        => $total,
            'payment_status'     => 'pending',
            'order_status'       => 'pending',
            'payment_proof_path' => $filename,
        ]);

        // Simpan order item dan kurangi stok
        foreach ($flatCart as $productId => $attrs) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $attrs['quantity'],
                'price'      => $attrs['price'],
            ]);

            $product = Product::find($productId);
            if ($product) {
                $product->stock -= $attrs['quantity'];
                $product->save();
            }
        }

        // Simpan kode order di session
        $request->session()->put('order_code', $orderCode);

        // Kosongkan keranjang
        $request->session()->forget('cart');

        return redirect()->route('order.my_orders')->with('success', 'Pesanan berhasil dibuat. Simpan kode pesanan kamu untuk cek status!');
    }

    // Halaman “Pesanan Saya”
    public function myOrders(Request $request)
    {
        $orderCode = $request->session()->get('order_code');

        if (!$orderCode) {
            return redirect('/')->with('error', 'Tidak ada pesanan yang bisa ditampilkan. Silakan buat pesanan terlebih dahulu.');
        }

        $order = Order::whereRaw('LOWER(order_code) = ?', [strtolower($orderCode)])->first();

        if (!$order) {
            return redirect('/')->with('error', 'Pesanan tidak ditemukan.');
        }

        $orders = collect([$order]);

        return view('order.my_orders', compact('orders'));
    }

}
