<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight w-1/2">
        {{ __('CURRENT STOCKS') }}
    </h2>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-4 grid grid-cols-1 bg-white">
            <div class="border-b border-t border-gray-200 sm:border sm:rounded-lg overflow-hidden">
                <div class="bg-white px-4 py-3 flex items-center justify-between border-gray-200 sm:px-4 border-b">
                    <div class="flex flex-row mt-0 sm:mb-0">
                        @if ($viewMode != 1)
                            <a href="javascript:function() { return false; }"
                                class="bg-green-300 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-black">NOT
                                AVAILABLE</a>
                        @else
                            <a href="{{ route('generate-export') }}" target="_blank"
                                class="bg-green-300 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-black">EXPORT
                                EXCEL</a>
                        @endif


                        <a href="{{ route('generate-pdf', $viewMode) }}" target="_blank"
                            class="bg-red-300 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-black">EXPORT
                            PDF</a>

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
                            <select
                                class="appearance-none h-full border-t rounded-r-none border-r-0 border-b block appearance-none w-full bg-white border-gray-300 
                                text-gray-600 py-2 px-4 pr-8 leading-tight focus:outline-none focus:border-l focus:border-r focus:bg-white focus:border-gray-300"
                                wire:model="viewMode">
                                <option value="1">Summary</option>
                                <option value="2">Detailed</option>
                            </select>
                        </div>

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
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-full px-2 py-1 xl:w">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead>
                                    <tr>
                                        @if ($viewMode != 1)
                                            <th
                                                class="w-1/3 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Item Name
                                            </th>
                                            <th
                                                class="w-1/5 px-6 py-3 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Quantity
                                            </th>
                                            <th
                                                class="w-1/3 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Date Received
                                            </th>
                                        @else
                                            <th
                                                class="w-1/4 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Item Name
                                            </th>
                                            <th
                                                class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Unit Name
                                            </th>
                                            <th
                                                class="w-1/5 px-6 py-3 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                WHOLE
                                            </th>
                                            <th
                                                class="w-1/5 px-6 py-3 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                PIECES
                                            </th>
                                            <th
                                                class="w-1/3 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Last Updated
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if (count($stockitems) === 0)
                                        <tr>
                                            <td colspan="5" class="px-3 py-3 whitespace-no-wrap">
                                                <div class="flex items-center place-content-center">
                                                    <div class="text-sm leading-5 font-medium text-gray-500 font-bold">
                                                        NO DATA AVAILABLE</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($viewMode != 1)
                                        @foreach ($stockitems as $stock)
                                            <tr
                                                class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    {{ ucfirst(trans($stock['item_name'])) }}
                                                </td>
                                                <td class="text-right px-6 py-4 whitespace-no-wrap">
                                                    {{ $stock['totalqty'] }}</td>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    {{ $stock['created_at'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($stockitems as $stock)
                                            <tr
                                                class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    {{ ucfirst(trans($stock['item_name'])) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    {{ ucfirst(trans($stock['unit_name'])) }}
                                                </td>
                                                <td class="text-right px-6 py-4 whitespace-no-wrap">
                                                    {{ intval($stock['totalqtyWHOLE']) }}</td>
                                                <td class="text-right px-6 py-4 whitespace-no-wrap">
                                                    {{ $stock['totalqtyREMAINDER'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    {{ $stock['created_at'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
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
                                    {{ $stockitems->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
