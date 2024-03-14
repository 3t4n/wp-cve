(function($) {



    // popup-youtube
    $(document).ready(function() {
    	$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
    		disableOn: 700,
    		type: 'iframe',
    		mainClass: 'mfp-fade',
    		removalDelay: 160,
    		preloader: false,
    		fixedContentPos: false
    	});
    });

if ($(".home-slider").length > 0) {
    var aqwa_owlHome = $('.home-slider');
    aqwa_owlHome.owlCarousel({
        rtl: $("html").attr("dir") == 'rtl' ? true : false,
        items: 1,
        autoplay: false,
        autoplayTimeout: 10000,
        margin: 0,
        loop: true,
        dots: true,
        nav: true,
        navText: ['<i class="fas fa-long-arrow-alt-left"></i>', '<i class="fas fa-long-arrow-alt-right"></i>'],
        singleItem: true,
        transitionStyle: "fade",
        touchDrag: true,
        mouseDrag: false,
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 9900,
        smartSpeed: 1200,
      
        responsive: {
            0: {
                nav: false
            },
            768: {
                nav: true
            },
            992: {
                nav: true
            }
        }
    });
    aqwa_owlHome.owlCarousel();
    aqwa_owlHome.on('translate.owl.carousel', function(event) {
        var data_anim = $("[data-animation]");
        data_anim.each(function() {
            var anim_name = $(this).data('animation');
            $(this).removeClass('animated ' + anim_name).css('opacity', '0');
        });
    });
    $("[data-delay]").each(function() {
        var anim_del = $(this).data('delay');
        $(this).css('animation-delay', anim_del);
    });
    $("[data-duration]").each(function() {
        var anim_dur = $(this).data('duration');
        $(this).css('animation-duration', anim_dur);
    });
    aqwa_owlHome.on('translated.owl.carousel', function() {
        var data_anim = aqwa_owlHome.find('.owl-item.active').find("[data-animation]");
        data_anim.each(function() {
            var anim_name = $(this).data('animation');
            $(this).addClass('animated ' + anim_name).css('opacity', '1');
        });
    });
}


}(jQuery));