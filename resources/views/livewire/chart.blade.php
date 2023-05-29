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
                                        <div class="col-lg-20">
                                            <canvas id="orderChart" class="rounded shadow"></canvas>
                                            {{-- <canvas id="myChart"></canvas> --}}
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
{{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script> --}}

<!-- CHARTS -->
{{-- <script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            // labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Last Year Orders',
                backgroundColor: 'lightGray',
                data: [{
                        x: '2023-03-02',
                        y: 9
                    },
                    {
                        x: '2023-03-05',
                        y: 10
                    },
                    {
                        x: '2023-03-07',
                        y: 13
                    },
                    {
                        x: '2023-03-10',
                        y: 25
                    }
                ],
                borderWidth: 2,
                borderColor: 'red'
            }, {
                label: 'This Year Orders',
                backgroundColor: 'lightGreen',
                data: [{
                        x: '2023-05-03',
                        y: 2
                    },
                    {
                        x: '2023-05-07',
                        y: 23
                    },
                    {
                        x: '2023-05-09',
                        y: 30
                    },
                    {
                        x: '2023-05-15',
                        y: 4
                    }
                ],
                borderWidth: 2,
                borderColor: 'green'

            }]

        }
    });
</script> --}}

<script>
    var ctx = document.getElementById('orderChart').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($charts->labels) !!},

            datasets: [{
                label: 'ORDER COUNT',
                backgroundColor: {!! json_encode($charts->colours) !!},
                data: {!! json_encode($charts->dataset) !!},
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: [{
                    type: 'time',
                    time: {
                        unit: 'month'
                    }
                }]
            }
        }
    });
</script>
