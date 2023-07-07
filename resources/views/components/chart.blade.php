<canvas id="orderChart" class="rounded shadow" width="1205" height="500"></canvas>

@once
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.js"></script>
    <script>
        var ctx = document.getElementById('orderChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart->labels) !!},
                datasets: [{
                    label: 'ITEM COUNT',
                    backgroundColor: {!! json_encode($chart->colours) !!},
                    data: {!! json_encode($chart->dataset) !!},
                    borderWidth: 3,
                    borderColor: {!! json_encode($chart->colours) !!},
                }, ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: false,
                interaction: {
                    intersect: false,
                    axis: 'x'
                },
                plugins: {
                    subtitle: {
                        display: true,
                        text: 'Most Ordered Items',
                        font: {
                            size: 20,
                            family: 'tahoma',
                            weight: 'normal',
                        }
                    }
                },
                animations: {
                    tension: {
                        duration: 2000,
                        easing: 'easeOutSine',
                        from: 1,
                        to: 0,
                        loop: false
                    }
                },
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endonce
