@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">🛒 Keranjang Belanja</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if (empty($cart))
        <p class="text-center text-gray-500 italic">Keranjang masih kosong.</p>
    @else
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Gambar</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Harga</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Jumlah</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Subtotal</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($cart as $section => $items)
                    <tr>
                        <td colspan="6" class="bg-gray-50 px-6 py-4 font-semibold text-gray-800 text-left">
                            {{ ucfirst(str_replace('_', ' ', $section)) }}
                        </td>
                    </tr>
                    @foreach ($items as $productId => $item)
                        <tr>
                            <td class="px-6 py-4">
                                @if ($item['image_path'])
                                    <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded object-cover">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['name'] }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">{{ $item['quantity'] }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('cart.remove', ['section' => $section, 'productId' => $productId]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-700">Total:</td>
                    <td class="px-6 py-4 text-center font-bold text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-6 text-right">
        <a href="{{ route('order.checkout') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded shadow">
            Lanjut ke Konfirmasi
        </a>
    </div>
    @endif
</div>
@endsection
