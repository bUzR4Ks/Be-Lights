@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-4 text-center">Konfirmasi Pesanan</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-md mx-auto border rounded-lg p-6">
        <form action="{{ route('order.place') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="customer_name" class="block font-medium">Nama Pembeli</label>
                <input type="text" name="customer_name" id="customer_name" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium">
                    Produk (section:
                    @if (count($sections) > 1)
                        Multiple Booth
                    @else
                        {{ ucfirst(str_replace('_', ' ', $sections[0])) }}
                    @endif
                    )
                </label>
                <ul>
                    @foreach ($cart as $item)
                        <li>{{ $item['name'] }} × {{ $item['quantity'] }} → Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="mb-4">
                <p class="font-semibold">Total: Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
            <div class="mb-4">
                <label for="payment_proof" class="block font-medium">Upload Bukti Pembayaran (QRIS)</label>
                <input type="file" name="payment_proof" id="payment_proof" accept="image/*" class="border rounded w-full" required>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Kirim Pesanan & Bukti</button>
            </div>
        </form>
    </div>
</div>
@endsection
