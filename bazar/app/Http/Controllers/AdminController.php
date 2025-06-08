<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    public function index()
    {
        $pendingPayments = Order::where('payment_status', 'pending')
    ->with('items.product')
    ->orderBy('created_at', 'desc')
    ->get();

        $inProgressOrders = Order::where('payment_status', 'accepted')
    ->where('order_status', 'pending')
    ->with('items.product')
    ->orderBy('created_at', 'desc')
    ->get();

$completedOrders = Order::where('order_status', 'completed')
    ->with('items.product')
    ->orderBy('updated_at', 'desc')
    ->get();

        return view('admin.dashboard', compact('pendingPayments', 'inProgressOrders', 'completedOrders'));
    }

    // Terima/Deny pembayaran
    public function validatePayment(Request $request, Order $order)
    {
        $action = $request->input('action'); // 'accept' atau 'deny'

        if ($action === 'accept') {
            $order->payment_status = 'accepted';
        } elseif ($action === 'deny') {
            $order->payment_status = 'denied';
            // Kembalikan stok jika pembayaran ditolak
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->stock += $item->quantity;
                $product->save();
            }
        }
        $order->save();

        return redirect()->back()->with('success', 'Status pembayaran diperbarui.');
    }

    // Ubah status pesanan (in_progress / completed)
    public function updateOrderStatus(Request $request, Order $order)
    {
        $action = $request->input('action'); // 'in_progress' atau 'completed'

        if ($action === 'in_progress') {
            $order->order_status = 'in_progress';
        } elseif ($action === 'completed') {
            $order->order_status = 'completed';
        }

        $order->save();

        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }
    
    public function laporan()
    {
    $completedOrders = Order::where('order_status', 'completed')->with('items')->get();

    $groupedOrders = $completedOrders->groupBy('section');

    

    return view('admin.laporan', compact('groupedOrders'));
    }

}
