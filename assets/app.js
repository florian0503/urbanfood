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

/*
 * Nav transparente (accueil) : redevient creme avec bordure
 * des que l'utilisateur commence a scroller.
 */
function setupNavOverlay() {
    const nav = document.querySelector('.uf-nav--overlay');

    if (!nav) {
        return;
    }

    const update = () => {
        nav.classList.toggle('uf-nav--scrolled', window.scrollY > 40);
    };

    update();
    window.addEventListener('scroll', update, { passive: true });
}

/*
 * Bouton retour en haut : apparait apres 600px de scroll,
 * remonte en douceur au clic.
 */
function setupScrollTop() {
    const button = document.querySelector('.uf-totop');

    if (!button) {
        return;
    }

    const update = () => {
        button.classList.toggle('uf-totop--visible', window.scrollY > 600);
    };

    update();
    window.addEventListener('scroll', update, { passive: true });

    button.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

function init() {
    setupReveal();
    setupVideo();
    setupNavOverlay();
    setupScrollTop();
}

// Le module peut etre execute avant ou apres DOMContentLoaded
// selon le cache navigateur : on gere les deux cas.
if ('loading' === document.readyState) {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
