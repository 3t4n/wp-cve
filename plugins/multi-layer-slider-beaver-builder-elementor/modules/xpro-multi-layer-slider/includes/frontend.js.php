(function ($){

let slider = $(".fl-node-<?php echo $id ?> .xpro-dynamic-slider");

var slick = slider.slick({
    speed: <?php echo $settings->slide_duration; ?>,
    infinite: <?php echo $settings->slider_loop; ?>,
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    touchThreshold: 100,
    draggable: <?php echo $settings->slider_mousedrag; ?>,
    fade: <?php echo ($settings->slide_animation == 'fade') ? 'true' : 'false'; ?>,
    cssEase: 'linear',
    autoplay: <?php echo $settings->slider_autoplay; ?>,
    autoplaySpeed: <?php echo ($settings->slider_autoplay && $settings->slider_autoplay_timeout ) ? $settings->slider_autoplay_timeout * 1000 : 3000; ?>,
    dots: <?php echo $settings->slider_dots; ?>,
    vertical: <?php echo ($settings->slider_orientation == 'vertical') ? 'true' : 'false'; ?>,
    verticalSwiping: <?php echo ($settings->slider_orientation == 'vertical') ? 'true' : 'false'; ?>,
    customPaging: function (slider, i) {
        return '<span class="slick-dot"></span>';
    },
    arrows: <?php echo $settings->slider_nav; ?>,
    appendArrows: $('.fl-node-<?php echo $id ?> .xpro-dynamic-slider-navigation'),
    prevArrow: '<button type="button" class="slick-nav-prev"></button>',
    nextArrow: '<button type="button" class="slick-nav-next"></button>',
});

    //Mouse Wheel
    if (<?php echo $settings->slider_mousewheel; ?>) {

        let last_slide = false;
        let total_slide = slider.find('.slick-slide').length;
        let current_slide = 1;

        slider.on('wheel', (function (e) {
            if (e.originalEvent.deltaY < 0) {
                last_slide = false;
                current_slide = $(this).slick('slickCurrentSlide') + 1;
                if (current_slide === 1) {
                    last_slide = true;
                    return
                }
                e.preventDefault();
                $(this).slick('slickPrev');
            } else {
                last_slide = false;
                current_slide = $(this).slick('slickCurrentSlide') + 1;
                if (current_slide === total_slide) {
                    last_slide = true;
                    return
                }
                e.preventDefault();
                $(this).slick('slickNext');
            }
        }));
    }

    //Slide Content Animation
    var target = '';
    slick.on('beforeChange', function () {

        target = $('.fl-node-<?php echo $id ?> .slick-slide:not(.slick-current)').find('.fl-animation');

        target.each(function () {
            var $this = $(this);
            $this.removeClass('fl-animated');
            $this.css('visibility', 'hidden');
        });

    });
    slick.on('afterChange', function (event, slick, currentSlide) {

        target.each(function () {

            var $this = $(this);
            let delay = $(this).data("animation-duration") || 0;

            setTimeout(function () {
                $this.addClass('fl-animated');
                $this.css('visibility', 'visible');
            }, delay);

        });

    });

}(jQuery));
