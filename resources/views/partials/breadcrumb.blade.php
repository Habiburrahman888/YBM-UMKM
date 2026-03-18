<div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-3 sm:py-4">
    <nav class="flex items-center text-xs sm:text-sm overflow-x-auto scrollbar-hide" aria-label="Breadcrumb">
        <a href="" class="text-gray-500 hover:text-blue-600 transition-colors duration-200 flex-shrink-0"
            aria-label="Dashboard">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
        </a>

        @isset($breadcrumbs)
            @foreach ($breadcrumbs as $index => $breadcrumb)
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mx-1 sm:mx-2 text-gray-400 flex-shrink-0" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>

                @if (!empty($breadcrumb['url']) && $index !== count($breadcrumbs) - 1)
                    <a href="{{ $breadcrumb['url'] }}"
                        class="text-gray-600 hover:text-blue-600 transition-colors duration-200 whitespace-nowrap flex-shrink-0">
                        <span class="hidden sm:inline">{{ $breadcrumb['name'] }}</span>
                        <span class="sm:hidden">{{ Str::limit($breadcrumb['name'], 20) }}</span>
                    </a>
                @else
                    <span class="text-gray-700 font-medium whitespace-nowrap flex-shrink-0">
                        <span class="hidden sm:inline">{{ $breadcrumb['name'] }}</span>
                        <span class="sm:hidden">{{ Str::limit($breadcrumb['name'], 20) }}</span>
                    </span>
                @endif
            @endforeach
        @endisset
    </nav>
</div>

<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
