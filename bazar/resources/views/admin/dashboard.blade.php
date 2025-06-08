@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4">
    <h1 class="text-3xl font-bold text-center mb-8">Admin Dashboard</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- 1. Validasi Pembayaran --}}
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">🧾 Validasi Pembayaran (Pending)</h2>
        @if ($pendingPayments->isEmpty())
            <p class="text-gray-600 italic">Tidak ada pembayaran pending.</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($pendingPayments as $order)
            <div class="bg-white shadow rounded-lg p-4 flex flex-col">
                <a href="{{ asset('storage/payment_proofs/' . $order->payment_proof_path) }}" target="_blank" class="mb-3">
                    <img src="{{ asset('storage/payment_proofs/' . $order->payment_proof_path) }}" alt="Bukti Pembayaran" class="w-full h-32 object-cover rounded border" />
                </a>
                <div class="mb-2">
                    <p class="text-base font-bold text-gray-800">#{{ $order->id }} - {{ $order->customer_name }}</p>
                    <p class="text-sm text-gray-600">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
                    <p class="text-blue-600 font-semibold">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <p class="text-sm mt-1">Section: <span class="font-medium">
                        @if ($order->section == 'nasi_uduk')
                            Nasi Uduk
                        @elseif ($order->section == 'aneka_semur')
                            Aneka Semur
                        @elseif ($order->section == 'multiple')
                            Campuran
                        @else
                            Tidak diketahui
                        @endif
                    </span></p>
                </div>
                <div class="mb-3">
                    <h3 class="text-sm font-semibold">Rincian Produk:</h3>
                    <ul class="text-sm text-gray-700 mt-1 space-y-1 max-h-32 overflow-y-auto">
                        @foreach ($order->items as $item)
                            <li class="leading-snug">• {{ $item->product->name ?? '-' }} <br>Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }} = <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></li>
                        @endforeach
                    </ul>
                </div>
                <div class="flex space-x-2 mt-auto">
                    <form action="{{ route('admin.validate_payment', $order->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="action" value="accept">
                        <button class="w-full bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm font-semibold">Terima</button>
                    </form>
                    <form action="{{ route('admin.validate_payment', $order->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="action" value="deny">
                        <button class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm font-semibold">Tolak</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- 2. Pesanan Sedang Dibuat --}}
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">👨‍🍳 Pesanan Sedang Dibuat</h2>
        @if ($inProgressOrders->isEmpty())
            <p class="text-gray-600 italic">Tidak ada pesanan sedang dibuat.</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($inProgressOrders as $order)
            <div class="bg-white shadow rounded-lg p-4 flex flex-col justify-between">
                <div>
                    <p class="text-base font-bold text-gray-800">#{{ $order->id }} - {{ $order->customer_name }}</p>
                    <p class="text-sm text-gray-600">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
                    <p class="text-blue-600 font-semibold">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <p class="text-sm mt-1">Section: <span class="font-medium">
                        @if ($order->section == 'nasi_uduk')
                            Nasi Uduk
                        @elseif ($order->section == 'aneka_semur')
                            Aneka Semur
                        @elseif ($order->section == 'multiple')
                            Campuran
                        @else
                            Tidak diketahui
                        @endif
                    </span></p>
                    <div class="mt-2">
                        <h3 class="text-sm font-semibold">Rincian Produk:</h3>
                        <ul class="text-sm text-gray-700 mt-1 space-y-1 max-h-32 overflow-y-auto">
                            @foreach ($order->items as $item)
                                <li class="leading-snug">• {{ $item->product->name ?? '-' }} <br>Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }} = <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <form action="{{ route('admin.update_order_status', $order->id) }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="action" value="completed">
                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm font-semibold">Tandai Selesai</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- 3. Pesanan Selesai --}}
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">✅ Pesanan Selesai Dibuat</h2>
        @if ($completedOrders->isEmpty())
            <p class="text-gray-600 italic">Tidak ada pesanan selesai.</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($completedOrders as $order)
            <div class="bg-white shadow rounded-lg p-4 flex flex-col">
                <p class="text-base font-bold text-gray-800">#{{ $order->id }} - {{ $order->customer_name }}</p>
                <p class="text-sm text-gray-600">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
                <p class="text-blue-600 font-semibold">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p class="text-sm mt-1">Section: <span class="font-medium">
                    @if ($order->section == 'nasi_uduk')
                        Nasi Uduk
                    @elseif ($order->section == 'aneka_semur')
                        Aneka Semur
                    @elseif ($order->section == 'multiple')
                        Campuran
                    @else
                        Tidak diketahui
                    @endif
                </span></p>
                <p class="text-green-600 font-semibold mt-1">Status: Siap Diambil</p>
                <div class="mt-2">
                    <h3 class="text-sm font-semibold">Rincian Produk:</h3>
                    <ul class="text-sm text-gray-700 mt-1 space-y-1 max-h-32 overflow-y-auto">
                        @foreach ($order->items as $item)
                            <li class="leading-snug">• {{ $item->product->name ?? '-' }} <br>Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }} = <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
