@extends('layouts.app')

@section('title', 'Pengaturan Profil UMKM')
@section('page-title', 'Pengaturan Profil')

@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Profil UMKM</h1>
                    <p class="text-gray-600 mt-1">Kelola branding dan informasi publik UMKM Anda</p>
                </div>
                <a href="{{ route('umkm.settings.edit') }}"
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Profil
                </a>
            </div>

            <!-- Logo UMKM -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">Logo UMKM</h2>
                @if ($umkm->logo_umkm)
                    <div class="flex items-start gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Logo</p>
                            <div class="inline-block border border-gray-200 rounded-lg p-3 bg-white">
                                <img src="{{ asset('storage/' . $umkm->logo_umkm) }}" alt="Logo UMKM"
                                    class="w-24 h-24 object-contain">
                            </div>
                        </div>
                        <div class="self-center">
                            <p class="text-sm text-gray-500">Logo saat ini</p>
                            <button type="button" onclick="confirmDeleteLogo()"
                                class="mt-2 inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium rounded-lg transition duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Logo
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-gray-400">Logo belum diupload</p>
                    </div>
                @endif
            </div>

            <!-- Tentang -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">Tentang UMKM</h2>
                @if ($umkm->tentang)
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tentang</p>
                    <p class="text-gray-800 text-sm whitespace-pre-line leading-relaxed">{{ $umkm->tentang }}</p>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-400">Belum ada deskripsi tentang UMKM</p>
                    </div>
                @endif
            </div>

            <!-- Informasi Pembayaran -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">Informasi Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- QRIS -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">QRIS Pembayaran</p>
                        @if ($umkm->qris_foto)
                            <div class="flex items-start gap-4">
                                <div class="inline-block border border-gray-200 rounded-lg p-3 bg-white">
                                    <img src="{{ asset('storage/' . $umkm->qris_foto) }}" alt="QRIS"
                                        class="w-40 h-40 object-contain">
                                </div>
                                <button type="button" onclick="confirmDeleteQris()"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium rounded-lg transition duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus QRIS
                                </button>
                            </div>
                        @else
                            <div class="py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-center">
                                <p class="text-sm text-gray-400">QRIS belum diupload</p>
                            </div>
                        @endif
                    </div>

                    <!-- Rekening Bank -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Daftar Rekening Bank
                        </p>
                        <div class="space-y-3">
                            @forelse($umkm->rekening as $rek)
                                @php
                                    $bk = strtolower($rek->nama_bank);
                                    $bg = '#E5E7EB';
                                    $fg = '#374151';
                                    $label = strtoupper(substr($rek->nama_bank, 0, 4));

                                    if (str_contains($bk, 'bca')) {
                                        $bg = '#004B87';
                                        $fg = '#FFFFFF';
                                        $label = 'BCA';
                                    } elseif (str_contains($bk, 'mandiri')) {
                                        $bg = '#1E4BA8';
                                        $fg = '#FFCC00';
                                        $label = 'MNDR';
                                    } elseif (str_contains($bk, 'bni')) {
                                        $bg = '#FF6600';
                                        $fg = '#FFFFFF';
                                        $label = 'BNI';
                                    } elseif (str_contains($bk, 'bri')) {
                                        $bg = '#003F7D';
                                        $fg = '#FFFFFF';
                                        $label = 'BRI';
                                    } elseif (str_contains($bk, 'bsi') || str_contains($bk, 'syariah indonesia')) {
                                        $bg = '#006E51';
                                        $fg = '#FFFFFF';
                                        $label = 'BSI';
                                    } elseif (str_contains($bk, 'btn')) {
                                        $bg = '#0066B3';
                                        $fg = '#FFFFFF';
                                        $label = 'BTN';
                                    } elseif (str_contains($bk, 'cimb')) {
                                        $bg = '#CC0001';
                                        $fg = '#FFFFFF';
                                        $label = 'CIMB';
                                    } elseif (str_contains($bk, 'ocbc')) {
                                        $bg = '#C8102E';
                                        $fg = '#FFFFFF';
                                        $label = 'OCBC';
                                    } elseif (str_contains($bk, 'danamon')) {
                                        $bg = '#EE3124';
                                        $fg = '#FFFFFF';
                                        $label = 'DNM';
                                    } elseif (str_contains($bk, 'permata')) {
                                        $bg = '#00704A';
                                        $fg = '#FFFFFF';
                                        $label = 'PMT';
                                    } elseif (str_contains($bk, 'maybank')) {
                                        $bg = '#F5A623';
                                        $fg = '#000000';
                                        $label = 'MBK';
                                    } elseif (str_contains($bk, 'mega')) {
                                        $bg = '#F97316';
                                        $fg = '#FFFFFF';
                                        $label = 'MEGA';
                                    } elseif (str_contains($bk, 'bukopin')) {
                                        $bg = '#359B2B';
                                        $fg = '#FFFFFF';
                                        $label = 'BKP';
                                    } elseif (str_contains($bk, 'panin')) {
                                        $bg = '#1E3A8A';
                                        $fg = '#FFFFFF';
                                        $label = 'PNB';
                                    } elseif (str_contains($bk, 'jago')) {
                                        $bg = '#FF5C00';
                                        $fg = '#FFFFFF';
                                        $label = 'JAGO';
                                    } elseif (str_contains($bk, 'seabank')) {
                                        $bg = '#EF6820';
                                        $fg = '#FFFFFF';
                                        $label = 'SEA';
                                    } elseif (str_contains($bk, 'blu')) {
                                        $bg = '#2563EB';
                                        $fg = '#FFFFFF';
                                        $label = 'blu';
                                    } elseif (str_contains($bk, 'allo')) {
                                        $bg = '#7C3AED';
                                        $fg = '#FFFFFF';
                                        $label = 'ALLO';
                                    } elseif (str_contains($bk, 'muamalat')) {
                                        $bg = '#006240';
                                        $fg = '#FFFFFF';
                                        $label = 'MML';
                                    } elseif (str_contains($bk, 'bjb') || str_contains($bk, 'jabar')) {
                                        $bg = '#004B9E';
                                        $fg = '#FFFFFF';
                                        $label = 'BJB';
                                    } elseif (str_contains($bk, 'jateng')) {
                                        $bg = '#005A9C';
                                        $fg = '#FFFFFF';
                                        $label = 'JTG';
                                    } elseif (str_contains($bk, 'jatim')) {
                                        $bg = '#003087';
                                        $fg = '#FFFFFF';
                                        $label = 'JTM';
                                    } elseif (str_contains($bk, 'gopay') || str_contains($bk, 'gojek')) {
                                        $bg = '#00AED6';
                                        $fg = '#FFFFFF';
                                        $label = 'GPAY';
                                    } elseif (str_contains($bk, 'dana')) {
                                        $bg = '#118EEA';
                                        $fg = '#FFFFFF';
                                        $label = 'DANA';
                                    } elseif (str_contains($bk, 'ovo')) {
                                        $bg = '#4C3494';
                                        $fg = '#FFFFFF';
                                        $label = 'OVO';
                                    } elseif (str_contains($bk, 'shopeepay') || str_contains($bk, 'shopee')) {
                                        $bg = '#EE4D2D';
                                        $fg = '#FFFFFF';
                                        $label = 'SPAY';
                                    } elseif (str_contains($bk, 'bpd')) {
                                        $bg = '#1D4ED8';
                                        $fg = '#FFFFFF';
                                    }
                                @endphp
                                <div
                                    class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 shadow-sm">
                                    <!-- Badge bank dengan warna brand -->
                                    <div class="w-14 h-14 flex-shrink-0 rounded-xl flex items-center justify-center"
                                        style="background-color: {{ $bg }};">
                                        <span class="font-extrabold text-center leading-tight px-1"
                                            style="color: {{ $fg }}; font-size: {{ strlen($label) > 3 ? '9px' : '13px' }}; font-family: Arial, sans-serif; letter-spacing: -0.5px;">
                                            {{ $label }}
                                        </span>
                                    </div>
                                    <!-- Info rekening -->
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide"
                                            style="color: {{ $bg }};">{{ strtoupper($rek->nama_bank) }}</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $rek->nomor_rekening }}</p>
                                        <p class="text-xs text-gray-500">a.n. {{ $rek->nama_rekening }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-center">
                                    <p class="text-sm text-gray-400">Belum ada data rekening</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">Sosial Media</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 inline-block mr-1 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                <path
                                    d="M12 0C5.373 0 0 5.373 0 12c0 2.124.558 4.121 1.535 5.856L.057 23.625a.75.75 0 00.918.918l5.769-1.478A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.716 9.716 0 01-4.964-1.363l-.355-.212-3.68.942.96-3.596-.232-.371A9.718 9.718 0 012.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z" />
                            </svg>
                            WhatsApp
                        </label>
                        @if ($umkm->telepon)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $umkm->telepon) }}" target="_blank"
                                class="text-primary-600 hover:text-primary-800 text-sm break-words block">
                                {{ $umkm->telepon }}
                            </a>
                        @else
                            <p class="text-sm text-gray-400">-</p>
                        @endif
                    </div>

                    <!-- Facebook -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 inline-block mr-1 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                            Facebook
                        </label>
                        @if ($umkm->facebook)
                            <a href="{{ $umkm->facebook }}" target="_blank"
                                class="text-primary-600 hover:text-primary-800 text-sm break-words block">
                                {{ $umkm->facebook }}
                            </a>
                        @else
                            <p class="text-sm text-gray-400">-</p>
                        @endif
                    </div>

                    <!-- Instagram -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 inline-block mr-1 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                            Instagram
                        </label>
                        @if ($umkm->instagram)
                            <a href="{{ $umkm->instagram }}" target="_blank"
                                class="text-primary-600 hover:text-primary-800 text-sm break-words block">
                                {{ $umkm->instagram }}
                            </a>
                        @else
                            <p class="text-sm text-gray-400">-</p>
                        @endif
                    </div>

                    <!-- YouTube -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 inline-block mr-1 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                            YouTube
                        </label>
                        @if ($umkm->youtube)
                            <a href="{{ $umkm->youtube }}" target="_blank"
                                class="text-primary-600 hover:text-primary-800 text-sm break-words block">
                                {{ $umkm->youtube }}
                            </a>
                        @else
                            <p class="text-sm text-gray-400">-</p>
                        @endif
                    </div>

                    <!-- TikTok -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 inline-block mr-1 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z" />
                            </svg>
                            TikTok
                        </label>
                        @if ($umkm->tiktok)
                            <a href="{{ $umkm->tiktok }}" target="_blank"
                                class="text-primary-600 hover:text-primary-800 text-sm break-words block">
                                {{ $umkm->tiktok }}
                            </a>
                        @else
                            <p class="text-sm text-gray-400">-</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDeleteLogo() {
            if (!confirm('Apakah Anda yakin ingin menghapus logo UMKM?')) return;
            fetch("{{ route('umkm.settings.logo.delete') }}", {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert(data.message || 'Gagal menghapus logo.');
                })
                .catch(() => alert('Terjadi kesalahan saat menghapus logo.'));
        }

        function confirmDeleteQris() {
            if (!confirm('Apakah Anda yakin ingin menghapus foto QRIS?')) return;
            fetch("{{ route('umkm.settings.qris.delete') }}", {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert(data.message || 'Gagal menghapus QRIS.');
                })
                .catch(() => alert('Terjadi kesalahan saat menghapus QRIS.'));
        }
    </script>
@endpush
