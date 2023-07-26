<div class="flex flex-wrap">
    <div class="w-full px-3 py-3 xl:w">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                <thead>
                    <tr>
                        <th
                            class="w-1/3 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Supplier Name
                        </th>

                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Item Name
                        </th>


                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs text-left leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Order Type
                        </th>

                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs text-right leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>
                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs text-right leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs text-right leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Total Amount
                        </th>

                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        @if ($formTitle === 'Order Details')
                            <th
                                class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Received
                            </th>
                            <div class="grid justify-items-end">
                                <div>
                                    <input type="checkbox" wire:model="selectALL" />
                                    Select All
                                </div>
                            </div>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($details as $detail)
                        <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->suppliers->suppliers_name }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ ucfirst(trans($detail->items->item_name)) }}
                            </td>
                            <td class="text-left px-6 py-4 whitespace-no-wrap">{{ $detail->order_type }}</td>

                            <td class="text-right px-6 py-4 whitespace-no-wrap">
                                &#8369;{{ number_format($detail->price, 2) }}
                            </td>

                            <td class="text-right px-6 py-4 whitespace-no-wrap">{{ $detail->quantity }}</td>

                            <td class="text-right px-6 py-4 whitespace-no-wrap">
                                &#8369;{{ number_format($detail->total_amount, 2) }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->order_status }}</td>
                            @if ($formTitle === 'Order Details')
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    @if ($detail->order_status === 'pending')
                                        <input type="checkbox" wire:model.defer="selectedRecord"
                                            value="{{ $detail->id }}" />
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
</div>
@if ($formTitle === 'Order Details')
    <input name="completedOrder" type="checkbox" wire:model="completedOrder">Order Complete

    <div class="flex flex-row-reverse">
        <x-jet-button class="ml-2" wire:click.prevent="saveMethod" wire:loading.attr="disabled">
            SAVE</x-jet-button>
        <x-jet-input-error for="selectedRecord" class="mt-2" />
    </div>
@endif
