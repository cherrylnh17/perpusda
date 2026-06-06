<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('karyawan.index')" :active="request()->routeIs('karyawan.index')">
                        {{ __('Karyawan') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('pendidikan.index')" :active="request()->routeIs('pendidikan.index')">
                        {{ __('Pendidikan') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('jabatan.index')" :active="request()->routeIs('jabatan.index')">
                        {{ __('Jabatan') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('kontrak.index')" :active="request()->routeIs('kontrak.index')">
                        {{ __('Kontrak') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('golongan.index')" :active="request()->routeIs('golongan.index')">
                        {{ __('Golongan') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('countdown')">
                        {{ __('Hitung Mundur') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                {{-- ── Lonceng Notifikasi Kenaikan ── --}}
                <div x-data="{ openNotif: false }" class="relative" @click.outside="openNotif = false">
                    <button @click="openNotif = !openNotif"
                        class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors focus:outline-none">
                        {{-- Ikon lonceng --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        {{-- Badge jumlah notifikasi --}}
                        @if($jumlahNotifikasi > 0)
                        <span class="absolute top-0.5 right-0.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full leading-none">
                            {{ $jumlahNotifikasi > 9 ? '9+' : $jumlahNotifikasi }}
                        </span>
                        @endif
                    </button>

                    {{-- Dropdown panel notifikasi --}}
                    <div x-show="openNotif"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                         style="display: none;">

                        {{-- Header panel --}}
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-sm font-semibold text-gray-800">Notifikasi Kenaikan</h3>
                            </div>
                            @if($jumlahNotifikasi > 0)
                            <span class="text-xs font-medium text-white bg-red-500 rounded-full px-2 py-0.5">{{ $jumlahNotifikasi }} baru</span>
                            @endif
                        </div>

                        {{-- List notifikasi --}}
                        <ul class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                            @forelse ($notifikasiKenaikan as $notif)
                            @php
                                $sisa = \Carbon\Carbon::today()->diffInDays($notif->tanggal_kenaikan, false);
                                $isGaji = $notif->tipe === 'gaji';
                                $iconBg = $isGaji ? 'bg-green-100' : 'bg-violet-100';
                                $iconColor = $isGaji ? 'text-green-600' : 'text-violet-600';
                                $badgeColor = $sisa <= 7 ? 'text-red-600 bg-red-50' : ($sisa <= 14 ? 'text-amber-600 bg-amber-50' : 'text-blue-600 bg-blue-50');
                            @endphp
                            <li class="px-4 py-3 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start gap-3">
                                    {{-- Icon tipe --}}
                                    <div class="w-8 h-8 rounded-full {{ $iconBg }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                        @if($isGaji)
                                        <svg class="w-4 h-4 {{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4 {{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        @endif
                                    </div>
                                    {{-- Info karyawan --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 truncate">{{ $notif->karyawan?->nama_lengkap ?? '-' }}</p>
                                        <p class="text-xs text-gray-500 truncate">
                                            {{ $isGaji ? 'Kenaikan Gaji' : 'Kenaikan Jabatan' }}
                                            · {{ $notif->karyawan?->jabatan?->nama_jabatan ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $notif->tanggal_kenaikan->format('d M Y') }}
                                        </p>
                                    </div>
                                    {{-- Badge H- --}}
                                    <span class="flex-shrink-0 text-xs font-bold px-2 py-0.5 rounded-full {{ $badgeColor }}">
                                        H-{{ $sisa }}
                                    </span>
                                </div>
                            </li>
                            @empty
                            <li class="px-4 py-8 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-xs text-gray-400">Tidak ada notifikasi</p>
                            </li>
                            @endforelse
                        </ul>

                        {{-- Footer --}}
                        @if($jumlahNotifikasi > 0)
                        <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50">
                            <p class="text-xs text-center text-gray-400">Menampilkan {{ $notifikasiKenaikan->count() }} dari {{ $jumlahNotifikasi }} notifikasi</p>
                        </div>
                        @endif
                    </div>
                </div>
                {{-- ── end Lonceng ── --}}

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
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
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('karyawan.index')" :active="request()->routeIs('karyawan.index')">
                {{ __('Karyawan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.index')" :active="request()->routeIs('pendidikan.index')">
                {{ __('Pendidikan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('jabatan.index')" :active="request()->routeIs('jabatan.index')">
                {{ __('Jabatan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kontrak.index')" :active="request()->routeIs('kontrak.index')">
                {{ __('Kontrak') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Hitung mundur') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
