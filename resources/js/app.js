/**
 * SIAPTIKA — Vanilla JS Script
 * Pure Vanilla JavaScript implementation for dashboard text counter animations.
 * No external libraries (No GSAP).
 */

function initVanillaCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    const animateCount = (el) => {
        const target = parseInt(el.getAttribute('data-counter'), 10) || 0;
        if (target === 0) {
            el.textContent = '0';
            return;
        }

        const duration = 1200; // 1.2 detik
        const startTime = performance.now();

        // Ease-out quad formula for smooth deceleration (cepat di awal, memelan di akhir)
        const easeOutQuad = (t) => t * (2 - t);

        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = easeOutQuad(progress);

            const currentVal = Math.round(eased * target);
            el.textContent = currentVal;

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = target;
            }
        };

        el.textContent = '0';
        requestAnimationFrame(step);
    };

    // Jalankan animasi saat counter terlihat di layar (IntersectionObserver)
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCount(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        counters.forEach(counter => observer.observe(counter));
    } else {
        counters.forEach(counter => animateCount(counter));
    }
}

document.addEventListener('DOMContentLoaded', initVanillaCounters);
