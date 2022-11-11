<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight w-1/2">
        {{ __('Items') }}
    </h2>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-4 grid grid-cols-1">
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
                            <select
                                class="appearance-none h-full rounded-l border block appearance-none w-full bg-white border-gray-300 text-gray-600 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-300">
                                <option>5</option>
                                <option>10</option>
                                <option>20</option>
                            </select>
                        </div>

                        <div class="relative">
                            <select
                                class="appearance-none h-full border-t rounded-r-none border-r-0 border-b block appearance-none w-full bg-white border-gray-300 text-gray-600 py-2 px-4 pr-8 leading-tight focus:outline-none focus:border-l focus:border-r focus:bg-white focus:border-gray-300">
                                <option>All</option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>

                        <div class="block relative">
                            <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-600">
                                    <path
                                        d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z" />
                                </svg>
                            </span>
                            <input placeholder="Search"
                                class="appearance-none rounded-r rounded-l-none border border-gray-300 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-500 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-600 focus:outline-none" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-full px-3 py-3 xl:w">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead>
                                    <tr>
                                        <th
                                            class="w-1/3 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Item Name
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Unit Name
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            PiecesPerUnit
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $item->item_name }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $item->unit_name }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $item->pieces_perUnit }}</td>


                                            <td
                                                class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                                <div class="flex items-center">
                                                    <a href="#" title="Open"
                                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    &nbsp;
                                                    &nbsp;
                                                    <a href="#" title="Open"
                                                        class="text-indigo-600 hover:text-indigo-900">Delete</a>
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
                                    <div>
                                        <p class="text-sm text-gray-700">Showing <span class="font-medium"> 1
                                            </span> to <span class="font-medium"> 5 </span> of <span
                                                class="font-medium"> 90
                                            </span>results</p>
                                    </div>
                                    <div>
                                        <nav class="relative z-0 inline-flex shadow-sm -space-x-px"
                                            aria-label="Pagination">
                                            <a href="#"
                                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Previous</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <a href="#"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                                            <a href="#"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                                            <a href="#"
                                                class="hidden md:inline-flex relative items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</a>
                                            <span
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                                            <a href="#"
                                                class="hidden md:inline-flex relative items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">8</a>
                                            <a href="#"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">9</a>
                                            <a href="#"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">10</a>
                                            <a href="#"
                                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Next</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </nav>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
