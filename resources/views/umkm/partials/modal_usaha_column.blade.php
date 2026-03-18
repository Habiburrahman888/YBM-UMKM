@if ($item->modalUmkm->isNotEmpty())
    @php
        $modalCount = $item->modalUmkm->count();
        $modals = $item->modalUmkm;
    @endphp
    @if ($modalCount === 1)
        @php $modal = $modals->first(); @endphp
        <div class="flex items-center gap-2.5">
            @if ($modal->foto_cover)
                <img src="{{ asset('storage/' . $modal->foto_cover) }}"
                    class="w-9 h-9 rounded-lg object-cover flex-shrink-0 border border-gray-100">
            @else
                <div
                    class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0 border border-gray-100">
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
            <div class="min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">
                    {{ $modal->nama_item }}</div>
                <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                    <span class="text-[10px] text-gray-400 capitalize">{{ $modal->kategori_modal }}</span>
                    <span
                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium
            {{ $modal->kondisi === 'baru' ? 'bg-green-50 text-green-600' : ($modal->kondisi === 'baik' ? 'bg-blue-50 text-blue-600' : ($modal->kondisi === 'cukup' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600')) }}">
                        {{ ucfirst($modal->kondisi) }}
                    </span>
                </div>
                <div class="text-[11px] font-semibold text-gray-600 mt-0.5">
                    {{ $modal->nilai_rupiah }}</div>
            </div>
        </div>
    @else
        <div class="space-y-1.5">
            <div class="flex flex-wrap gap-1">
                @foreach ($modals->take(2) as $m)
                    <div class="relative inline-block" onmouseenter="showModalTooltip(this)"
                        onmouseleave="hideModalTooltip()" data-nama="{{ $m->nama_item }}"
                        data-kategori="{{ ucfirst($m->kategori_modal) }}" data-nilai="{{ $m->nilai_rupiah }}"
                        data-kondisi="{{ $m->kondisi }}" data-kondisi-label="{{ ucfirst($m->kondisi) }}">
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[11px] font-medium cursor-help hover:bg-gray-200 transition-colors">
                            {{ $m->nama_item }}
                        </span>
                    </div>
                @endforeach
                @if ($modalCount > 2)
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-500 text-[11px] font-medium"
                        title="{{ $modals->skip(2)->pluck('nama_item')->implode(', ') }}">
                        +{{ $modalCount - 2 }} lainnya
                    </span>
                @endif
            </div>
            <div class="text-[11px] text-gray-400">Total: <span
                    class="font-semibold text-gray-600">{{ $item->total_modal }}</span>
            </div>
        </div>
    @endif
@else
    <span class="text-sm text-gray-400">—</span>
@endif
