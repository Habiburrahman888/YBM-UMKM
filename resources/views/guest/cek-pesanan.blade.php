@extends('layouts.guest')

@section('title', 'Cek Status Pesanan')

@push('styles')
    <style>
        :root {
            --brand: #1a3199;
            --brand-dark: #152780;
            --brand-soft: #eef1fb;
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }

        .card-tracking {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin: 0 auto;
        }

        .section-title {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 1.75rem;
            color: #0f172a;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1.25rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--brand);
            outline: none;
            box-shadow: 0 0 0 4px rgba(26, 49, 153, 0.1);
        }

        .btn-track {
            width: 100%;
            padding: 1rem;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 16px rgba(26, 49, 153, 0.2);
            margin-top: 1rem;
        }

        .btn-track:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(26, 49, 153, 0.3);
        }

        /* ── STATUS TRACKER ── */
        .status-container {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .order-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
        }

        .order-meta-item .label {
            font-size: 0.75rem;
            color: #64748b;
            display: block;
        }

        .order-meta-item .value {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
        }

        .stepper {
            list-style: none;
            padding: 0;
            position: relative;
        }

        .stepper::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .step-item {
            position: relative;
            padding-left: 50px;
            margin-bottom: 2.5rem;
        }

        .step-dot {
            position: absolute;
            left: 0;
            top: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: all 0.3s;
        }

        .step-item.active .step-dot {
            border-color: var(--brand);
            background: var(--brand);
            color: #fff;
            box-shadow: 0 0 0 4px rgba(26, 49, 153, 0.15);
        }

        .step-item.completed .step-dot {
            border-color: #22c55e;
            background: #22c55e;
            color: #fff;
        }

        .step-content .step-title {
            display: block;
            font-size: 1rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.25rem;
        }

        .step-item.active .step-title {
            color: var(--brand);
        }

        .step-content .step-desc {
            font-size: 0.85rem;
            color: #64748b;
        }

        .badge-status {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-diproses {
            background: #e0f2fe;
            color: #075985;
        }

        .badge-dikirim {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-selesai {
            background: #dcfce7;
            color: #166534;
        }

        .badge-dibatalkan {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 640px) {
            .card-tracking {
                padding: 1.5rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .order-meta {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="bg-neutral-50 min-h-screen py-12 px-4">
        <div class="max-w-7xl mx-auto">

            <div class="card-tracking">
                <h1 class="section-title">Lacak Pesanan Saya</h1>

                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('guest.search-pesanan') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div class="form-group">
                            <label class="form-label">ID Pesanan (UUID)</label>
                            <input type="text" name="id_pesanan" class="form-control"
                                placeholder="Contoh: 123e4567-e89b-12d3..."
                                value="{{ old('id_pesanan', isset($pesanan) ? $pesanan->uuid : '') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp Pembeli</label>
                            <input type="text" name="telepon" class="form-control" placeholder="Contoh: 08123456789"
                                value="{{ old('telepon', isset($pesanan) ? $pesanan->telepon_pembeli : '') }}" required>
                        </div>

                        <button type="submit" class="btn-track">
                            <i class="fas fa-search mr-2"></i> Periksa Status
                        </button>

                        @if (isset($pesanan) || old('id_pesanan'))
                            <div class="text-center mt-4">
                                <a href="{{ route('guest.cek-pesanan') }}"
                                    class="text-sm font-bold text-slate-500 hover:text-brand transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-sync-alt text-xs"></i> Lacak Pesanan Lain
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Order History --}}
                    <div id="order-history-section" class="mt-8 hidden">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-history"></i> Riwayat Pesanan Terakhir
                        </h3>
                        <div id="history-list" class="space-y-3">
                            <!-- Items will be injected by JS -->
                        </div>
                    </div>
                </form>

                @if (isset($pesanan))
                    <div class="status-container">
                        <div class="order-meta">
                            <div class="order-meta-item">
                                <span class="label">Status Saat Ini</span>
                                <span class="badge-status badge-{{ $pesanan->status }}">
                                    @if ($pesanan->status == 'pending')
                                        Pending
                                    @elseif($pesanan->status == 'diproses')
                                        Diproses
                                    @elseif($pesanan->status == 'dikirim')
                                        Sedang Diantar
                                    @elseif($pesanan->status == 'selesai')
                                        Selesai
                                    @elseif($pesanan->status == 'dibatalkan')
                                        Dibatalkan
                                    @else
                                        {{ ucfirst($pesanan->status) }}
                                    @endif
                                </span>
                            </div>
                            <div class="order-meta-item text-right">
                                <span class="label">Total Pembayaran</span>
                                <span class="value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <ul class="stepper">
                            <li
                                class="step-item {{ in_array($pesanan->status, ['pending', 'diproses', 'dikirim', 'selesai']) ? 'completed' : '' }}">
                                <div class="step-dot"><i class="fas fa-file-invoice"></i></div>
                                <div class="step-content">
                                    <span class="step-title">Pesanan Diterima</span>
                                    <p class="step-desc">Pesanan Anda telah masuk dan menunggu verifikasi pembayaran.</p>
                                </div>
                            </li>

                            <li
                                class="step-item {{ $pesanan->status == 'diproses' ? 'active' : (in_array($pesanan->status, ['dikirim', 'selesai']) ? 'completed' : '') }}">
                                <div class="step-dot"><i class="fas fa-box-open"></i></div>
                                <div class="step-content">
                                    <span class="step-title">Sedang Diproses</span>
                                    <p class="step-desc">Penjual sedang menyiapkan pesanan atau memproses pengiriman.</p>
                                </div>
                            </li>

                            <li
                                class="step-item {{ $pesanan->status == 'dikirim' ? 'active' : ($pesanan->status == 'selesai' ? 'completed' : '') }}">
                                <div class="step-dot"><i class="fas fa-truck-loading"></i></div>
                                <div class="step-content">
                                    <span class="step-title">Sedang Diantar</span>
                                    <p class="step-desc">Pesanan Anda sedang dalam perjalanan menuju alamat pengiriman.</p>
                                </div>
                            </li>

                            <li class="step-item {{ $pesanan->status == 'selesai' ? 'active completed' : '' }}">
                                <div class="step-dot"><i class="fas fa-check"></i></div>
                                <div class="step-content">
                                    <span class="step-title">Selesai</span>
                                    <p class="step-desc">Pesanan telah sampai atau sudah diambil oleh pembeli.</p>
                                </div>
                            </li>
                        </ul>

                        {{-- Review Button placeholder for later --}}
                        @if ($pesanan->status == 'completed')
                            <div class="mt-8 p-4 bg-brand-soft rounded-xl border border-blue-100 text-center">
                                <p class="text-sm font-semibold text-brand mb-2">Puas dengan pesanannya?</p>
                                <button class="btn-track mt-0"
                                    style="background:#0d9488;box-shadow:0 8px 16px rgba(13,148,136,0.2);">
                                    <i class="fas fa-star mr-2"></i> Beri Ulasan (Segera Hadir)
                                </button>
                            </div>
                        @endif

                        <div class="mt-6 text-center">
                            <p class="text-xs text-neutral-400">Hubungi penjual via WhatsApp jika ada pertanyaan.</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesanan->umkm->telepon) }}"
                                class="text-brand font-bold text-sm hover:underline mt-1 inline-block">
                                <i class="fab fa-whatsapp mr-1"></i> Chat {{ $pesanan->umkm->nama_usaha }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('guest.beranda') }}"
                    class="text-neutral-500 hover:text-brand font-semibold text-sm transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const history = JSON.parse(localStorage.getItem('order_history') || '[]');
            const historySection = document.getElementById('order-history-section');
            const historyList = document.getElementById('history-list');
            const inputUuid = document.querySelector('input[name="id_pesanan"]');
            const inputTelp = document.querySelector('input[name="telepon"]');

            if (history.length > 0 && !inputUuid.value) {
                historySection.classList.remove('hidden');

                history.forEach((order, index) => {
                    const item = document.createElement('div');
                    item.className =
                        'group p-3 bg-slate-50 border border-slate-100 rounded-xl hover:bg-white hover:border-brand-soft hover:shadow-md transition-all cursor-pointer';
                    item.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold text-slate-800">${order.nama_umkm}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">${order.nama_pembeli || 'Pembeli'} • ${order.tanggal} ${order.jam || ''}</div>
                            </div>
                            <i class="fas fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand transition-colors"></i>
                        </div>
                    `;

                    item.onclick = () => {
                        inputUuid.value = order.uuid;
                        inputTelp.value = order.telepon;

                        // Feedback visual
                        inputUuid.focus();
                        inputUuid.classList.add('ring-2', 'ring-brand/20');
                        setTimeout(() => inputUuid.classList.remove('ring-2', 'ring-brand/20'), 1000);

                        // Opsional: Langsung submit
                        // item.closest('form').submit();
                    };

                    historyList.appendChild(item);
                });
            }
        });
    </script>
@endpush
