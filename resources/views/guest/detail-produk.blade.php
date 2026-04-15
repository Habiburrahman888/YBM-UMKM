@extends('layouts.guest')

@section('title', $produk->nama_produk)

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <style>
        :root {
            --brand: #1a3199;
            --brand-dark: #152780;
            --brand-soft: #f0f4ff;
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* ── GALLERY ── */
        .main-swiper {
            width: 100%;
            height: 0;
            padding-bottom: 75%;
            /* 4:3 Aspect Ratio for better product display */
            border-radius: 32px;
            overflow: hidden;
            display: block;
            position: relative;
            background: #f8fafc;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
        }

        .main-swiper .swiper-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .main-swiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 32px;
        }

        .thumb-swiper .swiper-slide {
            width: 80px !important;
            height: 80px;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            opacity: 0.6;
            transition: all 0.3s var(--ease);
            background: #f1f5f9;
        }

        .thumb-swiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumb-swiper .swiper-slide-thumb-active {
            border-color: var(--brand);
            opacity: 1;
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -5px rgba(26, 49, 153, 0.3);
        }

        .thumb-swiper-container {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
        }

        .main-swiper .swiper-button-next,
        .main-swiper .swiper-button-prev {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 50%;
            color: #1e293b;
            transition: all 0.3s var(--ease);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .main-swiper .swiper-button-next::after,
        .main-swiper .swiper-button-prev::after {
            font-size: 1rem;
            font-weight: 900;
        }

        .main-swiper .swiper-button-next:hover,
        .main-swiper .swiper-button-prev:hover {
            background: white;
            color: var(--brand);
            transform: scale(1.1);
        }

        /* ── BADGES ── */
        .shop-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--brand-soft);
            color: var(--brand);
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.7rem;
            padding: 0.4rem 0.9rem;
            border-radius: 12px;
        }

        /* ── TEXT STYLES ── */
        .section-label {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.075em;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            display: block;
        }

        .price-big {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--brand);
            letter-spacing: -0.02em;
        }

        /* ── BUTTON ── */
        .buy-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 1.1rem;
            background: var(--brand);
            color: white;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 12px 24px -6px rgba(26, 49, 153, 0.4);
            transition: all 0.3s var(--ease);
        }

        .buy-button:hover {
            background: var(--brand-dark);
            transform: translateY(-3px);
            box-shadow: 0 16px 32px -8px rgba(26, 49, 153, 0.5);
            color: white;
        }

        .seller-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            padding: 1.5rem;
            box-shadow: 0 4px 20px -10px rgba(0, 0, 0, 0.05);
        }

        .seller-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--brand);
            text-decoration: none;
            transition: all 0.2s;
        }

        .seller-link:hover {
            gap: 0.6rem;
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .price-big {
                font-size: 1.8rem;
            }

            .main-swiper {
                border-radius: 24px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="bg-[#fcfdfe] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-10">

            {{-- Breadcrumb --}}
            <nav
                class="flex items-center gap-2 text-[0.7rem] font-bold uppercase tracking-wider text-slate-400 mb-8 overflow-x-auto whitespace-nowrap pb-2 no-scrollbar">
                <a href="{{ route('guest.beranda') }}" class="hover:text-brand transition-colors">Beranda</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('guest.katalog') }}" class="hover:text-brand transition-colors">Katalog</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-600">{{ $produk->nama_produk }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">

                {{-- Kolom Kiri: Visual --}}
                <div class="lg:col-span-7">
                    @php
                        $fotos = $produk->foto_produk;
                        $hasFotos = is_array($fotos) && count($fotos) > 0;
                    @endphp

                    <div class="swiper main-swiper">
                        <div class="swiper-wrapper">
                            @if ($hasFotos)
                                @foreach ($fotos as $f)
                                    <div class="swiper-slide">
                                        <a href="{{ Storage::url($f) }}" class="glightbox" data-gallery="product">
                                            <img src="{{ Storage::url($f) }}" alt="{{ $produk->nama_produk }}">
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="swiper-slide bg-slate-50 flex items-center justify-center p-20">
                                    <i class="fas fa-shopping-bag text-slate-200 text-8xl"></i>
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

                {{-- Kolom Kanan: Detail --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="space-y-4">
                        <a href="{{ route('guest.detail-umkm', $produk->umkm->uuid ?? '#') }}" class="shop-badge">
                            <i class="fas fa-store"></i>
                            {{ $produk->umkm->nama_usaha ?? 'Toko UMKM' }}
                        </a>

                        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight tracking-tight">
                            {{ $produk->nama_produk }}
                        </h1>
                    </div>

                    {{-- Card Utama --}}
                    <div
                        class="bg-white rounded-[32px] border border-slate-100 shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] p-6 sm:p-8 space-y-8">
                        <div>
                            <span class="section-label">Harga Produk</span>
                            <div class="price-big">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                        </div>

                        <div>
                            <span class="section-label">Deskripsi Produk</span>
                            <div class="text-slate-600 text-sm leading-relaxed max-w-none">
                                {!! nl2br(e($produk->deskripsi_produk ?? 'Tidak ada deskripsi.')) !!}
                            </div>
                        </div>

                        <div>
                            <span class="section-label">Stok Tersedia</span>
                            <div class="text-lg font-bold {{ $produk->stok > 0 ? 'text-slate-900' : 'text-red-500' }}">
                                {{ $produk->stok }} {{ $produk->kategori_satuan }}
                                @if ($produk->stok <= 0)
                                    <span class="text-xs font-normal ml-2">(Habis)</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @if ($produk->kategori_satuan)
                                <span class="category-badge">
                                    <i class="fas fa-box text-blue-400"></i>
                                    {{ $produk->kategori_satuan }}
                                </span>
                            @endif
                            <span class="category-badge">
                                <i class="fas fa-tag text-blue-400"></i>
                                {{ $produk->umkm->kategori->nama ?? 'Umum' }}
                            </span>
                        </div>

                        @if ($produk->stok > 0)
                            <a href="{{ route('guest.checkout', $produk->uuid) }}" class="buy-button">
                                <i class="fas fa-shopping-cart"></i>
                                Pesan Sekarang
                            </a>
                        @else
                            <button class="buy-button opacity-50 cursor-not-allowed" disabled>
                                <i class="fas fa-times-circle"></i>
                                Stok Habis
                            </button>
                        @endif
                    </div>

                    {{-- Seller Card --}}
                    <div class="seller-card space-y-4">
                        <div>
                            <span class="section-label !text-brand">Penjual</span>
                            <h3 class="text-lg font-bold text-slate-900">{{ $produk->umkm->nama_usaha }}</h3>
                        </div>

                        <div class="flex gap-3 text-sm text-slate-500">
                            <i class="fas fa-map-marker-alt text-red-400 mt-1"></i>
                            <span class="leading-snug">{{ $produk->umkm->alamat_lengkap }}</span>
                        </div>

                        <a href="{{ route('guest.detail-umkm', $produk->umkm->uuid ?? '#') }}" class="seller-link">
                            Lihat Profil Lengkap <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>

            {{-- Produk Lainnya --}}
            @if ($related->count() > 0)
                <div class="mt-20 space-y-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-slate-900">Produk Lainnya dari Toko Ini</h2>
                        <div class="h-1 flex-1 bg-slate-100 mx-6 rounded-full hidden sm:block"></div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
                        @foreach ($related as $rel)
                            @include('guest.partials.product-card', ['produk' => $rel])
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lightbox
            const lightbox = GLightbox({
                selector: '.glightbox'
            });

            // Swiper Thumbs
            const thumbSwiper = new Swiper('.thumb-swiper', {
                spaceBetween: 12,
                slidesPerView: 'auto',
                freeMode: true,
                watchSlidesProgress: true,
            });

            // Swiper Main
            new Swiper('.main-swiper', {
                loop: true,
                spaceBetween: 0,
                speed: 800,
                autoplay: {
                    delay: 4000
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: thumbSwiper
                },
            });
        });
    </script>
@endpush
