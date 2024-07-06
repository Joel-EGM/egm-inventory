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
                        <div class="relative">
                            <div x-data="{ isFormOpen: @entangle('isFormOpen'), isDeleteOpen: @entangle('isDeleteOpen') }" class="px-2 py-4">
                                <a href="javascript:" wire:click.prevent="modalToggle"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Show Previous Rain Coat Request
                                </a>

                                <x-modals.modal-form :formTitle="$formTitle" wire:model="isFormOpen" maxWidth="7xl">
                                    @include('partials.raincoat')
                                </x-modals.modal-form>

                                <x-modals.modal-deletion :formTitle="$formTitle" wire:model="isDeleteOpen" />
                            </div>
                        </div>

                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.js"></script>
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
