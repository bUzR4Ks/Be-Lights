@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6 max-w-xl">
    <h1 class="text-xl font-semibold mb-4">Pesanan Offline</h1>

    <form method="POST" action="{{ route('admin.offline_orders.store') }}" enctype="multipart/form-data">
        @csrf

        <div id="item-list" class="space-y-4 mb-4">
            <div class="flex gap-2">
                <select name="items[0][product_id]" class="w-full border rounded px-2 py-1">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                    @endforeach
                </select>
                <input type="number" name="items[0][quantity]" class="w-24 border rounded px-2 py-1" value="1" min="1">
            </div>
        </div>

        <button type="button" id="add-item" class="text-blue-600 hover:underline mb-4">+ Tambah Item</button>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Upload Bukti Pembayaran</label>
            <input type="file" name="payment_proof" accept="image/*" class="border rounded px-2 py-1 w-full">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">QRIS (Scan untuk Bayar)</label>
            <img src="{{ asset('images/qris.png') }}" alt="QRIS" class="w-40 h-auto">
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Buat Pesanan</button>
    </form>
</div>
<script>
    let itemIndex = 1;
    document.getElementById('add-item').addEventListener('click', () => {
        const container = document.getElementById('item-list');
        const html = `
            <div class="flex gap-2">
                <select name="items[${itemIndex}][product_id]" class="w-full border rounded px-2 py-1">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                    @endforeach
                </select>
                <input type="number" name="items[${itemIndex}][quantity]" class="w-24 border rounded px-2 py-1" value="1" min="1">
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
    });
</script>
@endsection
