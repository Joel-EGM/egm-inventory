<!--Body-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="flex flex-row -mx-3 mb-6 space-x-4">

    <div class="w-full px-3">
        <x-jet-label value="{{ __('Order Date') }}" />
        <input type="date" wire:model="order_date" maxlength="50"
            class="block appearance-none w-full bg-white border border-gray-400
                hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                leading-tight focus:outline-none focus:shadow-outline"
            value="None" min="<?= date('Y-m-d', strtotime('-7 days')) ?>"
            max="<?= date('Y-m-d', strtotime('+0 days')) ?>" />

    </div>

    <div class="w-full">
        <x-jet-label value="{{ __('Branch') }}" />
        @if ($has_inventory != 1)
            <select name="branch_id" wire:model="branch_id"
                class="block appearance-none w-full bg-white border border-gray-400
                hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                leading-tight focus:outline-none focus:shadow-outline"
                disabled>
                <option value="{{ $branchFind->id }}" class="text-center">{{ $branchFind->branch_name }}
                </option>
            @else
                <select name="branch_id" wire:model="branch_id"
                    class="block appearance-none w-full bg-white border border-gray-400
                hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                leading-tight focus:outline-none focus:shadow-outline">
                    <option value="None" class="text-center text-gray-400">--select branch--</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}
                        </option>
                    @endforeach
        @endif
        </select>

    </div>

    <div class="w-full px-3">
        <x-jet-label value="{{ __('Supplier') }}" />
        <select name="supplier_id" wire:model="supplier_id"
            class="block appearance-none w-full bg-white border border-gray-400
                hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                leading-tight focus:outline-none focus:shadow-outline">

            <option value="None" class="text-center text-gray-400">--select supplier--</option>
            @foreach ($filteredSuppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->suppliers_name }}</option>
            @endforeach

        </select>

    </div>

</div>

<div class="flex flex-row -mx-3 mb-6 space-x-4">
    <div class="w-full px-3">
        <x-jet-label value="{{ __('Item') }}" />

        <select name="item_id" wire:model="item_id"
            class="block appearance-none w-full bg-white border border-gray-400
                hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                leading-tight focus:outline-none focus:shadow-outline">
            <option value="None" class="text-center text-gray-400">--select item--</option>
            @if ($branch_id != 1)
                @foreach ($itemList as $item)
                    <option value="{{ $item['id'] }}">{{ $item['item_name'] }}
                    </option>
                @endforeach
            @else
                @foreach ($itemList as $item)
                    <option value="{{ $item['item_id'] }}">{{ $item['items']['item_name'] }}
                    </option>
                @endforeach
            @endif
        </select>

    </div>
    <div class="w-full">
        <x-jet-label value="{{ __('Pricing') }}" />
        <select name="unit_id" wire:model="unit_id"
            class="block appearance-none w-full bg-white border border-gray-400
             hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
             leading-tight focus:outline-none focus:shadow-outline">

            @foreach ($unitName as $unit)
                @if ($unit->fixed_unit === 1)
                    <option class="w-full" value="{{ $unit->id }} Unit" selected>Per Unit</option>
                @elseif ($unit->fixed_pieces === 1)
                    <option class="w-full" value="{{ $unit->id }} Unit" selected>Per Pieces</option>
                @else
                    <option value="None" class="text-center text-gray-400">--select pricing--</option>
                    <option class="w-full" value="{{ $unit->id }} Unit">Per Unit</option>
                    <option class="w-full" value="{{ $unit->id }} Pieces">Per Pieces</option>
                @endif
            @endforeach
        </select>

    </div>
    @can('isAdmin')
        <div class="w-full px-3">
            <x-jet-label value="{{ __('Available') }}" />

            <x-jet-input wire:model="inStocks" id="inStocks" type="text" maxlength="50"
                class="block appearance-none w-full bg-gray-300 border border-gray-400
        hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
        leading-tight focus:outline-none focus:shadow-outline"
                placeholder="In Pieces" autocomplete="inStocks" readonly />
        </div>
    @endcan
    @can('isUser')
        <div class="w-full px-3">
            @if ($item_id != 30)
                <x-jet-label value="{{ __('Feature Not Available') }}" />
                <x-jet-input type="text" maxlength="50"
                    class="block appearance-none w-full bg-gray-300 border border-gray-400
                    hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                    leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="N/A" readonly />
            @else
                <x-jet-label value="{{ __('Rain Coat') }}" />
                <select name="requester" wire:model="requester"
                    class="block appearance-none w-full bg-white border border-gray-400
                hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
                leading-tight focus:outline-none focus:shadow-outline"
                    multiple>
                    @foreach ($result as $ao_name)
                        <option value="{{ $ao_name }}">{{ $ao_name }}
                        </option>
                    @endforeach

                </select>
            @endif

        </div>

    @endcan


</div>
<br />
<div class="flex
                            flex-row space-x-4">
    @if ($item_id != 30)
        <x-jet-input wire:model.debounce.500ms="quantity" id="quantity" type="number" maxlength="50"
            class="mt-1 block w-full text-center" placeholder="Input Quantity" autocomplete="quantity" />
    @else
        <x-jet-input wire:model.debounce.500ms="quantity" id="quantity" type="number" maxlength="50"
            class="mt-1 block w-full text-center bg-gray-300" placeholder="Input Quantity" autocomplete="quantity"
            readonly />
    @endif


    <x-jet-input wire:model="unitPrice" id="unitPrice" type="text" maxlength="50"
        class="mt-1 block w-full text-center" placeholder="Price" autocomplete="unitPrice" />


    <x-jet-input wire:model="total_amount" id="total_amount" type="text" maxlength="50"
        class="mt-1 block w-full text-center bg-gray-300" placeholder="Total Amount" autocomplete="total_amount"
        readonly />

</div>

<br />
<div class="flex flex-col">
    <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
        <div class="py-2">
            <div class="overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-200 border-b">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Branch
                            </th>

                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Supplier
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Item
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                                Quantity
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Order Type
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                Price
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                Total Amount
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderArrays as $order)
                            <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">

                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $order['branch_name'] }}</td>


                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $order['suppliers_name'] }}</td>
                                @if ($order['requester'] != '')
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{ $order['item_name'] }} ({{ $order['requester'] }})</td>
                                @else
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{ $order['item_name'] }}</td>
                                @endif

                                <td class="text-sm text-right text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $order['quantity'] }}</td>
                                <td class="text-sm text-center text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $order['unit_name'] }}</td>

                                <td class="text-sm text-right text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    &#8369;{{ $order['price'] }}</td>

                                <td class="text-sm text-right text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    &#8369;{{ $order['total_amount'] }}</td>
                                @if ($formTitle === 'Create Order')
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <a href="javascript:" title="DeleteArray"
                                            wire:click="removeItem({{ $loop->index }})"
                                            class="text-gray-500 mt-1 ml-2 inline-flex">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2
                                                            2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1
                                                            1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </td>
                                @else
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <a href="javascript:" title="DeleteArray"
                                            wire:click="deleteItem({{ $loop->index }})"
                                            class="text-gray-500 mt-1 ml-2 inline-flex">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2
                                                    2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1
                                                    1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </td>
                                @endif

                            </tr>
                        @endforeach

                    </tbody>

                </table>
                <br />

            </div>
        </div>
    </div>
</div>
<br />
<button wire:click="addOrderArray"
    class="bg-green-300 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-black">ADD</button>
