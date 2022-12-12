<!--Body-->
<div class="grid grid-cols-6">
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="branchName" value="{{ __('Branch Name') }}" />
        <x-jet-input wire:model.debounce.1000ms="branchName" x-ref="branchName" id="branchName" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="branchName" />
        <x-jet-input-error for="branchName" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="branchAddress" value="{{ __('Branch Address') }}" />
        <x-jet-input wire:model.debounce.1000ms="branchAddress" x-ref="branchAddress" id="branchAddress" type="text"
            maxlength="50" class="mt-1 block w-full" autocomplete="branchAddress" />
        <x-jet-input-error for="branchAddress" class="mt-2" />
    </div>


    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="branchContactNo" value="{{ __('Branch Contact No.') }}" />
        <x-jet-input wire:model.debounce.1000ms="branchContactNo" x-ref="branchContactNo" id="branchContactNo"
            type="text" maxlength="50" class="mt-1 block w-full" autocomplete="branchContactNo" />
        <x-jet-input-error for="branchContactNo" class="mt-2" />
    </div>
</div>
