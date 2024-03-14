(function($) {
    "use strict";



    // testimonial slider 
    $('.better .port-slide').slick({
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


})(jQuery);