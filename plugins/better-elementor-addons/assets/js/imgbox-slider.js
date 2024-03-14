(function($) {
    "use strict";

        $('.better-img-box-slider.style-1 .prod-slick').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '.better-img-box-slider.style-1 .prev',
            nextArrow: '.better-img-box-slider.style-1 .next',
            dots: true,
            autoplay: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
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

})(jQuery);