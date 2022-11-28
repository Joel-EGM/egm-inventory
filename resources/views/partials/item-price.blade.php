<x-modal.modal-notification />
<!--Body-->

<select name="supplier_id" wire:model="supplier_id" class="form-control">
    <option value="">--select supplier--</option>
    @foreach ($suppliers as $supplier)
        <option value="{{ $supplier->id }}">{{ $supplier->suppliers_name }}</option>
    @endforeach
</select>

<select name="item_id" wire:model="item_id" class="form-control">
    <option value="">--select item--</option>
    @foreach ($items as $item)
        <option value="{{ $item->id }}">{{ $item->item_name }}</option>
    @endforeach
</select>

<div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
    <x-jet-label for="price" value="{{ __('Price') }}" />
    <x-jet-input wire:model.debounce.1000ms="price" x-ref="price" id="price" type="text" maxlength="50"
        class="mt-1 block w-full" autocomplete="price" />
    <x-jet-input-error for="price" class="mt-2" />
</div>

<table class="table-fixed w-full">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">Supplier</th>

            <th class="px-4 py-2">Item</th>

            <th class="px-4 py-2">Price</th>

            <th class="px-4 py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($itemprices as $price)
            <tr>
                {{-- 

                        <td class="border px-4 py-2">{{ $price['item_id'] }}</td>

                        <td class="border px-4 py-2">{{ $price['quantity'] }}</td>

                        <td class="border px-4 py-2">{{ $price['price'] }}</td>

                        <td class="border px-4 py-2">{{ $price['total_amount'] }}</td> --}}

            </tr>
        @endforeach
    </tbody>
</table>
<button wire:click.prevent="addPriceArray" class="btn">ADD</button>
