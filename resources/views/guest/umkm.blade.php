@extends('layouts.guest')

@section('title', 'Daftar Mitra UMKM')

@push('styles')
    <style>
        :root {
            --umkm-ease: cubic-bezier(0.22, 1, 0.36, 1);
            --brand: #1a3199;
            --brand-dark: #152780;
            --brand-soft: #eef1fb;
        }

        .umkm-page {
            background: #f8fafc;
            min-height: 60vh;
        }

        .umkm-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        @media (max-width: 1280px) {
            .umkm-grid {
                grid-template-columnxs: repeat(3, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .umkm-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .umkm-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
        }

        @media (max-width: 400px) {
            .umkm-grid {
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

        #filterCardUmkm.is-open {
            display: block !important;
        }

        /* ── Search focus ring biru brand ── */
        .search-wrap:focus-within {
            border-color: var(--brand) !important;
            box-shadow: 0 4px 24px rgba(26, 49, 153, 0.13) !important;
        }

        /* ── Sidebar filter pill active ── */
        .nav-pill-active {
            background: var(--brand);
            color: white !important;
        }

        /* ── UMKM Card ── */
        .umkm-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.04);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            position: relative;
            transition: all 0.35s var(--umkm-ease);
        }

        .umkm-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--brand);
            opacity: 0;
            transition: opacity 0.35s var(--umkm-ease);
        }

        .umkm-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(26, 49, 153, 0.18);
            border-color: transparent;
        }

        .umkm-card:hover::before {
            opacity: 1;
        }

        .umkm-logo {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            overflow: hidden;
            background: var(--brand-soft);
            border: 2px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
            transition: transform 0.35s var(--umkm-ease);
        }

        .umkm-card:hover .umkm-logo {
            transform: scale(1.06) rotate(2deg);
        }

        .umkm-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .umkm-cat-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--brand);
            background: var(--brand-soft);
            padding: 0.25rem 0.65rem;
            border-radius: 50px;
        }

        .umkm-card:hover .card-title {
            color: var(--brand);
        }

        .card-title {
            transition: color 0.3s var(--umkm-ease);
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.75rem;
            color: #64748b;
            background: #f8fafc;
            padding: 0.25rem 0.65rem;
            border-radius: 50px;
        }

        .stat-pill i {
            color: var(--brand);
            font-size: 0.65rem;
        }

        .btn-visit {
            width: 100%;
            padding: 0.65rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            background: var(--brand-soft);
            color: var(--brand);
            border: 1px solid rgba(26, 49, 153, 0.12);
            transition: all 0.3s var(--umkm-ease);
        }

        .umkm-card:hover .btn-visit {
            background: var(--brand);
            color: white;
            border-color: var(--brand);
            box-shadow: 0 8px 20px -6px rgba(26, 49, 153, 0.4);
        }

        .btn-visit i {
            transition: transform 0.3s var(--umkm-ease);
        }

        .umkm-card:hover .btn-visit i {
            transform: translateX(3px);
        }

        /* ── Pagination active ── */
        .pagination .page-item.active .page-link {
            background: var(--brand) !important;
            border-color: var(--brand) !important;
        }
    </style>
@endpush

@section('content')

    <div class="umkm-page">

        {{-- PAGE HEADER --}}
        <header class="relative pt-40 pb-40 text-center text-white overflow-hidden">
            <div class="absolute inset-0 z-0"
                style="background-image: linear-gradient(rgba(15,23,42,0.55),rgba(15,23,42,0.55)), url('{{ asset('landing/mitra.jpg') }}'); background-size:cover; background-position:center;">
            </div>
            <div class="absolute inset-0 z-0 opacity-30"
                style="background-image:url(\"data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E\")">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 flex flex-col items-center justify-center">
                <h1
                    class="font-heading text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight mb-3 text-center">
                    Mitra UMKM Nusantara
                </h1>
                <p class="text-sm md:text-base opacity-75 max-w-md mx-auto leading-relaxed text-center">
                    Dukung ribuan mitra binaan {{ $setting->nama_expo ?? 'kami' }} untuk naik kelas dan mandiri
                </p>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            {{-- SEARCH --}}
            <div class="max-w-2xl mx-auto -mt-7 mb-12 relative z-[60]">
                <form action="{{ route('guest.umkm') }}" method="GET"
                    class="search-wrap bg-white flex items-center gap-2 pl-5 pr-2 py-2 rounded-full
                            border border-neutral-200
                            shadow-[0_4px_24px_rgba(0,0,0,0.07),0_1px_4px_rgba(0,0,0,0.04)]
                            transition-all duration-300">

                    @if (request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif

                    <i class="fas fa-search text-neutral-400 text-sm flex-shrink-0"></i>

                    <input type="text" name="cari" placeholder="Cari nama toko atau usaha..."
                        value="{{ request('cari') }}" autocomplete="off"
                        class="flex-1 min-w-0 border-none bg-transparent py-2.5 text-sm font-semibold
                                text-neutral-800 placeholder:font-normal placeholder:text-neutral-400
                                focus:outline-none px-4">

                    <div class="flex items-center gap-2 pr-2">
                        @include('guest.partials.location-selector', ['route' => 'guest.umkm'])

                        <button type="submit"
                            class="flex-shrink-0 inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full
                                    text-white text-sm font-bold whitespace-nowrap min-h-[40px]
                                    transition-all duration-200 focus:outline-none shadow-sm hover:shadow-md"
                            style="background: var(--brand);">
                            <i class="fas fa-search text-xs"></i>
                            <span>Cari</span>
                        </button>
                    </div>
                </form>

                {{-- Region select — mobile --}}
                <form action="{{ route('guest.umkm') }}" method="GET" class="sm:hidden mt-2 flex flex-col gap-2">
                    @if (request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    @if (request('cari'))
                        <input type="hidden" name="cari" value="{{ request('cari') }}">
                    @endif
                    <select name="province" onchange="this.form.submit()"
                        class="w-full py-2.5 px-4 rounded-xl border border-neutral-200 bg-white
                               text-sm text-neutral-400 font-medium outline-none cursor-pointer">
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinces_filter as $prov)
                            <option value="{{ $prov->code }}" {{ request('province') == $prov->code ? 'selected' : '' }}>
                                {{ $prov->name }}
                            </option>
                        @endforeach
                    </select>
                    @if (request()->filled('province'))
                        <select name="city" onchange="this.form.submit()"
                            class="w-full py-2.5 px-4 rounded-xl border border-neutral-200 bg-white animate-in fade-in slide-in-from-top-2 duration-300
                                   text-sm text-neutral-400 font-medium outline-none cursor-pointer">
                            <option value="">Semua Kota</option>
                            @foreach ($cities_filter as $city)
                                <option value="{{ $city->code }}"
                                    {{ request('city') == $city->code ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </form>
            </div>

            {{-- LAYOUT: Sidebar + Konten --}}
            <div class="grid grid-cols-1 lg:grid-cols-[250px_1fr] xl:grid-cols-[260px_1fr] gap-6 pb-24 items-start">

                {{-- SIDEBAR --}}
                <aside class="lg:sticky lg:top-24">

                    {{-- Mobile toggle --}}
                    <button id="filterToggleUmkm" type="button" aria-expanded="false" aria-controls="filterCardUmkm"
                        class="lg:hidden w-full flex justify-between items-center
                                bg-white border border-neutral-200 rounded-2xl px-5 py-3.5
                                font-bold text-sm text-neutral-900 cursor-pointer mb-3
                                transition-colors duration-200"
                        style="hover:border-color: var(--brand)">
                        <span>
                            <i class="fas fa-sliders-h mr-2" style="color: var(--brand);"></i>Filter Mitra
                        </span>
                        <i id="filterArrowUmkm" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>

                    {{-- Filter card --}}
                    <div id="filterCardUmkm"
                        class="hidden lg:block bg-white rounded-2xl border border-neutral-200 overflow-hidden
                                shadow-[0_2px_12px_rgba(0,0,0,0.06)]">

                        <div
                            class="flex items-center gap-2 px-4 py-3.5 border-b border-neutral-100
                                    font-bold text-sm text-neutral-700">
                            <i class="fas fa-sliders-h text-neutral-400"></i> Filter Mitra
                        </div>

                        <div class="p-3">

                            <p class="text-[0.62rem] font-bold uppercase tracking-widest text-neutral-400 mb-1 px-2">
                                Kategori
                            </p>

                            <nav class="flex flex-col gap-0.5" aria-label="Pilih kategori">
                                <a href="{{ route('guest.umkm', request()->only(['cari', 'province', 'city'])) }}"
                                    class="flex justify-between items-center gap-2 px-3 py-2.5 rounded-lg
                                           text-sm font-semibold no-underline transition-all duration-200
                                           {{ !request('kategori') ? 'nav-pill-active' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900' }}">
                                    Semua Mitra
                                </a>
                                @foreach ($kategori as $kat)
                                    <a href="{{ route('guest.umkm', array_merge(request()->only(['cari', 'province', 'city']), ['kategori' => $kat->id])) }}"
                                        class="flex justify-between items-center gap-2 px-3 py-2.5 rounded-lg
                                               text-sm font-semibold no-underline transition-all duration-200
                                               {{ request('kategori') == $kat->id ? 'nav-pill-active' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900' }}">
                                        <span>{{ $kat->nama }}</span>
                                    </a>
                                @endforeach
                            </nav>

                            @if (request('cari') || request('kategori'))
                                <a href="{{ route('guest.umkm') }}"
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
                        <p class="text-sm font-semibold text-neutral-400">
                            Menampilkan
                            <strong class="text-neutral-900 font-extrabold">{{ $umkm->total() }}</strong>
                            mitra
                            @if (request('cari'))
                                untuk <em>"{{ request('cari') }}"</em>
                            @endif
                        </p>

                        @if (request('kategori'))
                            @php $activeKat = $kategori->firstWhere('id', request('kategori')); @endphp
                            @if ($activeKat)
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                                border"
                                    style="background: var(--brand-soft); border-color: rgba(26,49,153,0.15); color: var(--brand);">
                                    <i class="fas fa-tag"></i> {{ $activeKat->nama }}
                                    <a href="{{ route('guest.umkm', request()->except('kategori')) }}"
                                        class="no-underline opacity-60 hover:opacity-100 ml-0.5"
                                        style="color: var(--brand);">
                                        <i class="fas fa-times text-[0.65rem]"></i>
                                    </a>
                                </span>
                            @endif
                        @endif
                    </div>

                    {{-- UMKM GRID --}}
                    <div class="umkm-grid">
                        @forelse($umkm as $u)
                            <a href="{{ route('guest.detail-umkm', $u->uuid) }}" class="umkm-card">

                                {{-- Header --}}
                                <div class="flex items-start justify-between p-5 pb-3">
                                    <div class="umkm-logo">
                                        @if ($u->logo_umkm)
                                            <img src="{{ Storage::url($u->logo_umkm) }}" alt="{{ $u->nama_usaha }}">
                                        @elseif ($setting?->logo_expo)
                                            <img src="{{ asset('storage/' . $setting->logo_expo) }}" alt="Placeholder"
                                                style="opacity: 0.4; filter: grayscale(0.5);">
                                        @else
                                            <span>🏪</span>
                                        @endif
                                    </div>
                                    <span class="umkm-cat-badge">
                                        <i class="fas fa-circle" style="font-size: 0.35rem;"></i>
                                        {{ $u->kategori->nama ?? 'UMKM' }}
                                    </span>
                                </div>

                                {{-- Body --}}
                                <div class="flex-1 px-5 pb-3">
                                    <h3
                                        class="card-title font-heading text-base font-bold text-neutral-900
                                                leading-snug mb-1.5">
                                        {{ $u->nama_usaha }}
                                    </h3>

                                    @if ($u->deskripsi)
                                        <p class="text-xs text-neutral-400 leading-relaxed mb-3 line-clamp-2">
                                            {{ Str::limit($u->deskripsi, 80) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="stat-pill">
                                            <i class="fas fa-box"></i>
                                            {{ $u->produk_umkm_count ?? '0' }} Produk
                                        </span>
                                    </div>

                                    @if ($u->alamat_lengkap)
                                        <div
                                            class="flex items-start gap-1.5 border-t border-dashed border-neutral-100 pt-3">
                                            <i class="fas fa-map-marker-alt text-red-400 text-xs mt-0.5 flex-shrink-0"></i>
                                            <span class="text-xs text-neutral-400 font-medium leading-snug line-clamp-2">
                                                {{ $u->alamat_lengkap }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Footer --}}
                                <div class="p-5 pt-3">
                                    <div class="btn-visit">
                                        Kunjungi Toko
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </div>
                                </div>

                            </a>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                                <div class="w-20 h-20 rounded-full flex items-center justify-center text-3xl mb-5"
                                    style="background: var(--brand-soft);">🏪</div>
                                <h3 class="font-heading text-xl font-bold text-neutral-900 mb-2">
                                    Mitra Tidak Ditemukan
                                </h3>
                                <p class="text-sm text-neutral-400 mb-5 max-w-xs leading-relaxed">
                                    Coba cari dengan kata kunci lain atau pilih kategori yang berbeda.
                                </p>
                                <a href="{{ route('guest.umkm') }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full
                                            text-white text-sm font-semibold transition-all duration-200"
                                    style="background: var(--brand);"
                                    onmouseover="this.style.background='var(--brand-dark)'"
                                    onmouseout="this.style.background='var(--brand)'">
                                    <i class="fas fa-arrow-left text-xs"></i> Lihat Semua Mitra
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-14 flex justify-center">
                        {{ $umkm->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        (function() {
            'use strict';

            const btn = document.getElementById('filterToggleUmkm');
            const card = document.getElementById('filterCardUmkm');
            const arrow = document.getElementById('filterArrowUmkm');
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

            @if (request('kategori') || request('cari'))
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
