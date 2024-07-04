<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight w-1/2">
        {{ __('Order History') }}
    </h2>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-4 grid grid-cols-1 bg-white">
            <div class="border-b border-t border-gray-200 sm:border sm:rounded-lg overflow-hidden">
                <div class="bg-white px-4 py-3 flex items-center justify-between border-gray-200 sm:px-4 border-b">
                    <div class="grid grid-cols-4 gap-1
                    content-start">
                        @can('isAdmin')
                            <input type="month" wire:model="order_date" min="2023-01"
                                class="block appearance-none bg-white border border-gray-400
                            hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                            leading-tight focus:outline-none focus:shadow-outline">

                            <a href="{{ route('generateMonthly', ['mos' => $order_date]) }}" target="_blank"
                                class="text-center truncate items-center px-4 py-2 bg-gray-800 border 
                            border-transparent rounded-md font-semibold text-sm text-white 
                            uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 
                            focus:outline-none focus:border-gray-900 focus:ring 
                            focus:ring-gray-300 disabled:opacity-25 transition">GENERATE
                                MONTHLY</a>
                            {{-- <a href="{{ route('generate-excel', ['mos' => $order_date]) }}" target="_blank"
                                class="text-center truncate items-center px-4 py-2 bg-gray-800 border 
                            border-transparent rounded-md font-semibold text-sm text-white 
                            uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 
                            focus:outline-none focus:border-gray-900 focus:ring 
                            focus:ring-gray-300 disabled:opacity-25 transition">EXPORT
                                EXCEL</a> --}}

                            <a href="{{ route('generate-csv', ['mos' => $order_date]) }}" target="_blank"
                                class="text-center truncate items-center px-4 py-2 bg-gray-800 border 
                            border-transparent rounded-md font-semibold text-sm text-white 
                            uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 
                            focus:outline-none focus:border-gray-900 focus:ring 
                            focus:ring-gray-300 disabled:opacity-25 transition">GENERATE
                                CSV</a>
                        @endcan

                        <div x-data="{ isFormOpen: @entangle('isFormOpen') }" class="px-2 py-4">

                            <x-modals.modal-form :formTitle="$formTitle" wire:model="isFormOpen" maxWidth="5xl">

                                @if ($formTitle === 'View Details')
                                    @include('partials.order-view')
                                @endif
                            </x-modals.modal-form>

                            <x-modals.modal-deletion :formTitle="$formTitle" wire:model="isDeleteOpen" />
                        </div>
                    </div>

                    <div class="flex flex-row mb-0 sm:mb-0">

                        <div class="relative">
                            <select wire:model="paginatePage"
                                class="appearance-none h-full rounded-l border block appearance-none w-fullj bg-white border-gray-300 text-gray-600 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-300">
                                <option value=5>5</option>
                                <option value=10>10</option>
                                <option value=20>20</option>
                            </select>
                        </div>

                        @if (Auth()->user()->branch_id === 1 || Auth()->user()->branch_id === 41)
                            <div class="relative">
                                <select wire:model="sortList"
                                    class="appearance-none h-full border-t rounded-r-none border-r-0 border-b block appearance-none w-full bg-white border-gray-300 text-gray-600 py-2 px-4 pr-8 leading-tight focus:outline-none focus:border-l focus:border-r focus:bg-white focus:border-gray-300">
                                    <option value="all">All</option>
                                    @foreach ($filteredBranches as $branch)
                                        <option value="{{ $branch->branch_id }}">{{ $branch->branches->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($sortList === 'all')
                                <div class="block relative">
                                    <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-600">
                                            <path
                                                d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z" />
                                        </svg>
                                    </span>
                                    <input placeholder="Search OR Number" wire:model="search"
                                        class="appearance-none rounded-r rounded-l-none border border-gray-300 border-b block pl-8 pr-6 
                                py-2 w-full bg-white text-sm placeholder-gray-500 text-gray-700 focus:bg-white 
                                focus:placeholder-gray-600 focus:text-gray-600 focus:outline-none" />
                                </div>
                            @endif
                        @endif

                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-full px-2 py-1 xl:w">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead>
                                    <tr>
                                        <th
                                            class="w-1/3 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Branch Name
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            OR No.
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Order Date
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if (count($orderHistory) === 0)
                                        <tr>
                                            <td colspan="5" class="px-3 py-3 whitespace-no-wrap">
                                                <div class="flex items-center place-content-center">
                                                    <div class="text-sm leading-5 font-medium text-gray-500 font-bold">
                                                        NO DATA AVAILABLE</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($orderHistory as $order)
                                        <tr
                                            class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                {{ $order->branches->branch_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                {{ sprintf('%08d', $order->or_number) }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $order->order_date }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                @if ($order->order_status === 'pending' || $order->order_status === 'incomplete')
                                                    <a href="javascript:" title="Details"
                                                        wire:click="viewOrderDetails({{ $order->id }})"
                                                        class="no-underline hover:underline font-mono text-blue-500">{{ $order->order_status }}</a>
                                                @else
                                                    <a href="javascript:" title="View"
                                                        wire:click="viewDetails({{ $order->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="ForestGreen"
                                                            class="w-6 h-6" name="view">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                <div class="flex items-center">
                                                    <a href="{{ route('generatePO', $order->id) }}" title="PO"
                                                        class="text-gray-500 mt-1 ml-2" target="_blank" name="po">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="red" class="h-4 w-4">
                                                            <path fill-rule="evenodd"
                                                                d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zm4.75 6.75a.75.75 0 011.5 0v2.546l.943-1.048a.75.75 0 011.114 1.004l-2.25 2.5a.75.75 0 01-1.114 0l-2.25-2.5a.75.75 0 111.114-1.004l.943 1.048V8.75z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 whitespace-no-wrap">
                                                @if ($order->getIsEditedAttribute() === true)
                                                    <p class="text-sm truncate">Today's Entry</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div
                                class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                                <div class="flex-1 flex justify-between sm:hidden">
                                    <a href="#"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:text-gray-500">Previous</a>
                                    <a href="#"
                                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:text-gray-500">Next</a>
                                </div>
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    {{ $orderHistory->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
