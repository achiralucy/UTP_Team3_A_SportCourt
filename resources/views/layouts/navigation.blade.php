<nav x-data="{
    open: false,
    isDark: document.documentElement.classList.contains('dark'),
    toggleTheme() {
        this.isDark = !this.isDark;
        if (this.isDark) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
            localStorage.theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
            localStorage.theme = 'light';
        }
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: this.isDark } }));
    }
}" class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40 transition-colors duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 min-h-[44px]">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-emerald-500/20">
                            SC
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                            Sport<span class="text-emerald-500">Court</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 lg:-my-px lg:ms-8 lg:flex">
                    @auth
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-semibold' : 'text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('courts.index') }}"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-xl transition {{ request()->routeIs('courts.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-semibold' : 'text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                Kelola Lapangan
                            </a>
                            <a href="{{ route('admin.bookings.index') }}"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.bookings.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-semibold' : 'text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
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
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-xl transition {{ request()->routeIs('teams.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-semibold' : 'text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                Data Tim / Komunitas
                            </a>
                        @else
                            <a href="{{ route('penyewa.dashboard') }}"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-xl transition {{ request()->routeIs('penyewa.dashboard') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-semibold' : 'text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('courts.index') }}"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-xl transition {{ request()->routeIs('courts.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-semibold' : 'text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                Katalog Lapangan
                            </a>
                            <a href="{{ route('bookings.index') }}"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-xl transition {{ request()->routeIs('bookings.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-semibold' : 'text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                Booking Saya
                            </a>
                            <a href="{{ route('teams.index') }}"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-xl transition {{ request()->routeIs('teams.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-semibold' : 'text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                Tim Saya
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings & Theme Toggle Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6 space-x-3">
                <!-- Theme Toggle Button -->
                <button type="button"
                        @click="toggleTheme()"
                        class="p-2 min-h-[40px] min-w-[40px] rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition flex items-center justify-center cursor-pointer shadow-sm"
                        :title="isDark ? 'Ganti ke Mode Terang (Light Mode)' : 'Ganti ke Mode Gelap (Dark Mode)'">
                    <!-- Sun icon shown when in dark mode (click to go light) -->
                    <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon shown when in light mode (click to go dark) -->
                    <svg x-show="!isDark" class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                @auth
                    <!-- Role Badge -->
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ Auth::user()->isAdmin() ? 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-500/30' : 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-500/30' }}">
                        {{ strtoupper(Auth::user()->role) }}
                    </span>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3.5 py-2 border border-slate-200 dark:border-slate-700 text-sm leading-4 font-medium rounded-xl text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:text-white dark:hover:bg-slate-750 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1.5">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-300">
                                Masuk sebagai <strong class="text-slate-900 dark:text-white">{{ Auth::user()->email }}</strong>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')" class="text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">
                                Edit Profil
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-rose-600 dark:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-700">
                                    Keluar (Logout)
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="min-h-[44px] inline-flex items-center text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-slate-950 dark:hover:text-white px-3 py-2 rounded-xl">Masuk</a>
                    <a href="{{ route('register') }}" class="min-h-[44px] inline-flex items-center text-sm font-semibold bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl transition shadow-md shadow-emerald-600/20">Daftar Akun</a>
                @endauth
            </div>

            <!-- Hamburger Button -->
            <div class="-me-2 flex items-center lg:hidden gap-2">
                <!-- Mobile Theme Toggle -->
                <button type="button"
                        @click="toggleTheme()"
                        class="p-2 min-h-[40px] min-w-[40px] rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300"
                        title="Ganti Mode">
                    <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="!isDark" class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <button @click="open = ! open" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div x-cloak :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-2 pb-4 space-y-1">
        @auth
            @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Dashboard Admin
                </a>
                <a href="{{ route('courts.index') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('courts.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Kelola Lapangan
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('admin.bookings.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Kelola Booking
                </a>
                <a href="{{ route('teams.index') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('teams.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Data Tim / Komunitas
                </a>
            @else
                <a href="{{ route('penyewa.dashboard') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('penyewa.dashboard') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Dashboard Penyewa
                </a>
                <a href="{{ route('courts.index') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('courts.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Katalog Lapangan
                </a>
                <a href="{{ route('bookings.index') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('bookings.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Booking Saya
                </a>
                <a href="{{ route('teams.index') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('teams.*') ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Tim Saya
                </a>
            @endif

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 mt-3">
                <div class="px-3 text-sm text-slate-700 dark:text-slate-300 font-medium">{{ Auth::user()->name }} ({{ Auth::user()->role }})</div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Edit Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-sm text-rose-600 dark:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Masuk</a>
            <a href="{{ route('register') }}" class="min-h-[44px] flex items-center px-3 py-2.5 rounded-xl text-base font-medium text-emerald-600 dark:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800">Daftar Akun</a>
        @endauth
    </div>
</nav>
