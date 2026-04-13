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
            class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white/95
                   flex items-center justify-center shadow-sm
                   transition-all duration-200 hover:scale-110 focus:outline-none z-10"
            @click.prevent.stop="$store.wishlist.toggle('{{ $produk->id }}')" aria-label="Wishlist">
            <i class="fa-heart text-[10px]"
                :class="$store.wishlist.includes('{{ $produk->id }}') ? 'fas text-red-500' : 'far text-neutral-400'"></i>
        </button>
    </div>

    {{-- Body --}}
    <div class="flex flex-col flex-1 px-3 pt-2.5 pb-3 gap-1">

        {{-- UMKM info --}}
        <a href="{{ route('guest.detail-umkm', $produk->umkm->uuid ?? '#') }}"
            class="flex items-center gap-1 text-[10px] font-bold text-blue-500 uppercase tracking-wide truncate
                   hover:text-blue-600 transition-colors outline-none leading-none">
            <i class="fas fa-store text-[9px] shrink-0"></i>
            <span class="truncate">{{ Str::limit($produk->umkm?->nama_usaha ?? 'UMKM', 20) }}</span>
        </a>

        <div class="flex items-center gap-1 text-[10px] text-neutral-400 truncate leading-none">
            <i class="fas fa-location-dot text-[9px] shrink-0"></i>
            <span class="truncate">{{ Str::limit($produk->umkm?->lokasi_singkat ?? '-', 22) }}</span>
        </div>

        {{-- Nama + deskripsi --}}
        <div class="mt-0.5">
            <div class="text-sm font-bold text-neutral-800 leading-snug line-clamp-2">
                {{ $produk->nama_produk }}
            </div>
            <p class="text-[11px] text-neutral-400 line-clamp-2 leading-relaxed mt-0.5">
                {{ $produk->deskripsi_produk }}
            </p>
        </div>

        {{-- Harga + tombol --}}
        <div class="flex items-center justify-between mt-auto pt-2 gap-1">
            <span class="text-sm font-black text-neutral-900 tracking-tight leading-none whitespace-nowrap">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </span>
            <div class="flex items-center gap-1.5 shrink-0">
                <a href="{{ route('guest.detail-produk', $produk->uuid) }}"
                    class="flex items-center justify-center px-2.5 py-1.5 rounded-lg
                           bg-neutral-100 text-neutral-600 text-[11px] font-bold
                           hover:bg-blue-500 hover:text-white transition-all duration-200 whitespace-nowrap">
                    Detail
                </a>
                <a href="{{ route('guest.checkout', $produk->uuid) }}"
                    class="w-8 h-8 rounded-lg bg-neutral-900 text-white flex items-center justify-center
                           hover:bg-blue-600 transition-colors duration-200 shrink-0">
                    <i class="fas fa-cart-shopping text-[11px]"></i>
                </a>
            </div>
        </div>

    </div>
</div>