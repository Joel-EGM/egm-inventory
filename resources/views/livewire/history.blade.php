<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight w-1/2">
        {{ __('Order History') }}
    </h2>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-4 grid grid-cols-1">
            <div class="border-b border-t border-gray-200 sm:border sm:rounded-lg overflow-hidden">
                <div class="bg-white px-4 py-3 flex items-center justify-between border-gray-200 sm:px-4 border-b">
                    <div class="flex flex-row mt-0 sm:mb-0">
                        <div class="relative">
                            <div x-data="{ isFormOpen: @entangle('isFormOpen') }" class="px-2 py-4">

                                <x-modals.modal-form :formTitle="$formTitle" wire:model="isFormOpen" maxWidth="5xl">

                                    @if ($formTitle === 'View Details')
                                        @include('partials.order-view')
                                    @endif
                                </x-modals.modal-form>

                                <x-modals.modal-deletion :formTitle="$formTitle" wire:model="isDeleteOpen" />
                            </div>
                        </div>

                    </div>
                    <div class="flex flex-row mb-0 sm:mb-0">
                        <div class="relative">
                            <select wire:model="paginatePage"
                                class="appearance-none h-full rounded-l border block appearance-none w-full bg-white border-gray-300 text-gray-600 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-300">
                                <option value=5>5</option>
                                <option value=10>10</option>
                                <option value=20>20</option>
                            </select>
                        </div>

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
                                <input placeholder="Search" wire:model="search"
                                    class="appearance-none rounded-r rounded-l-none border border-gray-300 border-b block pl-8 pr-6 
                                py-2 w-full bg-white text-sm placeholder-gray-500 text-gray-700 focus:bg-white 
                                focus:placeholder-gray-600 focus:text-gray-600 focus:outline-none" />
                                {{-- <button type="button" wire:click="itemsearch()">search</button> --}}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-full px-3 py-3 xl:w">
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
                                            Order Date
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if (count($orderHistory) === 0)
                                        <tr>
                                            <td colspan="4" class="px-3 py-3 whitespace-no-wrap">
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
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </a>
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
