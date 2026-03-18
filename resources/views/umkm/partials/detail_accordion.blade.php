<div class="ml-5">
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        {{-- Informasi Usaha + Kontak & Lokasi --}}
        <div class="grid grid-cols-2 divide-x divide-gray-100">
            <div class="px-6 py-5">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                    Informasi Usaha</p>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-xs text-gray-400">Nama Usaha</dt>
                        <dd class="text-sm font-semibold text-gray-900 mt-0.5">
                            {{ $item->nama_usaha }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Nama Pemilik</dt>
                        <dd class="text-sm text-gray-700 mt-0.5">
                            {{ $item->nama_pemilik }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Kategori</dt>
                        <dd class="mt-0.5">
                            @if ($item->kategori)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-xs font-medium">{{ $item->kategori->nama }}</span>
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    @if ($item->tahun_berdiri)
                        <div>
                            <dt class="text-xs text-gray-400">Tahun Berdiri</dt>
                            <dd class="text-sm text-gray-700 mt-0.5">
                                {{ $item->tahun_berdiri }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
            <div class="px-6 py-5">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                    Kontak &amp; Lokasi</p>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-3">
                    @if ($item->telepon)
                        <div>
                            <dt class="text-xs text-gray-400">Telepon</dt>
                            <dd class="text-sm text-gray-700 mt-0.5">
                                {{ $item->telepon }}</dd>
                        </div>
                    @endif
                    @if ($item->email)
                        <div>
                            <dt class="text-xs text-gray-400">Email</dt>
                            <dd class="text-sm text-gray-700 mt-0.5 break-all">
                                {{ $item->email }}</dd>
                        </div>
                    @endif
                    @if ($item->alamat || $item->village || $item->district || $item->city || $item->province || $item->kode_pos)
                        <div class="col-span-2">
                            <dt class="text-xs text-gray-400 mb-0.5">Alamat Lengkap
                            </dt>
                            <dd class="text-sm text-gray-700 leading-relaxed">
                                {{ collect([$item->alamat, $item->village ? 'Kel. ' . $item->village->name : null, $item->district ? 'Kec. ' . $item->district->name : null, $item->city?->name, $item->province?->name, $item->kode_pos])->filter()->implode(', ') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Branding --}}
        <div class="border-t border-gray-100 px-6 py-5">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                Branding</p>
            @if ($item->tentang || $item->logo_umkm || $item->instagram || $item->facebook || $item->tiktok || $item->youtube)
                <div class="flex items-start gap-6">
                    @if ($item->logo_umkm)
                        <div class="flex-shrink-0">
                            <img src="{{ Storage::url($item->logo_umkm) }}" alt="Logo"
                                class="w-20 h-20 rounded-xl object-cover border border-gray-100 shadow-sm cursor-pointer hover:brightness-95 transition"
                                onclick="openGaleri(['{{ Storage::url($item->logo_umkm) }}'], 0)">
                        </div>
                    @endif
                    <div class="flex-1">
                        @if ($item->tentang)
                            <p class="text-sm text-gray-600 leading-relaxed italic line-clamp-3">"{{ $item->tentang }}"
                            </p>
                        @endif
                        <div class="flex items-center gap-4 mt-3">
                            @if ($item->instagram)
                                <a href="https://instagram.com/{{ str_replace('@', '', $item->instagram) }}"
                                    target="_blank"
                                    class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-pink-600 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                    {{ str_replace('@', '', $item->instagram) }}
                                </a>
                            @endif
                            @if ($item->facebook)
                                <a href="#"
                                    class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                    Facebook
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-400 italic">Belum ada informasi branding</p>
            @endif
        </div>

        {{-- Produk (Carousel Mini) --}}
        <div class="border-t border-gray-100 px-6 py-5 bg-gray-50/30">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Produk Unggulan</p>
            @if ($item->produkUmkm->isNotEmpty())
                <div class="relative overflow-visible">
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach ($item->produkUmkm as $produk)
                            <div
                                class="flex-shrink-0 w-64 bg-white rounded-xl border border-gray-100 p-3 shadow-sm hover:shadow-md transition-shadow group">
                                <div class="flex gap-3">
                                    @if (!empty($produk->foto_produk))
                                        <img src="{{ asset('storage/' . $produk->foto_produk[0]) }}"
                                            alt="{{ $produk->nama_produk }}"
                                            class="w-16 h-16 rounded-lg object-cover cursor-pointer hover:brightness-90 transition"
                                            onclick="openGaleri({{ json_encode($produk->foto_urls) }}, 0)">
                                    @else
                                        <div class="w-16 h-16 rounded-lg bg-gray-50 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-200" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 truncate">{{ $produk->nama_produk }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 leading-relaxed">
                                            {{ $produk->deskripsi_produk }}</p>
                                    </div>
                                </div>
                                <dl class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-gray-50">
                                    <div>
                                        <dt class="text-[10px] text-gray-400">Harga</dt>
                                        <dd class="text-xs font-bold text-primary">{{ $produk->harga_rupiah }}</dd>
                                    </div>
                                    <div class="text-right">
                                        <dt class="text-[10px] text-gray-400">Update</dt>
                                        <dd class="text-[10px] text-gray-500">
                                            {{ $produk->updated_at->format('d/m/y') }} WIB</dd>
                                    </div>
                                </dl>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-400 italic">Belum ada produk terdaftar</p>
            @endif
        </div>

        {{-- Modal Usaha --}}
        <div class="border-t border-gray-100 px-6 py-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Modal Usaha</p>
                @if ($item->modalUmkm->isNotEmpty())
                    <span class="text-xs text-gray-400">Total: <span
                            class="font-semibold text-gray-600">{{ $item->total_modal }}</span></span>
                @endif
            </div>
            @if ($item->modalUmkm->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                    @foreach ($item->modalUmkm as $modal)
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 flex gap-3">
                            @if ($modal->foto_cover)
                                <img src="{{ asset('storage/' . $modal->foto_cover) }}" alt="{{ $modal->nama_item }}"
                                    class="w-12 h-12 rounded-lg object-cover border border-gray-100 flex-shrink-0 cursor-pointer hover:brightness-90 transition"
                                    onclick="openGaleri({{ json_encode(array_map(fn($f) => asset('storage/' . $f), $modal->foto ?? [])) }}, 0)">
                            @else
                                <div
                                    class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $modal->nama_item }}</p>
                                <p class="text-xs text-gray-400 capitalize">{{ $modal->kategori_modal }}</p>
                                <p class="text-sm font-medium text-gray-700 mt-0.5">{{ $modal->nilai_rupiah }}</p>
                                <span
                                    class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium mt-1
                                {{ $modal->kondisi === 'baru' ? 'bg-green-50 text-green-600' : ($modal->kondisi === 'baik' ? 'bg-blue-50 text-blue-600' : ($modal->kondisi === 'cukup' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600')) }}">
                                    {{ ucfirst($modal->kondisi) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 italic">Belum ada data modal terdaftar</p>
            @endif
        </div>
    </div>
</div>
