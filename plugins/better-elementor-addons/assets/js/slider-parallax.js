(function($) {
"use strict";

    /**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetHelloWorldHandler = function( $scope, $ ) {
		console.log( $scope );
	};

    // Make sure you run this code under Elementor.
    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/hello-world.default', WidgetHelloWorldHandler );
    } );

    /* ===============================  Slider-parallax  =============================== */ 

    function betterSliderparallaxy($scope, $) {
        var sliderparallax;
        var sliderparallaxOptions = {
            speed: 1300,
            autoplay: true,
            parallax: true,
            mousewheel: true,
            loop: true,

           on: {
                init: function () {
                    var swiper = this;
                    for (var i = 0; i < swiper.slides.length; i++) {
                        $(swiper.slides[i])
                            .find('.better-bg-img')
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
                el: '.showcase-full .parallax-slider .swiper-pagination',
                type: 'fraction',
                clickable: true,
                type: 'bullets', // Set the pagination type to 'bullets'
            },

            navigation: {
                nextEl: '.showcase-full .parallax-slider .next-ctrl',
                prevEl: '.showcase-full .parallax-slider .prev-ctrl'
            }
        };
        sliderparallax = new Swiper('.showcase-full .parallax-slider', sliderparallaxOptions);
    }

    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/bea-slider-parallax.default', betterSliderparallaxy);

    });

})(jQuery);