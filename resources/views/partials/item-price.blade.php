<x-modal.modal-notification />
<!--Body-->
<div>
    <x-jet-label for="price" value="{{ __('Supplier') }}" />
    <select name="supplier_id" wire:model="supplier_id" class="form-control mt-1 block w-full">
        <option value="" class="text-center">--select supplier--</option>
        @foreach ($suppliers as $supplier)
            <option value="{{ $supplier->id }}" class="text-center">{{ $supplier->suppliers_name }}</option>
        @endforeach
    </select>
</div>
<br />
<div>
    <x-jet-label for="price" value="{{ __('Item') }}" />
    <select name="item_id" wire:model="item_id" class="form-control mt-1 block w-full">
        <option value="" class="text-center">--select item--</option>
        @foreach ($items as $item)
            <option value="{{ $item->id }}" class="text-center">{{ $item->item_name }}</option>
        @endforeach
    </select>
</div>
<br />
<div>
    <x-jet-label for="price" value="{{ __('Price') }}" />
    <x-jet-input wire:model.debounce.1000ms="price" x-ref="price" id="price" type="text" maxlength="50"
        class="mt-1 block w-full text-center" autocomplete="price" />
    <x-jet-input-error for="price" class="mt-2" />
</div>

<br />
<br />

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
        @foreach ($priceArrays as $price)
            <tr>

                <td class="border px-4 py-2">{{ $price['suppliers_name'] }}</td>

                <td class="border px-4 py-2">{{ $price['item_name'] }}</td>

                <td class="border px-4 py-2">{{ $price['price'] }}</td>

                <td class="border px-4 py-2">
                    <a href="javascript:" title="DeleteArray" wire:click="removeItem({{ $loop->index }})"
                        class="text-gray-500 mt-1 ml-2 inline-flex">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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
<button wire:click.prevent="addPriceArray"
    class="bg-green-300 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-black">ADD</button>
