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

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($details as $detail)
                        <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->suppliers->suppliers_name }}</td>
                            @if ($detail->requester != '')
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    {{ ucfirst(trans($detail->items->item_name)) }} ({{ $detail->requester }})
                                </td>
                            @else
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    {{ ucfirst(trans($detail->items->item_name)) }}
                                </td>
                            @endif
                            <td class="text-left px-6 py-4 whitespace-no-wrap">{{ $detail->unit_name }}</td>

                            <td class="text-right px-6 py-4 whitespace-no-wrap">
                                &#8369;{{ number_format($detail->price, 2) }}
                            </td>

                            <td class="text-right px-6 py-4 whitespace-no-wrap">{{ $detail->quantity }}</td>

                            <td class="text-right px-6 py-4 whitespace-no-wrap">
                                &#8369;{{ number_format($detail->total_amount, 2) }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $detail->order_status }}</td>

                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
</div>
