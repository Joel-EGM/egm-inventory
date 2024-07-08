<div class="flex flex-wrap">
    <div class="w-full px-3 py-3 xl:w">
        <div class="relative">
            <div class="block relative">
                <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-600">
                        <path
                            d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z" />
                    </svg>
                </span>
                <input placeholder="Search" wire:model.debounce.500ms="search"
                    class="appearance-none rounded-r rounded-l-none border border-gray-300 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-500 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-600 focus:outline-none" />
            </div>
        </div>
        <br />
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
