<!--Body-->
<div class="grid grid-cols-6">

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="itemName" value="{{ __('Item Name') }}" />
        <x-jet-input wire:model="itemName" x-ref="itemName" id="itemName" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="itemName" />
        <x-jet-input-error for="item_name" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="unitName" value="{{ __('Unit Name') }}" />
        <x-jet-input wire:model="unitName" x-ref="unitName" id="unitName" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="unitName" />
        <x-jet-input-error for="unitName" class="mt-2" />
    </div>


    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="piecesPerUnit" value="{{ __('Pcs per Unit') }}" />
        <x-jet-input wire:model="piecesPerUnit" x-ref="piecesPerUnit" id="piecesPerUnit" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="piecesPerUnit" />
        <x-jet-input-error for="piecesPerUnit" class="mt-2" />
    </div>
    <input type="checkbox" wire:model="fixedUnit">Fix Unit
</div>
