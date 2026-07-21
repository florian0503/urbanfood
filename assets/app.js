import './styles/app.css';

/*
 * Apparition au scroll : les elements portant [data-reveal] deviennent
 * visibles quand ils entrent dans le viewport, avec le delai (en ms)
 * porte par l'attribut.
 */
function setupReveal() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const delay = parseInt(el.getAttribute('data-reveal') || '0', 10);
                setTimeout(() => el.setAttribute('data-revealed', '1'), delay);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.08 });

    document.querySelectorAll('[data-reveal]:not([data-revealed])').forEach((el) => {
        observer.observe(el);
    });
}

/*
 * Video hero : boucle forcee + relance si l'autoplay est bloque
 * par le navigateur (relance au premier clic).
 */
function setupVideo() {
    const video = document.querySelector('[data-uf-video]');

    if (!video) {
        return;
    }

    video.loop = true;
    video.muted = true;

    if (video.paused) {
        video.play().catch(() => {});
    }

    video.addEventListener('canplay', () => {
        if (video.paused) {
            video.play().catch(() => {});
        }
    });

    video.addEventListener('ended', () => {
        video.currentTime = 0;
        video.play().catch(() => {});
    });

    document.addEventListener('click', () => {
        if (video.isConnected && video.paused) {
            video.play().catch(() => {});
        }
    }, true);
}

document.addEventListener('DOMContentLoaded', () => {
    setupReveal();
    setupVideo();
});
