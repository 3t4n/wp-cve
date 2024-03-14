(function ($) {
    var config = {
        loop: true,
        center: true,
        dots: true,
        lazyLoad: true,
        autoplay: true,
        autoplayTimeout: 4000,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 3
            }
        }
    };
    $(".owl-carousel").owlCarousel(config);
})(jQuery);
