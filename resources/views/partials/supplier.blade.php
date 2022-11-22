<x-modal.modal-notification />
<!--Body-->
<div class="grid grid-cols-6">
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="supplierName" value="{{ __('Supplier Name') }}" />
        <x-jet-input wire:model.debounce.1000ms="supplierName" x-ref="supplierName" id="supplierName" type="text"
            maxlength="50" class="mt-1 block w-full" autocomplete="supplierName" />
        <x-jet-input-error for="supplierName" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="supplierEmail" value="{{ __('Supplier Email') }}" />
        <x-jet-input wire:model.debounce.1000ms="supplierEmail" x-ref="supplierEmail" id="supplierEmail" type="text"
            maxlength="50" class="mt-1 block w-full" autocomplete="supplierEmail" />
        <x-jet-input-error for="supplierEmail" class="mt-2" />
    </div>


    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="supplierContactNo" value="{{ __('Supplier Contact No.') }}" />
        <x-jet-input wire:model.debounce.1000ms="supplierContactNo" x-ref="supplierContactNo" id="supplierContactNo"
            type="text" maxlength="50" class="mt-1 block w-full" autocomplete="supplierContactNo" />
        <x-jet-input-error for="supplierContactNo" class="mt-2" />
    </div>
</div>
