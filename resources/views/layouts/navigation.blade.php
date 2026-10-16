<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-emerald-500/20">
                            SC
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">
                            Sport<span class="text-emerald-400">Court</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-8 sm:flex">
                    @auth
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('courts.index') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('courts.*') ? 'bg-slate-800 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                Kelola Lapangan
                            </a>
                            <a href="{{ route('admin.bookings.index') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.bookings.*') ? 'bg-slate-800 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                Kelola Booking
                                @php
                                    $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                                @endphp
                                @if ($pendingCount > 0)
                                    <span class="ms-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-500 text-slate-950">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('teams.index') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('teams.*') ? 'bg-slate-800 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                Data Tim / Komunitas
                            </a>
                        @else
                            <a href="{{ route('penyewa.dashboard') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('penyewa.dashboard') ? 'bg-slate-800 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('courts.index') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('courts.*') ? 'bg-slate-800 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                Katalog Lapangan
                            </a>
                            <a href="{{ route('bookings.index') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('bookings.*') ? 'bg-slate-800 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                Booking Saya
                            </a>
                            <a href="{{ route('teams.index') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('teams.*') ? 'bg-slate-800 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                Tim Saya
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-3">
                @auth
                    <!-- Role Badge -->
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ Auth::user()->isAdmin() ? 'bg-purple-950/60 text-purple-300 border-purple-500/30' : 'bg-emerald-950/60 text-emerald-300 border-emerald-500/30' }}">
                        {{ strtoupper(Auth::user()->role) }}
                    </span>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-slate-700 text-sm leading-4 font-medium rounded-xl text-slate-200 bg-slate-800 hover:text-white hover:bg-slate-700 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1.5">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-slate-700 text-xs text-slate-400">
                                Masuk sebagai <strong class="text-slate-200">{{ Auth::user()->email }}</strong>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')" class="text-slate-200 hover:bg-slate-700">
                                Edit Profil
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-rose-400 hover:bg-slate-700">
                                    Keluar (Logout)
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white px-3 py-2 rounded-lg">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl transition shadow-lg shadow-emerald-600/20">Daftar Akun</a>
                @endauth
            </div>

            <!-- Hamburger Button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-800 bg-slate-900 px-4 pt-2 pb-4 space-y-1">
        @auth
            @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    Dashboard Admin
                </a>
                <a href="{{ route('courts.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('courts.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    Kelola Lapangan
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.bookings.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    Kelola Booking
                </a>
                <a href="{{ route('teams.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('teams.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    Data Tim / Komunitas
                </a>
            @else
                <a href="{{ route('penyewa.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('penyewa.dashboard') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    Dashboard Penyewa
                </a>
                <a href="{{ route('courts.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('courts.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    Katalog Lapangan
                </a>
                <a href="{{ route('bookings.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('bookings.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    Booking Saya
                </a>
                <a href="{{ route('teams.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('teams.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    Tim Saya
                </a>
            @endif

            <div class="pt-4 border-t border-slate-800 mt-3">
                <div class="px-3 text-sm text-slate-400">{{ Auth::user()->name }} ({{ Auth::user()->role }})</div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Edit Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-rose-400 hover:bg-slate-800">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-300 hover:bg-slate-800">Masuk</a>
            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-emerald-400 hover:bg-slate-800">Daftar Akun</a>
        @endauth
    </div>
</nav>
