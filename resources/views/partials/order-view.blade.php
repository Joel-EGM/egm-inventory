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
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Unit Name
                        </th>

                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                        </th>

                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>

                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Total Amount
                        </th>

                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>

                        <th
                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Received
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($details as $detail)
                        <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->suppliers->suppliers_name }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->items->item_name }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->items->unit_name }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->quantity }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->price }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->total_amount }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->order_status }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">
                                @if ($detail->order_status === 'pending')
                                    <input type="checkbox" wire:model="selectedRecord" value="{{ $detail->id }}" />
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
</div>
<input type="checkbox" wire:model="completedOrder">Order Complete
<button wire:click.prevent="saveCheckedItems"
    class="bg-green-300 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-black">SAVE</button>
