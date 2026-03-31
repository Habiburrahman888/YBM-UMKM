<div class="relative z-50" x-data="{
    open: false,
    activeProv: '{{ request('province') }}',
    detecting: false,
    selectedLabel: '@php
        $label = 'Semua Wilayah';
        $cityCode = request('city') ?? session('city');
        $provCode = request('province') ?? session('province');
        
        if($cityCode) {
            $c = \Laravolt\Indonesia\Models\City::where('code', $cityCode)->first();
            if($c) $label = $c->name;
        } elseif($provCode) {
            $p = \Laravolt\Indonesia\Models\Province::where('code', $provCode)->first();
            if($p) $label = $p->name;
        }
        echo $label;
    @endphp',
    detectLocation() {
        this.detecting = true;
        this.selectedLabel = 'Mencari...';
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung deteksi lokasi.');
            this.detecting = false;
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                window.location.href = `{{ route('guest.navigasi-terdekat') }}?lat=${lat}&lng=${lng}`;
            },
            (error) => {
                alert('Gagal mendapatkan lokasi. Pastikan izin lokasi aktif.');
                this.detecting = false;
            }
        );
    }
}" @click.away="open = false">

    {{-- Trigger Button --}}
    <button type="button" @click="open = !open"
        class="h-10 px-4 flex items-center gap-2 bg-white border border-neutral-200 rounded-full shadow-sm hover:border-blue-500 transition-all focus:outline-none relative z-50">
        <i class="fas fa-map-marker-alt text-blue-600 text-sm"></i>
        <span class="text-sm font-bold text-neutral-700 whitespace-nowrap" x-text="selectedLabel"></span>
        <i class="fas fa-chevron-down text-[10px] text-neutral-400 transition-transform duration-300"
            :class="open ? 'rotate-180' : ''"></i>
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="absolute z-[10000] mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-neutral-100 overflow-hidden right-0"
        style="display: none;">

        {{-- Header: Detect Location --}}
        <div class="p-4 border-b border-neutral-50 bg-blue-50/30">
            <button type="button" @click="detectLocation()" class="flex items-center gap-3 w-full text-left group" :disabled="detecting">
                <div
                    class="w-8 h-8 rounded-full bg-white border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fas fa-crosshairs text-xs" :class="detecting ? 'fa-spin' : ''"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-neutral-800" x-text="detecting ? 'Mendeteksi...' : 'Temukan Lokasi Terdekat'"></p>
                    <p class="text-[0.6rem] text-neutral-400">Deteksi lokasi perangkat Anda</p>
                </div>
            </button>
        </div>

        {{-- Scrollable Content --}}
        <div class="max-h-80 overflow-y-auto custom-scrollbar">

            {{-- Option: Semua Wilayah --}}
            <a href="{{ route($route, request()->except(['province', 'city'])) . ($route == 'guest.beranda' ? '#produk' : '') }}"
                class="flex items-center px-5 py-3 text-xs font-bold transition-colors {{ !request('province') ? 'bg-blue-50 text-blue-700' : 'text-neutral-600 hover:bg-neutral-50' }}">
                Semua Wilayah
            </a>

            <div class="px-5 py-2">
                <p class="text-[0.6rem] font-bold text-neutral-300 uppercase tracking-widest mb-2">Provinsi & Kota</p>

                <div class="flex flex-col gap-1">
                    @foreach ($grouped_locations as $prov)
                        <div x-data="{ expanded: activeProv === '{{ $prov->code }}' }">
                            <div @click="expanded = !expanded"
                                class="flex items-center justify-between py-2 cursor-pointer group">
                                <span
                                    class="text-[0.7rem] font-bold tracking-wide transition-colors {{ request('province') == $prov->code ? 'text-blue-600' : 'text-neutral-500 group-hover:text-neutral-900' }}">
                                    {{ strtoupper($prov->name) }}
                                </span>
                                <i class="fas fa-chevron-right text-[8px] transition-transform duration-200"
                                    :class="expanded ? 'rotate-90 text-blue-600' : 'text-neutral-300'"></i>
                            </div>

                            <div x-show="expanded" x-collapse
                                class="flex flex-col pl-2 border-l-2 border-blue-50 ml-0.5 my-1">
                                <a href="{{ route($route, array_merge(request()->except(['city']), ['province' => $prov->code])) . ($route == 'guest.beranda' ? '#produk' : '') }}"
                                    class="py-1.5 px-3 text-[0.75rem] font-semibold transition-all rounded-lg {{ request('province') == $prov->code && !request('city') ? 'text-blue-700 bg-blue-50' : 'text-neutral-400 hover:text-neutral-700 hover:bg-neutral-50' }}">
                                    Semua di {{ ucwords(strtolower($prov->name)) }}
                                </a>
                                @foreach ($prov->cities as $city)
                                    <a href="{{ route($route, array_merge(request()->except(['city']), ['province' => $prov->code, 'city' => $city->code])) . ($route == 'guest.beranda' ? '#produk' : '') }}"
                                        class="py-1.5 px-3 text-[0.75rem] font-semibold transition-all rounded-lg {{ request('city') == $city->code ? 'text-blue-700 bg-blue-50' : 'text-neutral-400 hover:text-neutral-700 hover:bg-neutral-50' }}">
                                        {{ $city->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
