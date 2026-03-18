{{-- Splash Screen - YBM UMKM | Soft Blue Gradient + Sparkle --}}
<div id="splashscreen" class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden"
    style="background: linear-gradient(135deg, #e0f0ff 0%, #c8e4f8 30%, #d6eeff 60%, #eaf6ff 100%);">

    {{-- Animated gradient overlay --}}
    <div class="absolute inset-0 pointer-events-none" id="splash-gradient-overlay"
        style="background: linear-gradient(220deg, #b8d9f5 0%, #dff0ff 40%, #c2e2f7 80%, #e8f5ff 100%);
               opacity: 0; animation: gradientShift 3s 0.3s ease forwards;">
    </div>

    {{-- Canvas untuk partikel --}}
    <canvas id="splash-canvas" class="absolute inset-0 pointer-events-none" style="width:100%;height:100%;"></canvas>

    {{-- Soft blobs --}}
    <div class="absolute rounded-full pointer-events-none"
        style="width:600px;height:600px;
               background:radial-gradient(circle, rgba(147,210,255,0.35) 0%, transparent 70%);
               top:-100px;left:-150px; animation:blobMove 6s ease-in-out infinite alternate;">
    </div>
    <div class="absolute rounded-full pointer-events-none"
        style="width:400px;height:400px;
               background:radial-gradient(circle, rgba(180,225,255,0.3) 0%, transparent 70%);
               bottom:-80px;right:-100px; animation:blobMove 7s 1s ease-in-out infinite alternate-reverse;">
    </div>

    {{-- Corner TL --}}
    <div class="absolute top-6 left-6 w-14 h-14 opacity-0" style="animation: cornerFade 1s 0.8s ease forwards;">
        <svg viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line x1="0" y1="20" x2="20" y2="20" stroke="#5AAEE0" stroke-width="1.5" />
            <line x1="20" y1="20" x2="20" y2="0" stroke="#5AAEE0" stroke-width="1.5" />
            <circle cx="20" cy="20" r="2.5" fill="#5AAEE0" />
            <line x1="0" y1="10" x2="10" y2="10" stroke="#A8D8F0" stroke-width="0.8" />
            <line x1="10" y1="10" x2="10" y2="0" stroke="#A8D8F0" stroke-width="0.8" />
        </svg>
    </div>

    {{-- Corner BR --}}
    <div class="absolute bottom-6 right-6 w-14 h-14 opacity-0"
        style="animation: cornerFade 1s 1s ease forwards; transform:rotate(180deg);">
        <svg viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line x1="0" y1="20" x2="20" y2="20" stroke="#5AAEE0" stroke-width="1.5" />
            <line x1="20" y1="20" x2="20" y2="0" stroke="#5AAEE0" stroke-width="1.5" />
            <circle cx="20" cy="20" r="2.5" fill="#5AAEE0" />
            <line x1="0" y1="10" x2="10" y2="10" stroke="#A8D8F0" stroke-width="0.8" />
            <line x1="10" y1="10" x2="10" y2="0" stroke="#A8D8F0" stroke-width="0.8" />
        </svg>
    </div>

    {{-- Center content --}}
    <div class="flex flex-col items-center z-10">

        {{-- Logo --}}
        <div class="mb-2 opacity-0" style="animation: zoomIn 0.9s 0.2s cubic-bezier(.22,1,.36,1) forwards;">
            @if ($setting?->logo_expo)
            <img src="{{ asset('storage/' . $setting->logo_expo) }}"
                alt="{{ $setting->nama_sekolah ?? 'YBM UMKM' }}" class="w-44"
                style="filter: drop-shadow(0 8px 32px rgba(60,140,210,0.25));">
            @else
            <img src="{{ asset('images/logo.png') }}" alt="YBM UMKM" class="w-44"
                style="filter: drop-shadow(0 8px 32px rgba(60,140,210,0.25));">
            @endif
        </div>
        {{-- Divider --}}
        <div class="flex items-center gap-3 mb-3 opacity-0"
            style="width:210px; animation: ruleIn 0.9s 0.6s ease forwards;">
            <div class="flex-1 h-px" style="background:linear-gradient(90deg,transparent,rgba(90,174,224,0.6));"></div>
            <div style="width:5px;height:5px;background:#3AABDE;transform:rotate(45deg);flex-shrink:0;"></div>
            <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(90,174,224,0.6),transparent);"></div>
        </div>

        {{-- Tagline --}}
        <div class="text-center mb-10 opacity-0"
            style="animation: fadeUp 0.9s 0.65s cubic-bezier(.22,1,.36,1) forwards;">
            <span class="block font-light mb-1"
                style="font-family:'Georgia',serif; font-size:19px; color:#1A4A6B; letter-spacing:0.02em;">
                Menjejak Manfaat
            </span>
            <span class="block font-light"
                style="font-size:10px; letter-spacing:0.3em; text-transform:uppercase; color:#6BAFD4;">
                Yayasan Baitul Mal
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="opacity-0" style="width:180px; animation: fadeUp 0.9s 0.85s ease forwards;">
            <div class="w-full overflow-hidden mb-2"
                style="height:2px; background:rgba(255,255,255,0.5); border-radius:2px;">
                <div id="splash-progress" class="h-full"
                    style="width:0%; border-radius:2px;
                           background:linear-gradient(90deg,#3AABDE,#76CCF0,#3AABDE);
                           background-size:200% 100%;
                           animation: shimmer 1.5s linear infinite;
                           transition: width 2.5s cubic-bezier(.4,0,.2,1);">
                </div>
            </div>
            <p class="text-center font-light"
                style="font-size:9px; letter-spacing:0.3em; text-transform:uppercase; color:#7BBFD8;">
                Memuat...
            </p>
        </div>

    </div>

    {{-- Bottom stamp --}}
    <p class="absolute bottom-5 font-light opacity-0"
        style="font-size:9px; letter-spacing:0.22em; text-transform:uppercase; color:#9DCCE4;
               animation: fadeUp 1s 1.5s ease forwards;">
        {{ $setting?->nama_sekolah ?? 'YBM PLN' }} &nbsp;·&nbsp; UMKM
    </p>

</div>

<style>
    @keyframes gradientShift {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes blobMove {
        from {
            transform: translate(0, 0) scale(1);
        }

        to {
            transform: translate(30px, 20px) scale(1.08);
        }
    }

    @keyframes cornerFade {
        from {
            opacity: 0;
            transform: scale(0.7);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes ruleIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.5);
            opacity: 0.6;
        }
    }

    @keyframes sparkleAnim {
        0% {
            opacity: 0;
            transform: scale(0) rotate(0deg);
        }

        50% {
            opacity: 1;
            transform: scale(1) rotate(180deg);
        }

        100% {
            opacity: 0;
            transform: scale(0) rotate(360deg);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Particle / Sparkle Canvas ──────────────────────────────────────
        const canvas = document.getElementById('splash-canvas');
        const ctx = canvas.getContext('2d');

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        const particles = [];
        const COLORS = ['#3AABDE', '#76CCF0', '#B0E0FF', '#FFFFFF', '#5DC4F0', '#A0D8EF'];

        function randomBetween(a, b) {
            return a + Math.random() * (b - a);
        }

        // Buat 60 partikel sparkle
        for (let i = 0; i < 60; i++) {
            particles.push({
                x: randomBetween(0, window.innerWidth),
                y: randomBetween(0, window.innerHeight),
                r: randomBetween(1, 3.5),
                color: COLORS[Math.floor(Math.random() * COLORS.length)],
                alpha: 0,
                speed: randomBetween(0.3, 1.2),
                dir: randomBetween(-1, 1),
                life: 0,
                maxLife: randomBetween(60, 160),
                delay: randomBetween(0, 120),
                type: Math.random() > 0.5 ? 'circle' : 'star',
            });
        }

        let frame = 0;
        let animId;

        function drawStar(ctx, x, y, r, color, alpha) {
            ctx.save();
            ctx.globalAlpha = alpha;
            ctx.fillStyle = color;
            ctx.translate(x, y);
            ctx.beginPath();
            for (let i = 0; i < 4; i++) {
                ctx.rotate(Math.PI / 2);
                ctx.moveTo(0, 0);
                ctx.lineTo(r * 0.4, r * 0.4);
                ctx.lineTo(0, r);
                ctx.lineTo(-r * 0.4, r * 0.4);
            }
            ctx.closePath();
            ctx.fill();
            ctx.restore();
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            frame++;

            particles.forEach(p => {
                if (frame < p.delay) return;

                p.life++;
                const progress = p.life / p.maxLife;
                // fade in then fade out
                p.alpha = progress < 0.3 ?
                    (progress / 0.3) :
                    progress < 0.7 ?
                    1 :
                    1 - ((progress - 0.7) / 0.3);
                p.alpha = Math.max(0, Math.min(0.85, p.alpha));

                p.y -= p.speed * 0.4;
                p.x += p.dir * 0.3;

                if (p.life >= p.maxLife) {
                    // respawn
                    p.x = randomBetween(0, canvas.width);
                    p.y = randomBetween(canvas.height * 0.2, canvas.height);
                    p.life = 0;
                    p.maxLife = randomBetween(60, 160);
                    p.delay = frame + randomBetween(0, 40);
                    p.color = COLORS[Math.floor(Math.random() * COLORS.length)];
                    p.r = randomBetween(1, 3.5);
                }

                if (p.type === 'star') {
                    drawStar(ctx, p.x, p.y, p.r * 2, p.color, p.alpha);
                } else {
                    ctx.save();
                    ctx.globalAlpha = p.alpha;
                    ctx.fillStyle = p.color;
                    ctx.shadowColor = p.color;
                    ctx.shadowBlur = 6;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();
                }
            });

            animId = requestAnimationFrame(animate);
        }
        animate();

        // ── Progress bar ──────────────────────────────────────────────────
        setTimeout(function() {
            const bar = document.getElementById('splash-progress');
            if (bar) bar.style.width = '100%';
        }, 150);

        // ── Hide splash setelah 3 detik ───────────────────────────────────
        setTimeout(function() {
            const splash = document.getElementById('splashscreen');
            if (splash) {
                splash.style.transition = 'opacity 0.6s ease';
                splash.style.opacity = '0';
                setTimeout(() => {
                    cancelAnimationFrame(animId);
                    splash.remove();
                }, 600);
            }
        }, 3000);
    });
</script>