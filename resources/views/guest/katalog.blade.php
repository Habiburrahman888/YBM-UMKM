@extends('layouts.guest')

@section('title', 'Katalog Produk')

@push('styles')
    <style>
        :root {
            --katalog-ease: cubic-bezier(0.22, 1, 0.36, 1);
            --brand: #1a3199;
            --brand-dark: #152780;
            --brand-soft: #eef1fb;
        }

        .katalog-page {
            background: #f8fafc;
            min-height: 60vh;
        }

        .produk-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        @media (max-width: 1280px) {
            .produk-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .produk-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .produk-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
        }

        @media (max-width: 400px) {
            .produk-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Pagination override */
        nav[role="navigation"] svg,
        [aria-label="Pagination Navigation"] svg {
            display: none !important;
        }

        nav[role="navigation"] [aria-label="Previous »"] span,
        nav[role="navigation"] [aria-label="Next »"] span,
        nav[role="navigation"] span[aria-hidden="true"] {
            display: none !important;
        }

        nav[role="navigation"] [aria-label="Previous »"]::after {
            content: '← Prev';
            font-size: 0.8rem;
            font-weight: 600;
        }

        nav[role="navigation"] [aria-label="Next »"]::after {
            content: 'Next →';
            font-size: 0.8rem;
            font-weight: 600;
        }

        #filterCard.is-open {
            display: block !important;
        }

        /* Search focus ring — brand */
        .search-wrap:focus-within {
            border-color: var(--brand) !important;
            box-shadow: 0 4px 24px rgba(26, 49, 153, 0.13) !important;
        }

        /* Sidebar nav pill active — brand */
        .nav-pill-active {
            background: var(--brand) !important;
            color: white !important;
        }

        /* Wishlist button active — brand */
        .wishlist-active {
            background: var(--brand) !important;
            color: white !important;
        }

        /* Active filter chip — brand */
        .chip-brand {
            background: var(--brand-soft);
            border-color: rgba(26, 49, 153, 0.15);
            color: var(--brand);
        }

        .chip-brand a {
            color: var(--brand);
        }
    </style>
@endpush

@section('content')

    <div class="katalog-page">

        {{-- PAGE HEADER --}}
        <header class="relative pt-40 pb-40 text-center text-white overflow-hidden">
            <div class="absolute inset-0 z-0"
                style="background-image: linear-gradient(rgba(15,23,42,0.55),rgba(15,23,42,0.55)), url('{{ asset('landing/katalog.png') }}'); background-size:cover; background-position:center;">
            </div>
            <div class="absolute inset-0 z-0 opacity-30"
                style="background-image:url(\"data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E\")">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 flex flex-col items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 flex flex-col items-center justify-center">
                    <h1
                        class="font-heading text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight mb-3 text-center">
                        E-Katalog Produk
                    </h1>
                    <p class="text-sm md:text-base opacity-75 max-w-md mx-auto leading-relaxed text-center">
                        Temukan ratusan produk berkualitas dari pengusaha binaan YBM UMKM
                    </p>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            {{-- SEARCH --}}
            <div class="max-w-2xl mx-auto -mt-7 mb-12 relative z-20">

                <form action="{{ route('guest.katalog') }}" method="GET"
                    class="search-wrap bg-white flex items-center gap-2 pl-5 pr-2 py-2 rounded-full
                           border border-neutral-200
                           shadow-[0_4px_24px_rgba(0,0,0,0.07),0_1px_4px_rgba(0,0,0,0.04)]
                           transition-all duration-300">

                    @if (request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif

                    <i class="fas fa-search text-neutral-400 text-sm flex-shrink-0"></i>

                    <input type="text" name="cari" placeholder="Cari nama produk..." value="{{ request('cari') }}"
                        autocomplete="off"
                        class="flex-1 min-w-0 border-none bg-transparent py-2.5 text-sm font-semibold
                               text-neutral-800 placeholder:font-normal placeholder:text-neutral-400
                               focus:outline-none">

                    {{-- City select — desktop --}}
                    <div class="hidden sm:flex items-center border-l border-neutral-200 pl-3 mr-1 shrink-0">
                        <div class="relative flex items-center">
                            <select name="city" onchange="this.form.submit()"
                                class="appearance-none py-2 pl-3 pr-7 bg-transparent border-none outline-none
                                       cursor-pointer text-neutral-500 font-medium text-sm max-w-[140px]">
                                <option value="">Semua Kota</option>
                                @foreach ($cities_filter as $city)
                                    <option value="{{ $city->code }}"
                                        {{ request('city') == $city->code ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fas fa-chevron-down text-[10px] text-neutral-400 absolute right-0 pointer-events-none"></i>
                        </div>
                    </div>

                    <button type="submit"
                        class="flex-shrink-0 inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full
                               text-white text-sm font-semibold whitespace-nowrap min-h-[42px]
                               transition-all duration-200 focus:outline-none"
                        style="background: var(--brand);" onmouseover="this.style.background='var(--brand-dark)'"
                        onmouseout="this.style.background='var(--brand)'">
                        <i class="fas fa-search text-xs"></i>
                        <span class="hidden sm:inline">Cari</span>
                    </button>
                </form>

                {{-- City select — mobile --}}
                <form action="{{ route('guest.katalog') }}" method="GET" class="sm:hidden mt-2">
                    @if (request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    @if (request('cari'))
                        <input type="hidden" name="cari" value="{{ request('cari') }}">
                    @endif
                    <select name="city" onchange="this.form.submit()"
                        class="w-full py-2.5 px-4 rounded-xl border border-neutral-200 bg-white
                               text-sm text-neutral-400 font-medium outline-none cursor-pointer">
                        <option value="">Lokasi Kota (Semua)</option>
                        @foreach ($cities_filter as $city)
                            <option value="{{ $city->code }}" {{ request('city') == $city->code ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- KATALOG LAYOUT --}}
            <div class="grid grid-cols-1 lg:grid-cols-[250px_1fr] xl:grid-cols-[260px_1fr] gap-6 pb-24 items-start">

                {{-- SIDEBAR --}}
                <aside class="lg:sticky lg:top-24">

                    {{-- Mobile toggle --}}
                    <button id="filterToggle" type="button" aria-expanded="false" aria-controls="filterCard"
                        class="lg:hidden w-full flex justify-between items-center
                               bg-white border border-neutral-200 rounded-2xl px-5 py-3.5
                               font-bold text-sm text-neutral-900 cursor-pointer mb-3
                               transition-colors duration-200">
                        <span>
                            <i class="fas fa-sliders-h mr-2" style="color: var(--brand);"></i>Filter Produk
                        </span>
                        <i id="filterArrow" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>

                    {{-- Filter card --}}
                    <div id="filterCard"
                        class="hidden lg:block bg-white rounded-2xl border border-neutral-200 overflow-hidden
                               shadow-[0_2px_12px_rgba(0,0,0,0.06)]">

                        <div
                            class="flex items-center gap-2 px-4 py-3.5 border-b border-neutral-100
                                    font-bold text-sm text-neutral-700">
                            <i class="fas fa-sliders-h text-neutral-400"></i> Filter Produk
                        </div>

                        <div class="p-3">

                            {{-- Favorit --}}
                            <p class="text-[0.62rem] font-bold uppercase tracking-widest text-neutral-400 mb-1 px-2">
                                Favorit
                            </p>
                            <div class="mb-3">
                                <button type="button" @click="$store.wishlist.toggleFilter()"
                                    :class="$store.wishlist.showOnly ?
                                        'wishlist-active' :
                                        'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900'"
                                    class="flex justify-between items-center gap-2 w-full text-left px-3 py-2 rounded-lg
                                           text-sm font-semibold border-none cursor-pointer transition-all duration-200">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-heart text-xs" :class="$store.wishlist.showOnly ? 'fas' : 'far'"
                                            :style="$store.wishlist.showOnly ? '' : 'color:#ef4444'"></i>
                                        <span>Wishlist Saya</span>
                                    </div>
                                    <span class="text-[0.7rem] font-bold opacity-60" x-text="$store.wishlist.count"></span>
                                </button>
                            </div>

                            <div class="border-t border-neutral-100 mb-3"></div>

                            {{-- Kategori --}}
                            <p class="text-[0.62rem] font-bold uppercase tracking-widest text-neutral-400 mb-1 px-2">
                                Kategori
                            </p>
                            <nav class="flex flex-col gap-0.5" aria-label="Pilih kategori">
                                <a href="{{ route('guest.katalog', request()->only(['cari'])) }}"
                                    @click="$store.wishlist.showOnly = false"
                                    :class="(!$store.wishlist.showOnly && {{ !request('kategori') ? 'true' : 'false' }}) ?
                                    'nav-pill-active' :
                                    'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900'"
                                    class="flex justify-between items-center gap-2 px-3 py-2.5 rounded-lg
                                           text-sm font-semibold no-underline transition-all duration-200">
                                    Semua Kategori
                                </a>
                                @foreach ($kategori as $kat)
                                    <a href="{{ route('guest.katalog', array_merge(request()->only(['cari']), ['kategori' => $kat->id])) }}"
                                        @click="$store.wishlist.showOnly = false"
                                        :class="(!$store.wishlist.showOnly &&
                                            {{ request('kategori') == $kat->id ? 'true' : 'false' }}) ?
                                        'nav-pill-active' :
                                        'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900'"
                                        class="flex justify-between items-center gap-2 px-3 py-2.5 rounded-lg
                                               text-sm font-semibold no-underline transition-all duration-200">
                                        <span>{{ $kat->nama }}</span>
                                        @if (!empty($kat->produk_count))
                                            <span
                                                class="text-[0.7rem] font-bold
                                                         {{ request('kategori') == $kat->id ? 'opacity-70' : 'opacity-40' }}">
                                                {{ $kat->produk_count }}
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            </nav>

                            @if (request('cari') || request('kategori') || request('lat'))
                                <a href="{{ route('guest.katalog') }}"
                                    class="flex items-center justify-center gap-1.5 w-full mt-3 py-2.5 rounded-xl
                                           border border-dashed border-neutral-200 text-sm font-bold
                                           text-neutral-400 no-underline transition-all duration-200
                                           hover:border-red-400 hover:text-red-500 hover:bg-red-50">
                                    <i class="fas fa-times text-xs"></i> Reset Filter
                                </a>
                            @endif

                        </div>
                    </div>
                </aside>

                {{-- KONTEN UTAMA --}}
                <div>

                    {{-- Toolbar --}}
                    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
                        <div>
                            <p class="text-sm font-semibold text-neutral-400">
                                Menampilkan
                                <strong class="text-neutral-900 font-extrabold">{{ $produk->total() }}</strong>
                                produk
                                @if (request('cari'))
                                    untuk <em>"{{ request('cari') }}"</em>
                                @endif
                            </p>
                            @if (request('lat') && request('lng'))
                                <p class="text-xs font-bold mt-0.5" style="color: var(--brand);">
                                    <i class="fas fa-location-dot"></i> Menampilkan hasil terdekat dari lokasi Anda
                                </p>
                            @endif
                        </div>

                        {{-- Active filter chips --}}
                        <div class="flex items-center gap-2 flex-wrap">
                            @if (request('city'))
                                <span
                                    class="chip-brand inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                             text-xs font-bold border">
                                    <i class="fas fa-city"></i> Kota terpilih
                                    <a href="{{ route('guest.katalog', request()->except('city')) }}"
                                        class="no-underline opacity-60 hover:opacity-100 ml-0.5">
                                        <i class="fas fa-times text-[0.65rem]"></i>
                                    </a>
                                </span>
                            @endif
                            @if (request('kategori'))
                                @php $activeKat = $kategori->firstWhere('id', request('kategori')); @endphp
                            @endif
                        </div>
                    </div>

                    {{-- PRODUCT GRID --}}
                    <div class="produk-grid" x-show="!$store.wishlist.showOnly || $store.wishlist.count > 0">
                        @forelse ($produk as $p)
                            @include('guest.partials.product-card', ['produk' => $p])
                        @empty
                            <div x-show="!$store.wishlist.showOnly"
                                class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                                <h3 class="font-heading text-xl font-bold text-neutral-900 mb-2">
                                    Produk Tidak Ditemukan
                                </h3>
                                <p class="text-sm text-neutral-400 mb-5">
                                    Coba cari dengan kata kunci lain atau pilih kategori yang berbeda.
                                </p>
                                <a href="{{ route('guest.katalog') }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full
                                           text-white text-sm font-semibold transition-all duration-200"
                                    style="background: var(--brand);"
                                    onmouseover="this.style.background='var(--brand-dark)'"
                                    onmouseout="this.style.background='var(--brand)'">
                                    <i class="fas fa-arrow-left text-xs"></i> Lihat Semua Produk
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Empty wishlist state --}}
                    <div x-show="$store.wishlist.showOnly && $store.wishlist.count === 0"
                        x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" style="display:none"
                        class="flex flex-col items-center justify-center py-20 text-center">
                        <h3 class="font-heading text-xl font-bold text-neutral-900 mb-2">
                            Wishlist Kosong
                        </h3>
                        <p class="text-sm text-neutral-400 mb-5 max-w-xs">
                            Belum ada produk yang kamu simpan. Tekan ♡ pada produk untuk menambahkannya.
                        </p>
                        <button type="button" @click="$store.wishlist.toggleFilter()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full
                                   text-white text-sm font-semibold transition-all duration-200"
                            style="background: var(--brand);" onmouseover="this.style.background='var(--brand-dark)'"
                            onmouseout="this.style.background='var(--brand)'">
                            <i class="fas fa-arrow-left text-xs"></i> Lihat Semua Produk
                        </button>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-14 flex justify-center">
                        {{ $produk->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                const extend = () => {
                    const store = Alpine.store('wishlist');
                    if (!store) return setTimeout(extend, 50);

                    if (typeof store.showOnly === 'undefined') {
                        store.showOnly = false;
                    }
                    if (typeof store.toggleFilter === 'undefined') {
                        store.toggleFilter = function() {
                            this.showOnly = !this.showOnly;
                        };
                    }
                };
                extend();
            });
        </script>
        <script>
            (function() {
                'use strict';

                const btn = document.getElementById('filterToggle');
                const card = document.getElementById('filterCard');
                const arrow = document.getElementById('filterArrow');
                if (!btn || !card) return;

                const LG_BP = 1024;

                function isMobile() {
                    return window.innerWidth < LG_BP;
                }

                function setOpen(open) {
                    card.classList.toggle('is-open', open);
                    card.classList.toggle('hidden', !open);
                    btn.setAttribute('aria-expanded', String(open));
                    if (arrow) arrow.style.transform = open ? 'rotate(180deg)' : '';
                }

                @if (request('kategori') || request('cari') || request('city'))
                    if (isMobile()) setOpen(true);
                @endif

                btn.addEventListener('click', () => setOpen(!card.classList.contains('is-open')));

                window.addEventListener('resize', () => {
                    if (!isMobile()) {
                        card.classList.remove('is-open', 'hidden');
                        btn.setAttribute('aria-expanded', 'false');
                        if (arrow) arrow.style.transform = '';
                    }
                });
            })();
        </script>
    @endpush

@endsection
