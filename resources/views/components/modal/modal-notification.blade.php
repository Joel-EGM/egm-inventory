<div x-cloak x-data="{
    showMessage: false,
    notificationType: null,
    messagePrimary: null,
    messageSecondary: null
}"
    x-on:show-message.window="
        showMessage = true; 
        notificationType = event.detail.notificationType; 
        messagePrimary = event.detail.messagePrimary; 
        messageSecondary = event.detail.messageSecondary; 
        setTimeout(() => { showMessage = false }, 2500)
    "
    aria-live="assertive"
    class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
    <div x-show="showMessage" x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <template x-if="notificationType === 'success'">
                        <svg class="h-6 w-6 text-green-400" x-description="Heroicon name: outline/check-circle"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </template>

                    <template x-if="notificationType === 'error'">
                        <svg class="h-6 w-6 text-red-400" x-description="Heroicon name: outline/check-circle"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>

                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p x-text="messagePrimary" class="text-sm font-bold text-gray-900"></p>
                    <p x-text="messageSecondary" class="mt-1 text-sm text-gray-600"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="showMessage = false"
                        class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" x-description="Heroicon name: solid/x" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
