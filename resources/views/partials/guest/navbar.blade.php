@php
    /**
     * $isHeroPage = true  → Beranda: navbar transparan dengan teks putih di atas hero gelap
     * $isHeroPage = false → Halaman lain: navbar solid putih dengan teks gelap
     */
    $isHeroPage = request()->routeIs('guest.beranda');
@endphp

{{-- TOPBAR --}}
<div class="bg-neutral-900 text-white text-xs h-9 sm:h-10 flex items-center border-b border-white/5 relative z-50">
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 flex items-center justify-between">

        <div class="flex items-center gap-2 sm:gap-5">
            @if ($setting?->phone)
                <div class="hidden sm:flex items-center gap-1.5 text-white/70">
                    <i class="fas fa-phone text-[10px]"></i>
                    <span>{{ $setting->phone }}</span>
                </div>
            @endif
            @if ($setting?->email)
                <a href="mailto:{{ $setting->email }}"
                    class="flex items-center gap-1.5 text-white/70 hover:text-white transition-colors">
                    <i class="fas fa-envelope text-[10px]"></i>
                    <span
                        class="truncate max-w-[160px] sm:max-w-none text-[11px] sm:text-xs">{{ $setting->email }}</span>
                </a>
            @endif
        </div>

        <div class="flex items-center gap-2.5 sm:gap-4">
            @if ($sosmed?->facebook)
                <a href="{{ $sosmed->facebook }}" target="_blank"
                    class="text-white/60 hover:text-white transition-colors">
                    <i class="fab fa-facebook-f text-[12px] sm:text-sm"></i>
                </a>
            @endif
            @if ($sosmed?->instagram)
                <a href="{{ $sosmed->instagram }}" target="_blank"
                    class="text-white/60 hover:text-white transition-colors">
                    <i class="fab fa-instagram text-[12px] sm:text-sm"></i>
                </a>
            @endif
            @if ($sosmed?->youtube)
                <a href="{{ $sosmed->youtube }}" target="_blank"
                    class="text-white/60 hover:text-white transition-colors">
                    <i class="fab fa-youtube text-[12px] sm:text-sm"></i>
                </a>
            @endif
        </div>
    </div>
</div>

{{-- HERO NAVBAR --}}
<div id="heroNav"
    class="z-40 {{ $isHeroPage ? 'absolute top-9 sm:top-10 left-0 right-0 bg-gradient-to-b from-black/60 via-black/20 to-transparent' : 'relative bg-white border-b border-neutral-100' }}">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-3.5 flex items-center justify-between gap-4 sm:gap-6">

        {{-- Brand --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3.5 shrink-0 group">
            @if ($setting?->logo_expo)
                <div class="relative">
                    <img src="{{ asset('storage/' . $setting->logo_expo) }}" alt="{{ $setting->nama_expo }}"
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl object-contain transition-transform group-hover:scale-105">
                </div>
            @else
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center
                            font-bold text-lg sm:text-xl font-heading flex-shrink-0 transition-transform group-hover:scale-105
                            {{ $isHeroPage ? 'bg-white/10 text-white' : 'bg-primary/10 text-primary' }}">
                    {{ substr($setting->nama_expo ?? 'Y', 0, 1) }}
                </div>
            @endif
            <div class="flex flex-col {{ $isHeroPage ? 'drop-shadow-md' : '' }}">
                <div
                    class="text-lg sm:text-xl font-bold leading-tight font-heading tracking-wide
                            {{ $isHeroPage ? 'text-white' : 'text-neutral-900' }}">
                    {{ $setting->nama_expo ?? 'YBM PLN' }}
                </div>
                <div
                    class="text-[11px] sm:text-xs font-medium tracking-wider uppercase
                            {{ $isHeroPage ? 'text-white/90' : 'text-neutral-400' }}">
                    Marketplace UMKM
                </div>
            </div>
        </a>

        {{-- Links (desktop) --}}
        <div class="hidden lg:flex items-center gap-6 xl:gap-8 flex-1 justify-center">
            @foreach ([['route' => 'guest.beranda', 'label' => 'Beranda'], ['route' => 'guest.katalog', 'label' => 'Katalog'], ['route' => 'guest.umkm', 'label' => 'Daftar UMKM']] as $link)
                <a href="{{ route($link['route']) }}"
                    class="text-sm font-bold transition-all {{ $isHeroPage ? 'drop-shadow-md' : '' }}
                            {{ request()->routeIs($link['route'])
                                ? 'text-blue-400'
                                : ($isHeroPage ? 'text-white hover:text-blue-400' : 'text-neutral-600 hover:text-blue-500') }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="hidden lg:flex items-center gap-3 xl:gap-4 shrink-0">
            <a href="{{ route('guest.katalog') }}"
                class="relative flex items-center gap-2 text-sm font-bold transition-all px-2 py-2
                        {{ $isHeroPage
                            ? 'text-white hover:text-blue-400'
                            : 'text-neutral-600 hover:text-blue-500' }}"
                x-data>
                <i class="fas fa-heart text-sm"></i>
                <span>Wishlist</span>
                <span x-show="$store.wishlist.count > 0" x-text="$store.wishlist.count"
                    class="absolute top-0 right-0 bg-red-500 text-white text-[9px] font-bold
                            w-4 h-4 rounded-full flex items-center justify-center">
                </span>
            </a>
            <a href="{{ route('login') }}"
                class="px-6 py-2 rounded-full text-sm font-bold transition-all whitespace-nowrap
                        {{ $isHeroPage
                        ? 'bg-white text-blue-600 hover:bg-neutral-100'
                        : 'bg-blue-500 text-white hover:bg-blue-600' }}">
                Masuk
            </a>
        </div>

        {{-- Hamburger --}}
        <button @click="$dispatch('toggle-mobile-menu')"
            class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl transition-colors
                    {{ $isHeroPage ? 'text-white hover:bg-white/10' : 'text-neutral-700 hover:bg-neutral-100' }}">
            <i class="fas fa-bars text-base"></i>
        </button>
    </div>
</div>

{{-- STICKY NAVBAR — muncul setelah scroll Selalu putih, z-index di atas heroNav Hanya muncul setelah scroll 100px (baik di hero maupun non-hero) karena sudah ada heroNav sebagai initial nav --}}
<nav id="stickyNav" x-data="{ scrolled: false }" x-init="scrolled = window.scrollY > 100;
window.addEventListener('scroll', () => { scrolled = window.scrollY > 100 })"
    :class="scrolled ? 'translate-y-0 opacity-100 pointer-events-auto' : '-translate-y-full opacity-0 pointer-events-none'"
    class="fixed top-0 left-0 right-0 z-50 bg-white shadow-[0_2px_20px_rgba(0,0,0,0.08)] transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2 sm:py-2.5 flex items-center justify-between gap-3 sm:gap-4">

        {{-- Brand --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group">
            @if ($setting?->logo_expo)
                <img src="{{ asset('storage/' . $setting->logo_expo) }}" alt="{{ $setting->nama_expo }}"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl object-contain transition-transform group-hover:scale-105">
            @else
                <div
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-primary flex items-center justify-center
                            text-white font-bold text-sm transition-transform group-hover:scale-105">
                    {{ substr($setting->nama_expo ?? 'Y', 0, 1) }}
                </div>
            @endif
            <div class="hidden sm:flex flex-col">
                <div class="text-lg font-bold text-neutral-900 leading-tight font-heading">
                    {{ $setting->nama_expo ?? 'YBM PLN' }}
                </div>
                <div class="text-xs text-neutral-400 font-medium tracking-wide">Marketplace UMKM</div>
            </div>
        </a>

        {{-- Links (desktop) --}}
        <div class="hidden lg:flex items-center gap-6 xl:gap-8 flex-1 justify-center">
            @foreach ([['route' => 'guest.beranda', 'label' => 'Beranda'], ['route' => 'guest.katalog', 'label' => 'Katalog'], ['route' => 'guest.umkm', 'label' => 'Daftar UMKM']] as $link)
                <a href="{{ route($link['route']) }}"
                    class="text-sm font-bold transition-all
                    {{ request()->routeIs($link['route']) ? 'text-blue-500' : 'text-neutral-500 hover:text-blue-500' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
            <a href="{{ route('guest.katalog') }}"
                class="relative hidden sm:flex items-center gap-2 text-sm font-bold px-2 py-2
                        text-neutral-500 hover:text-blue-500 transition-all"
                x-data>
                <i class="fas fa-heart text-sm"></i>
                <span class="hidden md:inline">Wishlist</span>
                <span x-show="$store.wishlist.count > 0" x-text="$store.wishlist.count"
                    class="absolute top-1.5 right-1.5 bg-red-500 text-white text-[9px] font-bold
                            w-4 h-4 rounded-full flex items-center justify-center">
                </span>
            </a>
            <a href="{{ route('login') }}"
                class="hidden sm:inline-flex px-6 py-2 rounded-full bg-blue-500 text-white text-sm font-bold
                        hover:bg-blue-600 transition-all whitespace-nowrap">
                Masuk
            </a>
            <button @click="$dispatch('toggle-mobile-menu')"
                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl
                            hover:bg-neutral-100 transition-colors">
                <i class="fas fa-bars text-neutral-700 text-base"></i>
            </button>
        </div>
    </div>
</nav>

{{-- MOBILE MENU — sidebar dari kanan --}}
<div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" x-cloak
    class="fixed inset-0 z-[1100]" style="display:none">

    {{-- Backdrop --}}
    <div @click="open = false" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-neutral-900/50 backdrop-blur-sm">
    </div>

    {{-- Panel --}}
    <div x-show="open" x-transition:enter="transition-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute top-0 right-0 bottom-0 w-[280px] sm:w-72 bg-white shadow-2xl overflow-y-auto">

        <div class="p-4 sm:p-5">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6 sm:mb-7">
                <span class="font-heading text-base font-bold text-neutral-900">Menu</span>
                <button @click="open = false"
                    class="w-9 h-9 rounded-xl bg-neutral-100 flex items-center justify-center
                            text-neutral-500 hover:bg-neutral-200 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            {{-- Nav Links --}}
            <nav class="space-y-1">
                @foreach ([['route' => 'guest.beranda', 'icon' => 'fa-home', 'label' => 'Beranda'], ['route' => 'guest.katalog', 'icon' => 'fa-shopping-bag', 'label' => 'Katalog Produk'], ['route' => 'guest.umkm', 'icon' => 'fa-store', 'label' => 'Daftar UMKM']] as $link)
                    <a href="{{ route($link['route']) }}"
                        class="flex items-center gap-3 px-4 py-2.5 sm:py-3 rounded-xl text-sm font-semibold transition-all
                        {{ request()->routeIs($link['route'])
                            ? 'bg-primary/10 text-primary'
                            : 'text-neutral-600 hover:bg-neutral-50 hover:text-primary' }}">
                        <i class="fas {{ $link['icon'] }} w-4 text-center text-sm"></i>
                        {{ $link['label'] }}
                    </a>
                @endforeach

                <a href="{{ route('guest.katalog') }}"
                    class="flex items-center gap-3 px-4 py-2.5 sm:py-3 rounded-xl text-sm font-semibold
                            text-neutral-600 hover:bg-neutral-50 hover:text-primary transition-all">
                    <i class="fas fa-heart w-4 text-center text-sm"></i>
                    Wishlist
                </a>
            </nav>

            {{-- CTA --}}
            <div class="mt-6 sm:mt-7 pt-5 sm:pt-6 border-t border-neutral-100">
                <a href="{{ route('login') }}"
                    class="flex items-center justify-center gap-2 w-full bg-primary text-white
                            font-bold text-sm px-4 py-3 rounded-xl hover:bg-primary/90 transition-colors shadow-sm">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk / Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    .hero-slider {
        position: relative !important;
    }
</style>
