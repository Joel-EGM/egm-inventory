<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight w-1/2">
        {{ __('ITEMS') }}
    </h2>
</x-slot>

<div class="py-8">
    <x-modal.modal-notification />
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-4 grid grid-cols-1 bg-white">
            <div class="border-b border-t border-gray-200 sm:border sm:rounded-lg overflow-hidden">
                <div class="bg-white px-4 py-3 flex items-center justify-between border-gray-200 sm:px-4 border-b">
                    <div class="flex flex-row mt-0 sm:mb-0">
                        <div class="relative">
                            <div x-data="{ isFormOpen: @entangle('isFormOpen'), isDeleteOpen: @entangle('isDeleteOpen') }" class="px-2 py-4">
                                <a href="javascript:" wire:click.prevent="modalToggle"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    New Item
                                </a>

                                <x-modals.modal-form :formTitle="$formTitle" wire:model="isFormOpen">
                                    @include('partials.item')
                                </x-modals.modal-form>

                                <x-modals.modal-deletion :formTitle="$formTitle" wire:model="isDeleteOpen" />
                            </div>
                        </div>

                    </div>


                    <div class="flex flex-row mb-0 sm:mb-0">
                        <div class="relative">
                            <select wire:model="paginatePage"
                                class="appearance-none h-full rounded-l border block appearance-none w-full bg-white border-gray-300 text-gray-600 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-300">
                                <option value=5>5</option>
                                <option value=10>10</option>
                                <option value=20>20</option>
                            </select>
                        </div>

                        <div class="block relative">
                            <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-600">
                                    <path
                                        d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z" />
                                </svg>
                            </span>
                            <input placeholder="Search" wire:keydown.enter="$set('search', $event.target.value)"
                                class="appearance-none rounded-r rounded-l-none border border-gray-300 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-500 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-600 focus:outline-none" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-full px-2 py-1 xl:w">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead>
                                    <tr>

                                        <th
                                            class="w-1/4 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Item Name
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Unit Name
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-center">
                                            Ordered By
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            PiecesPerUnit
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Reorder Level
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if (count($listItems) === 0)
                                        <tr>
                                            <td colspan="4" class="px-3 py-3 whitespace-no-wrap">
                                                <div class="flex items-center place-content-center">
                                                    <div class="text-sm leading-5 font-medium text-gray-500 font-bold">
                                                        NO DATA AVAILABLE</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($listItems as $item)
                                        <tr
                                            class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                {{ ucfirst(trans($item->item_name)) }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $item->unit_name }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $item->category }}</td>
                                            @if ($item->fixed_unit === 1)
                                                <td
                                                    class="px-6 py-4 whitespace-no-wrap bg-red-700 text-center text-white">
                                                    Per Unit
                                                </td>
                                            @elseif ($item->fixed_pieces === 1)
                                                <td
                                                    class="px-6 py-4 whitespace-no-wrap bg-green-700 text-center text-white">
                                                    Per
                                                    Pieces</td>
                                            @else
                                                <td
                                                    class="px-6 py-4 whitespace-no-wrap bg-yellow-500 text-center text-white">
                                                    Unit/Pieces</td>
                                            @endif
                                            <td class="text-right px-6 py-4 whitespace-no-wrap">
                                                {{ $item->pieces_perUnit }}</td>
                                            <td class="text-right px-6 py-4 whitespace-no-wrap">
                                                {{ $item->reorder_level }}</td>


                                            <td
                                                class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                                <div class="flex items-center">
                                                    <a href="javascript:" title="Edit"
                                                        wire:click="modalEdit({{ $item->id }}, 'Edit')"
                                                        class="text-gray-500 mt-1 ml-2">
                                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="LimeGreen">
                                                            <path
                                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                            <path fill-rule="evenodd"
                                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                    &nbsp;
                                                    &nbsp;
                                                    <a href="javascript:" title="Delete"
                                                        wire:click="modalDelete({{ $item->id }}, 'Delete')"
                                                        class="text-gray-500 mt-1 ml-2">
                                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="crimson">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div
                                class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                                <div class="flex-1 flex justify-between sm:hidden">
                                    <a href="#"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:text-gray-500">Previous</a>
                                    <a href="#"
                                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:text-gray-500">Next</a>
                                </div>
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    {{ $listItems->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
