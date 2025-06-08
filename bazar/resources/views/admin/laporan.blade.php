@extends('layouts.app')

@section('content')
@php

    $productQuantities = [];

        foreach ($groupedOrders as $section => $orders) {
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $productName = $item->product->name ?? 'Unknown';
                    $productQuantities[$productName] = ($productQuantities[$productName] ?? 0) + $item->quantity;
                }
            }
        }

        // Urutkan dari terbanyak ke terkecil
        arsort($productQuantities);

        // Ambil 5 teratas (bisa diganti jadi 10 atau semua)
        $topProducts = array_slice($productQuantities, 0, 5, true);

        // Pisahkan label dan data
        $topProductLabels = array_keys($topProducts);
        $topProductQuantities = array_values($topProducts);


    $hpp = [
        'Nasi Uduk Original' => 5620,
        'Nasi Uduk Kimbap' => 6441,
        'Semur Ayam' => 5278,
        'Semur Telur' => 2912,
        'Semur Tahu Kentang' => 2862,
        'Semur Daging' => 10816,
    ];

    $chartLabels = [];
    $chartTotals = [];

    $totalLaba = 0;
    $totalNasiUduk = 0;
    $totalAnekaSemur = 0;
    $totalLabaNasiUduk = 0;
    $totalLabaAnekaSemur = 0;

    foreach ($groupedOrders as $section => $orders) {
        $sectionName = $section == 'nasi_uduk' ? 'Nasi Uduk' : 'Aneka Semur';
        $sectionTotal = 0;

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $productName = $item->product->name ?? '';
                $hppPerItem = $hpp[$productName] ?? 0;

                $hargaJual = $item->price;
                $jumlah = $item->quantity;
                $subtotal = $hargaJual * $jumlah;

                $marginPerItem = $hargaJual - $hppPerItem;
                $totalMargin = $marginPerItem * $jumlah;

                $totalLaba += $totalMargin;
                $sectionTotal += $subtotal;

                if ($section == 'nasi_uduk') {
                    $totalNasiUduk += $subtotal;
                    $totalLabaNasiUduk += $totalMargin;
                } else {
                    $totalAnekaSemur += $subtotal;
                    $totalLabaAnekaSemur += $totalMargin;
                }
            }
        }

        $chartLabels[] = $sectionName;
        $chartTotals[] = $sectionTotal;
    }
@endphp




<div class="mb-4 flex justify-end space-x-2">
        <button onclick="downloadExcel()" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-grey-600">Download Excel</button>
        <button onclick="downloadPDF()" class="bg-slate-700 text-white px-4 py-2 rounded hover:bg-grey-600">Cetak PDF</button>
 </div>

 
<div class="container mx-auto py-6">
    <div id="isi">
    <h1 class="text-2xl font-semibold mb-4 text-center" style="padding-bottom: 5%">Laporan Penjualan</h1>

   <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
        <div class="flex-1">
            @include('components.sales_chart', ['chartLabels' => $chartLabels, 'chartTotals' => $chartTotals])
             @include('components.top_products_chart', ['labels' => $topProductLabels, 'quantities' => $topProductQuantities])
        </div>

        <div class="w-full md:w-1/2 mt-6 md:mt-0">
            <div class="bg-gray-100 p-6 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4">Ringkasan Laporan</h2>
                <div class="grid grid-cols-1 gap-4 text-sm md:text-base">
                    <div class="p-4 bg-white rounded shadow">
                        <h3 class="font-semibold mb-2">Total Keseluruhan:</h3>
                        <p class="mt-2 text-lg"> <strong class="text-blue-800">Rp {{ number_format($totalNasiUduk + $totalAnekaSemur, 0, ',', '.') }}</strong></p>
                        <p>Total Nasi Uduk: <strong>Rp {{ number_format($totalNasiUduk, 0, ',', '.') }}</strong></p>
                        <p>Total Aneka Semur: <strong>Rp {{ number_format($totalAnekaSemur, 0, ',', '.') }}</strong></p>
                    </div>
                    <div class="p-4 bg-white rounded shadow">
                        <h3 class="font-semibold mb-2">Laba: </h3>
                        <p class="mt-2 text-lg"> <strong class="text-green-600">Rp {{ number_format($totalLaba, 0, ',', '.') }}</strong></p>
                        <p>Laba Nasi Uduk: <strong>Rp {{ number_format($totalLabaNasiUduk, 0, ',', '.') }}</strong></p>
                        <p>Laba Aneka Semur: <strong>Rp {{ number_format($totalLabaAnekaSemur, 0, ',', '.') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @forelse ($groupedOrders as $section => $orders)
        <div class="page-break mb-8">
            <h2 class="text-xl font-bold mb-2">
                {{ $section == 'nasi_uduk' ? 'Nasi Uduk' : 'Aneka Semur' }}
            </h2>

            <div class="overflow-x-auto mb-6 print:overflow-visible">
                <table class="min-w-full border border-b-orange-200-300 w-full print:w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-6 py-2">Nama Makanan</th>
                            <th class="border px-6 py-2">Tanggal</th>
                            <th class="border px-6 py-2">Kategori</th> 
                            <th class="border px-6 py-2">Harga</th>
                            <th class="border px-6 py-2">Jumlah</th>
                            <th class="border px-6 py-2">Margin</th>
                            <th class="border px-6 py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                            $summary = [];
                        @endphp
                        @foreach ($orders as $order)
                            @foreach ($order->items as $item)

                              @php
                                      $productName = $item->product->name ?? '';
                                    $hargaJual = $item->price;
                                    $jumlah = $item->quantity;
                                    $subtotal = $hargaJual * $jumlah;
                                    $hppPerItem = $hpp[$productName] ?? 0;

                                    $marginPerItem = $hargaJual - $hppPerItem;
                                    $totalMargin = $marginPerItem * $jumlah;

                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td class="border px-6 py-2">{{ $item->product->name ?? '-' }}</td>
                                    <td class="border px-6 py-2"> {{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td class="border px-6 py-2">{{ $item->category ?? '-' }}</td> 
                                    <td class="border px-6 py-2">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="border px-6 py-2">{{ $item->quantity }}</td>
                                    <td class="border px-6 py-2">Rp {{ number_format($totalMargin, 0, ',','.')}}</td>
                                    <td class="border px-6 py-2">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                              
                            @endforeach
                        @endforeach
                        <tr class="bg-gray-100 font-semibold">
                            <td colspan="5" class="border px-7 py-2 text-right">Total</td>
                            <td class="border px-7 py-2"> Rp {{ number_format($section == 'nasi_uduk' ? $totalLabaNasiUduk : $totalLabaAnekaSemur, 0, ',', '.') }} </td>
                            <td class="border px-7 py-2">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                        
                    </tbody>
                </table>
                
            </div>
        </div>
    @empty
        <p>Tidak ada pesanan yang selesai.</p>
    @endforelse
   
</div>
</div>
<style>
    table, tr, td, th {
        page-break-inside: avoid !important;
    }

    .page-break {
        page-break-before: always;
    }

    @media print {
        .print\\:overflow-visible {
            overflow: visible !important;
        }
    }
</style>



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    const element = document.querySelector('#isi');
    const opt = {
        margin:       0.5,
        filename:     'laporan_penjualan.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { 
            scale: 2, 
            useCORS: true,
            windowWidth: document.body.scrollWidth
        },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' },
        pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
    };

    html2pdf().set(opt).from(element).save();
}

</script>


<script>
  function downloadExcel() {
    const wb = XLSX.utils.book_new();
    const ws_data = [['Nama Makanan', 'Tanggal', 'Kategori', 'Harga', 'Jumlah', 'Margin', 'Subtotal']];

    document.querySelectorAll('table tbody tr').forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length === 7) {
            const rowData = Array.from(cells).map(cell => cell.innerText);
            ws_data.push(rowData);
        }
    });

    const ws = XLSX.utils.aoa_to_sheet(ws_data);
    XLSX.utils.book_append_sheet(wb, ws, 'Laporan');

    XLSX.writeFile(wb, 'laporan_penjualan.xlsx');
}

</script>
@endpush

@endsection