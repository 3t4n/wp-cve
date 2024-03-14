(function ($) {
    "use strict";

    function effectTarget(effectParams, $slideEl) {
        if (effectParams.transformEl) {
            return $slideEl.find(effectParams.transformEl).css({
                'backface-visibility': 'hidden',
                '-webkit-backface-visibility': 'hidden'
            });
        }
        return $slideEl;
    }

    function effectInit(params) {
        const {
            effect,
            swiper,
            on,
            setTranslate,
            setTransition,
            overwriteParams,
            perspective,
            recreateShadows,
            getEffectParams
        } = params;
        on('beforeInit', () => {
            if (swiper.params.effect !== effect) return;
            swiper.classNames.push(`${swiper.params.containerModifierClass}${effect}`);

            if (perspective && perspective()) {
                swiper.classNames.push(`${swiper.params.containerModifierClass}3d`);
            }
            const overwriteParamsResult = overwriteParams ? overwriteParams() : {};
            Object.assign(swiper.params, overwriteParamsResult);
            Object.assign(swiper.originalParams, overwriteParamsResult);
        });
        on('setTranslate', () => {
            if (swiper.params.effect !== effect) return;
            setTranslate();
        });
        on('setTransition', (_s, duration) => {
            if (swiper.params.effect !== effect) return;
            setTransition(duration);
        });
        on('transitionEnd', () => {
            if (swiper.params.effect !== effect) return;

            if (recreateShadows) {
                if (!getEffectParams || !getEffectParams().slideShadows) return; // remove shadows

                swiper.slides.each(slideEl => {
                    const $slideEl = swiper.$(slideEl);
                    $slideEl.find('.swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left').remove();
                }); // create new one

                recreateShadows();
            }
        });
        let requireUpdateOnVirtual;
        on('virtualUpdate', () => {
            if (swiper.params.effect !== effect) return;
            if (!swiper.slides.length) {
                requireUpdateOnVirtual = true;
            }
            requestAnimationFrame(() => {
                if (requireUpdateOnVirtual && swiper.slides && swiper.slides.length) {
                    setTranslate();
                    requireUpdateOnVirtual = false;
                }
            });
        });
    }

    const myEffect = function( _ref ) {
        let {
            swiper,
            extendParams,
            on
        } = _ref;
        extendParams({
            fadeEffect: {
                crossFade: false,
                transformEl: null
            }
        });

        const setTranslate = () => {
            const {
                slides
            } = swiper;
            const params = swiper.params.fadeEffect;

            for (let i = 0; i < slides.length; i += 1) {
                const $slideEl = swiper.slides.eq(i);
                const offset = $slideEl[0].swiperSlideOffset;
                let tx = -offset;
                if (!swiper.params.virtualTranslate) tx -= swiper.translate;
                let ty = 0;

                if (!swiper.isHorizontal()) {
                    ty = tx;
                    tx = 0;
                }

                const slideOpacity = swiper.params.fadeEffect.crossFade ? Math.max(1 - Math.abs($slideEl[0].progress), 0) : 1 + Math.min(Math.max($slideEl[0].progress, -1), 0);
                const $targetEl = effectTarget(params, $slideEl);
                $targetEl.css({
                    opacity: slideOpacity
                }).transform(`translate3d(${tx}px, ${ty}px, 0px)`);
            }
        };

        const setTransition = duration => {
            const {
                transformEl
            } = swiper.params.fadeEffect;
            const $transitionElements = transformEl ? swiper.slides.find(transformEl) : swiper.slides;
            $transitionElements.transition(duration);
        };

        effectInit({
            effect: 'duy',
            swiper,
            on,
            setTranslate,
            setTransition,
            overwriteParams: () => ({
                slidesPerView: 1,
                slidesPerGroup: 1,
                watchSlidesProgress: true,
                spaceBetween: 0,
                virtualTranslate: !swiper.params.cssMode
            })
        });
    }

    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addFilter('lastudio-kit/carousel/options', ( swiperOptions, $scope, carousel_id ) => {
            // swiperOptions.modules = [ myEffect ];
            // swiperOptions.effect = 'duy';
            // console.log(swiperOptions);
            return swiperOptions;
        });

    })
}(jQuery));
