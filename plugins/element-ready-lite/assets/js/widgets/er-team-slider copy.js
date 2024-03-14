(function($) {

      /*---------------------------------
        OWL CAROUSEL HANDLER
    ---------------------------------*/
    var Owl_Team_Carousel_Script_Handle = function($scope, $) {

        var carousel_elem = $scope.find('.element-ready-carousel-active').eq(0);
        var settings = carousel_elem.data('settings');

        if (typeof settings !== 'undefined') {

            var item_on_large = settings['item_on_large'] ? settings['item_on_large'] : 1;
            var item_on_medium = settings['item_on_medium'] ? settings['item_on_medium'] : 1;
            var item_on_tablet = settings['item_on_tablet'] ? settings['item_on_tablet'] : 1;
            var item_on_mobile = settings['item_on_mobile'] ? settings['item_on_mobile'] : 1;
            var stage_padding = settings['stage_padding'] ? settings['stage_padding'] : 0;
            var item_margin = settings['item_margin'] ? settings['item_margin'] : 0;
            var autoplay = settings['autoplay'] ? settings['autoplay'] : true;
            var autoplaytimeout = settings['autoplaytimeout'] ? settings['autoplaytimeout'] : 3000;
            var slide_speed = settings['slide_speed'] ? settings['slide_speed'] : 1000;
            var slide_animation = settings['slide_animation'] ? settings['slide_animation'] : false;
            var slide_animate_in = settings['slide_animate_in'] ? settings['slide_animate_in'] : 'fadeIn';
            var slide_animate_out = settings['slide_animate_out'] ? settings['slide_animate_out'] : 'fadeOut';
            var nav = settings['nav'] ? settings['nav'] : false;
            var nav_position = settings['nav_position'] ? settings['nav_position'] : 'outside_vertical_center_nav';
            var next_icon = (settings['next_icon']) ? settings['next_icon'] : 'fa fa-angle-right';
            var prev_icon = (settings['prev_icon']) ? settings['prev_icon'] : 'fa fa-angle-left';
            var dots = settings['dots'] ? settings['dots'] : false;
            var loop = settings['loop'] ? settings['loop'] : true;
            var hover_pause = settings['hover_pause'] ? settings['hover_pause'] : false;
            var center = settings['center'] ? settings['center'] : false;
            var rtl = settings['rtl'] ? settings['rtl'] : false;

            if ('yes' == slide_animation) {
                var animateIn = slide_animate_in;
                var animateOut = slide_animate_out;
            } else {
                var animateIn = '';
                var animateOut = '';
            }

            if (carousel_elem.length > 0) {
                carousel_elem.owlCarousel({
                    merge: true,
                    smartSpeed: slide_speed,
                    loop: loop,
                    nav: nav,
                    dots: dots,
                    autoplayHoverPause: hover_pause,
                    center: center,
                    rtl: rtl,
                    navText: ['<i class ="' + prev_icon + '"></i>', '<i class="' + next_icon + '"></i>'],
                    autoplay: autoplay,
                    autoplayTimeout: autoplaytimeout,
                    stagePadding: stage_padding,
                    margin: item_margin,
                    animateIn: '' + animateIn + '',
                    animateOut: '' + animateOut + '',
                    responsiveClass: true,
                    responsive: {
                        0: {
                            items: item_on_mobile
                        },
                        600: {
                            items: item_on_tablet
                        },
                        1000: {
                            items: item_on_medium
                        },
                        1200: {
                            items: item_on_medium
                        },
                        1900: {
                            items: item_on_large
                        }
                    }
                });
            }

            var thumbs_slide = $('.testmonial__thumb__content__slider');
            if (thumbs_slide.length > 0) {
                /*--------------------------
                    THUMB CAROUSEL ACTIVE
                ---------------------------*/
                var thumbs_slide = $('.testmonial__thumb__content__slider');
                var duration = 300;
                var thumbs = 3;

                /*--------------------------
                    MAIN CAROUSEL TRIGGER
                ---------------------------*/
                carousel_elem.on('click', '.owl-next', function() {
                    thumbs_slide.trigger('next.owl.carousel')
                });
                carousel_elem.on('click', '.owl-prev', function() {
                    thumbs_slide.trigger('prev.owl.carousel')
                });
                carousel_elem.on('dragged.owl.carousel', function(e) {
                    if (e.relatedTarget.state.direction == 'left') {
                        thumbs_slide.trigger('next.owl.carousel')
                    } else {
                        thumbs_slide.trigger('prev.owl.carousel')
                    }
                });

                /*--------------------------
                    THUMBS CAROUSEL TRIGGER
                ----------------------------*/
                thumbs_slide.on('click', '.owl-next', function() {
                    carousel_elem.trigger('next.owl.carousel')
                });
                thumbs_slide.on('click', '.owl-prev', function() {
                    carousel_elem.trigger('prev.owl.carousel')
                });
                thumbs_slide.on('dragged.owl.carousel', function(e) {
                    if (e.relatedTarget.state.direction == 'left') {
                        carousel_elem.trigger('next.owl.carousel')
                    } else {
                        carousel_elem.trigger('prev.owl.carousel')
                    }
                });

                /*--------------------------
                    THUMB CAROUSEL ACTIVE
                ----------------------------*/
                thumbs_slide.owlCarousel({
                    loop: loop,
                    items: thumbs,
                    margin: 10,
                    cente: true,
                    autoplay: autoplay,
                    autoplayTimeout: autoplaytimeout,
                    autoplayHoverPause: hover_pause,
                    smartSpeed: slide_speed,
                    nav: false,
                    responsive: {
                        0: {
                            items: 3
                        },
                        768: {
                            items: 3
                        }
                    }
                }).on('click', '.owl-item', function() {
                    var i = $(this).index() - (thumbs + 1);
                    thumbs_slide.trigger('to.owl.carousel', [i, slide_speed, true]);
                    carousel_elem.trigger('to.owl.carousel', [i, slide_speed, true]);
                });
            }
        }
    }


    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Teams_Widget.default', Owl_Team_Carousel_Script_Handle);
       
    });
})(jQuery);