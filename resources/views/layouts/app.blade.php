<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @if ($setting?->logo_expo)
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->logo_expo) }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . $setting->logo_expo) }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/' . $setting->logo_expo) }}">
    @endif

    <title>
        {{ $setting?->nama_sekolah ? $setting->nama_sekolah . ' - ' : '' }}@yield('title')
    </title>
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">

    @include('partials.splashscreen')

    <div class="flex h-screen overflow-hidden">
        @include('partials.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('partials.navbar')

            <main class="flex-1 overflow-y-auto bg-gray-50 relative">
                @include('partials.breadcrumb')

                <div class="p-6">
                    @yield('content')
                </div>
            </main>

            @include('partials.footer')
        </div>
    </div>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    @if (session('success'))
    <div id="flash-success"
        class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 animate-slide-in max-w-md cursor-pointer transition-opacity duration-300">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div id="flash-error"
        class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 animate-slide-in max-w-md cursor-pointer transition-opacity duration-300">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flashDuration = 5000;
            const flashIDs = ['flash-success', 'flash-error'];

            flashIDs.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    // Auto-hide after duration
                    setTimeout(() => hideFlash(element), flashDuration);

                    // Hide on click
                    element.addEventListener('click', () => hideFlash(element));
                }
            });

            function hideFlash(element) {
                if (!element) return;
                element.style.transition = 'all 0.5s ease-in-out';
                element.style.opacity = '0';
                element.style.transform = 'translateX(100px)';
                setTimeout(() => {
                    element.remove();
                }, 500);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>