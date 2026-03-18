@extends('layouts.guest')

@section('title', $produk->nama_produk)

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <style>
        :root {
            --brand: #1a3199;
            --brand-dark: #152780;
            --brand-soft: #eef1fb;
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* ── GALLERY ── */
        .main-swiper {
            width: 100%;
            height: 0;
            padding-bottom: 100%; /* Rock-solid 1:1 Ratio */
            border-radius: 24px;
            overflow: hidden;
            display: block;
            position: relative;
            background: #f8fafc;
        }

        .main-swiper .swiper-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .main-swiper .swiper-slide {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-swiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Fill the square completely */
            border-radius: 24px;
        }

        .thumb-swiper .swiper-slide {
            width: 72px !important;
            height: 72px;
            border-radius: 14px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            opacity: 0.5;
            transition: all 0.3s var(--ease);
            background: #f8fafc;
        }

        .thumb-swiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumb-swiper .swiper-slide-thumb-active {
            border-color: var(--brand);
            opacity: 1;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -4px rgba(26,49,153,0.25);
        }

        .thumb-swiper-container {
            padding-top: 0;
            margin-top: -1.25rem;
            display: flex;
            justify-content: center;
            width: 100%;
            position: relative;
            z-index: 10;
        }

        .thumb-swiper {
            width: 100%;
            max-width: fit-content;
            margin: 0;
        }

        .main-swiper .swiper-button-next,
        .main-swiper .swiper-button-prev {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(8px);
            border-radius: 50%;
            color: #1e293b;
            transition: all 0.3s var(--ease);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: none;
            margin: 0 10px;
        }

        .main-swiper .swiper-button-next::after,
        .main-swiper .swiper-button-prev::after {
            font-size: 0.85rem;
            font-weight: 800;
        }

        .main-swiper .swiper-button-next:hover,
        .main-swiper .swiper-button-prev:hover {
            background: white;
            color: var(--brand);
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0,0,0,0.18);
        }

        @media (max-width: 480px) {
            .main-swiper .swiper-button-next,
            .main-swiper .swiper-button-prev { display: none; }
        }

        /* ── SHOP BADGE ── */
        .shop-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: var(--brand-soft);
            color: var(--brand);
            font-weight: 700;
            font-size: 0.78rem;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            width: fit-content;
            transition: all 0.2s var(--ease);
        }

        .shop-badge:hover {
            background: var(--brand);
            color: white;
        }

        /* ── PRICE ── */
        .price-big {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--brand);
            line-height: 1;
        }

        /* ── BUY BUTTON ── */
        .buy-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            width: 100%;
            padding: 0.9rem;
            background: var(--brand);
            color: white;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(26,49,153,0.22);
            transition: all 0.3s var(--ease);
        }

        .buy-button:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(26,49,153,0.32);
            color: white;
        }

        /* ── SELLER LINK ── */
        .seller-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--brand);
            text-decoration: none;
            transition: gap 0.2s;
        }

        .seller-link:hover { gap: 0.65rem; color: var(--brand-dark); }

        /* ── RELATED GRID ── */
        .produk-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.25rem;
        }

        @media (max-width: 640px) {
            .produk-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
            .price-big { font-size: 1.6rem; }
        }
    </style>
@endpush

@section('content')

    {{-- Memberikan padding top agar tidak tertutup Navbar yang Fixed --}}
    <div class="pt-0">

    {{-- BREADCRUMB STRIP --}}
    <div class="bg-white border-b border-neutral-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
            <nav class="flex items-center gap-2 text-sm font-semibold flex-wrap">
                <a href="{{ route('guest.beranda') }}"
                   class="no-underline transition-colors duration-200 hover:underline"
                   style="color: var(--brand);">Beranda</a>
                <i class="fas fa-chevron-right text-neutral-300" style="font-size: 0.65rem;"></i>
                <a href="{{ route('guest.katalog') }}"
                   class="no-underline transition-colors duration-200 hover:underline"
                   style="color: var(--brand);">Katalog</a>
                <i class="fas fa-chevron-right text-neutral-300" style="font-size: 0.65rem;"></i>
                <span class="text-neutral-600 font-bold line-clamp-1">{{ $produk->nama_produk }}</span>
            </nav>
        </div>
    </div>

    <div class="bg-neutral-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 pb-24">

            {{-- PRODUCT GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start mb-16">

                {{-- Kolom Kiri: Gallery --}}
                <div class="lg:sticky lg:top-20">
                    @php
                        $fotos    = $produk->foto_produk;
                        $hasFotos = is_array($fotos) && count($fotos) > 0;
                    @endphp

                    <div class="swiper main-swiper mb-0">
                        <div class="swiper-wrapper">
                            @if ($hasFotos)
                                @foreach ($fotos as $f)
                                    <div class="swiper-slide">
                                        <a href="{{ Storage::url($f) }}" class="glightbox"
                                            data-gallery="product-gallery">
                                            <img src="{{ Storage::url($f) }}" alt="{{ $produk->nama_produk }}">
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="swiper-slide">
                                    @if ($setting?->logo_expo)
                                        <img src="{{ asset('storage/' . $setting->logo_expo) }}"
                                            alt="Placeholder"
                                            style="opacity:0.1; filter:grayscale(1); width:55%; object-fit:contain;">
                                    @else
                                        <span style="font-size:5rem;">🛍️</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>

                    @if ($hasFotos && count($fotos) > 1)
                        <div class="thumb-swiper-container">
                            <div class="swiper thumb-swiper">
                                <div class="swiper-wrapper">
                                    @foreach ($fotos as $f)
                                        <div class="swiper-slide">
                                            <img src="{{ Storage::url($f) }}" alt="{{ $produk->nama_produk }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Info --}}
                <div class="flex flex-col gap-4">

                    <a href="{{ route('guest.detail-umkm', $produk->umkm->uuid ?? '#') }}" class="shop-badge">
                        <i class="fas fa-store text-xs"></i>
                        {{ $produk->umkm->nama_usaha ?? 'UMKM Mitra' }}
                    </a>

                    <h1 class="font-heading text-2xl md:text-3xl font-bold text-neutral-900 leading-snug">
                        {{ $produk->nama_produk }}
                    </h1>

                    {{-- Harga, Deskripsi & CTA --}}
                    <div class="bg-white rounded-3xl border border-neutral-100 shadow-[0_10px_30px_-5px_rgba(0,0,0,0.05)] overflow-hidden">
                        {{-- Harga Section --}}
                        <div class="px-6 pt-6 pb-5 border-b border-dashed border-neutral-100">
                            <p class="text-[0.65rem] font-black uppercase tracking-widest text-neutral-400 mb-2">
                                Harga Produk
                            </p>
                            <div class="price-big">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                        </div>

                        {{-- Deskripsi In-Card Section --}}
                        <div class="px-6 py-5">
                            <p class="text-[0.65rem] font-black uppercase tracking-widest text-neutral-400 mb-3">
                                Deskripsi Produk
                            </p>
                            <p class="text-sm text-neutral-600 leading-relaxed mb-5">
                                {!! nl2br(e($produk->deskripsi_produk ?? ($produk->deskripsi ?? 'Tidak ada deskripsi tersedia.'))) !!}
                            </p>
                            
                            {{-- Attributes --}}
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="inline-flex items-center gap-1.5 text-[0.7rem] font-bold text-neutral-600
                                             bg-neutral-50 px-3 py-1.5 rounded-xl border border-neutral-100">
                                    <i class="fas fa-box text-xs" style="color:var(--brand);"></i>
                                    {{ $produk->kategori_satuan ?? ($produk->unit ?? '-') }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-[0.7rem] font-bold text-neutral-600
                                             bg-neutral-50 px-3 py-1.5 rounded-xl border border-neutral-100">
                                    <i class="fas fa-tag text-xs" style="color:var(--brand);"></i>
                                    {{ $produk->umkm->kategori->nama ?? '-' }}
                                </span>
                            </div>

                            {{-- CTA Button --}}
                            <a href="{{ route('guest.checkout', $produk->uuid) }}" class="buy-button">
                                <i class="fas fa-shopping-cart text-sm"></i>
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>

                    {{-- Seller --}}
                    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-5">
                        <p class="text-[0.65rem] font-bold uppercase tracking-widest mb-1"
                           style="color: var(--brand);">Penjual</p>
                        <h3 class="font-heading text-lg font-bold text-neutral-900 mb-2">
                            {{ $produk->umkm->nama_usaha ?? '-' }}
                        </h3>
                        <div class="flex items-start gap-2 text-xs text-neutral-500 mb-3">
                            <i class="fas fa-map-marker-alt text-red-400 mt-0.5 flex-shrink-0"></i>
                            <span>{{ $produk->umkm->alamat_lengkap ?? ($produk->umkm->city->name ?? 'Indonesia') }}</span>
                        </div>
                        <a href="{{ route('guest.detail-umkm', $produk->umkm->uuid ?? '#') }}" class="seller-link">
                            Lihat Profil Lengkap <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                </div>
            </div>

            {{-- RELATED PRODUCTS --}}
            @if ($related->count() > 0)
                <div>
                    <h2 class="font-heading text-xl font-bold text-neutral-900 mb-5">
                        Produk Lainnya dari Toko Ini
                    </h2>
                    <div class="produk-grid">
                        @foreach ($related as $rel)
                            @include('guest.partials.product-card', ['produk' => $rel])
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    </div>{{-- end pt-[104px] wrapper --}}

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                zoomable: true,
            });

            const thumbEl = document.querySelector('.thumb-swiper');
            let thumbSwiper = null;

            if (thumbEl) {
                thumbSwiper = new Swiper('.thumb-swiper', {
                    spaceBetween: 10,
                    slidesPerView: 'auto',
                    freeMode: true,
                    watchSlidesProgress: true,
                });
            }

            new Swiper('.main-swiper', {
                loop: true,
                spaceBetween: 0,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: { swiper: thumbSwiper },
            });

        });
    </script>
@endpush