<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
            <div class="mt-4 shadow-md">
                <div class="flex flex-wrap -mx-6">
                    <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
                        <div class="flex items-center px-5 py-6 rounded-md bg-white">
                            <div class="p-3 rounded-full bg-yellow-300 bg-opacity-75">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>

                            <div class="mx-5">
                                <div class="text-gray-500 text-sm font-bold">Active Branch</div>

                                <h4 class="text-2xl font-bold text-gray-700">{{ $branches->count() }}</h4>

                            </div>
                        </div>
                    </div>

                    <div class="w-full mt-6 px-6 sm:w-1/2 xl:w-1/4 xl:mt-0">
                        <div class="flex items-center px-5 py-6 rounded-md bg-white">
                            <div class="p-3 rounded-full bg-red-300 bg-opacity-75">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div class="mx-5">
                                <div class="text-gray-500 text-sm font-bold">Pending PO (Bracnh to HO)</div>
                                <h4 class="text-2xl font-bold text-gray-700">215,542</h4>
                            </div>
                        </div>
                    </div>

                    <div class="w-full mt-6 px-6 sm:w-1/2 xl:w-1/4 sm:mt-0">
                        <div class="flex items-center px-5 py-6 rounded-md bg-white">
                            <div class="p-3 rounded-full bg-purple-300 bg-opacity-75">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <div class="mx-5">
                                <div class="text-gray-500 text-sm font-bold">Pending PO (HO to Supplier)</div>
                                <h4 class="text-2xl font-bold text-gray-700">{{ $orders->count() }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="w-full mt-6 px-6 sm:w-1/2 xl:w-1/4 xl:mt-0">
                        <div class="flex items-center px-5 py-6 rounded-md bg-white">
                            <div class="p-3 rounded-full bg-green-300 bg-opacity-75">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>

                            <div class="mx-5">
                                <div class="text-gray-500 text-sm font-bold">Current Stocks</div>
                                <h4 class="text-2xl font-bold text-gray-700">215,542</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
