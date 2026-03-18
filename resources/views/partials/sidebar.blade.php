<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200
           transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out
           flex flex-col">

    {{-- Brand / Logo --}}
    <div class="px-4 py-4 border-b border-gray-200">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            @if ($setting?->logo_expo)
                <img src="{{ asset('storage/' . $setting->logo_expo) }}"
                    alt="{{ $setting->nama_expo ?? config('app.name') }}"
                    class="h-8 w-8 rounded-lg object-contain bg-white">
            @else
                <div class="h-10 w-10 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($setting->nama_expo ?? config('app.name'), 0, 1)) }}
                </div>
            @endif

            <div class="leading-tight">
                <div class="text-sm font-semibold text-gray-900">
                    {{ $setting->nama_expo ?? config('app.name') }}
                </div>
                <div class="text-xs text-gray-500">
                    Admin Panel
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
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3 transition-colors
                    {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
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
            {{-- ---- SETTING ---- --}}
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Setting
                </p>

                <a href="{{ route('settings.show') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                    {{ request()->routeIs('settings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                        {{ request()->routeIs('settings*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Setting
                </a>
            </div>

            {{-- ---- ADMINISTRASI ---- --}}
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Administrasi
                </p>

                {{-- Pengguna --}}
                <a href="{{ route('user.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                    {{ request()->routeIs('user*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                        {{ request()->routeIs('user*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
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
                <a href="{{ route('kategori.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                    {{ request()->routeIs('kategori*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                        {{ request()->routeIs('kategori*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
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
                <a href="{{ route('unit.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                    {{ request()->routeIs('unit*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                        {{ request()->routeIs('unit*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
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
                <a href="{{ route('umkm.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                    {{ request()->routeIs('umkm.index', 'umkm.show', 'umkm.edit', 'umkm.create') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                        {{ request()->routeIs('umkm.index', 'umkm.show', 'umkm.edit', 'umkm.create') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
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
                <a href="{{ route('umkm.report.preview') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                    {{ request()->routeIs('umkm.report*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                        {{ request()->routeIs('umkm.report*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan Unit
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
        {{ request()->routeIs('umkm.index', 'umkm.show', 'umkm.edit', 'umkm.create') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
            {{ request()->routeIs('umkm.index', 'umkm.show', 'umkm.edit', 'umkm.create') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Data UMKM
                </a>
            </div>

            <div class="space-y-1">
                <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Laporan
                </div>

                {{-- Laporan UMKM (unit) --}}
                <a href="{{ route('umkm.report.preview') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
            {{ request()->routeIs('umkm.report*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                {{ request()->routeIs('umkm.report*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan UMKM
                </a>

                {{-- Laporan Transaksi (unit) --}}
                <a href="{{ route('unit.laporan-transaksi.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
            {{ request()->routeIs('unit.laporan-transaksi*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors
                {{ request()->routeIs('unit.laporan-transaksi*') ? 'text-indigo-700' : 'text-gray-500 group-hover:text-gray-700' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
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
