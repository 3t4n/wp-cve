(function($) {
    "use strict";

    function bettertestimonial($scope, $) {

		/* ===============================  slick Carousel  =============================== */

        $('.better-testimonial.style-3 .slic-item').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '.better-testimonial.style-3 .prev',
            nextArrow: '.better-testimonial.style-3 .next',
            dots: true,
            autoplay: true,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

		$('.better-testimonial.style-6 .tistem').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			prevArrow: '.better-testimonial.style-6 .prev',
			nextArrow: '.better-testimonial.style-6 .next',
			dots: true,
			autoplay: true
		});

        $('.better-testimonial.style-7').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
            autoplay: true
        });

        // testimonial slider 
        $scope.find('.better-testimonial.testi-slider').each(function() {
            $(this).slick({
                autoplay: true,
                dots: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: false,
                autoplaySpeed: 3000,
                speed:1500,
                fade: false,
                pauseOnHover: false,
                pauseOnFocus: false,
                responsive: [{
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                ]
            });
        });

        $('.better-testimonial.style-4.creative .slic-item').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '.better-testimonial.style-4 .prev',
            nextArrow: '.better-testimonial.style-4 .next',
            dots: true,
            autoplay: true
        });

        $('.better-testimonial.style-5.classic .slic-item').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '.better-testimonial.style-5 .prev',
            nextArrow: '.better-testimonial.style-5 .next',
            dots: true,
            autoplay: true,
        });

        $('.better-testimonial.style-8 .testim').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '.testimonial .prev',
            nextArrow: '.testimonial .next',
            dots: true,
            autoplay: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

	}
		
	jQuery(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/better-testimonial.default', bettertestimonial);
	});

})(jQuery);