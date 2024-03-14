jQuery(function ($) {

    $('.lsow-testimonials-slider').each(function () {

        var slider_elem = $(this);

        var settings = slider_elem.data('settings');

        slider_elem.flexslider({
            selector: ".lsow-slides > .lsow-slide",
            animation: settings['slide_animation'],
            direction: settings['direction'],
            slideshowSpeed: settings['slideshow_speed'],
            animationSpeed: settings['animation_speed'],
            namespace: "lsow-flex-",
            pauseOnAction: settings['pause_on_action'],
            pauseOnHover: settings['pause_on_hover'],
            controlNav: settings['control_nav'],
            directionNav: settings['direction_nav'],
            prevText: "Previous<span></span>",
            nextText: "Next<span></span>",
            smoothHeight: false,
            animationLoop: true,
            slideshow: true,
            easing: "swing",
            controlsContainer: "lsow-testimonials-slider"});

    });

});