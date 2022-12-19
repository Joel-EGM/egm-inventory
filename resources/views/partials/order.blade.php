<!--Body-->
<div>
    <x-jet-label for="order_date" value="{{ __('Order Date') }}" />
    <input type="date" wire:model="order_date" maxlength="50" class="w-full text-center" value="" />
    <x-jet-input-error for="order_date" class="mt-2" />
</div>
<div>
    <x-jet-label for="branch_id" value="{{ __('Branch') }}" />
    <select name="branch_id" wire:model.debounce.1000ms="branch_id" class="form-control mt-1 block w-full">
        <option value="" class="text-center">--select branch--</option>
        @foreach ($branches as $branch)
            <option value="{{ $branch->id }}" class="text-center">{{ $branch->branch_name }}
            </option>
        @endforeach
    </select>
    <x-jet-input-error for="branch_id" class="mt-2" />
</div>
<div>
    <x-jet-label for="supplier_id" value="{{ __('Supplier') }}" />
    <select name="supplier_id" wire:model.debounce.1000ms="supplier_id" class="form-control mt-1 block w-full">
        <option value="" class="text-center">--select supplier--</option>
        @foreach ($suppliers as $supplier)
            <option value="{{ $supplier->id }}" class="text-center">{{ $supplier->suppliers_name }}</option>
        @endforeach
    </select>
    <x-jet-input-error for="supplier_id" class="mt-2" />
</div>
<div>
    <x-jet-label for="item_id" value="{{ __('Item') }}" />
    <select name="item_id" wire:model="item_id" class="form-control mt-1 block w-full">
        <option value="" class="text-center">--select item--</option>
        @foreach ($items as $item)
            <option value="{{ $item->id }}" class="text-center">{{ $item->item_name }}
            </option>
        @endforeach
    </select>
    <x-jet-input-error for="item_id" class="mt-2" />
</div>

<div>
    <x-jet-label for="unitName" value="{{ __('Unit Name') }}" />
    {{-- @if (!empty($unitName)) --}}
    <select name="unit_id" class="form-control mt-1 block w-full">
        <option value="" class="text-center">--select unit--</option>
        @foreach ($unitName as $unit)
            <option class="w-full text-center" value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
        @endforeach
        {{-- @endif --}}
    </select>
</div>
<div>
    <x-jet-label for="unitPrice" value="{{ __('Pricing') }}" />
    <select name="unit_id" wire:model="unit_id" class="form-control mt-1 block w-full">
        <option value="None" class="text-center">--select pricing--</option>
        @foreach ($unitName as $unit)
            <option class="w-full text-center" value="{{ $unit->id }} Unit">Per Unit</option>
            <option class="w-full text-center" value="{{ $unit->id }} Pieces">Per Pieces</option>
        @endforeach
    </select>
</div>
<br />
<div>
    <x-jet-label for="quantity" value="{{ __('Quantity') }}" />
    <x-jet-input wire:model.debounce.250ms="quantity" x-ref="quantity" id="quantity" type="text" maxlength="50"
        class="mt-1 block w-full text-center" autocomplete="quantity" />
    <x-jet-input-error for="quantity" class="mt-2" />
</div>
<div>
    <x-jet-label for="unitPrice" value="{{ __('Price') }}" />
    <x-jet-input wire:model.debounce.1000ms="unitPrice" x-ref="unitPrice" id="unitPrice" type="text" maxlength="50"
        class="mt-1 block w-full text-center" autocomplete="unitPrice" readonly />
    <x-jet-input-error for="unitPrice" class="mt-2" />
</div>
<div>
    <x-jet-label for="total_amount" value="{{ __('Total Amount') }}" />
    <x-jet-input wire:model="total_amount" x-ref="total_amount" id="total_amount" type="text" maxlength="50"
        class="mt-1 block w-full text-center" autocomplete="total_amount" value="{{ $total_amount }}" />
    <x-jet-input-error for="total_amount" class="mt-2" />
</div>
{{-- <br />
<div>
    <x-jet-label for="unitName" value="{{ __('Unit Name') }}" />
    @foreach ($unitName as $unit)
        <input type="text" maxlength="50" class="w-full text-center" value="{{ $unit->unit_name }}"readonly />
    @endforeach
</div> --}}
{{-- <br />
<div>
    <x-jet-label for="price_perUnit" value="{{ __('Price Per Unit') }}" />
    <x-jet-input wire:model.debounce.1000ms="price_perUnit" x-ref="price_perUnit" id="price_perUnit" type="text"
        maxlength="50" class="mt-1 block w-full text-center" autocomplete="price_perUnit" />
    <x-jet-input-error for="price_perUnit" class="mt-2" />
</div>
<br />
<div>
    <x-jet-label for="price_perPieces" value="{{ __('Price Per Pieces') }}" />
    <x-jet-input wire:model.debounce.1000ms="price_perPieces" x-ref="price_perPieces" id="price_perPieces"
        type="text" maxlength="50" class="mt-1 block w-full text-center" autocomplete="price_perPieces" />
    <x-jet-input-error for="price_perPieces" class="mt-2" />
</div>

<br /> --}}
<br />

<table class="table-fixed w-full">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">Branch</th>

            <th class="px-4 py-2">Date</th>

            <th class="px-4 py-2">Supplier</th>

            <th class="px-4 py-2">Item</th>

            <th class="px-4 py-2">Unit Name</th>

            <th class="px-2 py-2">Quantity</th>

            <th class="px-4 py-2">Price</th>

            <th class="px-4 py-2">Total Amount</th>

            <th class="px-4 py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orderArrays as $order)
            <tr>

                <td class="border px-4 py-2">{{ $order['branch_name'] }}</td>

                <td class="border px-4 py-2 text-sm">{{ $order['order_date'] }}</td>

                <td class="border px-4 py-2">{{ $order['suppliers_name'] }}</td>

                <td class="border px-4 py-2">{{ $order['item_name'] }}</td>

                <td class="border px-4 py-2">{{ $order['quantity'] }}</td>

                <td class="border px-4 py-2">{{ $order['price'] }}</td>

                <td class="border px-4 py-2">{{ $order['total_amount'] }}</td>

                <td class="border px-4 py-2">
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
<br />
<button wire:click.prevent="addOrderArray"
    class="bg-green-300 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-black">ADD</button>
