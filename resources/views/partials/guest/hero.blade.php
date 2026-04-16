{{-- Font elegan untuk hero title --}}
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

@php
    $slides = [
        [
            'bg' => asset('landing/gerobak1.jpg'),
            'tag' => 'Pemberdayaan UMKM',
            'title' => 'Raih Masa Depan <em>UMKM</em> Lebih Cerah',
            'desc'  => 'Memberdayakan UMKM lokal berkualitas dengan dukungan penuh dari YBM PLN untuk kesejahteraan umat.',
        ],
        [
            'bg' => asset('landing/gerobak2.jpg'),
            'tag' => 'Produk Unggulan',
            'title' => 'Produk Lokal <em>Berkualitas</em> Pilihan Terbaik',
            'desc'  => 'Temukan beragam produk unggulan dari pelaku UMKM binaan YBM PLN yang siap memenuhi kebutuhan Anda.',
        ],
        [
            'bg' => asset('landing/gerobak7.jpg'),
            'tag' => 'Zakat Produktif',
            'title' => 'Bersama <em>YBM PLN</em> Wujudkan Kemandirian',
            'desc'  => 'Zakat produktif YBM PLN menggerakkan roda ekonomi melalui pemberdayaan UMKM yang berdaya saing.',
        ],
    ];
@endphp

<section class="hero-swiper swiper" aria-label="Hero Slider">
    <div class="swiper-wrapper">
        @foreach ($slides as $slide)
            <div class="swiper-slide hero-slide">
                
                {{-- Left Content Side --}}
                <div class="hero-content-wrap">
                    <div class="hero-content-inner">
                        <div class="hero-badge reveal-item">
                            <span class="hero-dot"></span>
                            {{ $slide['tag'] }}
                        </div>

                        <h1 class="hero-title reveal-item">{!! $slide['title'] !!}</h1>
                        <p class="hero-desc reveal-item">{{ $slide['desc'] }}</p>

                        <div class="hero-stats reveal-item">
                            @foreach ([
                                ['val' => number_format($totalUmkm), 'lab' => 'UMKM Aktif', 'icon' => 'fa-store'],
                                ['val' => number_format($totalProduk), 'lab' => 'Produk', 'icon' => 'fa-box'],
                                ['val' => $totalKategori, 'lab' => 'Kategori', 'icon' => 'fa-tag'],
                            ] as $s)
                                <div class="hero-stat-card">
                                    <div class="hero-stat-icon">
                                        <i class="fas {{ $s['icon'] }}"></i>
                                    </div>
                                    <div class="hero-stat-info">
                                        <span class="hero-stat-val">{{ $s['val'] }}+</span>
                                        <span class="hero-stat-lab">{{ $s['lab'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="hero-actions reveal-item">
                            <a href="{{ route('guest.katalog') }}" class="hero-btn hero-btn--primary">
                                <span>Jelajahi Produk</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('guest.umkm') }}" class="hero-btn hero-btn--outline">
                                <span>Lihat UMKM</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Photo Side - Optimized to not cut image --}}
                <div class="hero-photo-wrap">
                    {{-- Blurry background to fill gaps --}}
                    <div class="hero-photo-blur" style="background-image:url('{{ $slide['bg'] }}');"></div>
                    {{-- Actual original image --}}
                    <img src="{{ $slide['bg'] }}" class="hero-photo-main" alt="{{ strip_tags($slide['title']) }}">
                    <div class="hero-photo-overlay"></div>
                </div>

            </div>
        @endforeach
    </div>

    {{-- Navigation --}}
    <div class="hero-controls">
        <div class="hero-pagination"></div>
        <div class="hero-arrows">
            <button class="hero-arrow hero-arrow--prev"><i class="fas fa-chevron-left"></i></button>
            <button class="hero-arrow hero-arrow--next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>

<style>
:root {
    --hero-primary: #3b82f6;
    --hero-primary-dark: #2563eb;
    --hero-bg-deep: #0f172a;
    --hero-transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.hero-swiper {
    height: clamp(700px, 95vh, 900px);
    width: 100%;
    background: var(--hero-bg-deep);
    overflow: hidden;
}

@media (max-width: 1024px) {
    .hero-swiper {
        height: auto;
        min-height: 100svh;
    }
}

.hero-slide {
    display: flex;
    height: 100%;
}

@media (max-width: 1024px) {
    .hero-slide {
        flex-direction: column-reverse;
    }
}

/* ── Content Area ── */
.hero-content-wrap {
    flex: 0 0 50%;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
    padding: 8rem 4rem 4rem 8%;
    display: flex;
    align-items: center;
    position: relative;
    z-index: 10;
}

@media (max-width: 1280px) { .hero-content-wrap { padding-left: 5%; } }
@media (max-width: 1024px) {
    .hero-content-wrap {
        flex: 1;
        padding: 4rem 1.5rem 6rem;
        justify-content: center;
        text-align: center;
    }
}

.hero-content-inner {
    max-width: 580px;
}

@media (max-width: 1024px) {
    .hero-content-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
}

/* ── Photo Area - NO CROPPING ── */
.hero-photo-wrap {
    flex: 1;
    position: relative;
    background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.hero-photo-overlay {
    position: absolute;
    inset: 0;
    z-index: 3;
    pointer-events: none;
    background: radial-gradient(circle at center, transparent 40%, rgba(15, 23, 42, 0.4) 100%);
}

@media (max-width: 1024px) {
    .hero-photo-wrap {
        height: 45vh;
        width: 100%;
    }
}

.hero-photo-blur {
    position: absolute;
    inset: -20px;
    background-size: cover;
    background-position: center;
    filter: blur(40px) brightness(0.7);
    opacity: 0.5;
    z-index: 1;
}

.hero-photo-main {
    position: relative;
    z-index: 2;
    max-width: 92%;
    max-height: 85%;
    object-fit: contain; /* INI KUNCINYA: agar foto tidak terpotong */
    border-radius: 16px;
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6);
    transition: transform 12s ease, filter 0.5s ease;
    animation: heroFloat 6s ease-in-out infinite;
}

.swiper-slide-active .hero-photo-main {
    transform: scale(1.05);
}

@keyframes heroFloat {
    0%, 100% { transform: translateY(0) scale(1.05); }
    50% { transform: translateY(-15px) scale(1.05); }
}

/* ── Visual Elements ── */
.hero-badge {
    display: inline-flex; align-items: center; gap: .75rem;
    padding: .6rem 1.25rem; background: rgba(59,130,246,.12);
    border: 1px solid rgba(59,130,246,0.25); border-radius: 100px;
    color: #93c5fd; font-size: .8rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .12em; margin-bottom: 2.5rem;
}

.hero-dot {
    width: 8px; height: 8px; background: #3b82f6; border-radius: 50%;
    box-shadow: 0 0 10px #3b82f6; animation: heroPulse 2s infinite;
}

.hero-title {
    font-family: 'DM Serif Display', serif; 
    font-size: clamp(2.35rem, 4.8vw, 4rem);
    line-height: 1.05; 
    color: #fff; 
    margin-bottom: 1.75rem; 
    font-weight: 400;
    letter-spacing: -0.01em;
}

.hero-title em { font-style: italic; color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.3); }

.hero-desc {
    font-size: clamp(.95rem, 1.1vw, 1.05rem); line-height: 1.7;
    color: rgba(255,255,255,0.7); margin-bottom: 2.5rem; max-width: 500px;
}

/* ── Stats ── */
.hero-stats {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 1rem; margin-bottom: 3rem; width: 100%;
}

.hero-stat-card {
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
    backdrop-filter: blur(8px); padding: 1.25rem; border-radius: 18px;
    display: flex; align-items: center; gap: .85rem; transition: var(--hero-transition);
}

.hero-stat-card:hover { background: rgba(255,255,255,0.08); transform: translateY(-4px); }

.hero-stat-icon {
    width: 36px; height: 36px; background: rgba(59,130,246,0.15);
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
    color: #60a5fa; font-size: .9rem;
}

.hero-stat-val { font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: #fff; line-height: 1; }
.hero-stat-lab { font-size: .75rem; font-weight: 600; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.02em; }

@media (max-width: 640px) {
    .hero-stats { grid-template-columns: 1fr; max-width: 280px; }
}

/* ── Buttons ── */
.hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }

.hero-btn {
    padding: .9rem 1.75rem; border-radius: 10px; font-weight: 700; font-size: .9rem;
    display: inline-flex; align-items: center; gap: .75rem; transition: var(--hero-transition);
    text-decoration: none;
}

.hero-btn--primary { background: var(--hero-primary); color: #fff; box-shadow: 0 10px 20px rgba(59,130,246,0.25); }
.hero-btn--primary:hover { background: var(--hero-primary-dark); transform: translateY(-3px); }
.hero-btn--outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,0.15); }
.hero-btn--outline:hover { background: rgba(255,255,255,0.05); border-color: #fff; }

@media (max-width: 640px) {
    .hero-actions { flex-direction: column; width: 100%; max-width: 280px; }
    .hero-btn { width: 100%; justify-content: center; }
}

/* ── Controls ── */
.hero-controls {
    position: absolute; bottom: 3rem; left: 8%; right: 4rem;
    z-index: 30; display: flex; align-items: center; justify-content: space-between;
    pointer-events: none;
}

@media (max-width: 1024px) {
    .hero-controls { left: 0; right: 0; bottom: 1.5rem; justify-content: center; padding: 0 1.5rem; }
    .hero-arrows { display: none; }
}

.hero-pagination { display: flex; gap: .5rem; pointer-events: auto; }
.hero-pagination .swiper-pagination-bullet {
    width: 30px; height: 3px; background: rgba(255,255,255,0.2);
    border-radius: 0; opacity: 1; transition: var(--hero-transition);
}
.hero-pagination .swiper-pagination-bullet-active { background: var(--hero-primary); width: 50px; }

.hero-arrows { display: flex; gap: 1rem; pointer-events: auto; }
.hero-arrow {
    width: 54px; height: 54px; border-radius: 50%;
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
    color: #fff; display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 1rem; transition: var(--hero-transition);
}
.hero-arrow:hover { background: var(--hero-primary); border-color: var(--hero-primary); }

/* ── Animations ── */
.reveal-item { opacity: 0; transform: translateY(20px); }
.swiper-slide-active .reveal-item {
    animation: heroReveal 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
}
.swiper-slide-active .hero-badge { animation-delay: 0.1s; }
.swiper-slide-active .hero-title { animation-delay: 0.2s; }
.swiper-slide-active .hero-desc  { animation-delay: 0.3s; }
.swiper-slide-active .hero-stats { animation-delay: 0.4s; }
.swiper-slide-active .hero-actions { animation-delay: 0.5s; }

@keyframes heroReveal { to { opacity: 1; transform: translateY(0); } }
@keyframes heroPulse {
    0% { box-shadow: 0 0 0 0 rgba(59,130,246,0.7); }
    70% { box-shadow: 0 0 0 10px rgba(59,130,246,0); }
    100% { box-shadow: 0 0 0 0 rgba(59,130,246,0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.hero-swiper', {
        loop: true,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: 1000,
        autoplay: { delay: 7000, disableOnInteraction: false },
        pagination: { el: '.hero-pagination', clickable: true },
        navigation: { nextEl: '.hero-arrow--next', prevEl: '.hero-arrow--prev' },
    });
});
</script>