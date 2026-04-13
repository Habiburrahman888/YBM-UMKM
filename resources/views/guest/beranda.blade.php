@extends('layouts.guest')

@section('title', 'Beranda')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-display {
            font-family: 'DM Serif Display', Georgia, serif;
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .65s ease, transform .65s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .section-eyebrow {
            display: inline-block;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: #3b82f6;
            margin-bottom: .75rem;
        }

        /* ══ CAROUSEL ══ */
        #carousel-outer {
            overflow: hidden;
        }

        #carousel-track {
            display: flex;
            gap: 16px;
            transition: transform .42s cubic-bezier(.4, 0, .2, 1);
            will-change: transform;
            align-items: stretch;
        }

        /* Setiap slot kartu: lebar fixed, tinggi stretch */
        .carousel-card {
            flex: 0 0 200px;
            width: 200px;
            min-width: 0;
            display: flex;
            /* agar produk-card bisa h-full */
        }

        /* produk-card mengisi penuh slot */
        .carousel-card>.produk-card {
            width: 100%;
        }

        .carousel-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .09);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s, opacity .2s;
        }

        .carousel-nav-btn:hover {
            background: #f3f4f6;
        }

        .carousel-nav-btn.disabled {
            opacity: .25;
            pointer-events: none;
        }

        #carousel-prev {
            left: -18px;
        }

        #carousel-next {
            right: -18px;
        }
    </style>
@endpush

@section('content')

    @include('partials.guest.hero')

    {{-- MARQUEE --}}
    <div class="bg-white border-b border-neutral-100 py-5 select-none overflow-hidden"
        style="position:relative;z-index:20;isolation:isolate;transform:translate3d(0,0,0);will-change:transform;">
        <div id="marquee-outer" style="overflow:hidden;width:100%;position:relative;">
            <div id="marquee-track" style="display:flex;align-items:center;will-change:transform;white-space:nowrap;">
                @php $items = $kategori->pluck('nama')->toArray(); @endphp
                @foreach ($items as $item)
                    <div style="display:inline-flex;align-items:center;flex-shrink:0;">
                        <span class="marquee-label">{{ $item }}</span>
                        <span class="marquee-dot"></span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <style>
        .marquee-label {
            padding: 0 2.8rem;
            font-weight: 700;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: .18em;
            color: #525252;
            transition: color .25s;
            cursor: default;
            white-space: nowrap;
        }

        .marquee-label:hover {
            color: #3b82f6;
        }

        .marquee-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(59, 130, 246, .4);
            flex-shrink: 0;
        }
    </style>
    <script>
        (function() {
            const track = document.getElementById('marquee-track');
            if (!track) return;
            const original = track.innerHTML;
            track.innerHTML = original + original + original;
            const speed = 0.6;
            let x = 0;
            const oneThird = track.scrollWidth / 3;

            function tick() {
                x -= speed;
                if (Math.abs(x) >= oneThird) x = 0;
                track.style.transform = `translate3d(${x}px,0,0)`;
                requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        })();
    </script>

    {{-- STATS --}}
    <div class="stats-wave-wrap" id="stats-section">
        <canvas id="stats-snow"
            style="position:absolute;inset:0;width:100%;height:100%;z-index:2;pointer-events:none;"></canvas>
        <div class="wave-container">
            <svg class="wave wave1" viewBox="0 0 1440 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0,60 C120,100 240,20 360,60 C480,100 600,20 720,60 C840,100 960,20 1080,60 C1200,100 1320,20 1440,60 L1440,0 L0,0 Z"
                    fill="white" />
            </svg>
        </div>
        <div class="stats-inner">
            <h3 class="stats-heading">{{ $setting->nama_expo ?? 'YBM PLN UMKM' }} <em>dalam Angka</em></h3>
            <div class="stats-grid">
                @foreach ([['num' => $totalUmkm, 'sup' => '+', 'label' => 'UMKM Terdaftar', 'desc' => 'UMKM binaan aktif bersama YBM PLN'], ['num' => $totalProduk, 'sup' => '+', 'label' => 'Produk Dijual', 'desc' => 'Produk unggulan dari seluruh Indonesia'], ['num' => $totalKategori, 'sup' => '+', 'label' => 'Kategori Produk', 'desc' => 'Kategori produk pilihan berkualitas'], ['num' => 34, 'sup' => '', 'label' => 'Provinsi Indonesia', 'desc' => 'Jangkauan UMKM di seluruh nusantara']] as $i => $stat)
                    <div class="stats-col">
                        @if ($i > 0)
                            <div class="stats-sep"></div>
                        @endif
                        <div class="stats-col-inner">
                            <span class="stats-num" data-target="{{ $stat['num'] }}">
                                <span class="count-val">0</span><span class="stats-sup">{{ $stat['sup'] }}</span>
                            </span>
                            <span class="stats-label">{{ $stat['label'] }}</span>
                            <span class="stats-desc">{{ $stat['desc'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="wave-container wave-bottom-wrap">
            <svg class="wave wave3" viewBox="0 0 1440 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0,40 C120,0 240,80 360,40 C480,0 600,80 720,40 C840,0 960,80 1080,40 C1200,0 1320,80 1440,40 L1440,100 L0,100 Z"
                    fill="white" />
            </svg>
        </div>
    </div>
    <style>
        .stats-wave-wrap {
            position: relative;
            background: linear-gradient(160deg, #1e3a8a 0%, #1e40af 50%, #1d4ed8 100%);
            overflow: hidden;
        }

        .stats-wave-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(147, 197, 253, .08) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
            z-index: 1;
        }

        .wave-container {
            position: relative;
            line-height: 0;
            height: 100px;
        }

        .wave-bottom-wrap {
            margin-top: 0;
        }

        .wave {
            position: absolute;
            left: 0;
            width: 200%;
            height: 100%;
            display: block;
        }

        .wave-container:first-child .wave {
            top: 0;
            bottom: auto;
        }

        .wave-container:last-child .wave {
            bottom: 0;
            top: auto;
        }

        .wave1 {
            animation: waveSweep 12s linear infinite;
            z-index: 3;
        }

        .wave3 {
            animation: waveSweep 14s linear infinite reverse;
            z-index: 3;
        }

        @keyframes waveSweep {
            0% {
                transform: translateX(0)
            }

            100% {
                transform: translateX(-50%)
            }
        }

        .stats-inner {
            position: relative;
            z-index: 10;
            padding: 1.5rem 1rem 2.5rem;
            max-width: 80rem;
            margin: 0 auto;
            text-align: center;
        }

        @media(min-width:640px) {
            .stats-inner {
                padding: 1.5rem 1.5rem 2.5rem;
            }
        }

        .stats-heading {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: clamp(1.85rem, 3.5vw, 2.65rem);
            font-weight: 400;
            color: #fff;
            margin-bottom: 3rem;
        }

        .stats-heading em {
            font-style: italic;
            color: #93c5fd;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            position: relative;
        }

        @media(max-width:768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem 0;
            }

            .stats-sep {
                display: none;
            }
        }

        .stats-col {
            position: relative;
            display: flex;
            align-items: stretch;
        }

        .stats-sep {
            position: absolute;
            left: 0;
            top: 15%;
            bottom: 15%;
            width: 1px;
            background: rgba(255, 255, 255, .12);
        }

        .stats-col-inner {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .35rem;
            padding: 0 1rem;
        }

        .stats-num {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: clamp(3.5rem, 5.5vw, 5rem);
            font-weight: 400;
            color: #fff;
            line-height: 1;
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .7s ease, transform .7s ease;
        }

        .stats-sup {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .45em;
            font-weight: 700;
            color: #93c5fd;
            vertical-align: super;
            line-height: 0;
        }

        .stats-label {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .95rem;
            font-weight: 700;
            color: #fff;
            text-align: center;
            opacity: 0;
            transition: opacity .7s ease .2s;
        }

        .stats-desc {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .85rem;
            color: rgba(255, 255, 255, .45);
            text-align: center;
            line-height: 1.5;
            max-width: 160px;
            opacity: 0;
            transition: opacity .7s ease .35s;
        }
    </style>
    <script>
        (function() {
            const section = document.getElementById('stats-section');
            if (!section) return;

            function easeOutQuart(t) {
                return 1 - Math.pow(1 - t, 4);
            }
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    observer.unobserve(entry.target);
                    entry.target.querySelectorAll('.stats-num').forEach((el, i) => {
                        const target = parseInt(el.dataset.target) || 0;
                        setTimeout(() => {
                            el.style.opacity = '1';
                            el.style.transform = 'translateY(0)';
                            el.closest('.stats-col-inner').querySelector('.stats-label')
                                .style.opacity = '1';
                            el.closest('.stats-col-inner').querySelector('.stats-desc')
                                .style.opacity = '1';
                            const countEl = el.querySelector('.count-val');
                            const start = performance.now();
                            (function tick(now) {
                                const p = Math.min((now - start) / 1800, 1);
                                countEl.textContent = Math.floor(easeOutQuart(p) *
                                    target).toLocaleString('id-ID');
                                if (p < 1) requestAnimationFrame(tick);
                                else countEl.textContent = target.toLocaleString(
                                    'id-ID');
                            })(performance.now());
                        }, i * 150);
                    });
                });
            }, {
                threshold: 0.3
            });
            observer.observe(section);
        })();
        (function() {
            const canvas = document.getElementById('stats-snow');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            function resize() {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            }
            resize();
            window.addEventListener('resize', resize);
            const flakes = Array.from({
                length: 80
            }, () => ({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                r: Math.random() * 2.5 + .5,
                speed: Math.random() * .5 + .15,
                drift: (Math.random() - .5) * .25,
                alpha: Math.random() * .45 + .1
            }));

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                flakes.forEach(f => {
                    ctx.beginPath();
                    ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255,255,255,${f.alpha})`;
                    ctx.fill();
                    f.y += f.speed;
                    f.x += f.drift;
                    if (f.y > canvas.height + 4) {
                        f.y = -4;
                        f.x = Math.random() * canvas.width;
                    }
                    if (f.x > canvas.width + 4) f.x = -4;
                    if (f.x < -4) f.x = canvas.width + 4;
                });
                requestAnimationFrame(draw);
            }
            draw();
        })();
    </script>

    {{-- ══ PRODUK ══ --}}
    @php
        $currentKategori = $kategori->firstWhere('id', request('kategori'));
        $activeCatName = $currentKategori ? $currentKategori->nama : 'all';
    @endphp

    <section id="produk" class="bg-white pb-28 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            {{-- Section Head --}}
            <div class="flex items-center justify-between flex-wrap gap-5 pt-20 mb-6 reveal relative z-[60]">
                <div>
                    <h2 class="font-display font-light text-neutral-900 leading-tight"
                        style="font-size:clamp(1.75rem,2.8vw,2.25rem);">
                        Produk <em class="italic" style="color:#1e40af;">Pilihan UMKM</em>
                    </h2>
                    <p class="text-neutral-400 text-base mt-1 font-normal">Belanja produk autentik berkualitas dari
                        pengusaha lokal terpilih</p>
                </div>
                <div class="flex items-center gap-3">
                    @include('guest.partials.location-selector', ['route' => 'guest.beranda'])
                    <a href="{{ route('guest.katalog') }}"
                        class="h-10 px-5 inline-flex items-center bg-neutral-900 text-white text-sm font-bold rounded-full hover:bg-neutral-800 transition-all whitespace-nowrap shadow-sm">
                        Semua Produk
                    </a>
                </div>
            </div>

            {{-- Filter Pills --}}
            <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-3 mb-6">
                <a href="{{ route('guest.beranda', array_merge(request()->query(), ['kategori' => '', 'section' => 'produk'])) }}#produk"
                    class="shrink-0 h-9 px-4 inline-flex items-center rounded-full border text-sm font-semibold whitespace-nowrap transition-all
                           {{ $activeCatName === 'all' ? 'bg-neutral-900 text-white border-neutral-900' : 'bg-white text-neutral-500 border-neutral-200 hover:border-neutral-400' }}">
                    Semua
                </a>
                @foreach ($kategori as $kat)
                    <a href="{{ route('guest.beranda', array_merge(request()->query(), ['kategori' => $kat->id, 'section' => 'produk'])) }}#produk"
                        class="shrink-0 h-9 px-4 inline-flex items-center rounded-full border text-sm font-semibold whitespace-nowrap transition-all
                               {{ $activeCatName === $kat->nama ? 'bg-neutral-900 text-white border-neutral-900' : 'bg-white text-neutral-500 border-neutral-200 hover:border-neutral-400' }}">
                        {{ $kat->nama }}
                    </a>
                @endforeach
            </div>

            {{-- Carousel --}}
            <div class="relative reveal" style="padding:0 22px;">

                <button id="carousel-prev" class="carousel-nav-btn" aria-label="Sebelumnya">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </button>

                <div id="carousel-outer">
                    <div id="carousel-track">
                        @forelse($produk as $p)
                            <div class="carousel-card" data-cat="{{ $p->umkm->kategori->nama ?? '' }}">
                                @include('guest.partials.product-card', ['produk' => $p])
                            </div>
                        @empty
                            <div class="w-full py-24 text-center">
                                <p class="font-display font-light text-2xl text-neutral-300 mb-2">Belum ada produk</p>
                                <p class="text-base text-neutral-400">Kami sedang mengkurasi produk terbaik untuk Anda.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <button id="carousel-next" class="carousel-nav-btn" aria-label="Berikutnya">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </button>

            </div>

            <div id="carousel-empty" style="display:none;" class="py-16 text-center">
                <p class="font-display font-light text-2xl text-neutral-600 mb-1">Produk Kosong</p>
                <p class="text-sm text-neutral-400">Belum ada komoditas untuk kategori ini.</p>
            </div>

        </div>
    </section>

    {{-- MAP --}}
    <section class="py-28 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14 reveal">
                <span class="section-eyebrow">Peta Sebaran</span>
                <h2 class="font-display font-light text-neutral-900 mb-3" style="font-size:clamp(1.75rem,2.8vw,2.25rem);">
                    Lokasi <em class="italic" style="color:#1e40af;">UMKM Nusantara</em>
                </h2>
                <p class="text-base text-neutral-400 max-w-md mx-auto">
                    Temukan mitra UMKM binaan {{ $setting->nama_expo ?? '' }} yang tersebar di seluruh penjuru Indonesia.
                    Klik titik pada peta untuk melihat daftar UMKM.
                </p>
            </div>
            <div class="reveal">
                <div id="map" data-map-data='{!! json_encode($umkmMap) !!}' data-selected-city='{!! json_encode($selectedCityCoords) !!}'
                    class="w-full rounded-2xl"
                    style="height:480px;background:#e8f0fe;box-shadow:0 4px 32px rgba(0,0,0,.07);">
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="hiw-section" id="cara-kerja">
        <div class="hiw-container">
            <div class="hiw-header reveal">
                <span class="section-eyebrow">Cara Kerja</span>
                <h2 class="hiw-title">Mudah Belanja, <em>Mudah Berbagi</em></h2>
                <p class="hiw-subtitle">Dukung UMKM lokal dengan berbelanja produk berkualitas dalam 4 langkah mudah</p>
            </div>
            @php
                $steps = [
                    [
                        'num' => '01',
                        'tag' => 'Langkah 1',
                        'title' => 'Pilih Produk',
                        'desc' =>
                            'Temukan produk UMKM pilihan sesuai kebutuhanmu menggunakan filter kategori atau kota.',
                    ],
                    [
                        'num' => '02',
                        'tag' => 'Langkah 2',
                        'title' => 'Isi Form & Hubungi',
                        'desc' => 'Lengkapi form pembelian dan hubungi pemilik UMKM untuk koordinasi pengiriman.',
                    ],
                    [
                        'num' => '03',
                        'tag' => 'Langkah 3',
                        'title' => 'Lakukan Pembayaran',
                        'desc' => 'Bayar via QRIS, transfer bank, atau metode lain yang disepakati bersama penjual.',
                    ],
                    [
                        'num' => '04',
                        'tag' => 'Langkah 4',
                        'title' => 'Terima Produk',
                        'desc' => 'Produk dikirim langsung dari UMKM ke tanganmu. Nikmati produk lokal berkualitas.',
                    ],
                ];
            @endphp
            <div class="hiw-timeline reveal">
                <div class="hiw-row hiw-row--top">
                    @foreach ($steps as $i => $step)
                        <div class="hiw-cell">
                            @if ($i % 2 === 0)
                                <div class="hiw-card">
                                    <div class="hiw-card-tag">{{ $step['tag'] }}</div>
                                    <h3 class="hiw-card-title">{{ $step['title'] }}</h3>
                                    <p class="hiw-card-desc">{{ $step['desc'] }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="hiw-row hiw-row--track">
                    @foreach ($steps as $i => $step)
                        <div class="hiw-cell hiw-cell--node">
                            <div class="hiw-stem hiw-stem--{{ $i % 2 === 0 ? 'up' : 'down' }}"></div>
                            <div class="hiw-node-wrap">
                                <div class="hiw-node">
                                    <div class="hiw-node-pulse" style="animation-delay:{{ $i * 0.5 }}s"></div>
                                    <div class="hiw-node-ring"></div>
                                    <span class="hiw-node-num">{{ $step['num'] }}</span>
                                </div>
                            </div>
                            <div class="hiw-stem hiw-stem--{{ $i % 2 === 0 ? 'down' : 'up' }}"></div>
                        </div>
                        @if ($i < count($steps) - 1)
                            <div class="hiw-cell hiw-cell--connector">
                                <div class="hiw-connector">
                                    <div class="hiw-connector-fill"></div>
                                    <svg class="hiw-arrow-svg" viewBox="0 0 60 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path class="hiw-dash" d="M0 8 L50 8" stroke="#cbd5e1" stroke-width="1.5"
                                            stroke-dasharray="5 4" />
                                        <path class="hiw-head" d="M44 3 L54 8 L44 13" stroke="#94a3b8" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="hiw-row hiw-row--bottom">
                    @foreach ($steps as $i => $step)
                        <div class="hiw-cell">
                            @if ($i % 2 === 1)
                                <div class="hiw-card">
                                    <div class="hiw-card-tag">{{ $step['tag'] }}</div>
                                    <h3 class="hiw-card-title">{{ $step['title'] }}</h3>
                                    <p class="hiw-card-desc">{{ $step['desc'] }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <style>
        .hiw-section {
            background: #fafafa;
            padding: 6rem 0 7rem;
            border-top: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .hiw-container {
            max-width: 80rem;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .hiw-header {
            text-align: center;
            margin-bottom: 4.5rem;
        }

        .hiw-title {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: clamp(1.95rem, 3.2vw, 2.65rem);
            font-weight: 400;
            color: #0f172a;
            line-height: 1.2;
            margin: 0 0 .75rem;
        }

        .hiw-title em {
            font-style: italic;
            color: #1e40af;
        }

        .hiw-subtitle {
            font-size: 1rem;
            color: #94a3b8;
            max-width: 340px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .hiw-timeline {
            display: flex;
            flex-direction: column;
        }

        .hiw-row {
            display: flex;
            align-items: stretch;
        }

        .hiw-cell {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hiw-cell--node {
            flex: 0 0 80px;
            width: 80px;
        }

        .hiw-cell--connector {
            flex: 1;
            min-width: 0;
        }

        .hiw-row--top {
            align-items: flex-end;
            min-height: 150px;
        }

        .hiw-row--top .hiw-cell {
            justify-content: flex-end;
        }

        .hiw-row--bottom {
            align-items: flex-start;
            min-height: 150px;
        }

        .hiw-row--bottom .hiw-cell {
            justify-content: flex-start;
        }

        .hiw-row--track {
            align-items: center;
        }

        .hiw-stem {
            width: 1px;
            background: #e2e8f0;
            flex: 1;
            min-height: 20px;
        }

        .hiw-row--track .hiw-stem--up,
        .hiw-row--track .hiw-stem--down {
            height: 32px;
            flex: none;
        }

        .hiw-node-wrap {
            flex-shrink: 0;
            z-index: 2;
        }

        .hiw-node {
            position: relative;
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hiw-node-ring {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            position: absolute;
            inset: 0;
            transition: border-color .3s, box-shadow .3s;
        }

        .hiw-node-wrap:hover .hiw-node-ring {
            border-color: #1e40af;
            box-shadow: 0 0 0 5px rgba(30, 64, 175, .07);
        }

        .hiw-node-pulse {
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 1px solid rgba(30, 64, 175, .18);
            animation: nodePulse 2.4s ease-out infinite;
        }

        @keyframes nodePulse {
            0% {
                transform: scale(.85);
                opacity: 0
            }

            40% {
                opacity: 1
            }

            100% {
                transform: scale(1.45);
                opacity: 0
            }
        }

        .hiw-node-num {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 1.2rem;
            color: #0f172a;
            position: relative;
            z-index: 1;
            line-height: 1;
        }

        .hiw-cell--connector {
            display: flex;
            align-items: center;
        }

        .hiw-connector {
            position: relative;
            width: 100%;
            height: 20px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hiw-connector::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            height: 1px;
            background: #e2e8f0;
            transform: translateY(-50%);
        }

        .hiw-connector-fill {
            position: absolute;
            left: 0;
            top: 50%;
            height: 1px;
            width: 0%;
            background: linear-gradient(90deg, #1e40af, #3b82f6);
            transform: translateY(-50%);
            transition: width 1.2s ease .3s;
            z-index: 1;
        }

        .hiw-timeline.filled .hiw-connector-fill {
            width: 100%;
        }

        .hiw-arrow-svg {
            position: relative;
            width: 100%;
            height: 16px;
            z-index: 2;
            display: block;
        }

        .hiw-dash {
            stroke-dasharray: 5 4;
            animation: dashRight 1.4s linear infinite;
        }

        @keyframes dashRight {
            0% {
                stroke-dashoffset: 36
            }

            100% {
                stroke-dashoffset: 0
            }
        }

        .hiw-head {
            animation: headBob 1.4s ease-in-out infinite;
            transform-origin: 51px 8px;
        }

        @keyframes headBob {

            0%,
            100% {
                transform: translateX(0);
                opacity: .5
            }

            50% {
                transform: translateX(5px);
                opacity: 1
            }
        }

        .hiw-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem 1.4rem;
            width: 100%;
            transition: box-shadow .3s, transform .3s, border-color .3s;
        }

        .hiw-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, .07);
            transform: translateY(-2px);
            border-color: #cbd5e1;
        }

        .hiw-card-tag {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: .45rem;
        }

        .hiw-card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 .4rem;
            line-height: 1.35;
        }

        .hiw-card-desc {
            font-size: .88rem;
            line-height: 1.75;
            color: #94a3b8;
            margin: 0;
        }

        @media(max-width:768px) {
            .hiw-timeline {
                display: none;
            }

            .hiw-mobile-grid {
                display: grid !important;
            }

            .hiw-section {
                padding-bottom: 5rem;
            }
        }
    </style>

    <div class="hiw-mobile-grid"
        style="display:none;max-width:80rem;margin:0 auto;padding:0 1.5rem 3rem;grid-template-columns:1fr 1fr;gap:1rem;">
        @foreach ($steps as $step)
            <div
                style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:.65rem;">
                <span
                    style="font-family:'DM Serif Display',Georgia,serif;font-size:2rem;color:#e2e8f0;line-height:1;">{{ $step['num'] }}</span>
                <div>
                    <div class="hiw-card-tag">{{ $step['tag'] }}</div>
                    <h3 class="hiw-card-title">{{ $step['title'] }}</h3>
                    <p class="hiw-card-desc">{{ $step['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        (function() {
            const timeline = document.querySelector('.hiw-timeline');
            if (!timeline) return;
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        setTimeout(() => timeline.classList.add('filled'), 200);
                        io.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.25
            });
            io.observe(timeline);
        })();
    </script>

@endsection

@push('scripts')
    @if (request('section') === 'produk' || request('city'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const el = document.getElementById('produk');
                if (el) setTimeout(() => el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                }), 100);
            });
        </script>
    @endif

    {{-- Reveal on scroll --}}
    <script>
        const revealEls = document.querySelectorAll('.reveal');
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    setTimeout(() => e.target.classList.add('visible'), i * 70);
                    io.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.08
        });
        revealEls.forEach(el => io.observe(el));
    </script>

    {{-- ══ CAROUSEL SCRIPT ══ --}}
    <script>
        (function() {
            const outer = document.getElementById('carousel-outer');
            const track = document.getElementById('carousel-track');
            const btnPrev = document.getElementById('carousel-prev');
            const btnNext = document.getElementById('carousel-next');
            const emptyMsg = document.getElementById('carousel-empty');
            if (!outer || !track) return;

            // Harus sama dengan CSS: flex: 0 0 200px + gap 16px
            const CARD_W = 200 + 16;
            const activeCat = '{{ addslashes($activeCatName) }}';
            let idx = 0;

            function allCards() {
                return Array.from(track.querySelectorAll('.carousel-card'));
            }

            function visibleCards() {
                return allCards().filter(c => {
                    const cat = (c.dataset.cat || '').trim();
                    return activeCat === 'all' || cat === activeCat;
                });
            }

            function perPage() {
                return Math.max(1, Math.floor(outer.offsetWidth / CARD_W));
            }

            function applyFilter() {
                allCards().forEach(c => {
                    const cat = (c.dataset.cat || '').trim();
                    const show = activeCat === 'all' || cat === activeCat;
                    c.style.display = show ? 'flex' : 'none';
                });
                idx = 0;
                render();
                if (emptyMsg) emptyMsg.style.display = visibleCards().length === 0 ? 'block' : 'none';
            }

            function render() {
                const visible = visibleCards();
                const maxIdx = Math.max(0, visible.length - perPage());
                idx = Math.min(Math.max(idx, 0), maxIdx);

                // Hitung offset: cari posisi kartu visible[idx] dalam DOM flex
                // (hidden cards masih ada tapi display:none, jadi tidak mengambil ruang)
                // Kita geser sejumlah idx * CARD_W dari posisi awal track visible
                track.style.transform = `translateX(-${idx * CARD_W}px)`;

                btnPrev.classList.toggle('disabled', idx === 0);
                btnNext.classList.toggle('disabled', idx >= maxIdx);
            }

            btnPrev.addEventListener('click', () => {
                idx -= perPage();
                render();
            });
            btnNext.addEventListener('click', () => {
                idx += perPage();
                render();
            });

            // Swipe
            let tx = 0;
            outer.addEventListener('touchstart', e => {
                tx = e.touches[0].clientX;
            }, {
                passive: true
            });
            outer.addEventListener('touchend', e => {
                const diff = tx - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 40) {
                    idx += diff > 0 ? 1 : -1;
                    render();
                }
            }, {
                passive: true
            });

            window.addEventListener('resize', () => {
                idx = 0;
                render();
            });

            applyFilter();
        })();
    </script>

    {{-- Leaflet Map --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapContainer = document.getElementById('map');
            if (!mapContainer) return;
            setTimeout(() => {
                let center = [-2.5489, 118.0149],
                    zoom = 5;
                const selectedCity = JSON.parse(mapContainer.dataset.selectedCity || 'null');
                if (selectedCity && selectedCity[0] && selectedCity[1]) {
                    center = [parseFloat(selectedCity[0]), parseFloat(selectedCity[1])];
                    zoom = 10;
                }
                const map = L.map('map', {
                    maxBounds: [
                        [-15.0, 92.0],
                        [10.0, 145.0]
                    ],
                    maxBoundsViscosity: 0.9,
                    minZoom: 4,
                    maxZoom: 15,
                    zoomSnap: 0.5
                }).setView(center, zoom);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 18,
                    minZoom: 4,
                    noWrap: true
                }).addTo(map);
                const cityData = JSON.parse(mapContainer.dataset.mapData || '[]');

                function makeCityIcon(count) {
                    const size = count >= 10 ? 46 : count >= 5 ? 42 : 38,
                        fontSize = count >= 10 ? 12 : 11;
                    const svg =
                        `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${size} ${size+14}" width="${size}" height="${size+14}"><filter id="sh"><feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="rgba(30,64,175,.35)"/></filter><path d="M${size/2} 0 C${size*.138} 0 ${size*.042} ${size*.179} ${size*.042} ${size*.4} c0 ${size*.3} ${size*.458} ${size*1.0} ${size*.458} ${size*1.0} S${size*.958} ${size*.7} ${size*.958} ${size*.4} C${size*.958} ${size*.179} ${size*.862} 0 ${size/2} 0z" fill="#1e40af" filter="url(#sh)"/><circle cx="${size/2}" cy="${size*.4}" r="${size*.28}" fill="white"/><text x="${size/2}" y="${size*.4+fontSize*.4}" text-anchor="middle" font-family="'Plus Jakarta Sans',sans-serif" font-size="${fontSize}" font-weight="700" fill="#1e40af">${count}</text></svg>`;
                    return L.divIcon({
                        html: svg,
                        className: '',
                        iconSize: [size, size + 14],
                        iconAnchor: [size / 2, size + 14],
                        popupAnchor: [0, -(size + 16)]
                    });
                }
                cityData.forEach(city => {
                    const lat = parseFloat(city.latitude),
                        lng = parseFloat(city.longitude);
                    if (isNaN(lat) || isNaN(lng)) return;
                    const umkmList = city.umkm_list || [],
                        count = city.count || umkmList.length;
                    const listHtml = umkmList.map(u =>
                        `<a href="/mitra/${u.uuid}" style="display:flex;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;gap:10px;text-decoration:none;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'"><span style="width:32px;height:32px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span><span style="flex:1;min-width:0;"><span style="display:block;font-size:12px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${u.nama_usaha}</span><span style="display:block;font-size:10px;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${u.alamat||'-'}</span></span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></a>`
                        ).join('');
                    const popupContent =
                        `<div style="min-width:260px;max-width:300px;font-family:'Plus Jakarta Sans',sans-serif;"><div style="background:linear-gradient(135deg,#1e3a8a,#1e40af);padding:14px;border-radius:8px 8px 0 0;margin:-1px -1px 0;"><div style="font-size:10px;color:#93c5fd;font-weight:600;letter-spacing:.05em;text-transform:uppercase;">Kota / Kabupaten</div><div style="font-size:16px;font-weight:700;color:#fff;margin-top:2px;">${city.city_name}</div><div style="font-size:11px;color:#bfdbfe;margin-top:2px;">${count} UMKM terdaftar</div></div><div style="padding:4px 14px;max-height:240px;overflow-y:auto;scrollbar-width:thin;">${listHtml}</div></div>`;
                    L.marker([lat, lng], {
                        icon: makeCityIcon(count)
                    }).addTo(map).bindPopup(popupContent, {
                        maxWidth: 320,
                        className: 'city-popup'
                    });
                });
                map.invalidateSize();
            }, 300);
        });
    </script>
    <style>
        .city-popup .leaflet-popup-content-wrapper {
            padding: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(30, 64, 175, .18);
            border: 1px solid #e0e7ff;
        }

        .city-popup .leaflet-popup-content {
            margin: 0;
        }

        .city-popup .leaflet-popup-tip {
            background: #1e40af;
        }
    </style>
@endpush
