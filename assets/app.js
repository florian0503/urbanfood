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

    const darkSections = document.querySelectorAll('.uf-hero, .uf-manifesto, .uf-footer');

    const update = () => {
        button.classList.toggle('uf-totop--visible', window.scrollY > 600);

        // Sur fond sombre, le bouton passe du noir au vert pour rester visible.
        const rect = button.getBoundingClientRect();
        let overDark = false;

        darkSections.forEach((section) => {
            const sectionRect = section.getBoundingClientRect();

            if (sectionRect.top < rect.bottom && sectionRect.bottom > rect.top) {
                overDark = true;
            }
        });

        button.classList.toggle('uf-totop--invert', overDark);
    };

    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });

    button.addEventListener('click', () => {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
}

/*
 * Consentement cookies (RGPD) : bandeau Accepter / Personnaliser / Refuser.
 * Le choix est stocke 6 mois dans un cookie premiere partie "uf_consent".
 * Les futurs scripts de mesure ne doivent se charger que si
 * window.ufConsent.isGranted('statistics') (ou 'marketing') est vrai,
 * ou en ecoutant l'evenement "uf:consent".
 */
function readConsent() {
    const match = document.cookie.match(/(?:^|; )uf_consent=([^;]*)/);

    if (!match) {
        return null;
    }

    try {
        return JSON.parse(decodeURIComponent(match[1]));
    } catch {
        return null;
    }
}

function writeConsent(categories) {
    const value = encodeURIComponent(JSON.stringify({
        version: 1,
        date: new Date().toISOString(),
        categories,
    }));

    document.cookie = `uf_consent=${value}; max-age=15552000; path=/; SameSite=Lax`;
}

function setupCookieConsent() {
    const banner = document.querySelector('.uf-cookies');

    if (!banner) {
        return;
    }

    const panel = banner.querySelector('.uf-cookies__panel');
    const statsBox = banner.querySelector('#uf-consent-stats');
    const mktBox = banner.querySelector('#uf-consent-mkt');

    const apply = (categories) => {
        writeConsent(categories);
        banner.hidden = true;
        document.dispatchEvent(new CustomEvent('uf:consent', { detail: categories }));
    };

    banner.querySelector('.uf-cookies__accept').addEventListener('click', () => {
        apply({ statistics: true, marketing: true });
    });

    banner.querySelector('.uf-cookies__refuse').addEventListener('click', () => {
        apply({ statistics: false, marketing: false });
    });

    banner.querySelector('.uf-cookies__custom').addEventListener('click', () => {
        panel.hidden = !panel.hidden;
    });

    banner.querySelector('.uf-cookies__save').addEventListener('click', () => {
        apply({ statistics: statsBox.checked, marketing: mktBox.checked });
    });

    // Lien "Gerer les cookies" du footer : rouvre le bandeau avec les choix actuels.
    document.querySelectorAll('.uf-cookies-manage').forEach((el) => {
        el.addEventListener('click', () => {
            const current = readConsent();

            if (current && current.categories) {
                statsBox.checked = Boolean(current.categories.statistics);
                mktBox.checked = Boolean(current.categories.marketing);
                panel.hidden = false;
            }

            banner.hidden = false;
        });
    });

    if (!readConsent()) {
        banner.hidden = false;
    }

    window.ufConsent = {
        isGranted: (category) => {
            const consent = readConsent();

            return Boolean(consent && consent.categories && consent.categories[category]);
        },
    };
}

function init() {
    setupReveal();
    setupVideo();
    setupNavOverlay();
    setupScrollTop();
    setupCookieConsent();
}

// Le module peut etre execute avant ou apres DOMContentLoaded
// selon le cache navigateur : on gere les deux cas.
if ('loading' === document.readyState) {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
