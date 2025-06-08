@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Pesanan Saya</h1>

    {{-- Notifikasi --}}
    <div id="notification" class="hidden bg-blue-100 border border-blue-300 text-blue-800 px-4 py-3 rounded mb-6 text-center transition-opacity duration-500"></div>

    @if ($orders->isEmpty())
        <div class="text-center text-gray-500 text-lg font-medium">Belum ada pesanan.</div>
    @else
        <div class="space-y-6" id="order-list">
            @foreach ($orders as $order)
                <div id="order-{{ $order->id }}" class="bg-white shadow-md rounded-2xl p-6 border border-gray-200 transition duration-300 hover:shadow-xl">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-indigo-700">#{{ $order->order_code }}</h2>
                            <p class="text-sm text-gray-500">Dibuat pada {{ $order->created_at->format('d-m-Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="order-status px-3 py-1 rounded-full text-xs font-bold 
                                {{ $order->order_status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p><span class="font-medium">Nama:</span> {{ $order->customer_name }}</p>
                            <p><span class="font-medium">Total:</span> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Pembayaran:</span> 
                                <span class="payment-status font-bold uppercase 
                                    {{ $order->payment_status == 'pending' ? 'text-yellow-500' : 'text-green-600' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </p>
                            <p><span class="font-medium">Section:</span> 
                                {{ $order->section == 'nasi_uduk' ? 'Nasi Uduk' : 'Aneka Semur' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function showAlert(message) {
        const notif = document.getElementById('notification');
        notif.textContent = message;
        notif.classList.remove('hidden', 'opacity-0');
        notif.classList.add('opacity-100');

        setTimeout(() => {
            notif.classList.remove('opacity-100');
            notif.classList.add('opacity-0');
            setTimeout(() => {
                notif.classList.add('hidden');
            }, 500);
        }, 5000);
    }

    // Cek apakah ada order yang sudah selesai dan munculkan alert
    document.querySelectorAll('.order-status').forEach((el) => {
        const status = el.textContent.trim().toLowerCase();
        if (status === 'completed') {
            showAlert('Pesanan kamu sudah selesai! Silakan ke booth untuk ambil pesanan 🎉');
        }
    });

    // Refresh otomatis tiap 8 detik
    setInterval(() => {
        location.reload();
    }, 8000);
</script>
@endsection
