(function () {
    function initHeroSlider(section) {
        if (section.dataset.heroSliderReady === '1') return;

        const container = section.querySelector('.hero-slider__swiper');
        if (!container || typeof Swiper === 'undefined') return;

        section.dataset.heroSliderReady = '1';

        // The intro zoom plays once; drop the class so a later DOM move by
        // Swiper's loop can't restart it.
        const intro = container.querySelector('.hero-slider__slide--intro');
        if (intro) {
            intro.addEventListener('animationend', function () {
                intro.classList.remove('hero-slider__slide--intro');
            }, {once: true});
        }

        const slides      = Array.from(container.querySelectorAll('.hero-slider__slide'));
        const useAutoplay = container.dataset.autoplay === '1' && slides.length > 1;
        const delay       = parseInt(container.dataset.autoplayDelay, 10) || 6000;
        const loop        = container.dataset.loop === '1';
        const pagination  = section.querySelector('.hero-slider__pagination');
        const nextBtn     = section.querySelector('.hero-slider__btn--next');
        const prevBtn     = section.querySelector('.hero-slider__btn--prev');

        let swiper = null;

        // Single slide: no slider needed, the video already autoplays and loops
        // exactly like the hero video block.
        if (slides.length < 2) {
            bindToggle(slides[0]);
            return;
        }

        swiper = new Swiper(container, {
            loop: loop,
            speed: 800,
            effect: 'fade',
            fadeEffect: {crossFade: true},
            slidesPerView: 1,
            autoplay: useAutoplay ? {delay: delay, disableOnInteraction: false} : false,
            pagination: pagination ? {el: pagination, clickable: true} : false,
            navigation: (nextBtn && prevBtn) ? {nextEl: nextBtn, prevEl: prevBtn} : false,
            on: {
                init: function () {
                    slides.forEach(bindToggle);
                    handleSlide(this);
                },
                slideChange: function () {
                    handleSlide(this);
                },
            },
        });

        // Rewind every video, then play the one on the active slide. A video slide
        // holds the slider until the video ends; image and iframe slides use the
        // timed delay instead.
        function handleSlide(instance) {
            slides.forEach(function (slide) {
                const video = slide.querySelector('video');
                if (!video) return;
                video.pause();
                video.currentTime = 0;
                setToggleState(slide, false);
            });

            const active = instance.slides[instance.activeIndex];
            if (!active) return;

            const video = active.querySelector('video');

            if (!video) {
                if (useAutoplay && instance.autoplay) instance.autoplay.start();
                return;
            }

            if (instance.autoplay) instance.autoplay.stop();

            video.onended = function () {
                if (loop || instance.activeIndex < instance.slides.length - 1) {
                    instance.slideNext();
                } else if (useAutoplay && instance.autoplay) {
                    instance.autoplay.start();
                }
            };

            const played = video.play();
            if (played && typeof played.catch === 'function') {
                // Autoplay blocked (rare while muted) — fall back to the timed delay.
                played.catch(function () {
                    setToggleState(active, false);
                    if (useAutoplay && instance.autoplay) instance.autoplay.start();
                });
            }
        }

        function bindToggle(slide) {
            if (!slide) return;

            const video = slide.querySelector('video');
            const btn   = slide.querySelector('.hero-slider__toggle');
            if (!video || !btn) return;

            btn.addEventListener('click', function () {
                if (video.paused) {
                    video.play();
                } else {
                    video.pause();
                    if (swiper && swiper.autoplay) swiper.autoplay.stop();
                }
            });

            video.addEventListener('play', function () {
                setToggleState(slide, true);
            });
            video.addEventListener('pause', function () {
                setToggleState(slide, false);
            });
        }

        function setToggleState(slide, playing) {
            const btn = slide.querySelector('.hero-slider__toggle');
            if (!btn) return;

            const iconPause = btn.querySelector('.hero-slider__toggle-icon--pause');
            const iconPlay  = btn.querySelector('.hero-slider__toggle-icon--play');
            if (!iconPause || !iconPlay) return;

            btn.setAttribute('aria-label', playing ? 'Pause video' : 'Play video');
            btn.setAttribute('data-state', playing ? 'playing' : 'paused');
            iconPause.classList.toggle('d-none', !playing);
            iconPlay.classList.toggle('d-none', playing);
        }
    }

    function init() {
        document.querySelectorAll('.block-hero-slider').forEach(initHeroSlider);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
