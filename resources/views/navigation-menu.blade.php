<nav x-data="{ open: false }" class="bg-white-500">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <x-jet-application-mark class="block h-9 w-auto" />
                    </a>
                </div>
                <!-- Navigation Links -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-jet-nav-link>

                        @can('isAdmin')
                            <!-- Stocks DropDown List -->
                            <x-jet-dropdown align="right" width="48" class="bg-gray-800">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white-300 hover:bg-gray-700 hover:text-white focus:bg-gray-400 focus:outline-none transition ease-in-out duration-150">
                                            {{ __('Stocks') }}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>
                                <x-slot name="content">

                                    <x-jet-dropdown-link href="{{ route('stocks') }}">
                                        {{ __('Current Stock') }}
                                    </x-jet-dropdown-link>
                                    <x-jet-dropdown-link href="{{ route('charts') }}">
                                        {{ __('Track Usage') }}
                                    </x-jet-dropdown-link>

                                </x-slot>
                            </x-jet-dropdown>


                            <!-- Manage DropDown List -->
                            <x-jet-dropdown align="right" width="48" class="bg-gray-800">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white-300 hover:bg-gray-700 hover:text-white focus:bg-gray-400 focus:outline-none transition ease-in-out duration-150">
                                            {{ __('Manage') }}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>
                                <x-slot name="content">

                                    <x-jet-dropdown-link href="{{ route('branches') }}">
                                        {{ __('Branch') }}
                                    </x-jet-dropdown-link>
                                    <x-jet-dropdown-link href="{{ route('suppliers') }}">
                                        {{ __('Supplier') }}
                                    </x-jet-dropdown-link>
                                    <x-jet-dropdown-link href="{{ route('items') }}">
                                        {{ __('Items') }}
                                    </x-jet-dropdown-link>
                                    <x-jet-dropdown-link href="{{ route('prices') }}">
                                        {{ __('Pricing') }}
                                    </x-jet-dropdown-link>
                                    <x-jet-dropdown-link href="{{ route('users') }}">
                                        {{ __('User') }}
                                    </x-jet-dropdown-link>
                                </x-slot>
                            </x-jet-dropdown>
                        @endcan
                        @canany(['isAdmin', 'isUser'])
                            <!-- Orders DropDown List -->
                            <x-jet-dropdown align="right" width="48" class="bg-gray-800">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white-300 hover:bg-gray-700 hover:text-white focus:bg-gray-400 focus:outline-none transition ease-in-out duration-150">
                                            {{ __('Orders') }}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>
                                <x-slot name="content">

                                    <x-jet-dropdown-link href="{{ route('orders') }}">
                                        {{ __('Create Order') }}
                                    </x-jet-dropdown-link>
                                    <x-jet-dropdown-link href="{{ route('history') }}">
                                        {{ __('Order History') }}
                                    </x-jet-dropdown-link>
                                </x-slot>
                            </x-jet-dropdown>
                        @endcanany
                    </div>
                </div>
            </div>


            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Settings Dropdown -->
                {{-- <div wire:poll.750ms class="text-sm">
                    Current Date & Time: {{ now()->format('m/d/y g:i:s a') }}
                </div> --}}
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48" class="bg-gray-800">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white-300 hover:bg-gray-700 hover:text-white focus:bg-gray-400 focus:outline-none transition ease-in-out duration-150">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>
                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-jet-dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                                    {{ __('Logout') }}
                                </x-jet-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>
            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-400 focus:text-white-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-jet-responsive-nav-link>

            <x-jet-responsive-nav-link href="#" :active="request()->routeIs()">
                {{ __('Stocks') }}
            </x-jet-responsive-nav-link>

            <x-jet-responsive-nav-link href="#" :active="request()->routeIs()">
                {{ __('Manage') }}
            </x-jet-responsive-nav-link>

            <x-jet-responsive-nav-link href="#" :active="request()->routeIs()">
                {{ __('Order') }}
            </x-jet-responsive-nav-link>
        </div>
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="flex-shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif
                <div>
                    <div class="font-medium text-base text-gray-300">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-jet-responsive-nav-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-jet-responsive-nav-link href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                    this.closest('form').submit();">
                        {{ __('Logout') }}
                    </x-jet-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
