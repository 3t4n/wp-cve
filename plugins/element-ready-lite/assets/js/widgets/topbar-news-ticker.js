(function($) {

    var Element_Ready_Theme_News_Ticker_Widget = function($scope, $) {

        var trancarousel = $scope.find('.element-ready-topbar-carousel').eq(0);
        var is_rtl = trancarousel.attr('data-is_rtl') == 'yes' ? true : false;
        var autoplayHoverPause = trancarousel.attr('data-autoplayHoverPause') == 'yes' ? true : false;
        var loop = trancarousel.attr('data-loop') == 'yes' ? true : false;
        var nav = trancarousel.attr('data-nav') == 'yes' ? true : false;
        var autoplay = trancarousel.attr('data-autoplay') == 'yes' ? true : false;
        var autoplayTimeout = trancarousel.attr('data-autoplayTimeout');
        var smartSpeed = trancarousel.attr('data-smartSpeed');
        var margin = trancarousel.attr('data-margin');
        trancarousel.owlCarousel({
            rtl: is_rtl,
            loop: loop,
            nav: nav,
            autoplayHoverPause: autoplayHoverPause,
            autoplay: autoplay,
            autoplayTimeout: autoplayTimeout,
            items: 1,
            smartSpeed: smartSpeed,
            margin: margin,
            navText: ["<i class=\'fal fa-angle-left\'></i>", "<i class=\'fal fa-angle-right\'></i>"],
        });
       
    }


    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Theme_News_Ticker_Widget.default', Element_Ready_Theme_News_Ticker_Widget);
       
    });
})(jQuery);