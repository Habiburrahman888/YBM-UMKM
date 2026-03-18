{{-- FOOTER --}}

{{-- ── Lengkungan ── --}}
<div class="w-full overflow-hidden leading-none -mb-1" style="background: #f8fafc;">
    <svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg"
         preserveAspectRatio="none"
         class="w-full block" style="height: 70px;">
        <path d="M0,0 Q600,120 1200,0 L1200,120 L0,120 Z"
              fill="#1e2d45"/>
    </svg>
</div>

<footer style="background: linear-gradient(to bottom, #1e2d45, #0f3d4a);" class="text-white/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-6 sm:pb-8">

        {{-- Grid utama --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-[2fr_1fr_1.5fr] gap-8 sm:gap-12 lg:gap-16 pt-10 sm:pt-12 lg:pt-14 mb-10 sm:mb-12 footer-fade">

            {{-- ── Brand ── --}}
            <div>
                @if ($setting?->logo_expo)
                    <div class="flex items-center gap-3 mb-5">
                        <img src="{{ asset('storage/' . $setting->logo_expo) }}"
                             alt="{{ $setting->nama_expo }}"
                             class="w-12 h-12 object-contain rounded-xl">
                        <div>
                            <p class="font-heading text-base font-bold text-white leading-tight">
                                {{ $setting->nama_expo ?? 'YBM UMKM' }}
                            </p>
                            <p class="text-[10px] text-blue-400 font-semibold uppercase tracking-widest mt-0.5">
                                Marketplace UMKM Nusantara
                            </p>
                        </div>
                    </div>
                @else
                    <p class="font-heading text-base font-bold text-white leading-tight mb-1">
                        {{ $setting->nama_expo ?? 'YBM UMKM' }}
                    </p>
                    <p class="text-[10px] text-blue-400 font-semibold uppercase tracking-widest mb-5">
                        Marketplace UMKM Nusantara
                    </p>
                @endif

                <p class="text-sm leading-relaxed text-white/65 mb-5 text-justify">
                    {{ $setting->tentang ?? 'Platform marketplace resmi untuk UMKM binaan ' . ($setting->nama_expo ?? 'YBM UMKM') . ' seluruh Indonesia, menghadirkan produk lokal berkualitas tinggi.' }}
                </p>

                {{-- Sosial Media --}}
                <div class="flex gap-2">
                    @if ($sosmed?->facebook)
                        <a href="{{ $sosmed->facebook }}" target="_blank"
                           class="w-9 h-9 rounded-xl bg-white/8 flex items-center justify-center text-white/65
                                  hover:bg-blue-600 hover:text-white transition-all hover:-translate-y-0.5">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                    @endif
                    @if ($sosmed?->instagram)
                        <a href="{{ $sosmed->instagram }}" target="_blank"
                           class="w-9 h-9 rounded-xl bg-white/8 flex items-center justify-center text-white/65
                                  hover:bg-pink-600 hover:text-white transition-all hover:-translate-y-0.5">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                    @endif
                    @if ($sosmed?->youtube)
                        <a href="{{ $sosmed->youtube }}" target="_blank"
                           class="w-9 h-9 rounded-xl bg-white/8 flex items-center justify-center text-white/65
                                  hover:bg-red-600 hover:text-white transition-all hover:-translate-y-0.5">
                            <i class="fab fa-youtube text-sm"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- ── Navigasi ── --}}
            <div class="flex flex-col items-start text-left pl-8 sm:pl-12 lg:pl-16">
                <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-5">
                    Navigasi
                </h4>
                <div class="space-y-2.5">
                    <a href="{{ route('guest.beranda') }}" class="block text-sm text-white/65 hover:text-white transition-all">Beranda</a>
                    <a href="{{ route('guest.katalog') }}" class="block text-sm text-white/65 hover:text-white transition-all">Katalog Produk</a>
                    <a href="{{ route('guest.umkm') }}"   class="block text-sm text-white/65 hover:text-white transition-all">Daftar UMKM</a>
                    <a href="{{ route('login') }}"        class="block text-sm text-white/65 hover:text-white transition-all">Masuk</a>
                </div>
            </div>

            {{-- ── Kontak ── --}}
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-5">
                    Hubungi Kami
                </h4>
                <div class="space-y-3.5">
                    @if ($setting?->alamat)
                        <div class="flex gap-3 text-sm">
                            <i class="fas fa-map-marker-alt text-white/40 mt-0.5 shrink-0 text-xs"></i>
                            <span class="text-white/65 leading-relaxed">{{ $setting->alamat }}</span>
                        </div>
                    @endif
                    @if ($setting?->phone)
                        <div class="flex gap-3 text-sm">
                            <i class="fas fa-phone text-white/40 mt-0.5 shrink-0 text-xs"></i>
                            <a href="tel:{{ $setting->phone }}" class="text-white/65 hover:text-white transition-colors">
                                {{ $setting->phone }}
                            </a>
                        </div>
                    @endif
                    @if ($setting?->email)
                        <div class="flex gap-3 text-sm">
                            <i class="fas fa-envelope text-white/40 mt-0.5 shrink-0 text-xs"></i>
                            <a href="mailto:{{ $setting->email }}" class="text-white/65 hover:text-white transition-colors break-all">
                                {{ $setting->email }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ── Footer Bottom ── --}}
        <div class="pt-5 sm:pt-6 border-t border-white/10 flex items-center justify-center text-[11px] sm:text-xs text-white/40 footer-fade">
            <p>&copy; {{ date('Y') }} {{ $setting->nama_expo ?? 'YBM UMKM' }}. Seluruh Hak Cipta Dilindungi.</p>
        </div>

    </div>
</footer>

<style>
    .footer-fade {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity .7s ease, transform .7s ease;
    }
    .footer-fade.footer-visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
    (function () {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    setTimeout(() => e.target.classList.add('footer-visible'), i * 120);
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.footer-fade').forEach(el => io.observe(el));
    })();
</script>