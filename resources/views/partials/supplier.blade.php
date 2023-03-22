<!--Body-->
<div class="grid grid-cols-6">
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="getsupplierName" value="{{ __('Supplier Name') }}" />
        <x-jet-input wire:model.debounce.1000ms="getsupplierName" x-ref="getsupplierName" id="getsupplierName"
            type="text" maxlength="50" class="mt-1 block w-full" autocomplete="getsupplierName" />
        <x-jet-input-error for="getsupplierName" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="getsupplierEmail" value="{{ __('Supplier Email') }}" />
        <x-jet-input wire:model.debounce.1000ms="getsupplierEmail" x-ref="getsupplierEmail" id="getsupplierEmail"
            type="text" maxlength="50" class="mt-1 block w-full" autocomplete="getsupplierEmail" />
        <x-jet-input-error for="getsupplierEmail" class="mt-2" />
    </div>


    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="getsupplierContactNo" value="{{ __('Supplier Contact No.') }}" />
        <x-jet-input wire:model.debounce.1000ms="getsupplierContactNo" x-ref="getsupplierContactNo"
            id="getsupplierContactNo" type="text" maxlength="50" class="mt-1 block w-full"
            autocomplete="getsupplierContactNo" />
        <x-jet-input-error for="getsupplierContactNo" class="mt-2" />
    </div>
</div>
