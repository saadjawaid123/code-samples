// Mobile only: the desktop hover animation is scrubbed by scroll instead.
// Each card's progress (0 → 1) is written to --chwh-p; the SCSS maps it onto
// paused keyframes, so scrolling further advances the card's animation and
// scrolling back reverses it.
const CHWH_MOBILE_QUERY = '(max-width: 768px)';
// Animation starts when a card's top reaches START of the viewport height and
// completes when it reaches END.
const CHWH_START = 0.85;
const CHWH_END = 0.35;
// Cards already past START on load still rest at scroll 0 and animate over at
// least this much scroll (fraction of viewport height).
const CHWH_MIN_RANGE = 0.3;

// Layout position ignoring transforms (AOS fade-up translates the card column).
const chwhDocTop = (el) => {
    let top = 0;
    for (; el; el = el.offsetParent) top += el.offsetTop;
    return top;
};

const initCardsWithHeader = () => {
    const blocks = document.querySelectorAll('.block-cards-with-header');
    if (!blocks.length) return;

    const cards = [...document.querySelectorAll('.block-cards-with-header .card-item')];
    const mql = window.matchMedia(CHWH_MOBILE_QUERY);
    let ticking = false;

    const update = () => {
        ticking = false;
        const vh = window.innerHeight;
        const y = window.scrollY;

        // Read all positions before writing to avoid layout thrashing
        const progress = cards.map((card) => {
            const top = chwhDocTop(card);
            const s0 = Math.max(0, top - vh * CHWH_START);
            const s1 = Math.max(s0 + vh * CHWH_MIN_RANGE, top - vh * CHWH_END);
            return Math.min(1, Math.max(0, (y - s0) / (s1 - s0)));
        });

        cards.forEach((card, i) => card.style.setProperty('--chwh-p', progress[i].toFixed(4)));
    };

    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(update);
    };

    const toggle = () => {
        blocks.forEach((block) => block.classList.toggle('is-scroll-driven', mql.matches));

        if (mql.matches) {
            window.addEventListener('scroll', onScroll, {passive: true});
            window.addEventListener('resize', onScroll);
            window.addEventListener('load', onScroll);
            update();
        } else {
            window.removeEventListener('scroll', onScroll);
            window.removeEventListener('resize', onScroll);
            window.removeEventListener('load', onScroll);
            cards.forEach((card) => card.style.removeProperty('--chwh-p'));
        }
    };

    mql.addEventListener('change', toggle);
    toggle();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCardsWithHeader);
} else {
    initCardsWithHeader();
}
