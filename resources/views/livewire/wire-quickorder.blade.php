<div>
    <x-jet-input-error for="arrayItemId" class="mt-2 text-center" />

    <table class="min-w-full divide-y divide-gray-200 table-fixed">
        <thead>
            <tr>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Supplier
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Item
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Stock Quantity
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Order Type
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Order Quantity
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Price
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Total Amount
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Action
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($getBySupplier as $key => $order)
                <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        {{ $order['suppliers_name'] }}</td>

                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        {{ $order['item_name'] }}</td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        {{ $order['quantity'] }}</td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        <select name="arrayItemId" wire:model="arrayItemId.{{ $key }}"
                            class="block appearance-none bg-white border border-gray-400
                            hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                            leading-tight focus:outline-none focus:shadow-outline">

                            <option value="None" class="text-center text-gray-400">--select pricing--</option>
                            <option class="w-full" value="{{ $order['item_id'] }} Unit">Per Unit</option>
                            <option class="w-full" value="{{ $order['item_id'] }} Pieces">Per Pieces</option>

                        </select>
                    </td>
                    <td class="text-left px-6 py-4 whitespace-no-wrap">
                        <x-jet-input wire:model="arrayOrderQty.{{ $key }}" x-ref="quantity" id="quantity"
                            type="number" maxlength="50" class="mt-1 block w-full text-center shadow" placeholder="Qty"
                            autocomplete="quantity" />
                        <x-jet-input-error for="quantity" class="mt-2" />
                    </td>


                    <td class="text-sm text-right text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        <x-jet-input wire:model="arrayUnitPrice.{{ $key }}" x-ref="unitPrice" id="unitPrice"
                            type="text" maxlength="50" class="mt-1 block w-full text-center shadow"
                            placeholder="Price" autocomplete="unitPrice" />
                        <x-jet-input-error for="unitPrice" class="mt-2" />
                    </td>

                    <td class="text-sm text-right text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        <x-jet-input wire:model="arrayTotalAmt.{{ $key }}" x-ref="arrayTotalAmt"
                            id="arrayTotalAmt" type="text" maxlength="50"
                            class="mt-1 block w-full text-center bg-gray-300 shadow" placeholder="Total Amount"
                            autocomplete="arrayTotalAmt" readonly />
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        <a href="javascript:" title="DeleteArray" wire:click="removeItem({{ $loop->index }})"
                            class="text-gray-500 mt-1 ml-2 inline-flex">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 
                            2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 
                            1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
