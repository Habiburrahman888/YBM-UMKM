@php
    $fotos = $produk->foto_produk;
    $foto = is_array($fotos) ? $fotos[0] ?? null : null;
    $baseXShow = "!(\$store.wishlist?.showOnly ?? false) || \$store.wishlist?.includes('{$produk->id}')";
    $finalXShow = isset($xShowExtra) ? "({$xShowExtra}) && ({$baseXShow})" : $baseXShow;
@endphp

<div class="produk-card group relative bg-white rounded-2xl overflow-hidden flex flex-col
            shadow-[0_1px_3px_rgba(0,0,0,0.06),0_1px_2px_rgba(0,0,0,0.04)]
            hover:shadow-[0_8px_24px_rgba(0,0,0,0.10),0_2px_8px_rgba(0,0,0,0.06)]
            transition-all duration-300 ease-out hover:-translate-y-1.5 reveal"
    x-show="{!! $finalXShow !!}"
    x-transition:enter.duration.300ms {!! $attributes ?? '' !!}>

    {{-- Gambar --}}
    <a href="{{ route('guest.detail-produk', $produk->uuid) }}"
        class="produk-img relative block aspect-square overflow-hidden bg-neutral-50 outline-none focus:outline-none">

        @if ($foto)
            <img src="{{ asset('storage/' . $foto) }}" alt="{{ $produk->nama_produk }}" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.04]" />
        @else
            <div class="w-full h-full flex items-center justify-center text-neutral-200 text-4xl">
                <i class="fas fa-shopping-bag"></i>
            </div>
        @endif

        {{-- Wishlist --}}
        <button type="button"
            class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/95 backdrop-blur-sm
                   flex items-center justify-center shadow-[0_2px_8px_rgba(0,0,0,0.12)]
                   transition-all duration-200 hover:scale-110 hover:shadow-[0_4px_12px_rgba(0,0,0,0.15)]
                   focus:outline-none"
            @click.prevent.stop="$store.wishlist.toggle('{{ $produk->id }}')" aria-label="Wishlist">
            <i class="fa-heart text-[11px]"
                :class="$store.wishlist.includes('{{ $produk->id }}') ? 'fas text-red-500' : 'far text-neutral-400'"></i>
        </button>
    </a>

    {{-- Body --}}
    <div class="flex flex-col gap-1.5 px-4 pt-3.5 pb-4 flex-1">

        <div class="produk-meta flex flex-col gap-1 min-w-0">
            <a href="{{ route('guest.detail-umkm', $produk->umkm->uuid ?? '#') }}"
                class="flex items-center gap-1.5 text-xs font-bold text-blue-500 uppercase
                       tracking-wide truncate hover:text-blue-600 transition-colors outline-none">
                <i class="fas fa-store text-[10px] shrink-0"></i>
                {{ Str::limit($produk->umkm?->nama_usaha ?? 'UMKM', 24) }}
            </a>
            <div class="flex items-center gap-1.5 text-xs text-neutral-400 truncate">
                <i class="fas fa-location-dot text-[10px] shrink-0"></i>
                {{ $produk->umkm?->lokasi_singkat ?? 'Lokasi tidak tersedia' }}
            </div>
        </div>

        {{-- Nama Produk --}}
        <div class="flex flex-col gap-1">
            <a href="{{ route('guest.detail-produk', $produk->uuid) }}"
                class="produk-nama text-base font-bold text-neutral-800 leading-tight line-clamp-2
                       hover:text-blue-600 transition-colors outline-none">
                {{ $produk->nama_produk }}
            </a>
            
            {{-- Deskripsi Singkat --}}
            <p class="text-xs text-neutral-500 line-clamp-2 leading-relaxed">
                {{ $produk->deskripsi_produk }}
            </p>
        </div>

        {{-- Harga + Beli --}}
        <div class="flex items-center justify-between mt-auto pt-3">
            <span class="produk-harga text-lg font-black text-neutral-900 tracking-tight">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </span>
            <div class="flex items-center gap-2">
                <a href="{{ route('guest.detail-produk', $produk->uuid) }}"
                   class="btn-detail-card hidden md:flex items-center justify-center px-3 py-2 rounded-xl bg-neutral-100 text-neutral-600 text-xs font-bold hover:bg-neutral-200 transition-colors">
                    Detail
                </a>
                <a href="{{ route('guest.checkout', $produk->uuid) }}"
                    class="btn-buy w-10 h-10 rounded-xl bg-neutral-900 text-white flex items-center justify-center
                           text-sm hover:bg-blue-600 transition-colors duration-200 shrink-0 outline-none">
                    <i class="fas fa-cart-shopping"></i>
                </a>
            </div>
        </div>

    </div>
</div>
