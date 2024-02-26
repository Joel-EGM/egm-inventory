<!--Body-->

<div class="grid grid-cols-6">

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="itemName" value="{{ __('Item Name') }}" />
        <x-jet-input wire:model.defer="itemName" x-ref="itemName" id="itemName" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="itemName" />
        <x-jet-input-error for="item_name" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="unitIName" value="{{ __('Unit Name') }}" />
        <x-jet-input wire:model.defer="unitIName" x-ref="unitIName" id="unitIName" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="unitIName" />
        <x-jet-input-error for="unitIName" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="itemCategory" value="{{ __('Category') }}" />
        <select name="itemCategory" wire:model="itemCategory"
            class="block appearance-none w-full bg-white border border-gray-400
            hover:border-gray-500 px-4 py-2 pr-8 rounded shadow 
            leading-tight focus:outline-none focus:shadow-outline">
            <option value="Forms">Forms</option>
            <option value="Supplies">Supplies</option>
        </select>
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="piecesPerUnit" value="{{ __('Pcs per Unit') }}" />
        <x-jet-input wire:model.defer="piecesPerUnit" x-ref="piecesPerUnit" id="piecesPerUnit" type="number"
            maxlength="50" class="mt-1 block w-full" autocomplete="piecesPerUnit" />
        <x-jet-input-error for="piecesPerUnit" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="reorder_level" value="{{ __('Reorder Level') }}" />
        <x-jet-input wire:model.defer="reorder_level" x-ref="reorder_level" id="reorder_level" type="number"
            maxlength="50" class="mt-1 block w-full" autocomplete="reorder_level" />
        <x-jet-input-error for="reorder_level" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="reorder_level" value="{{ __('Fixed Unit') }}" />
        <input type="checkbox" wire:model.defer="fixedUnit">
    </div>

</div>
