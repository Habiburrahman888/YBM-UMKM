<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="{{ $setting->tentang ?? 'YBM PLN UMKM' }}" />
    <title>@yield('title', 'YBM PLN UMKM') — {{ $setting->nama_expo ?? 'YBM PLN UMKM' }}</title>

    {{-- Favicon --}}
    @if ($setting?->logo_expo)
        <link rel="shortcut icon" href="{{ asset('storage/' . $setting->logo_expo) }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $setting->logo_expo) }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/' . $setting->logo_expo) }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/' . $setting->logo_expo) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('images/default-logo.png') }}">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    {{-- Icons & Libraries --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading {
            font-family: 'DM Serif Display', Georgia, serif;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .navbar-scrolled {
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        }

        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
    </style>
</head>

<body x-data="{ mobileOpen: false }" :class="{ 'overflow-hidden': mobileOpen }" class="bg-white text-neutral-800 antialiased">

    @include('partials.guest.navbar')

    <main class="min-h-[60vh] bg-white">
        @yield('content')
    </main>

    @include('partials.guest.footer')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('wishlist', {
                items: JSON.parse(localStorage.getItem('wishlist') || '[]'),

                toggle(id) {
                    this.includes(id) ?
                        (this.items = this.items.filter(i => i !== id)) :
                        this.items.push(id);
                    localStorage.setItem('wishlist', JSON.stringify(this.items));
                },

                includes(id) {
                    return this.items.includes(id);
                },

                get count() {
                    return this.items.length;
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
