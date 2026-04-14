@extends('layouts.guest')

@section('title', 'Form Pembelian - ' . $produk->nama_produk)

@push('styles')
    <style>
        :root {
            --brand: #1a3199;
            --brand-dark: #152780;
            --brand-soft: #eef1fb;
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* ── LAYOUT ── */
        .checkout-grid {
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .checkout-grid { grid-template-columns: 1fr; }
        }

        /* ── CARD ── */
        .card-checkout {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }

        /* ── SECTION TITLE ── */
        .section-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 1.35rem;
            color: #0f172a;
            margin-bottom: 1.5rem;
        }

        .section-title i {
            font-size: 1rem;
            color: var(--brand);
        }

        /* ── FORM ── */
        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.45rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.92rem;
            color: #0f172a;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--brand);
            outline: none;
            box-shadow: 0 0 0 4px rgba(26,49,153,0.08);
        }

        .form-control.border-red-500 { border-color: #ef4444; }

        textarea.form-control { resize: vertical; }

        /* ── PAYMENT TAB SWITCHER ── */
        .pay-tabs {
            display: flex;
            gap: 0.5rem;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 0.35rem;
            margin-bottom: 1.25rem;
        }

        .pay-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 0.75rem;
            border-radius: 10px;
            border: none;
            background: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            transition: background 0.22s var(--ease), color 0.22s, box-shadow 0.22s;
            white-space: nowrap;
        }

        .pay-tab:hover { color: var(--brand); }

        .pay-tab.active {
            background: #fff;
            color: var(--brand);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .pay-tab i { font-size: 0.8rem; }

        /* ── TAB PANEL ── */
        .pay-panel {
            display: none;
        }

        .pay-panel.active {
            display: block;
            animation: fadeSlideIn 0.25s var(--ease);
        }

        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── UPLOAD ── */
        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 2rem;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }

        .upload-area:hover {
            border-color: var(--brand);
            background: var(--brand-soft);
        }

        /* ── ADD MORE SECTION ── */
        .add-more-section {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px dashed #e2e8f0;
        }

        .add-more-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--brand);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .product-select-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 0.65rem;
            max-height: 240px;
            overflow-y: auto;
            padding-right: 0.25rem;
        }

        .product-select-item {
            background: #fff;
            padding: 0.7rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }

        .product-select-item:hover {
            border-color: var(--brand);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26,49,153,0.1);
        }

        .product-select-img {
            width: 100%;
            height: 75px;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 0.5rem;
        }

        .product-select-name {
            font-size: 0.72rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.2rem;
        }

        .product-select-price {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--brand);
        }

        /* ── CART ── */
        .order-summary-item {
            display: flex;
            gap: 0.85rem;
            padding: 0.85rem 0;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
        }

        .btn-remove {
            position: absolute;
            top: 0.85rem;
            right: 0;
            color: #ef4444;
            cursor: pointer;
            font-size: 0.75rem;
            opacity: 0.7;
            transition: opacity 0.2s;
            background: none;
            border: none;
        }

        .btn-remove:hover { opacity: 1; }

        .order-img {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .order-info { flex: 1; min-width: 0; }

        .order-info .name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.3rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .order-info .price-qty {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-info .price {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--brand);
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: #f1f5f9;
            padding: 0.2rem 0.45rem;
            border-radius: 8px;
        }

        .qty-btn {
            border: none;
            background: none;
            cursor: pointer;
            font-weight: 800;
            font-size: 0.95rem;
            color: var(--brand);
            line-height: 1;
            padding: 0 0.2rem;
        }

        .qty-val {
            font-size: 0.82rem;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
        }

        /* ── TOTAL ── */
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0 0;
            margin-top: 0.25rem;
        }

        .total-label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
        }

        .total-amount {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 1.5rem;
            color: #0d9488;
            line-height: 1;
        }

        /* ── PAYMENT INFO ── */
        .payment-info {
            background: var(--brand-soft);
            border-radius: 16px;
            padding: 1.25rem;
            margin-top: 1.25rem;
        }

        .payment-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--brand);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .bank-item {
            background: #fff;
            padding: 0.9rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.65rem;
            border: 1px solid #e2e8f0;
        }

        .bank-name {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--brand);
            margin-bottom: 0.2rem;
        }

        .bank-acc {
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0.15rem 0;
        }

        .bank-owner {
            font-size: 0.75rem;
            color: #64748b;
        }

        .qris-img {
            max-width: 180px;
            margin: 1rem auto 0;
            display: block;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* ── SUBMIT BUTTON ── */
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
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(26,49,153,0.22);
            transition: all 0.3s var(--ease);
            margin-top: 1.25rem;
            text-decoration: none;
            position: relative;
        }

        .buy-button:hover:not(:disabled) {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(26,49,153,0.32);
            color: white;
        }

        .buy-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            filter: grayscale(0.5);
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        /* ── BACK LINK ── */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin-top: 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--brand); }

        @media (max-width: 640px) {
            .card-checkout { padding: 1.25rem; border-radius: 16px; }
            .section-title { font-size: 1.15rem; }
            .product-select-grid { grid-template-columns: repeat(2, 1fr); }
            .total-amount { font-size: 1.25rem; }
        }
    </style>
@endpush

@section('content')

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
                <a href="{{ route('guest.detail-produk', $produk->uuid) }}"
                   class="no-underline transition-colors duration-200 hover:underline line-clamp-1"
                   style="color: var(--brand);">{{ $produk->nama_produk }}</a>
                <i class="fas fa-chevron-right text-neutral-300" style="font-size: 0.65rem;"></i>
                <span class="text-neutral-600 font-bold">Form Pembelian</span>
            </nav>
        </div>
    </div>

    <div class="bg-neutral-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 pb-24">

            <form id="checkoutForm" action="{{ route('guest.store-checkout', $produk->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="checkout-grid">

                    {{-- ── KOLOM KIRI: FORM ── --}}
                    <div class="flex flex-col gap-5">

                        {{-- Informasi Pembeli --}}
                        <div class="card-checkout">
                            <h2 class="section-title">
                                <i class="fas fa-user"></i> Informasi Pembelian
                            </h2>

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_pembeli"
                                    class="form-control @error('nama_pembeli') border-red-500 @enderror"
                                    value="{{ old('nama_pembeli') }}"
                                    placeholder="Masukkan nama Anda" required>
                                @error('nama_pembeli')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor Telepon (WhatsApp) <span class="text-red-500">*</span></label>
                                <input type="text" name="telepon_pembeli"
                                    class="form-control @error('telepon_pembeli') border-red-500 @enderror"
                                    value="{{ old('telepon_pembeli') }}"
                                    placeholder="Contoh: 08123456789" required>
                                @error('telepon_pembeli')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap Pengiriman <span class="text-red-500">*</span></label>
                                <textarea name="alamat_pembeli" rows="4"
                                    class="form-control @error('alamat_pembeli') border-red-500 @enderror"
                                    placeholder="Masukkan alamat lengkap" required>{{ old('alamat_pembeli') }}</textarea>
                                @error('alamat_pembeli')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Catatan Pesanan <span class="text-neutral-400 font-normal normal-case" style="letter-spacing:0;">(Opsional)</span></label>
                                <textarea name="catatan" rows="2" class="form-control"
                                    placeholder="Contoh: Ukuran XL, Warna Merah">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        {{-- Metode & Bukti Pembayaran --}}
                        <div class="card-checkout">
                            <h2 class="section-title">
                                <i class="fas fa-credit-card"></i> Metode Pembayaran
                            </h2>

                            <input type="hidden" name="metode_pembayaran" id="input-metode" value="">

                            @php
                                $hasBank = $produk->umkm->rekening->count() > 0;
                                $hasQris = (bool) $produk->umkm->qris_foto;
                                $noPayment = !$hasBank && !$hasQris;
                            @endphp

                            @if ($noPayment)
                                <p class="text-xs font-semibold text-red-400 text-center py-2">
                                    Pemilik UMKM belum mengatur metode pembayaran.
                                </p>
                            @else
                                {{-- TAB BUTTONS --}}
                                <div class="pay-tabs">
                                    @if ($hasBank)
                                        <button type="button" class="pay-tab {{ !$hasQris ? 'active' : '' }}"
                                                id="tab-bank" onclick="switchTab('bank')">
                                            <i class="fas fa-university"></i> Transfer Bank
                                        </button>
                                    @endif
                                    @if ($hasQris)
                                        <button type="button" class="pay-tab {{ !$hasBank ? 'active' : '' }}"
                                                id="tab-qris" onclick="switchTab('qris')">
                                            <i class="fas fa-qrcode"></i> QRIS
                                        </button>
                                    @endif
                                </div>

                                {{-- PANEL: TRANSFER BANK --}}
                                @if ($hasBank)
                                    <div class="pay-panel {{ !$hasQris ? 'active' : '' }}" id="panel-bank">
                                        @foreach ($produk->umkm->rekening as $rek)
                                            <div class="bank-item">
                                                <div class="bank-name">{{ $rek->nama_bank }}</div>
                                                <div class="bank-acc">{{ $rek->nomor_rekening }}</div>
                                                <div class="bank-owner">a.n {{ $rek->nama_rekening }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- PANEL: QRIS --}}
                                @if ($hasQris)
                                    <div class="pay-panel {{ !$hasBank ? 'active' : '' }}" id="panel-qris">
                                        <div class="text-center py-2">
                                            <img src="{{ Storage::url($produk->umkm->qris_foto) }}"
                                                 class="qris-img"
                                                 alt="QRIS {{ $produk->umkm->nama_usaha }}">
                                            <p class="text-xs text-neutral-400 mt-3">
                                                Scan dengan e-wallet atau mobile banking apapun
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- DIVIDER --}}
                                <div style="border-top: 1px dashed #e2e8f0; margin: 1.25rem 0;"></div>

                                {{-- UPLOAD BUKTI --}}
                                <p class="text-xs font-bold uppercase tracking-widest mb-3"
                                   style="color: var(--brand); letter-spacing: 0.06em;">
                                    <i class="fas fa-camera mr-1"></i> Unggah Bukti Pembayaran
                                </p>

                                <input type="file" name="bukti_transfer" id="bukti_transfer"
                                    class="sr-only" accept="image/*" required
                                    onchange="previewImage(this)">

                                <div class="upload-area @error('bukti_transfer') border-red-500 @enderror"
                                    onclick="document.getElementById('bukti_transfer').click()">
                                    <div id="upload-preview">
                                        <i class="fas fa-cloud-upload-alt"
                                            style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 0.75rem;"></i>
                                        <p class="text-sm font-semibold text-neutral-500 mb-1">
                                            Klik untuk unggah Bukti Transfer <span class="text-red-500">*</span>
                                        </p>
                                        <p class="text-xs text-neutral-400">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                                    </div>
                                </div>

                                @error('bukti_transfer')
                                    <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p>
                                @enderror
                            @endif

                        </div>

                    </div>

                    {{-- ── KOLOM KANAN: SUMMARY & PAYMENT ── --}}
                    <div class="lg:sticky lg:top-24">
                        <div class="card-checkout">

                            {{-- Tambah Produk --}}
                            <div class="add-more-section">
                                <p class="add-more-title">
                                    <i class="fas fa-plus-circle"></i> Tambah Produk Lainnya
                                </p>
                                <div class="product-select-grid">
                                    @foreach ($umkm_produk as $up)
                                        @php
                                            $up_fotos = $up->foto_produk;
                                            $up_foto  = is_array($up_fotos) ? ($up_fotos[0] ?? null) : null;
                                        @endphp
                                        <div class="product-select-item {{ $up->stok <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            @if($up->stok > 0)
                                            onclick="addToCart(
                                                {{ $up->id }},
                                                '{{ addslashes($up->nama_produk) }}',
                                                {{ $up->harga }},
                                                '{{ $up_foto ? Storage::url($up_foto) : '' }}',
                                                {{ $up->stok }}
                                            )"
                                            @endif>
                                            @if ($up_foto)
                                                <img src="{{ Storage::url($up_foto) }}"
                                                     class="product-select-img"
                                                     alt="{{ $up->nama_produk }}">
                                            @elseif (isset($setting) && $setting->logo_expo)
                                                <div class="product-select-img"
                                                     style="background:#f1f5f9;display:flex;align-items:center;justify-content:center;padding:10px;">
                                                    <img src="{{ asset('storage/' . $setting->logo_expo) }}"
                                                         alt="Placeholder"
                                                         style="width:100%;height:100%;object-fit:contain;opacity:.2;filter:grayscale(1);">
                                                </div>
                                            @else
                                                <div class="product-select-img"
                                                     style="background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                                                    🛍️
                                                </div>
                                            @endif
                                            <div class="product-select-name">{{ $up->nama_produk }}</div>
                                            <div class="product-select-price">
                                                Rp {{ number_format($up->harga, 0, ',', '.') }}
                                            </div>
                                            <div class="text-[10px] font-bold {{ $up->stok > 0 ? 'text-slate-400' : 'text-red-500' }}">
                                                Stok: {{ $up->stok }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Cart List --}}
                            <div id="cart-list"></div>

                            {{-- Total --}}
                            <div class="total-row border-t border-dashed border-neutral-200">
                                <span class="total-label">Total Bayar</span>
                                <input type="hidden" name="total_harga" id="input-total-harga" value="0">
                                <span id="display-total" class="total-amount">Rp 0</span>
                            </div>

                            {{-- Submit --}}
                            <button type="submit" class="buy-button" id="btnSubmit">
                                <i class="fas fa-check-circle text-sm" id="btnIcon"></i>
                                <div class="spinner" id="btnSpinner"></div>
                                <span id="btnText">Konfirmasi Pembelian</span>
                            </button>

                            <a href="{{ route('guest.detail-produk', $produk->uuid) }}" class="back-link">
                                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Produk
                            </a>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        @php
            $fotos     = $produk->foto_produk;
            $main_foto = is_array($fotos) ? ($fotos[0] ?? null) : null;
        @endphp

        let cart = [{
            id:    {{ $produk->id }},
            name:  '{{ addslashes($produk->nama_produk) }}',
            price: {{ $produk->harga }},
            qty:   1,
            foto:  '{{ $main_foto ? Storage::url($main_foto) : '' }}',
            stok:  {{ $produk->stok }}
        }];

        const settingLogo = '{{ $setting && $setting->logo_expo ? asset('storage/' . $setting->logo_expo) : '' }}';

        function addToCart(id, name, price, foto, stok) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                if (existing.qty >= existing.stok) {
                    alert('Maaf, stok tidak mencukupi untuk menambah produk ini.');
                    return;
                }
                existing.qty++;
            } else {
                if (stok <= 0) {
                    alert('Maaf, stok produk ini sedang habis.');
                    return;
                }
                cart.push({ id, name, price, qty: 1, foto, stok });
            }
            renderCart();
        }

        function removeFromCart(index) {
            if (cart.length > 1) {
                cart.splice(index, 1);
                renderCart();
            } else {
                alert('Minimal harus ada 1 produk dalam pesanan.');
            }
        }

        function updateQty(index, delta) {
            const item = cart[index];
            if (delta > 0 && item.qty >= item.stok) {
                alert('Maaf, stok maksimal tersedia adalah ' + item.stok);
                return;
            }
            item.qty = Math.max(1, item.qty + delta);
            renderCart();
        }

        function imgHtml(foto, name) {
            if (foto) return `<img src="${foto}" class="order-img" alt="${name}">`;
            if (settingLogo) return `
                <div class="order-img" style="background:#f1f5f9;display:flex;align-items:center;justify-content:center;padding:6px;">
                    <img src="${settingLogo}" alt="placeholder" style="width:100%;height:100%;object-fit:contain;opacity:.2;filter:grayscale(1);">
                </div>`;
            return `<div class="order-img" style="background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🛍️</div>`;
        }

        function renderCart() {
            const list  = document.getElementById('cart-list');
            let total   = 0;
            list.innerHTML = '';

            cart.forEach((item, index) => {
                total += item.price * item.qty;
                list.innerHTML += `
                    <div class="order-summary-item">
                        <button type="button" class="btn-remove" onclick="removeFromCart(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        ${imgHtml(item.foto, item.name)}
                        <div class="order-info">
                            <div class="name">${item.name}</div>
                            <div class="text-[10px] text-slate-400 font-bold mb-1">Stok: ${item.stok}</div>
                            <div class="price-qty">
                                <div class="price">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</div>
                                <div class="qty-controls">
                                    <button type="button" class="qty-btn" onclick="updateQty(${index}, -1)">−</button>
                                    <span class="qty-val">${item.qty}</span>
                                    <button type="button" class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                                </div>
                            </div>
                            <input type="hidden" name="items[${index}][id]"     value="${item.id}">
                            <input type="hidden" name="items[${index}][jumlah]" value="${item.qty}">
                        </div>
                    </div>`;
            });

            document.getElementById('display-total').textContent =
                'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            document.getElementById('input-total-harga').value = total;
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderCart();
            // set default metode_pembayaran
            @if ($produk->umkm->rekening->count() > 0)
                document.getElementById('input-metode').value = 'bank';
            @elseif ($produk->umkm->qris_foto)
                document.getElementById('input-metode').value = 'qris';
            @endif
        });

        /* ── TAB SWITCHER ── */
        function switchTab(tab) {
            // panels
            document.querySelectorAll('.pay-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('active'));

            const panel = document.getElementById('panel-' + tab);
            const btn   = document.getElementById('tab-' + tab);
            if (panel) panel.classList.add('active');
            if (btn)   btn.classList.add('active');

            document.getElementById('input-metode').value = tab;
        }

        function previewImage(input) {
            if (!input.files || !input.files[0]) return;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('upload-preview').innerHTML = `
                    <div style="position:relative;display:inline-block;">
                        <img src="${e.target.result}"
                             style="max-height:200px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                        <div style="position:absolute;top:-10px;right:-10px;background:#10b981;color:#fff;
                                    width:24px;height:24px;border-radius:50%;display:flex;
                                    align-items:center;justify-content:center;font-size:0.8rem;">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <p style="margin-top:0.75rem;font-weight:700;color:#10b981;font-size:0.85rem;">
                        Bukti Berhasil Dipilih
                    </p>
                    <p style="font-size:0.75rem;color:var(--brand);cursor:pointer;">Ganti Gambar</p>`;
            };
            reader.readAsDataURL(input.files[0]);
        }
        const checkoutForm = document.getElementById('checkoutForm');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                const btn = document.getElementById('btnSubmit');
                if (btn) {
                    btn.disabled = true;
                    const text = document.getElementById('btnText');
                    const spinner = document.getElementById('btnSpinner');
                    const icon = document.getElementById('btnIcon');
                    if (text) text.textContent = 'Memproses...';
                    if (spinner) spinner.style.display = 'block';
                    if (icon) icon.style.display = 'none';
                }
            });
        }
    </script>
@endpush