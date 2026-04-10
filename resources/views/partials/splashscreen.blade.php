<div id="splashscreen" style="position:fixed; inset:0; z-index:9999; overflow:hidden;">
    <canvas id="splash-canvas" style="position:absolute; inset:0; width:100%; height:100%;"></canvas>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const splash = document.getElementById('splashscreen');
        const canvas = document.getElementById('splash-canvas');
        const ctx = canvas.getContext('2d');

        let W, H, animId, startTime = null,
            done = false;
        let offFull, phase = 'idle';

        const LOGO_SRC =
            "{{ $setting?->logo_expo ? asset('storage/' . $setting->logo_expo) : asset('images/logo.png') }}";
        const TITLE = 'Menjejak Manfaat';
        const SUBTITLE = 'YAYASAN BAITUL MAL';
        const LOGO_SIZE = Math.min(window.innerWidth * 0.30, 150);

        const D = {
            LOGO_IN: 600,
            RING_SPIN: 1800,
            RING_OUT: 400,
            TEXT_IN: 600,
            FULL_HOLD: 1200,
            FADE_OUT: 600,
            GAP: 300
        };
        const TOTAL = D.LOGO_IN + D.RING_SPIN + D.RING_OUT + D.TEXT_IN + D.FULL_HOLD + D.FADE_OUT + D.GAP;

        const logoImg = new Image();
        logoImg.src = LOGO_SRC;
        logoImg.onload = buildOffFull;

        function resize() {
            W = canvas.width = window.innerWidth;
            H = canvas.height = window.innerHeight;
            buildOffFull();
        }

        function drawBg(c) {
            const grad = c.createLinearGradient(0, 0, 0, H);
            grad.addColorStop(0, '#e8f0f7');
            grad.addColorStop(1, '#f5f9fc');
            c.fillStyle = grad;
            c.fillRect(0, 0, W, H);
        }

        function drawLogo(c, cx, cy, size, alpha) {
            if (!logoImg.complete || !logoImg.naturalWidth) return;
            c.save();
            c.globalAlpha = alpha;
            const aspect = logoImg.naturalHeight / logoImg.naturalWidth;
            const lw = size,
                lh = size * aspect;
            c.drawImage(logoImg, cx - lw / 2, cy - lh / 2, lw, lh);
            c.restore();
        }

        function drawRing(cx, cy, size, progress, ringAlpha, spinAngle) {
            const R = size * 0.58;
            ctx.save();
            ctx.globalAlpha = ringAlpha;

            ctx.beginPath();
            ctx.arc(cx, cy, R, 0, Math.PI * 2);
            ctx.strokeStyle = 'rgba(29,111,164,0.18)';
            ctx.lineWidth = 2;
            ctx.stroke();

            let arcLen;
            if (progress < 0.4) arcLen = (progress / 0.4) * Math.PI * 1.7;
            else if (progress < 0.75) arcLen = Math.PI * 1.7;
            else arcLen = Math.PI * 1.7 * (1 - (progress - 0.75) / 0.25);

            const steps = 40;
            for (let i = 0; i < steps; i++) {
                const t0 = i / steps,
                    t1 = (i + 1) / steps;
                ctx.beginPath();
                ctx.arc(cx, cy, R, spinAngle + t0 * arcLen, spinAngle + t1 * arcLen);
                ctx.strokeStyle = `rgba(29,111,164,${0.18 + (i / steps) * 0.72})`;
                ctx.lineWidth = 2.5;
                ctx.lineCap = 'round';
                ctx.stroke();
            }

            const tipAngle = spinAngle + arcLen;
            ctx.beginPath();
            ctx.arc(cx + Math.cos(tipAngle) * R, cy + Math.sin(tipAngle) * R, 3.5, 0, Math.PI * 2);
            ctx.fillStyle = '#2980b9';
            ctx.fill();

            [-1, 1].forEach((side, i) => {
                const da = spinAngle * 0.6 + side * Math.PI * 0.55 + i * 0.4;
                const dr = R + 10;
                ctx.beginPath();
                ctx.arc(cx + Math.cos(da) * dr, cy + Math.sin(da) * dr, 2, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(29,111,164,${0.3 + i * 0.15})`;
                ctx.fill();
            });

            ctx.restore();
        }

        function drawTexts(c, alpha, blurPx, offsetY) {
            const cx = W / 2,
                cy = H / 2 + LOGO_SIZE * 0.72;
            c.save();
            if (blurPx > 0) c.filter = `blur(${blurPx.toFixed(1)}px)`;
            c.globalAlpha = alpha;
            c.textAlign = 'center';
            c.font = '300 20px Georgia, serif';
            c.fillStyle = '#1a3a5c';
            c.fillText(TITLE, cx, cy + offsetY);
            c.font = '500 9.5px sans-serif';
            c.fillStyle = '#7a9ab5';
            c.letterSpacing = '0.35em';
            c.fillText(SUBTITLE, cx, cy + 20 + offsetY);
            c.letterSpacing = '0';
            c.filter = 'none';
            c.globalAlpha = 1;
            c.restore();
        }

        function buildOffFull() {
            if (!W || !H) return;
            offFull = document.createElement('canvas');
            offFull.width = W;
            offFull.height = H;
            const fc = offFull.getContext('2d');
            drawBg(fc);
            drawLogo(fc, W / 2, H / 2 - LOGO_SIZE * 0.15, LOGO_SIZE, 1);
            drawTexts(fc, 1, 0, 0);
        }

        function easeOut(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        // ✅ FIX: Fungsi untuk dismiss splash seketika tanpa jeda
        function dismissSplash() {
            if (done) return;
            done = true;
            cancelAnimationFrame(animId);

            // Langsung disable pointer events agar scroll/klik bisa langsung
            splash.style.pointerEvents = 'none';

            // CSS transition fade out
            splash.style.transition = 'opacity 0.4s ease-out';
            splash.style.opacity = '0';

            // Hapus elemen setelah transisi selesai
            setTimeout(() => {
                if (splash && splash.parentNode) {
                    splash.parentNode.removeChild(splash);
                }
            }, 420);
        }

        function animate(ts) {
            if (done) return;
            if (!startTime) startTime = ts;
            const e = ts - startTime;
            ctx.clearRect(0, 0, W, H);
            const logoCX = W / 2,
                logoCY = H / 2 - LOGO_SIZE * 0.15;

            if (e < D.LOGO_IN) {
                const p = easeOut(e / D.LOGO_IN);
                drawBg(ctx);
                ctx.save();
                ctx.translate(logoCX, logoCY);
                ctx.scale(0.4 + p * 0.6, 0.4 + p * 0.6);
                ctx.translate(-logoCX, -logoCY);
                drawLogo(ctx, logoCX, logoCY, LOGO_SIZE, p);
                ctx.restore();

            } else if (e < D.LOGO_IN + D.RING_SPIN) {
                const ringE = e - D.LOGO_IN;
                const ringP = ringE / D.RING_SPIN;
                const spinAngle = -Math.PI / 2 + (ringE / 1000) * 3.5;
                drawBg(ctx);
                drawLogo(ctx, logoCX, logoCY, LOGO_SIZE, 1);
                drawRing(logoCX, logoCY, LOGO_SIZE, ringP, 1, spinAngle);

            } else if (e < D.LOGO_IN + D.RING_SPIN + D.RING_OUT) {
                const outP = (e - D.LOGO_IN - D.RING_SPIN) / D.RING_OUT;
                const spinAngle = -Math.PI / 2 + (D.RING_SPIN / 1000) * 3.5 + (outP * D.RING_OUT / 1000) * 3.5;
                drawBg(ctx);
                drawLogo(ctx, logoCX, logoCY, LOGO_SIZE, 1);
                drawRing(logoCX, logoCY, LOGO_SIZE, 1, 1 - outP, spinAngle);

            } else if (e < D.LOGO_IN + D.RING_SPIN + D.RING_OUT + D.TEXT_IN) {
                const p = easeOut((e - D.LOGO_IN - D.RING_SPIN - D.RING_OUT) / D.TEXT_IN);
                drawBg(ctx);
                drawLogo(ctx, logoCX, logoCY, LOGO_SIZE, 1);
                drawTexts(ctx, p, (1 - p) * 10, (1 - p) * 12);

            } else if (e < D.LOGO_IN + D.RING_SPIN + D.RING_OUT + D.TEXT_IN + D.FULL_HOLD) {
                if (phase !== 'hold') {
                    phase = 'hold';
                    buildOffFull();
                }
                if (offFull) ctx.drawImage(offFull, 0, 0);

            } else if (e < TOTAL - D.GAP) {
                // ✅ FIX: Fade out dikerjakan oleh canvas dulu, LALU CSS transition
                const p = (e - D.LOGO_IN - D.RING_SPIN - D.RING_OUT - D.TEXT_IN - D.FULL_HOLD) / D.FADE_OUT;
                if (offFull) {
                    ctx.globalAlpha = 1 - p;
                    ctx.drawImage(offFull, 0, 0);
                    ctx.globalAlpha = 1;
                }

            } else {
                // ✅ FIX: Animasi selesai → dismiss langsung dari rAF loop
                dismissSplash();
                return;
            }

            animId = requestAnimationFrame(animate);
        }

        resize();
        window.addEventListener('resize', () => {
            resize();
            startTime = null;
        });
        animId = requestAnimationFrame(animate);

        // FIX: window.load sebagai fallback safety net saja
        // Jika halaman lambat load, splash tetap akan selesai via rAF loop di atas
        // Tapi kalau rAF sudah done duluan, ini tidak akan double-trigger
        window.addEventListener('load', function() {
            setTimeout(dismissSplash, TOTAL);
        });
    });
</script>
