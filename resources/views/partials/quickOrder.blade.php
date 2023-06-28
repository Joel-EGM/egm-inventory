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
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($getBySupplier as $order)
            <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                    {{ $order->suppliers->suppliers_name }}</td>

                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                    {{ $order->items->item_name }}</td>
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                    50</td>
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                    <select name="unit_id" wire:model="unit_id"
                        class="block appearance-none bg-white border border-gray-400
                            hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                            leading-tight focus:outline-none focus:shadow-outline">

                        @foreach ($unitName as $unit)
                            @if ($unit->fixed_unit != 1)
                                <option value="None" class="text-center text-gray-400">--select pricing--</option>
                                <option class="w-full" value="{{ $unit->id }} Unit">Per Unit</option>
                                <option class="w-full" value="{{ $unit->id }} Pieces">Per Pieces</option>
                            @else
                                <option class="w-full" value="{{ $unit->id }} Unit" selected>Per Unit</option>
                            @endif
                        @endforeach
                    </select>
                    <x-jet-input-error for="unitPrice" class="mt-2" />
                </td>
                <td class="text-left px-6 py-4 whitespace-no-wrap">
                    <x-jet-input wire:model="quantity" x-ref="quantity" id="quantity" type="number" maxlength="50"
                        class="mt-1 block w-1/2 text-center shadow" placeholder="Qty" autocomplete="quantity" />
                    <x-jet-input-error for="quantity" class="mt-2" />
                </td>


                <td class="text-sm text-right text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                    <x-jet-input wire:model="unitPrice" x-ref="unitPrice" id="unitPrice" type="text" maxlength="50"
                        class="mt-1 block w-full text-center shadow" placeholder="Price" autocomplete="unitPrice" />
                    <x-jet-input-error for="unitPrice" class="mt-2" />
                </td>

                <td class="text-sm text-right text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                    <x-jet-input wire:model="total_amount" x-ref="total_amount" id="total_amount" type="text"
                        maxlength="50" class="mt-1 block w-full text-center bg-gray-300 shadow"
                        placeholder="Total Amount" autocomplete="total_amount" readonly />
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- @if (count($getBySupplier) != 0)
    <table width="98%">
        <td style="text-align: right; text-size: sm">Grand Total:
            {{ array_sum($total_amount) }}.00</td>
    </table>
@endif --}}
