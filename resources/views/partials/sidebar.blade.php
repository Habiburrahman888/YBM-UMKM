<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200
            transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out
            flex flex-col">

    {{-- Brand / Logo --}}
    <div class="px-4 py-4 bg-gradient-to-b from-blue-700 to-blue-600">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            @if ($setting?->logo_expo)
                <img src="{{ asset('storage/' . $setting->logo_expo) }}"
                    alt="{{ $setting->nama_expo ?? config('app.name') }}"
                    class="h-14 w-14 object-contain">
            @else
                <div class="h-14 w-14 rounded-lg bg-white/20 flex items-center justify-center text-white font-bold text-2xl backdrop-blur-sm">
                    {{ strtoupper(substr($setting->nama_expo ?? config('app.name'), 0, 1)) }}
                </div>
            @endif

            <div class="leading-tight">
                <div class="text-sm font-bold text-white tracking-wide">
                    {{ $setting->nama_expo ?? 'YBM UMKM' }}
                </div>
                <div class="text-[10px] text-blue-100 font-medium uppercase tracking-widest opacity-80">
                    Panel Admin
                </div>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">

        {{-- ========================================= --}}
        {{-- MENU UTAMA --}}
        {{-- ========================================= --}}
        <div class="space-y-1">
            <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Menu Utama
            </div>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                <svg class="w-5 h-5 mr-3 transition-colors
                    {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
        </div>

        {{-- ========================================= --}}
        {{-- ADMIN ONLY --}}
        {{-- ========================================= --}}
        @if (auth()->user()->role === 'admin')
            {{-- ---- ADMINISTRASI ---- --}}
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Administrasi
                </p>

                {{-- Pengguna --}}
                <a href="{{ route('admin.user.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                            {{ request()->routeIs('admin.user*') ? 'bg-primary-50 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('admin.user*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm14 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                    Pengguna
                </a>
            </div>

            {{-- ---- MASTER DATA ---- --}}
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Master Data
                </p>

                {{-- Kategori UMKM --}}
                <a href="{{ route('admin.kategori.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                            {{ request()->routeIs('admin.kategori*') ? 'bg-primary-50 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('admin.kategori*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0
                                    01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Kategori UMKM
                </a>
            </div>

            {{-- ---- UNIT ---- --}}
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Unit
                </p>

                {{-- Data Unit --}}
                <a href="{{ route('admin.unit.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                            {{ request()->routeIs('admin.unit*') ? 'bg-primary-50 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('admin.unit*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Data Unit
                </a>
            </div>

            {{-- ---- UMKM ---- --}}
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    UMKM
                </p>

                {{-- Data UMKM --}}
                <a href="{{ route('admin.umkm.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                            {{ request()->routeIs('admin.umkm.*') ? 'bg-primary-50 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('admin.umkm.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993
                                    0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378
                                    3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                    Data UMKM
                </a>
            </div>

            {{-- ---- LAPORAN ---- --}}
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Laporan
                </p>

                {{-- Laporan Unit --}}
                <a href="{{ route('admin.report.preview') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                            {{ request()->routeIs('admin.report*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('admin.report*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan Unit
                </a>

                {{-- Laporan Transaksi (Admin) --}}
                <a href="{{ route('admin.laporan-transaksi.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                            {{ request()->routeIs('admin.laporan-transaksi*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('admin.laporan-transaksi*') ? 'text-indigo-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Laporan Transaksi
                </a>

                {{-- Audit Log (Admin) --}}
                <a href="{{ route('admin.activity-log.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                            {{ request()->routeIs('admin.activity-log*') ? 'bg-slate-100 text-slate-800' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('admin.activity-log*') ? 'text-slate-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Audit Log
                </a>
            </div>

            {{-- ---- SETTING ---- --}}
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Pengaturan
                </p>

                <a href="{{ route('admin.settings.show') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                            {{ request()->routeIs('admin.settings*') ? 'bg-primary-50 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('admin.settings*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                    </svg>
                    Pengaturan
                </a>
            </div>
        @endif
        {{-- END ADMIN --}}

        {{-- ========================================= --}}
        {{-- DATA UMKM (Unit Only) --}}
        {{-- ========================================= --}}
        @if (auth()->user()->role === 'unit')
            <div class="space-y-1">
                <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Data UMKM
                </div>

                {{-- UMKM --}}
                <a href="{{ route('umkm.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                            {{ request()->routeIs('umkm.index', 'umkm.edit', 'umkm.create') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('umkm.index', 'umkm.edit', 'umkm.create') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                    Data UMKM
                </a>
            </div>

            <div class="space-y-1">
                <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Laporan Umkm
                </div>

                {{-- Laporan UMKM (unit) --}}
                <a href="{{ route('unit.report.preview') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                    {{ request()->routeIs('unit.report*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                        {{ request()->routeIs('unit.report*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan Modal
                </a>

                {{-- Laporan Transaksi (unit) --}}
                <a href="{{ route('unit.laporan-transaksi.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                    {{ request()->routeIs('unit.laporan-transaksi*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                        {{ request()->routeIs('unit.laporan-transaksi*') ? 'text-indigo-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan Transaksi
                </a>
            </div>
        @endif

        {{-- ========================================= --}}
        {{-- SETTING UMKM (UMKM Only) --}}
        {{-- ========================================= --}}
        @if (auth()->user()->role === 'umkm')
            <div class="space-y-1">
                <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Pengaturan
                </div>

                {{-- Settings UMKM --}}
                <a href="{{ route('umkm.settings.show') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                            {{ request()->routeIs('umkm.settings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('umkm.settings*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan UMKM
                </a>
            </div>
            <div class="space-y-1">
                <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Produk
                </div>

                {{-- Produk UMKM --}}
                <a href="{{ route('umkm.produk.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                            {{ request()->routeIs('umkm.produk*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('umkm.produk*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Produk Saya
                </a>

                {{-- Pesanan UMKM --}}
                <a href="{{ route('umkm.pesanan.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                            {{ request()->routeIs('umkm.pesanan*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                                {{ request()->routeIs('umkm.pesanan*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002
                                    2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Pesanan Masuk
                    @php
                        $pendingCount = \App\Models\Pesanan::where('umkm_id', auth()->user()->umkm->id ?? 0)
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if ($pendingCount > 0)
                        <span
                            class="ml-auto inline-block py-0.5 px-2 text-xs font-bold rounded-full bg-red-100 text-red-600">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            </div>
        @endif
    </nav>
</aside>

{{-- Mobile Overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden hidden" onclick="toggleSidebar()">
</div>

@push('scripts')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
@endpush
