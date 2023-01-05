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
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if (count($order_details) === 0)
                        <tr>
                            <td colspan="4" class="px-3 py-3 whitespace-no-wrap">
                                <div class="flex items-center place-content-center">
                                    <div class="text-sm leading-5 font-medium text-gray-500 font-bold">
                                        NO DATA AVAILABLE</div>
                                </div>
                            </td>
                        </tr>
                    @endif

                    @foreach ($details as $detail)
                        <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">

                            <td>{{ $detail->suppliers->suppliers_name }}</td>

                            <td>{{ $detail->items->item_name }}</td>

                            <td>{{ $detail->items->unit_name }}</td>

                            <td>{{ $detail->quantity }}</td>

                            <td>{{ $detail->price }}</td>

                            <td>{{ $detail->total_amount }}</td>

                            <td>{{ $detail->order_status }}</td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
</div>
