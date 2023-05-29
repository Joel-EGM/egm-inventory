<!--Body-->
<div class="grid grid-cols-6">
    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="name" value="{{ __('User Name') }}" />
        <x-jet-input wire:model.defer="name" x-ref="name" id="name" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="name" />
        <x-jet-input-error for="name" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="email" value="{{ __('User Email') }}" />
        <x-jet-input wire:model.defer="email" x-ref="email" id="email" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="email" />
        <x-jet-input-error for="email" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="branch_id" value="{{ __('Branch') }}" />
        <select name="branch_id" wire:model.defer="branch_id" class="mt-1 block w-full">
            <option value="None" class="text-center text-gray-400">--select branch--</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" class="text-center">{{ $branch->branch_name }}
                </option>
            @endforeach
        </select>
        <x-jet-input-error for="branch_id" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="userRole" value="{{ __('Role') }}" />
        <select name="userRole" wire:model.defer="userRole" class="mt-1 block w-full">
            <option value="None" class="text-center text-gray-400">--select role--</option>
            <option class="w-full text-center" value="admin">Admin</option>
            <option class="w-full text-center" value="user">User</option>
        </select>
        <x-jet-input-error for="userRole" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="password" value="{{ __('Password') }}" />
        <x-jet-input wire:model.defer="password" x-ref="password" id="password" type="text" maxlength="50"
            class="mt-1 block w-full" autocomplete="password" />
        <x-jet-input-error for="password" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-6 lg:col-span-6 p-2">
        <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
        <x-jet-input wire:model.defer="password_confirmation" x-ref="password_confirmation" id="password_confirmation"
            type="text" maxlength="50" class="mt-1 block w-full" autocomplete="password_confirmation" />
        <x-jet-input-error for="password_confirmation" class="mt-2" />
    </div>
</div>
