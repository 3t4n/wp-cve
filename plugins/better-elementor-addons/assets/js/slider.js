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

    /* ===============================  Slider-0  =============================== */ 
         
    function betterSliderCTM($scope, $) {  

        /* --------------------  Swipper slide  --------------- */
        $scope.find('.better-slider-ctm.style-1').each(function () {
            var mySlider    = $( this );
            var myData      = mySlider.data( 'slider-settings' );
            var myspeed     = myData.speed;
            var myautoplay  = myData.autoplay;
            var myloop      = myData.loop;
            var myparallax  = myData.parallax;

            var bettersliderctm0;
            var better_slider_ctm_Options = {
                speed       : myspeed,
                autoplay    : myautoplay,
                loop        : myloop,
                parallax    : myparallax,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    type: 'fraction',
                },
                on: {
                    init: function () {
                        let swiper = this;
                        for ( let i = 0; i < swiper.slides.length; i++ ) {
                            $( swiper.slides[i] ).find( '.bg-img' ).attr({'data-swiper-parallax': 0.75 * swiper.width});
                            // $( swiper.slides[i] ).find( '.main-title' ).attr({'data-swiper-parallax': 0.65 * swiper.width});
                        }
                    },
                    resize: function () {
                        this.update();
                    }
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                } 

            };
            bettersliderctm0 = new Swiper( '.better-slider-ctm-1', better_slider_ctm_Options );            
        });
    }


    function betterSlider5($scope, $) {

        var slider5 = new Swiper('.better-slider.style-5 .swiper-container', {
            slidesPerView: 1,
            speed: 1000,
            loop: true,
            effect: "fade",

            pagination: {
                el: '.slid-scrn .swiper-pagination',
            },

            navigation: {
                nextEl: '.slid-scrn .swiper-button-next',
                prevEl: '.slid-scrn .swiper-button-prev'
            },
        });

    };

    function betterSlider2($scope, $) {
        var slider2;
        var slider2Options = {
            speed: 1000,
            autoplay: true,
            parallax: true,
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
                el: '.better-slider.style-2 .parallax-slider .swiper-pagination',
                type: 'fraction',
                clickable: true
            },

            navigation: {
                nextEl: '.better-slider.style-2 .parallax-slider .next-ctrl',
                prevEl: '.better-slider.style-2 .parallax-slider .prev-ctrl'
            }
        };
        slider2 = new Swiper('.better-slider.style-2 .parallax-slider', slider2Options);

    }

    function betterSlider1($scope, $) {
        if ($('#js-cta-better-slider-style-1').length) {

            const slider = document.getElementById("js-cta-better-slider-style-1");
            const sliderNext = document.getElementById("js-cta-better-slider-style-1-next");
            const sliderPrevious = document.getElementById("js-cta-better-slider-style-1-previous");
            const interleaveOffset = 0.75;

            var mySlider    = $( slider );
            var myData      = mySlider.data( 'slider-settings' );
            var myspeed     = myData.speed;

            const swiper = new Swiper(slider, {
                loop: true,
                direction: "vertical",
                speed: 800,
                grabCursor: true,
                watchSlidesProgress: true,
                autoplay: {
                    delay: myspeed,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.slid-half .swiper-pagination',
                    type: 'fraction',
                },
                navigation: {
                    nextEl: sliderNext,
                    prevEl: sliderPrevious
                },
                on: {
                    progress: function() {
                        let swiper = this;

                        for (let i = 0; i < swiper.slides.length; i++) {
                            let slideProgress = swiper.slides[i].progress;
                            let innerOffset = swiper.height * interleaveOffset;
                            let innerTranslate = slideProgress * innerOffset;

                            TweenMax.set(swiper.slides[i].querySelector(".slide-inner"), {
                                y: innerTranslate,
                            });
                        }
                    },
                    touchStart: function() {
                        let swiper = this;
                        for (let i = 0; i < swiper.slides.length; i++) {
                            swiper.slides[i].style.transition = "";
                        }
                    },
                    setTransition: function(speed) {
                        let swiper = this;
                        for (let i = 0; i < swiper.slides.length; i++) {
                            swiper.slides[i].style.transition = speed + "ms";
                            swiper.slides[i].querySelector(".slide-inner").style.transition = speed + "ms";
                        }
                    }
                }
            });
        };
    };

    function betterSlider7($scope, $) {
        if ($('.better-slider.style-7').length) {

            const slider = $(".better-slider.style-7");

            var mySlider    = $( slider );
            var myData      = mySlider.data( 'slider-settings' );
            var myspeed     = myData.speed;

            var slider7;
            var slider7Options = {
                speed: 1000,
                autoplay: {
                    delay: myspeed,
                    disableOnInteraction: false
                },
                parallax: true,
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
                    el: '.better-slider.style-7 .parallax-slider .swiper-pagination',
                    type: 'fraction',
                    clickable: true
                },

                navigation: {
                    nextEl: '.better-slider.style-7 .parallax-slider .next-ctrl',
                    prevEl: '.better-slider.style-7 .parallax-slider .prev-ctrl'
                }
            };
            slider7 = new Swiper('.better-slider.style-7 .parallax-slider', slider7Options);
        }

	}

	jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider.default', betterSlider7);
	});

    function betterSlider8($scope, $) {
        if ($('.better-slider.style-8').length) {

            const slider = $(".better-slider.style-8");

            var mySlider    = $( slider );
            var myData      = mySlider.data( 'slider-settings' );
            var myspeed     = myData.speed;

            /* Slider-8 */ 
            var slider8;
            var slider8Options = {
                speed: 1000,
                autoplay: {
                    delay: myspeed,
                    disableOnInteraction: false
                },
                parallax: true,
                loop: true,
                pagination: {
                    el: '.better-slider.style-8 .parallax-slider .swiper-pagination',
                    clickable: true
                },
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
                    el: '.better-slider.style-8 .parallax-slider .swiper-pagination',
                    type: 'fraction',
                },

                navigation: {
                    nextEl: '.better-slider.style-8 .parallax-slider .next-ctrl',
                    prevEl: '.better-slider.style-8 .parallax-slider .prev-ctrl'
                }
            };
            slider8 = new Swiper('.better-slider.style-8 .parallax-slider', slider8Options);
        }
        
    };

    /* Slider-13 */ 
    function betterSlider9($scope, $) {

        var slider9;
        var slider9Options = {
            loop: true,
            direction: "vertical",
            speed: 800,
            grabCursor: true,
            watchSlidesProgress: true,
            autoplay: {
            delay: 2500,
            disableOnInteraction: false
            },
            pagination: {
                el: '.better-slider.style-9 .swiper-pagination',
                type: 'fraction',
            },
            navigation: {
            nextEl: '.better-slider.style-9 #better-slider13-next',
            prevEl: '.better-slider.style-9 #better-slider13-previous'
            },
            on: {
                progress: function() {
                    let swiper = this;

                    for (let i = 0; i < swiper.slides.length; i++) {
                    let slideProgress = swiper.slides[i].progress;
                    let innerOffset = swiper.height * 0.75;
                    let innerTranslate = slideProgress * innerOffset;

                    TweenMax.set(swiper.slides[i].querySelector(".slide-inner"), {
                        y: innerTranslate,
                    });
                    }
                },
                touchStart: function() {
                    let swiper = this;
                    for (let i = 0; i < swiper.slides.length; i++) {
                    swiper.slides[i].style.transition = "";
                    }
                },
                setTransition: function(speed) {
                    let swiper = this;
                    for (let i = 0; i < swiper.slides.length; i++) {
                    swiper.slides[i].style.transition = speed + "ms";
                    swiper.slides[i].querySelector(".slide-inner").style.transition =
                        speed + "ms";
                    }
                }
            }
        };
        slider9 = new Swiper('.better-slider.style-9 .swiper-container', slider9Options);
    }

    /* Slider-11 */ 
    function betterSlider11($scope, $) {
        var slider11;
        var slider11Options = {
            speed: 1000,
            autoplay: true,
            parallax: true,
            loop: true,
            pagination: {
                el: '.better-slider.style-11 .parallax-slider .swiper-pagination',
                clickable: true
            },
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
                el: '.better-slider.style-11 .parallax-slider .swiper-pagination',
                type: 'fraction',
            },

            navigation: {
                nextEl: '.better-slider.style-11 .parallax-slider .next-ctrl',
                prevEl: '.better-slider.style-11 .parallax-slider .prev-ctrl'
            }
        };
        slider11 = new Swiper('.better-slider.style-11 .parallax-slider', slider11Options);
    }

    function betterSliderExtras($scope, $) {

        /* ===============================  Var Background image  =============================== */

        var pageSection = $(".better-bg-img, section");
        pageSection.each(function (indx) {

            if ($(this).attr("data-background")) {
                $(this).css("background-image", "url(" + $(this).data("background") + ")");
            }
        });

        /* ===============================  SPLITTING TEXT  =============================== */

        Splitting();

    }

    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider-ctm.default', betterSliderCTM);
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider.default', betterSlider5);
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider.default', betterSlider2);
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider.default', betterSlider1);
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider.default', betterSlider8);
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider.default', betterSlider9);
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider.default', betterSlider11);
        elementorFrontend.hooks.addAction('frontend/element_ready/better-slider.default', betterSliderExtras);
    });

})(jQuery);