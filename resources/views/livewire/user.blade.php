<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight w-1/2">
        {{ __('USERS') }}
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
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 
                                    bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    New User
                                </a>

                                <x-modals.modal-form :formTitle="$formTitle" wire:model="isFormOpen">
                                    @include('partials.user')
                                </x-modals.modal-form>

                                <x-modals.modal-deletion :formTitle="$formTitle" wire:model="isDeleteOpen" />

                            </div>

                        </div>

                    </div>

                    <a href="{{ route('generate-user', Auth()->user()->id) }}" target="_blank"
                        class="bg-red-300 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-black">EXPORT
                        ALL ACCOUNT TO
                        PDF</a>

                    <div class="flex flex-row mb-0 sm:mb-0">



                        <div class="relative">
                            <select wire:model="paginatePage"
                                class="appearance-none h-full rounded-l border block appearance-none w-full bg-white border-gray-300 text-gray-600 
                                py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-300">
                                <option value=5>5</option>
                                <option value=10>10</option>
                                <option value=20>20</option>
                            </select>
                        </div>

                        <div class="relative">
                            <select
                                class="appearance-none h-full border-t rounded-r-none border-r-0 border-b block appearance-none w-full bg-white 
                                border-gray-300 text-gray-600 py-2 px-4 pr-8 leading-tight focus:outline-none focus:border-l focus:border-r focus:bg-white focus:border-gray-300">
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
                            <input placeholder="Search" wire:model="search"
                                class="appearance-none rounded-r rounded-l-none border border-gray-300 border-b block pl-8 pr-6 py-2 w-full 
                                bg-white text-sm placeholder-gray-500 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-600 focus:outline-none" />
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
                                            class="w-1/3 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Branch
                                        </th>
                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            User Role
                                        </th>

                                        <th
                                            class="w-1/5 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if (count($allusers) === 0)
                                        <tr>
                                            <td colspan="4" class="px-3 py-3 whitespace-no-wrap">
                                                <div class="flex items-center place-content-center">
                                                    <div class="text-sm leading-5 font-medium text-gray-500 font-bold">
                                                        NO DATA AVAILABLE</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($listUsers as $user)
                                        <tr
                                            class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap">

                                                <select class="mt-1 block w-full"
                                                    wire:change="changeBranch({{ $user }},$event.target.value)">
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ $user->branch_id === $branch->id ? 'selected' : '' }}
                                                            class="text-center">
                                                            {{ $branch->branch_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                <select class="mt-1 block w-full"
                                                    wire:change="changeRole({{ $user }},$event.target.value)">

                                                    <option class="w-full text-center" value="admin"
                                                        {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option class="w-full text-center" value="user"
                                                        {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                </select>
                                            </td>



                                            <td
                                                class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                                <div class="flex items-center">
                                                    <a href="javascript:" title="Edit"
                                                        wire:click="modalEdit({{ $user->id }}, 'Edit')"
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
                                                        wire:click="selectArrayItem({{ $loop->index }}, 'Delete')"
                                                        class="text-gray-500 mt-1 ml-2">
                                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="crimson">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1
                                                                1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
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
                                    {{ $listUsers->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
