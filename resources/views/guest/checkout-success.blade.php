@extends('layouts.guest')

@section('title', 'Pesanan Berhasil')

@push('styles')
    <style>
        :root {
            --brand: #1a3199;
            --brand-dark: #152780;
            --brand-soft: #eef1fb;
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }

        .success-card {
            background: #fff;
            border-radius: 20px;
            padding: 3rem 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            max-width: 650px;
            margin: 0 auto;
            text-align: center;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: #dcfce7;
            color: #166534;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 2rem;
            animation: bounceIn 0.8s var(--ease);
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }

        .success-title {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 2rem;
            color: #0f172a;
            margin-bottom: 0.75rem;
        }

        .success-desc {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .order-info-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2.5rem;
            border: 1px solid #f1f5f9;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .info-row:last-child { margin-bottom: 0; }

        .info-label { color: #94a3b8; font-weight: 500; }
        .info-value { color: #0f172a; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; }

        .btn-wa {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 1.15rem;
            background: #25d366;
            color: #fff;
            border: none;
            border-radius: 14px;
            font-weight: 800;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s var(--ease);
            box-shadow: 0 10px 20px rgba(37,211,102,0.25);
            text-decoration: none;
            margin-bottom: 1rem;
        }

        .btn-wa:hover {
            background: #128c7e;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37,211,102,0.35);
            color: #fff;
        }

        .btn-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 1.15rem;
            background: #fff;
            color: var(--brand);
            border: 2px solid var(--brand);
            border-radius: 14px;
            font-weight: 800;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s var(--ease);
            text-decoration: none;
        }

        .btn-check:hover {
            background: var(--brand-soft);
            transform: translateY(-2px);
        }

    </style>
@endpush

@section('content')
    <div class="bg-neutral-50 min-h-screen py-16 px-4">
        <div class="success-card">
            <div class="icon-box">
                <i class="fas fa-check"></i>
            </div>

            <h1 class="success-title">Pesanan Berhasil Dibuat!</h1>
            <p class="success-desc">
                Data pesanan Anda telah tersimpan di sistem kami. Langkah terakhir adalah mengonfirmasi pesanan tersebut kepada penjual melalui WhatsApp.
            </p>

            <div class="order-info-box">
                <div class="info-row">
                    <span class="info-label">ID Pesanan</span>
                    <span class="info-value">{{ $pesanan->uuid }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pembeli</span>
                    <span class="info-value">{{ $pesanan->nama_pembeli }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Harga</span>
                    <span class="info-value" style="color:#0d9488;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ $waLink }}" target="_blank" class="btn-wa" id="wa-btn">
                <i class="fab fa-whatsapp text-xl"></i>
                Kirim Konfirmasi ke WhatsApp
            </a>

            <a href="{{ route('guest.cek-pesanan', ['id_pesanan' => $pesanan->uuid, 'telepon' => $pesanan->telepon_pembeli]) }}" class="btn-check">
                <i class="fas fa-search"></i>
                Lihat Status Pesanan
            </a>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simpan ID Pesanan ke LocalStorage agar tidak hilang
            const orderData = {
                uuid: "{{ $pesanan->uuid }}",
                telepon: "{{ $pesanan->telepon_pembeli }}",
                nama_pembeli: "{{ $pesanan->nama_pembeli }}",
                nama_umkm: "{{ $pesanan->umkm->nama_usaha }}",
                tanggal: "{{ $pesanan->created_at->format('d M Y') }}",
                jam: "{{ $pesanan->created_at->format('H:i') }} WIB"
            };
            localStorage.setItem('last_order', JSON.stringify(orderData));

            // Tambahkan ke riwayat jika belum ada (opsional untuk fitur masa depan)
            let history = JSON.parse(localStorage.getItem('order_history') || '[]');
            if (!history.find(h => h.uuid === orderData.uuid)) {
                history.unshift(orderData);
                localStorage.setItem('order_history', JSON.stringify(history.slice(0, 5))); // Simpan 5 terakhir
            }
        });
    </script>
@endpush
