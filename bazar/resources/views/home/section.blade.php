@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-6 text-center text-[#153448]">
        @switch($section)
            @case('nasi_uduk')
                Menu Nasi Uduk
                @break
            @case('aneka_semur')
                Menu Aneka Semur
                @break
            @case('multiple')
                Menu Bundling
                @break
            @default
                Menu Produk
        @endswitch
    </h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6 text-center">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-6 text-center">
            {{ session('error') }}
        </div>
    @endif

    @if ($section === 'multiple')
        {{-- Tampilan khusus bundling --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($bundlings as $title => $productIds)
                @php
                    $items = $products->whereIn('id', $productIds);
                    $totalPrice = $items->sum('price');
                @endphp

                <div class="bg-[#dfd1b7] rounded-xl shadow-md overflow-hidden border border-[#3c5b6f] flex flex-col">
                    <div class="p-4 flex flex-col flex-grow">
                        <h2 class="text-xl font-bold text-[#153448] mb-3">{{ $title }}</h2>
                        <ul class="mb-3 text-sm text-gray-700 list-disc list-inside">
                            @foreach ($items as $item)
                                <li>{{ $item->name }}</li>
                            @endforeach
                        </ul>
                        <p class="text-[#948878] mb-4">Total Harga: Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>

                        <form action="{{ route('cart.add.bundling') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="product_ids" value="{{ implode(',', $productIds) }}">
                            <button type="submit" class="w-full bg-[#3c5b6f] hover:bg-[#2e475b] text-white text-sm font-semibold py-2 rounded transition-all">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Tampilan produk biasa --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-[#dfd1b7] rounded-xl shadow-md overflow-hidden border border-[#3c5b6f] flex flex-col">
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h2 class="text-lg font-semibold text-[#153448] mb-1">{{ $product->name }}</h2>
                        <p class="text-[#948878] mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-gray-600 mb-4">Stok: {{ $product->stock }}</p>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                            @csrf
                            <div class="flex items-center gap-2 mb-2">
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="border border-[#3c5b6f] rounded w-16 text-center text-[#153448]">
                            </div>
                            <button type="submit" class="w-full bg-[#3c5b6f] hover:bg-[#2e475b] text-white text-sm font-semibold py-2 rounded transition-all">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-10 text-center">
        <a href="{{ route('cart.index') }}" class="inline-block text-[#3c5b6f] hover:underline text-sm font-semibold">
            🛒 Lihat Keranjang
        </a>
    </div>
</div>
@endsection
