@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <!--Title-->
    <div class="modal-content py-4 text-left px-6 bg-green-300">
        <div class="flex justify-between items-center">
            <p class="text-black text-2xl font-bold">{{ $formTitle }}</p>
            @if ($formTitle === 'Record of Rain Coat Request')
                <div wire:click.prevent="modalToggle" onclick="javascript:window.location.reload()"
                    class="modal-close cursor-pointer z-50">
                @else
                    <div wire:click.prevent="modalToggle" class="modal-close cursor-pointer z-50">
            @endif
            <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                viewBox="0 0 18 18">
                <path
                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                </path>
            </svg>
        </div>
    </div>
    </div>

    <!--Body-->
    <div class="modal-content py-4 text-left px-6 bg-white">
        {{ $slot }}
    </div>

    <!--Footer-->
    @if ($formTitle === 'Order Details' || $formTitle === 'View Details')
    @else
        <div class="modal-content flex justify-end p-4 bg-gray-100">
            @if ($formTitle === 'Record of Rain Coat Request')
                <x-jet-secondary-button wire:click.prevent="modalToggle" onclick="javascript:window.location.reload()"
                    wire:loading.attr="disabled">
                    Cancel
                </x-jet-secondary-button>
            @else
                <x-jet-secondary-button wire:click.prevent="modalToggle" wire:loading.attr="disabled">
                    Cancel
                </x-jet-secondary-button>
            @endif
            @if ($formTitle === 'Edit Order')
                <x-jet-button class="ml-2" wire:click.prevent="orderUpdate" wire:loading.attr="disabled">
                    Save</x-jet-button>
            @elseif ($formTitle === 'Edit Item' || $formTitle === 'Edit Price' || $formTitle === 'Edit Branch')
                <x-jet-button class="ml-2" wire:click.prevent="itemUpdate" wire:loading.attr="disabled">
                    Save</x-jet-button>
            @elseif($formTitle === 'Record of Rain Coat Request')
            @else
                <x-jet-button class="ml-2" wire:click.prevent="submit" wire:loading.attr="disabled">
                    Save
                </x-jet-button>
            @endif
            <span wire:loading.delay wire:target="submit">
                Loading...
            </span>
        </div>
    @endif
</x-jet-modal>
