<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight w-1/2">
        {{ __('TRACK USAGE') }}
    </h2>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-4 grid grid-cols-1">
            <div class="border-b border-t border-gray-200 sm:border sm:rounded-lg overflow-hidden">
                <div class="bg-white px-4 py-3">
                    <div class="flex flex-row mt-0 sm:mb-0">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="chart-container" style="height: 600px">
                                            <canvas id="orderChart" class="rounded shadow"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('orderChart').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($charts->labels) !!},
            datasets: [{
                label: 'ORDER COUNT',
                data: {!! json_encode($charts->dataset) !!},
                borderWidth: 3,
                backgroundColor: {!! json_encode($charts->colours) !!},
                borderColor: {!! json_encode($charts->colours) !!},
                fill: true
            }, ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: (ctx) => 'Count of ORDER By Branch'
                },
                tooltip: {
                    mode: 'index'
                },
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Branch Name'
                    }
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Value'
                    }
                }
            }
        }
    });
</script>
