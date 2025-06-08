<div class="w-full md:w-[300px]">
    <canvas id="salesChart" class="w-full h-[280px]"></canvas>
</div>

<div class="mt-4 space-y-2 text-sm">
    <div class="flex items-center space-x-2">
        <div class="w-4 h-4 rounded" style="background-color: #153448;"></div>
        <span>Nasi Uduk</span>
    </div>
    <div class="flex items-center space-x-2" style="padding-bottom: 5%">
        <div class="w-4 h-4 rounded" style="background-color: #4d7581;"></div>
        <span>Aneka Semur</span>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'pie', 
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: @json($chartTotals),
                backgroundColor: [
                    '#153448',
                    '#4d7581',
                    '#948878', 
                    '#e2cfad',
                    '#22c55e'
                ],
                hoverOffset: 2 
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true, 
                    position: 'center', 
                },
                title: {
                    display: true,
                    text: 'Diagram Total Penjualan per Kategori',
                    font: { size: 18 }
                },
                tooltip: { 
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush