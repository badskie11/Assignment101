<div>
    <canvas wire:poll="getDataPoints" id="chart"></canvas>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('chartUpdated', function () {
                // Get the data points from the Livewire component
                const dataPoints = @json($dataPoints);

                // Create the chart using Chart.js
                new Chart(document.getElementById('chart'), {
                    type: 'line',
                    data: {
                        labels: Array.from({ length: dataPoints.length }, (_, i) => i + 1),
                        datasets: [{
                            label: 'Data Points',
                            data: dataPoints,
                            fill: false,
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Time'
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Value'
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
