{{-- Font elegan untuk hero title --}}
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

@php
    $slides = [
        [
            'bg' => asset('landing/gerobak1.jpg'),
            'title' => 'Raih Masa Depan <em>UMKM</em> Lebih Cerah',
            'desc' =>
                'Memberdayakan UMKM lokal berkualitas dengan dukungan penuh dari YBM PLN untuk kesejahteraan umat.',
        ],
        [
            'bg' => asset('landing/gerobak2.jpg'),
            'title' => 'Produk Lokal <em>Berkualitas</em> Pilihan Terbaik',
            'desc' =>
                'Temukan beragam produk unggulan dari pelaku UMKM binaan YBM PLN yang siap memenuhi kebutuhan Anda.',
        ],
        [
            'bg' => asset('landing/gerobak7.jpg'),
            'title' => 'Bersama <em>YBM PLN</em> Wujudkan Kemandirian',
            'desc' => 'Zakat produktif YBM PLN menggerakkan roda ekonomi melalui pemberdayaan UMKM yang berdaya saing.',
        ],
    ];
@endphp

<section class="hero-slider swiper hero-full-height" style="isolation:isolate;">
    <div class="swiper-wrapper">

        @foreach ($slides as $slide)
            <div class="swiper-slide hero-slide">

                {{-- Background Ken Burns --}}
                <div class="hero-bg"
                    style="background-image:
                        linear-gradient(180deg,
                            rgba(0,0,0,.08) 0%,
                            rgba(0,0,0,.12) 100%
                        ),
                        url('{{ $slide['bg'] }}');">
                </div>

                {{-- Overlay gelap bawah --}}
                <div class="hero-overlay-bottom"></div>

                {{-- Content --}}
                <div class="hero-content-wrap">
                    <h1 class="hero-title">{!! $slide['title'] !!}</h1>
                    <p class="hero-desc">{{ $slide['desc'] }}</p>

                    {{-- Stats --}}
                    <div class="hero-stats">
                        @foreach ([['val' => number_format($totalUmkm) . '+', 'lab' => 'UMKM Aktif'], ['val' => number_format($totalProduk) . '+', 'lab' => 'Produk'], ['val' => $totalKategori . '+', 'lab' => 'Kategori']] as $i => $stat)
                            @if ($i > 0)
                                <div class="hero-stat-sep"></div>
                            @endif
                            <div class="hero-stat-item">
                                <span class="hero-stat-num">{{ $stat['val'] }}</span>
                                <span class="hero-stat-lab">{{ $stat['lab'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Actions --}}
                    <div class="hero-actions">
                        <a href="{{ route('guest.katalog') }}" class="hero-btn-primary">
                            <i class="fas fa-shopping-bag"></i> Jelajahi Produk
                        </a>
                        <a href="{{ route('guest.umkm') }}" class="hero-btn-ghost">
                            <i class="fas fa-store"></i> Lihat UMKM
                        </a>
                    </div>

                </div>
            </div>
        @endforeach

    </div>

    {{-- Navigation Arrows --}}
    <div class="hero-nav-prev hero-arrow"><i class="fas fa-chevron-left"></i></div>
    <div class="hero-nav-next hero-arrow"><i class="fas fa-chevron-right"></i></div>

    {{-- Pagination --}}
    <div class="swiper-pagination hero-pagination"></div>
</section>

<style>
    /* ─── Full height ─────────────────────── */
    .hero-full-height {
        height: calc(100svh - 36px);
        /* mobile: gunakan svh agar address bar tidak ganggu */
    }

    @media (min-width: 640px) {
        .hero-full-height {
            height: calc(100vh - 40px);
        }
    }

    /* ─── Slide layout ────────────────────── */
    .hero-slide {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* ─── Background Ken Burns ────────────── */
    .hero-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        transform: scale(1.08);
        transition: transform 7s cubic-bezier(.2, .4, .6, 1);
        z-index: 0;
    }

    .swiper-slide-active .hero-bg {
        transform: scale(1);
    }

    /* ─── Gradient overlay bawah ──────────── */
    .hero-overlay-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 35%;
        background: linear-gradient(to top, rgba(0, 0, 0, .15), transparent);
        z-index: 1;
        pointer-events: none;
    }

    /* ─── Content wrapper ─────────────────── */
    .hero-content-wrap {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 860px;
        margin: 0 auto;
        padding: 4rem 1.25rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
    }

    @media (min-width: 640px) {
        .hero-content-wrap {
            padding: 5rem 2rem;
            gap: 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .hero-content-wrap {
            padding: 6rem 2.5rem;
            gap: 1.75rem;
        }
    }

    /* ─── Title ───────────────────────────── */
    .hero-title {
        font-family: 'DM Serif Display', Georgia, serif;
        /* fluid: 1.9rem (mobile) → 3.25rem (desktop) */
        font-size: clamp(1.9rem, 5.5vw, 3.25rem);
        line-height: 1.12;
        font-weight: 400;
        color: #fff;
        letter-spacing: -.01em;
        margin: 0;
        opacity: 0;
        max-width: 720px;
        text-shadow:
            0 2px 4px rgba(0, 0, 0, .6),
            0 4px 16px rgba(0, 0, 0, .5),
            0 8px 32px rgba(0, 0, 0, .4);
    }

    .hero-title em {
        font-style: italic;
        color: #bfdbfe;
        text-shadow:
            0 2px 4px rgba(0, 0, 0, .7),
            0 4px 20px rgba(0, 0, 0, .5);
    }

    /* ─── Desc ────────────────────────────── */
    .hero-desc {
        font-size: clamp(.85rem, 2vw, .95rem);
        line-height: 1.7;
        color: #fff;
        max-width: 480px;
        margin: 0;
        opacity: 0;
        text-shadow: 0 1px 6px rgba(0, 0, 0, .7), 0 2px 16px rgba(0, 0, 0, .5);
        font-weight: 500;
    }

    /* ─── Stats ───────────────────────────── */
    .hero-stats {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        opacity: 0;
    }

    @media (min-width: 640px) {
        .hero-stats {
            gap: 4rem;
        }
    }

    .hero-stat-sep {
        width: 1px;
        height: 24px;
        background: rgba(147, 197, 253, .20);
        flex-shrink: 0;
    }

    @media (min-width: 640px) {
        .hero-stat-sep {
            height: 28px;
        }
    }

    .hero-stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }

    .hero-stat-num {
        font-family: 'DM Serif Display', Georgia, serif;
        font-weight: 700;
        font-size: clamp(1.25rem, 3vw, 1.6rem);
        color: #fff;
        line-height: 1;
        text-shadow: 0 1px 4px rgba(0, 0, 0, .5);
    }

    .hero-stat-lab {
        font-size: clamp(.58rem, 1.2vw, .65rem);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .15em;
        color: rgba(255, 255, 255, .75);
        white-space: nowrap;
        text-align: center;
    }

    /* ─── Actions ─────────────────────────── */
    .hero-actions {
        display: flex;
        align-items: center;
        gap: .6rem;
        flex-wrap: wrap;
        justify-content: center;
        opacity: 0;
    }

    @media (min-width: 640px) {
        .hero-actions {
            gap: .75rem;
        }
    }

    .hero-btn-primary,
    .hero-btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .7rem 1.35rem;
        border-radius: 50px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: .82rem;
        text-decoration: none;
        transition: all .25s;
        white-space: nowrap;
    }

    @media (min-width: 640px) {

        .hero-btn-primary,
        .hero-btn-ghost {
            padding: .8rem 1.75rem;
            font-size: .85rem;
            gap: .5rem;
        }
    }

    .hero-btn-primary {
        background: #60a5fa;
        color: #fff;
        box-shadow: 0 8px 28px rgba(96, 165, 250, .35);
    }

    .hero-btn-primary:hover {
        background: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(59, 130, 246, .45);
    }

    .hero-btn-ghost {
        background: rgba(255, 255, 255, .12);
        border: 1.5px solid rgba(255, 255, 255, .45);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        color: #fff;
        font-weight: 700;
        text-shadow: 0 1px 4px rgba(0, 0, 0, .3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, .1);
    }

    .hero-btn-ghost:hover {
        background: rgba(255, 255, 255, .25);
        border-color: rgba(255, 255, 255, 1);
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, .2);
    }

    /* ─── Nav arrows ──────────────────────── */
    .hero-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 20;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .20);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .8rem;
        cursor: pointer;
        transition: all .2s;
    }

    @media (min-width: 1024px) {
        .hero-arrow {
            width: 44px;
            height: 44px;
        }
    }

    .hero-arrow:hover {
        background: rgba(255, 255, 255, .28);
        border-color: rgba(255, 255, 255, .45);
        transform: translateY(-50%) scale(1.05);
    }

    /* Arrows: jarak proporsional dari tepi */
    .hero-nav-prev {
        left: clamp(2rem, 10vw, 16rem);
    }

    .hero-nav-next {
        right: clamp(2rem, 10vw, 16rem);
    }

    @media (max-width: 767px) {
        .hero-arrow {
            display: none;
        }
    }

    /* ─── Pagination ──────────────────────── */
    .swiper-pagination-bullet {
        width: 6px;
        height: 6px;
        background: rgba(147, 197, 253, .30);
        opacity: 1;
        transition: all .3s;
    }

    .swiper-pagination-bullet-active {
        background: #93c5fd;
        width: 22px;
        border-radius: 50px;
    }

    /* ─── Staggered animation ─────────────── */
    .swiper-slide-active .hero-title {
        animation: hFadeDown .6s .00s ease forwards;
    }

    .swiper-slide-active .hero-desc {
        animation: hFadeUp .6s .12s ease forwards;
    }

    .swiper-slide-active .hero-stats {
        animation: hFadeUp .6s .22s ease forwards;
    }

    .swiper-slide-active .hero-actions {
        animation: hFadeUp .6s .32s ease forwards;
    }

    @keyframes hFadeDown {
        from {
            opacity: 0;
            transform: translateY(-14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes hFadeUp {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.hero-slider', {
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 900,
            autoplay: {
                delay: 5500,
                disableOnInteraction: false
            },
            navigation: {
                nextEl: '.hero-nav-next',
                prevEl: '.hero-nav-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
</script>
