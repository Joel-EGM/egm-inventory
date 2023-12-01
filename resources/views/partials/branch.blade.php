<!--Body-->
<x-modal.modal-notification />

<div class="grid grid-cols-6">
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="branch_name" value="{{ __('Branch Name') }}" />
        <x-jet-input wire:model.defer="branch_name" x-ref="branch_name" id="branch_name" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="branch_name" />
        <x-jet-input-error for="branch_name" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="branch_address" value="{{ __('Branch Address') }}" />
        <x-jet-input wire:model.defer="branch_address" x-ref="branch_address" id="branch_address" type="text"
            maxlength="50" class="mt-1 block w-full" autocomplete="branch_address" />
        <x-jet-input-error for="branch_address" class="mt-2" />
    </div>


    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="branchContactNo" value="{{ __('Branch Contact No.') }}" />
        <x-jet-input wire:model.defer="branchContactNo" x-ref="branchContactNo" id="branchContactNo" type="text"
            maxlength="50" class="mt-1 block w-full" autocomplete="branchContactNo" />
        <x-jet-input-error for="branchContactNo" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="acc_number" value="{{ __('Branch Account Number') }}" />
        <x-jet-input wire:model.defer="acc_number" x-ref="acc_number" id="acc_number" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="acc_number" />
        <x-jet-input-error for="acc_number" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="area_number" value="{{ __('Branch Area Number') }}" />
        <x-jet-input wire:model.defer="area_number" x-ref="area_number" id="area_number" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="area_number" />
        <x-jet-input-error for="area_number" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <input name="has_inventory" type="checkbox" wire:model.defer="has_inventory">Has Inventory
    </div>
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <input name="can_createall" type="checkbox" wire:model.defer="can_createall">Admin Rights
    </div>
</div>
