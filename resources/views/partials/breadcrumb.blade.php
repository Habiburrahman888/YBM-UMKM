<div class="px-4 sm:px-6 py-4">
    <div class="bg-white rounded-2xl border border-gray-100 px-5 py-3 shadow-sm w-full">
        <nav class="flex items-center text-sm gap-2" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-primary transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
            </a>

            @isset($breadcrumbs)
                @foreach ($breadcrumbs as $index => $breadcrumb)
                    <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>

                    @if (!empty($breadcrumb['url']) && $index !== count($breadcrumbs) - 1)
                        <a href="{{ $breadcrumb['url'] }}"
                            class="text-gray-500 hover:text-primary transition-colors whitespace-nowrap">
                            {{ $breadcrumb['name'] }}
                        </a>
                    @else
                        <span class="text-gray-900 font-medium whitespace-nowrap">
                            {{ $breadcrumb['name'] }}
                        </span>
                    @endif
                @endforeach
            @else
                <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium whitespace-nowrap">@yield('page-title', 'Dashboard')</span>
            @endisset
        </nav>
    </div>
</div>