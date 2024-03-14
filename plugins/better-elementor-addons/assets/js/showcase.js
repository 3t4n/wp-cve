!(function ($) {


    /* Showcase-0 */ 
    function betterShowcase0($scope, $) {
        $scope.find('.better-showcase.style-0').each(function () {
            var parallaxShowCase0;
            var parallaxShowCaseOptions0 = {
                speed: 1000,
                autoplay: true,
                parallax: true,
                mousewheel: true,
                loop: true,

                on: {
                    init: function () {
                        var swiper = this;
                        for (var i = 0; i < swiper.slides.length; i++) {
                            $(swiper.slides[i])
                                .find('.bg-img')
                                .attr({
                                    'data-swiper-parallax': 0.75 * swiper.width
                                });
                        }
                    },
                    resize: function () {
                        this.update();
                    }
                },

                pagination: {
                    el: '.better-showcase.style-0 .parallax-slider .swiper-pagination',
                    clickable: true
                },

                navigation: {
                    nextEl: '.better-showcase.style-0 .parallax-slider .next-ctrl',
                    prevEl: '.better-showcase.style-0 .parallax-slider .prev-ctrl'
                }
            };
            parallaxShowCase0 = new Swiper('.better-showcase.style-0 .parallax-slider', parallaxShowCaseOptions0);
        });
    };

    jQuery(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction('frontend/element_ready/better-showcase.default', betterShowcase0);

    });


})(jQuery); 