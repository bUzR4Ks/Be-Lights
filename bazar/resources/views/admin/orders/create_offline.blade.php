@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Input Kasir</h1>

    <form action="{{ route('admin.orders.store_offline') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Nama Pembeli</label>
            <input type="text" name="customer_name" class="w-full border rounded p-2" required>
        </div>

        <div id="items-wrapper">
            <label class="block mb-1">Produk & Jumlah</label>
            <div class="item-row flex gap-2 mb-2">
                <select name="items[0][product_id]" class="product-select border p-2 rounded w-full" required>
                    <option value="">Pilih Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stok: {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
                <input type="number" name="items[0][quantity]" class="quantity-input w-24 border rounded p-2" min="1" value="1" required>
                <span class="item-total self-center">Rp 0</span>
            </div>
        </div>

        <button type="button" id="add-item" class="text-sm text-blue-600 mb-4">+ Tambah Produk</button>

        <div class="mb-4">
            <label class="block mb-1">Upload Bukti Pembayaran (QRIS)</label>
            <input type="file" name="payment_proof" class="w-full border rounded p-2" accept="image/*" required>
        </div>

        <div class="mb-4">
            <p><strong>Total Harga: Rp <span id="grand-total">0</span></strong></p>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Buat Pesanan</button>
    </form>
</div>

<script>
    let itemIndex = 1;
    const wrapper = document.getElementById('items-wrapper');
    const grandTotalSpan = document.getElementById('grand-total');

    function updateItemTotal(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const itemTotalSpan = row.querySelector('.item-total');

        const price = select.options[select.selectedIndex]?.dataset.price ?? 0;
        const quantity = parseInt(quantityInput.value || 0);
        const total = price * quantity;

        itemTotalSpan.textContent = 'Rp ' + parseInt(total).toLocaleString('id-ID');
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const price = select.options[select.selectedIndex]?.dataset.price ?? 0;
            const quantity = parseInt(quantityInput.value || 0);
            grandTotal += price * quantity;
        });
        grandTotalSpan.textContent = grandTotal.toLocaleString('id-ID');
    }

    function attachListeners(row) {
        row.querySelector('.product-select').addEventListener('change', () => updateItemTotal(row));
        row.querySelector('.quantity-input').addEventListener('input', () => updateItemTotal(row));
    }

    document.querySelectorAll('.item-row').forEach(attachListeners);

    document.getElementById('add-item').addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'item-row flex gap-2 mb-2';
        row.innerHTML = `
            <select name="items[${itemIndex}][product_id]" class="product-select border p-2 rounded w-full" required>
                <option value="">Pilih Produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                        {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stok: {{ $product->stock }})
                    </option>
                @endforeach
            </select>
            <input type="number" name="items[${itemIndex}][quantity]" class="quantity-input w-24 border rounded p-2" min="1" value="1" required>
            <span class="item-total self-center">Rp 0</span>
        `;
        wrapper.appendChild(row);
        attachListeners(row);
        itemIndex++;
    });
</script>
@endsection
