import gsap from 'gsap';

/**
 * SIAPTIKA GSAP Animation Engine
 * Menghadirkan animasi UI yang halus, modern, dan memanjakan mata.
 */

window.gsap = gsap;

const initGSAPAnimations = () => {
    // 1. Fade Up (Elemen tunggal)
    document.querySelectorAll('[data-gsap="fade-up"]').forEach((el) => {
        if (el.dataset.gsapInit) return;
        el.dataset.gsapInit = 'true';
        gsap.fromTo(
            el,
            { y: 22, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.6, ease: 'power2.out' }
        );
    });

    // 2. Fade Down (Header & Badges)
    document.querySelectorAll('[data-gsap="fade-down"]').forEach((el) => {
        if (el.dataset.gsapInit) return;
        el.dataset.gsapInit = 'true';
        gsap.fromTo(
            el,
            { y: -16, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.55, ease: 'power2.out' }
        );
    });

    // 3. Stagger Cards (Grid Kartu Statistik / Agenda / Arsip)
    document.querySelectorAll('[data-gsap="stagger-cards"]').forEach((parent) => {
        if (parent.dataset.gsapInit) return;
        parent.dataset.gsapInit = 'true';
        const children = parent.querySelectorAll('.gsap-card, > div, > article, > a');
        if (children.length > 0) {
            gsap.fromTo(
                children,
                { y: 24, autoAlpha: 0 },
                {
                    y: 0,
                    autoAlpha: 1,
                    duration: 0.55,
                    stagger: 0.07,
                    ease: 'power2.out'
                }
            );
        }
    });

    // 4. Stagger Rows (Baris Tabel & Item Daftar)
    document.querySelectorAll('[data-gsap="stagger-rows"]').forEach((parent) => {
        if (parent.dataset.gsapInit) return;
        parent.dataset.gsapInit = 'true';
        const rows = parent.querySelectorAll('tr, .gsap-row, > li');
        if (rows.length > 0) {
            gsap.fromTo(
                rows,
                { y: 12, autoAlpha: 0 },
                {
                    y: 0,
                    autoAlpha: 1,
                    duration: 0.4,
                    stagger: 0.035,
                    ease: 'power2.out'
                }
            );
        }
    });

    // 5. Hero / Banner Expansion
    document.querySelectorAll('[data-gsap="hero"]').forEach((el) => {
        if (el.dataset.gsapInit) return;
        el.dataset.gsapInit = 'true';
        gsap.fromTo(
            el,
            { scale: 0.98, autoAlpha: 0, y: 15 },
            { scale: 1, autoAlpha: 1, y: 0, duration: 0.65, ease: 'power2.out' }
        );
    });

    // 6. Hover Elevate Micro-Interaction
    document.querySelectorAll('[data-gsap-hover="elevate"]').forEach((el) => {
        if (el.dataset.gsapHoverInit) return;
        el.dataset.gsapHoverInit = 'true';

        el.addEventListener('mouseenter', () => {
            gsap.to(el, { y: -4, duration: 0.25, ease: 'power2.out' });
        });

        el.addEventListener('mouseleave', () => {
            gsap.to(el, { y: 0, duration: 0.25, ease: 'power2.out' });
        });
    });

    // 7. Hover Scale Micro-Interaction
    document.querySelectorAll('[data-gsap-hover="scale"]').forEach((el) => {
        if (el.dataset.gsapHoverInit) return;
        el.dataset.gsapHoverInit = 'true';

        el.addEventListener('mouseenter', () => {
            gsap.to(el, { scale: 1.025, duration: 0.2, ease: 'power2.out' });
        });

        el.addEventListener('mouseleave', () => {
            gsap.to(el, { scale: 1.0, duration: 0.2, ease: 'power2.out' });
        });
    });
};

// Global Helper untuk Animasi Modal / Dialog Entrance
window.animateModalEntrance = (modalId) => {
    const modalEl = typeof modalId === 'string' ? document.getElementById(modalId) : modalId;
    if (!modalEl) return;

    const dialogCard = modalEl.querySelector('.modal-card, [class*="max-w-"], .bg-white') || modalEl.firstElementChild;

    gsap.fromTo(modalEl, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.25, ease: 'power2.out' });

    if (dialogCard) {
        gsap.fromTo(
            dialogCard,
            { scale: 0.94, y: 18, autoAlpha: 0 },
            { scale: 1, y: 0, autoAlpha: 1, duration: 0.35, ease: 'power2.out', delay: 0.05 }
        );
    }
};

// Jalankan otomatis saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    initGSAPAnimations();
});

// Re-init helper untuk konten dinamis / AJAX / Livewire
window.SIAPTIKA_GSAP = {
    init: initGSAPAnimations,
    animateModal: window.animateModalEntrance
};
