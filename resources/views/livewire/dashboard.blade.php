<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('DASHBOARD') }}
    </h2>
</x-slot>

<div class="py-12">
    <x-modal.modal-notification />
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
            <div class="mt-4 shadow-md">
                <div class="flex flex-wrap -mx-6">
                    <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
                        <div class="flex items-center px-5 py-6 rounded-md bg-white">
                            <div class="p-3 rounded-full bg-yellow-300 bg-opacity-75">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <div class="mx-5">
                                <div class="text-gray-500 text-sm font-bold">Active Branch</div>

                                <h4 class="text-2xl font-bold text-gray-700">{{ $branches }}</h4>

                            </div>
                        </div>
                    </div>


                    <div class="w-full mt-6 px-6 sm:w-1/2 xl:w-1/4 xl:mt-0">
                        <div class="flex items-center px-5 py-6 rounded-md bg-white">
                            <div class="p-3 rounded-full bg-green-300 bg-opacity-75">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="mx-5">
                                <div class="text-gray-500 text-sm font-bold">Pending SO</div>
                                <h4 class="text-2xl font-bold text-gray-700">{{ $branch2HO->count() }}</h4>
                            </div>
                        </div>
                    </div>


                    <div class="w-full mt-6 px-6 sm:w-1/2 xl:w-1/4 xl:mt-0">
                        <div class="flex items-center px-5 py-6 rounded-md bg-white">
                            <div class="p-3 rounded-full bg-yellow-300 bg-opacity-75">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="mx-5">
                                <div class="text-gray-500 text-sm font-bold">Pending PO</div>
                                <h4 class="text-2xl font-bold text-gray-700">{{ $HO2Supplier->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>


                    <div class="w-full mt-6 px-6 sm:w-1/2 xl:w-1/4 sm:mt-0">
                        <div class="flex items-center px-5 py-6 rounded-md bg-white">
                            <div class="p-3 rounded-full bg-red-300 bg-opacity-75">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="mx-5">
                                <div class="text-gray-500 text-sm font-bold">Low On Stocks</div>
                                <h4 class="text-2xl font-bold text-gray-700">{{ $lowCollection->count() }}</h4>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br />
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
            <div class="mt-4 shadow-md">

                @if (Auth()->user()->branch_id === 1)
                    <div class="flex flex-wrap">
                        <div class="w-full px-2 py-1 xl:w">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <div x-data="{ isFormOpen: @entangle('isFormOpen'), isDeleteOpen: @entangle('isDeleteOpen') }"
                                    class="grid grid-cols-6 gap-4
                                        content-start">

                                    <x-modals.modal-form :formTitle="$formTitle" wire:model="isFormOpen" maxWidth="7xl">
                                        @if ($formTitle === 'Quick Order')
                                            @include('partials.quickOrder')
                                        @endif
                                    </x-modals.modal-form>
                                    <select name="supplier_id" wire:model="selectedSupplier"
                                        class="block appearance-none bg-white border border-gray-400
                                                hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                                                leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="All">-select supplier-</option>
                                        @foreach ($radioSupplier as $supplier)
                                            <option value="{{ $supplier['supplier_id'] }}">
                                                {{ $supplier['suppliers_name'] }}
                                        @endforeach
                                    </select>
                                    <button wire:click.prevent="quickOrder" wire:loading.attr="disabled"
                                        class="text-center truncate items-center px-4 py-2 bg-gray-800 border 
                                        border-transparent rounded-md font-semibold text-sm text-white 
                                        uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 
                                        focus:outline-none focus:border-gray-900 focus:ring 
                                        focus:ring-gray-300 disabled:opacity-25 transition">
                                        QUICK ORDER</button>
                                </div>
                                <div>
                                    <p class="font-bold text-center underline">STOCK(S) FOR ORDER</p>
                                    {{ $allLowStocks->links('vendor.pagination.tailwind') }}
                                    <br />
                                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="w-1/3 px-6 py-3 font-bold bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                    Item Name
                                                </th>
                                                <th
                                                    class="w-1/3 px-6 py-3 font-bold bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                    Supplier Name
                                                </th>
                                                <th
                                                    class="w-1/5 px-6 py-3 font-bold bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                    Quantity
                                                </th>
                                                <th
                                                    class="w-1/5 px-6 py-3 font-bold bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                    Reorder level
                                                </th>
                                                <th
                                                    class="w-1/5 px-6 py-3 font-bold bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($allLowStocks as $stock)
                                                <tr
                                                    class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                    <td class="px-6 py-4 whitespace-no-wrap">
                                                        {{ $stock['item_name'] }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-no-wrap">
                                                        {{ $stock['suppliers_name'] }}
                                                    </td>
                                                    <td class="text-left px-6 py-4 whitespace-no-wrap">
                                                        {{ $stock['total'] }}</td>
                                                    <td class="text-left px-6 py-4 whitespace-no-wrap">
                                                        {{ $stock['reorder_level'] }}</td>

                                                    <td class="px-6 py-4 whitespace-no-wrap">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="red"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 
                                                                3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 
                                                                3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 
                                                                15.75h.007v.008H12v-.008z" />
                                                        </svg>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="py-6">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="mt-4 shadow-md">
                    <div class="flex flex-wrap">
                        <div class="w-full px-2 py-1">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <x-chart></x-chart>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
