@extends('layouts.guest')

@section('title', $umkm->nama_usaha)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
    /* ════════════════════════════════════════════════
       DESIGN TOKENS
    ════════════════════════════════════════════════ */
    :root {
        --c-bg:           #f4f6fb;
        --c-surface:      #ffffff;
        --c-ink:          #0d1b2a;
        --c-ink-mid:      #4a5568;
        --c-ink-soft:     #94a3b8;
        --c-border:       #e8edf5;
        --c-accent:       #2563eb;
        --c-accent-soft:  #eff4ff;
        --c-accent-hover: #1d4ed8;
        --c-wa:           #25d366;
        --c-wa-hover:     #1da854;
        --c-verify:       #10b981;
        --radius-card:    20px;
        --radius-btn:     14px;
        --shadow-sm:      0 4px 16px rgba(0,0,0,.06);
        --shadow-md:      0 12px 36px rgba(0,0,0,.09);
        --shadow-lg:      0 24px 60px rgba(0,0,0,.12);
        --ease-spring:    cubic-bezier(0.175, 0.885, 0.32, 1.275);
        --ease-out:       cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--c-bg);
        color: var(--c-ink);
    }

    /* ════════════════════════════════════════════════
       ANIMATIONS
    ════════════════════════════════════════════════ */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.92); }
        to   { opacity: 1; transform: scale(1); }
    }
    .anim-1 { animation: fadeUp .5s var(--ease-spring) both; }
    .anim-2 { animation: fadeUp .5s .1s var(--ease-spring) both; }
    .anim-3 { animation: fadeUp .5s .2s var(--ease-spring) both; }
    .anim-4 { animation: fadeUp .5s .3s var(--ease-spring) both; }

    /* ════════════════════════════════════════════════
       HEADER
    ════════════════════════════════════════════════ */
    .umkm-header {
        background: var(--c-surface);
        border-bottom: 1px solid var(--c-border);
        padding-top: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    /* Decorative blobs */
    .hblob {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .hblob-1 {
        top: -100px; right: -120px;
        width: 420px; height: 420px;
        background: radial-gradient(circle, #dbeafe 0%, transparent 65%);
        filter: blur(70px);
        opacity: .8;
    }
    .hblob-2 {
        bottom: -80px; left: -100px;
        width: 320px; height: 320px;
        background: radial-gradient(circle, #e0f2fe 0%, transparent 65%);
        filter: blur(60px);
        opacity: .6;
    }

    .header-inner {
        position: relative;
        z-index: 1;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ─── Breadcrumb ─── */
    .breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .3rem .5rem;
        padding: 0.5rem 0 1.25rem;
        font-size: .875rem;
        font-weight: 600;
        color: var(--c-ink-soft);
    }
    .breadcrumb a {
        color: var(--c-accent);
        text-decoration: none;
        transition: color .2s;
    }
    .breadcrumb a:hover { color: var(--c-accent-hover); }
    .breadcrumb .sep {
        font-size: .7rem;
        opacity: .4;
    }
    .breadcrumb .cur {
        color: var(--c-ink);
        font-weight: 700;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ─── Hero Card ─── */
    .hero-card {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 2rem;
        align-items: center;
        padding-bottom: 2rem;
    }

    .logo-wrap { position: relative; flex-shrink: 0; }

    .logo-box {
        width: clamp(90px, 14vw, 140px);
        height: clamp(90px, 14vw, 140px);
        border-radius: 24px;
        background: #fff;
        border: 1.5px solid var(--c-border);
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: all .4s var(--ease-spring);
    }
    .logo-box:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: var(--shadow-lg);
    }
    .logo-box img {
        width: 100%; height: 100%;
        object-fit: contain;
        padding: 10px;
    }
    .logo-emoji { font-size: 3rem; line-height: 1; user-select: none; }

    .badge-v {
        position: absolute;
        bottom: -8px; right: -8px;
        width: 32px; height: 32px;
        border-radius: 50%;
        background: var(--c-verify);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        border: 3px solid #fff;
        box-shadow: 0 4px 12px rgba(16,185,129,.35);
    }

    .hero-category {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: var(--c-accent-soft);
        color: var(--c-accent);
        border: 1px solid #bfdbfe;
        padding: .28rem .85rem;
        border-radius: 100px;
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: .75rem;
    }

    .hero-name {
        font-family: 'DM Serif Display', serif;
        font-size: clamp(1.7rem, 5vw, 3rem);
        font-weight: 400;
        color: var(--c-ink);
        line-height: 1.1;
        margin: 0 0 1rem;
        letter-spacing: -.3px;
    }

    .hero-pills {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }
    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: var(--c-bg);
        border: 1px solid var(--c-border);
        padding: .35rem .8rem;
        border-radius: 100px;
        font-size: .78rem;
        font-weight: 600;
        color: var(--c-ink-mid);
    }
    .hero-pill i { color: var(--c-accent); font-size: .72rem; }
    .hero-pill.green {
        color: #059669;
        border-color: #a7f3d0;
        background: #f0fdf4;
    }
    .hero-pill.green i { color: #059669; }

    .hero-address-block {
        margin: -0.5rem 0 1.25rem;
        max-width: 700px;
    }
    .address-label {
        font-size: .75rem;
        font-weight: 700;
        color: var(--c-ink-soft);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: .25rem;
    }
    .address-content {
        font-size: .95rem;
        color: var(--c-ink-mid);
        line-height: 1.6;
        font-weight: 500;
    }


    /* ════════════════════════════════════════════════
       BODY
    ════════════════════════════════════════════════ */
    .umkm-body {
        padding: 2.5rem 0 6rem;
    }

    .body-grid {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ─── Section Title ─── */
    .sec-title {
        font-family: 'DM Serif Display', serif;
        font-size: clamp(1.4rem, 3.5vw, 1.9rem);
        font-weight: 400;
        color: var(--c-ink);
        margin: 0 0 1.75rem;
        letter-spacing: -.2px;
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .sec-title::after {
        content: '';
        flex: 1;
        height: 1.5px;
        background: linear-gradient(to right, var(--c-border), transparent);
        border-radius: 2px;
    }
    .sec-title .hl {
        background: linear-gradient(135deg, var(--c-accent), #60a5fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ════════════════════════════════════════════════
       PRODUCT GRID
    ════════════════════════════════════════════════ */
    .produk-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    @media (min-width: 640px) {
        .produk-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (min-width: 768px) {
        .produk-grid { grid-template-columns: repeat(4, 1fr); gap: 1rem; }
    }
    @media (min-width: 1024px) {
        .produk-grid { grid-template-columns: repeat(5, 1fr); gap: 1rem; }
    }


    /* ─── Empty State ─── */
    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 4rem 2rem;
        background: var(--c-surface);
        border-radius: var(--radius-card);
        border: 1.5px dashed var(--c-border);
    }
    .empty-state .ei { font-size: 3rem; display: block; margin-bottom: 1rem; }
    .empty-state h3 {
        font-family: 'DM Serif Display', serif;
        font-size: 1.4rem;
        font-weight: 400;
        margin: 0 0 .5rem;
        color: var(--c-ink);
    }
    .empty-state p { color: var(--c-ink-soft); font-size: .9rem; margin: 0; }


    /* ════════════════════════════════════════════════
       MOBILE FLOATING CTA
    ════════════════════════════════════════════════ */
    .mobile-cta {
        position: fixed;
        bottom: 16px;
        left: 16px; right: 16px;
        z-index: 100;
        display: none;
    }
    .mobile-cta-inner { display: flex; gap: .65rem; }
    .mobile-cta .btn-wa {
        flex: 1;
        box-shadow: 0 8px 28px rgba(0,0,0,.18);
    }
    .btn-call {
        width: 56px; height: 56px;
        background: var(--c-surface);
        color: var(--c-accent);
        display: flex; align-items: center; justify-content: center;
        border-radius: 16px;
        font-size: 1.1rem;
        box-shadow: 0 8px 28px rgba(0,0,0,.1);
        border: 1px solid var(--c-border);
        text-decoration: none;
        transition: all .25s ease;
        flex-shrink: 0;
    }
    .btn-call:hover {
        background: var(--c-accent-soft);
        transform: translateY(-2px);
    }

    /* ════════════════════════════════════════════════
       RESPONSIVE
    ════════════════════════════════════════════════ */
    @media (max-width: 1024px) {
        .body-grid {
            grid-template-columns: 1fr;
        }
        .sidebar-col {
            position: static;
            order: -1; /* contact card moves above product list on mobile */
        }
        .mobile-cta { display: block; }
        .umkm-body { padding-bottom: 100px; }
    }

    @media (max-width: 768px) {
        .hero-card {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 1.25rem;
        }
        .logo-wrap { margin: 0 auto; }
        .hero-pills { justify-content: center; }
        .breadcrumb { justify-content: center; }
        .umkm-header { padding-top: 4.5rem; }
        .body-grid { padding: 0 1rem; }
        .header-inner { padding: 0 1rem; }
    }


    @media (max-width: 380px) {
        .produk-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

@php
    $waNum = preg_replace('/[^0-9]/', '', $umkm->telepon ?? '');
    if (str_starts_with($waNum, '0')) {
        $waNum = '62' . substr($waNum, 1);
    }
@endphp

{{-- ═════════════ HEADER ═════════════ --}}
<div class="umkm-header">
    <div class="hblob hblob-1"></div>
    <div class="hblob hblob-2"></div>

    <div class="header-inner">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb anim-1" aria-label="Breadcrumb">
            <a href="{{ route('guest.beranda') }}">Beranda</a>
            <i class="fas fa-chevron-right sep" aria-hidden="true"></i>
            <a href="{{ route('guest.umkm') }}">Mitra Usaha</a>
            <i class="fas fa-chevron-right sep" aria-hidden="true"></i>
            <span class="cur">{{ $umkm->nama_usaha }}</span>
        </nav>

        {{-- Hero --}}
        <div class="hero-card anim-2">

            {{-- Logo --}}
            <div class="logo-wrap">
                <div class="logo-box">
                    @if ($umkm->logo_umkm)
                        <img src="{{ Storage::url($umkm->logo_umkm) }}" alt="Logo {{ $umkm->nama_usaha }}" loading="eager">
                    @elseif ($setting?->logo_expo)
                        <img src="{{ asset('storage/' . $setting->logo_expo) }}" alt="Placeholder"
                             style="width:100%;height:100%;object-fit:contain;opacity:.25;filter:grayscale(1);padding:8px;">
                    @else
                        <span class="logo-emoji" aria-hidden="true">🏪</span>
                    @endif
                </div>
                @if ($umkm->verified_at)
                    <div class="badge-v" title="Mitra Terverifikasi" aria-label="Terverifikasi">
                        <i class="fas fa-check" aria-hidden="true"></i>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div>
                <div class="hero-category">
                    {{ $umkm->kategori->nama ?? 'Sektor Umum' }}
                </div>
                <h1 class="hero-name">{{ $umkm->nama_usaha }}</h1>
                <div class="hero-address-block">
                    <div class="address-label">Alamat Lengkap</div>
                    <div class="address-content">
                        {{ $umkm->alamat_lengkap }}
                    </div>
                </div>
                <div class="hero-pills">
                    <span class="hero-pill">
                        <i class="fas fa-map-pin" aria-hidden="true"></i>
                        {{ $umkm->city->name ?? 'Indonesia' }}
                    </span>
                    <span class="hero-pill">
                        <i class="fas fa-user" aria-hidden="true"></i>
                        {{ $umkm->nama_pemilik }}
                    </span>
                    <span class="hero-pill">
                        <i class="fas fa-box-open" aria-hidden="true"></i>
                        {{ $umkm->produkUmkm->count() }} Produk
                    </span>
                    @if ($umkm->verified_at)
                        <span class="hero-pill green">
                            <i class="fas fa-circle-check" aria-hidden="true"></i>
                            Terverifikasi
                        </span>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ═════════════ BODY ═════════════ --}}
<div class="umkm-body">
    <div class="body-grid">

        {{-- ── LEFT COLUMN ── --}}
        <div class="anim-4">

            {{-- Products --}}
            <section id="produk">
                <h2 class="sec-title">Produk <span class="hl">Pilihan</span></h2>
                <div class="produk-grid">
                    @forelse ($umkm->produkUmkm as $p)
                        @include('guest.partials.product-card', ['produk' => $p])
                    @empty
                        <div class="empty-state">
                            <span class="ei">✨</span>
                            <h3>Katalog Segera Hadir</h3>
                            <p>Produk-produk terbaik sedang dikurasi untuk Anda.</p>
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</div>

{{-- ═════════════ MOBILE FLOATING CTA ═════════════ --}}
<div class="mobile-cta" aria-label="Kontak mobile">
    <div class="mobile-cta-inner">
        @if ($waNum ?? false)
            <a href="https://wa.me/{{ $waNum }}"
               target="_blank" rel="noopener noreferrer"
               class="btn-wa">
                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                Chat WhatsApp
            </a>
            <a href="tel:{{ $umkm->telepon }}"
               class="btn-call"
               aria-label="Telepon">
                <i class="fas fa-phone" aria-hidden="true"></i>
            </a>
        @endif
    </div>
</div>


@endsection