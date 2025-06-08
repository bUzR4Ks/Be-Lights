
    <div class="w-full md:w-[300px]">
    <canvas id="topProductsChart" class="w-full h-[300px]"></canvas>
</div>


@push('scripts')
<script>
const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
new Chart(topProductsCtx, {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Jumlah Pembelian (pcs)',
            data: @json($quantities),
            backgroundColor: '#4d7581',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Top 5 Produk Terbanyak Dibeli',
                font: { size: 18 }
            },
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endpush
