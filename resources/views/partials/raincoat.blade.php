<div class="flex flex-wrap">
    <div class="w-full px-3 py-3 xl:w">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                <thead>
                    <tr>
                        <th
                            class="w-1/8 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Branch Name
                        </th>

                        <th
                            class="w-1/8 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Month
                        </th>

                        <th
                            class="w-1/8 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Year
                        </th>
                        <th
                            class="w-1/2 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Name of Requester
                        </th>
                        <th
                            class="w-1/8 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Order Status
                        </th>

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($data_requester as $data)
                        <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $data['branch_name'] }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $data['month'] }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $data['year'] }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $data['requester'] }}</td>

                            <td class="px-6 py-4 whitespace-no-wrap">{{ $data['order_status'] }}</td>

                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
</div>
