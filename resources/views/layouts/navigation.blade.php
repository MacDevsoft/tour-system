<nav x-data="{ open: false }" class="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-slate-950/90 backdrop-blur-xl">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/ravers-logo-sinfondo.png') }}" alt="Logo" class="h-10 w-auto object-contain drop-shadow-lg">
                        <div class="hidden md:block">
                            <p class="text-sm font-semibold tracking-[0.22em] text-emerald-300">TOUR SYSTEM</p>
                            <p class="text-xs text-slate-400">Panel de viajes y reservaciones</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <span class="inline-flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 3.17 2 11v10h7v-6h6v6h7V11L12 3.17Z"/>
                            </svg>
                            <span>Inicio</span>
                        </span>
                    </x-nav-link>

                    @if(auth()->user()->role === 'user')
                        <x-nav-link :href="route('bookings.my-tours')" :active="request()->routeIs('bookings.my-tours')">
                            <span class="inline-flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M3 5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v14a1 1 0 0 1-1.57.82L11 16.33l-5.43 3.49A1 1 0 0 1 4 19V5Zm17 1h-1v12h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1Z"/>
                                </svg>
                                <span>Mis tours</span>
                            </span>
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                            <span class="inline-flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M10 3H3v7h7V3Zm11 0h-9v7h9V3ZM10 12H3v9h7v-9Zm11 0h-9v9h9v-9Z"/>
                                </svg>
                                <span>Administración</span>
                            </span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm leading-4 font-medium text-slate-100 shadow-sm transition duration-150 ease-in-out hover:border-emerald-400/40 hover:bg-white/10 hover:text-white focus:outline-none">
                            <div class="hidden rounded-full bg-emerald-500/15 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-300 md:block">
                                {{ Auth::user()->role }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.42 0-8 2.01-8 4.5V20h16v-1.5c0-2.49-3.58-4.5-8-4.5Z"/>
                                </svg>
                                <span>{{ __('Profile') }}</span>
                            </span>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <span class="inline-flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M10 17v-2h8V9h-8V7l-5 5 5 5Zm-6 3h12v2H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h12v2H4v16Z"/>
                                    </svg>
                                    <span>{{ __('Log Out') }}</span>
                                </span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-400 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:outline-none focus:bg-white/10 focus:text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <span class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 3.17 2 11v10h7v-6h6v6h7V11L12 3.17Z"/>
                    </svg>
                    <span>Inicio</span>
                </span>
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'user')
                <x-responsive-nav-link :href="route('bookings.my-tours')" :active="request()->routeIs('bookings.my-tours')">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M3 5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v14a1 1 0 0 1-1.57.82L11 16.33l-5.43 3.49A1 1 0 0 1 4 19V5Zm17 1h-1v12h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1Z"/>
                        </svg>
                        <span>Mis tours</span>
                    </span>
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M10 3H3v7h7V3Zm11 0h-9v7h9V3ZM10 12H3v9h7v-9Zm11 0h-9v9h9v-9Z"/>
                        </svg>
                        <span>Administración</span>
                    </span>
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/10">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.42 0-8 2.01-8 4.5V20h16v-1.5c0-2.49-3.58-4.5-8-4.5Z"/>
                        </svg>
                        <span>{{ __('Profile') }}</span>
                    </span>
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <span class="inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M10 17v-2h8V9h-8V7l-5 5 5 5Zm-6 3h12v2H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h12v2H4v16Z"/>
                            </svg>
                            <span>{{ __('Log Out') }}</span>
                        </span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
