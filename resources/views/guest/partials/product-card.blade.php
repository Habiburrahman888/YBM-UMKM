@php
    $fotos = $produk->foto_produk;
    $foto = is_array($fotos) ? $fotos[0] ?? null : null;
@endphp

{{--
    CATATAN:
    - Tidak ada class "reveal" (akan bikin opacity:0, merusak carousel)
    - Tidak ada x-show (Alpine conflict dengan flex carousel)
    - Lebar diatur sepenuhnya oleh wrapper .carousel-card di beranda
--}}
<div class="produk-card group relative bg-white rounded-2xl overflow-hidden flex flex-col w-full h-full
            border border-neutral-100
            shadow-[0_1px_4px_rgba(0,0,0,0.06)]
            hover:shadow-[0_8px_24px_rgba(0,0,0,0.10)]
            transition-all duration-300 ease-out hover:-translate-y-1" {!! $attributes ?? '' !!}>

    {{-- Gambar --}}
    <div class="relative overflow-hidden bg-neutral-50 flex-shrink-0" style="aspect-ratio:1/1;">
        @if ($foto)
            <img src="{{ asset('storage/' . $foto) }}" alt="{{ $produk->nama_produk }}" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" />
        @else
            <div class="w-full h-full flex items-center justify-center text-neutral-200">
                <i class="fas fa-shopping-bag text-4xl"></i>
            </div>
        @endif

        {{-- Wishlist button --}}
        <button type="button"
            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/95
                   flex items-center justify-center shadow-sm
                   transition-all duration-200 hover:scale-110 focus:outline-none z-10"
            @click.prevent.stop="$store.wishlist.toggle('{{ $produk->id }}')" aria-label="Wishlist">
            <i class="fa-heart text-xs"
                :class="$store.wishlist.includes('{{ $produk->id }}') ? 'fas text-red-500' : 'far text-neutral-400'"></i>
        </button>
    </div>

    {{-- Body --}}
    <div class="flex flex-col flex-1 px-4 pt-4 pb-4 gap-2">

        {{-- UMKM info --}}
        <a href="{{ route('guest.detail-umkm', $produk->umkm->uuid ?? '#') }}"
            class="flex items-center gap-1.5 text-xs font-bold text-blue-500 uppercase tracking-wide truncate
                   hover:text-blue-600 transition-colors outline-none leading-none">
            <i class="fas fa-store text-[10px] shrink-0"></i>
            <span class="truncate">{{ Str::limit($produk->umkm?->nama_usaha ?? 'UMKM', 25) }}</span>
        </a>

        <div class="flex items-center gap-1.5 text-xs text-neutral-400 truncate leading-none">
            <i class="fas fa-location-dot text-[10px] shrink-0"></i>
            <span class="truncate">{{ Str::limit($produk->umkm?->lokasi_singkat ?? '-', 28) }}</span>
        </div>

        {{-- Nama + deskripsi --}}
        <div class="mt-1">
            <div class="text-base font-bold text-neutral-800 leading-snug line-clamp-2">
                {{ $produk->nama_produk }}
            </div>
            <p class="text-[13px] text-neutral-400 line-clamp-2 leading-relaxed mt-1">
                {{ $produk->deskripsi_produk }}
            </p>
        </div>

        {{-- Harga + tombol --}}
        <div class="flex items-center justify-between mt-auto pt-4 gap-2">
            <span class="text-base font-black text-neutral-900 tracking-tight leading-none whitespace-nowrap">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </span>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('guest.detail-produk', $produk->uuid) }}"
                    class="flex items-center justify-center px-3 py-2 rounded-xl
                           bg-neutral-100 text-neutral-600 text-xs font-bold
                           hover:bg-blue-500 hover:text-white transition-all duration-200 whitespace-nowrap">
                    Detail
                </a>
                <a href="{{ route('guest.checkout', $produk->uuid) }}"
                    class="w-10 h-10 rounded-xl bg-neutral-900 text-white flex items-center justify-center
                           hover:bg-blue-600 transition-colors duration-200 shrink-0">
                    <i class="fas fa-cart-shopping text-xs"></i>
                </a>
            </div>
        </div>

    </div>
</div>