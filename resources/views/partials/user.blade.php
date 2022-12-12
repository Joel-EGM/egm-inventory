<!--Body-->
<div class="grid grid-cols-6">
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="userName" value="{{ __('User Name') }}" />
        <x-jet-input wire:model.debounce.1000ms="userName" x-ref="userName" id="userName" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="userName" />
        <x-jet-input-error for="userName" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="userEmail" value="{{ __('User Email') }}" />
        <x-jet-input wire:model.debounce.1000ms="userEmail" x-ref="userEmail" id="userEmail" type="text"
            maxlength="50" class="mt-1 block w-full" autocomplete="userEmail" />
        <x-jet-input-error for="userEmail" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="password" value="{{ __('Password') }}" />
        <x-jet-input wire:model.debounce.1000ms="password" x-ref="password" id="password" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="password" />
        <x-jet-input-error for="password" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
        <x-jet-input wire:model.debounce.1000ms="password_confirmation" x-ref="password_confirmation"
            id="password_confirmation" type="text" maxlength="50" class="mt-1 block w-full"
            autocomplete="password_confirmation" />
        <x-jet-input-error for="password_confirmation" class="mt-2" />
    </div>
</div>
