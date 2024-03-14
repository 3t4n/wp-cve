(function($) {

    /*------------------------------
            SLICK CAROUSEL HANDLER
        -------------------------------*/
        var Swiper_Carousel_Script_Handle = function($scope, $) {
    
            var carousel_elem = $scope.find('.element-ready-carousel-activation');
    
            var settings = carousel_elem.data('settings');
            var widget_id = carousel_elem.data('id');
            var slideid = settings['slideid'];
            
            var slide_item_margin = parseInt(settings['slide_item_margin']);
            var autoplay = settings['autoplay'];
            var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
            var animation_speed = parseInt(settings['animation_speed']) || 300;
            var center_mode = settings['center_mode'];
            var rows = settings['rows'] ? parseInt(settings['rows']) : 1;
            var focusonselect = settings['focusonselect'];
            var vertical = settings['vertical'];
            var infinite = settings['infinite'];
    
            var desktop_item = parseInt(settings['desktop_item']) || 1;
            var desktop_item_scroll = parseInt(settings['desktop_item_scroll']) || 1;
    
            var medium_item = parseInt(settings['medium_item']) || 1;
            var medium_item_margin = parseInt(settings['medium_item_margin']) || 800;
            var medium_item_scroll = parseInt(settings['medium_item_scroll']) || 1;
    
            var tablet_item = parseInt(settings['tablet_item']) || 1;
            var tablet_item_margin = parseInt(settings['tablet_item_margin']) || 800;
            var tablet_item_scroll = parseInt(settings['tablet_item_scroll']) || 1;
    
            var mobile_item = parseInt(settings['mobile_item']) || 1;
            var mobile_item_margin = parseInt(settings['mobile_item_margin']) || 480;
            var mobile_item_scroll = parseInt(settings['mobile_item_scroll']) || 1;
    
            /* ARROW */
            var arrows = settings['arrows'];
            if (arrows === true) {
                var navigation = {
                    nextEl: '.element-ready-carosul-next' + slideid,
                    prevEl: '.element-ready-carosul-prev' + slideid,
                };
            } else {
                var navigation = '';
            }
    
            /* DOTS */
            var dots = settings['dots'];
            var dots_type = settings['dots_type']
            var dynamic_dots = settings['dynamic_dots']
            if (dots === true) {
                var dots_type
                var pagination = {
                    el: '.element-ready-carousel-dots' + slideid,
                    type: dots_type,
                    /* String with type of pagination. Can be "bullets", "fraction", "progressbar" or "custom" */
                    dynamicBullets: dynamic_dots,
                    dynamicMainBullets: 1,
                    clickable: true,
                    bulletElement: 'div',
                };
            } else {
                var pagination = '';
            }
    
            /* SCROLL BAR */
            var slide_scrollbar = settings['slide_scrollbar'];
            var slide_scrollbar_dragable = settings['slide_scrollbar_dragable'];
            var slide_scrollbar_hide = settings['slide_scrollbar_hide'];
            if (slide_scrollbar === true) {
                var scrollbar = {
                    el: '.swiper-scrollbar' + slideid,
                    draggable: slide_scrollbar_dragable,
                    hide: slide_scrollbar_hide,
                };
            } else {
                var scrollbar = '';
            }
    
            /* SLIDE STYLE */
            var slide_style = settings['slide_style'];
    
            /* FADE */
            var cross_fade = settings['cross_fade'];
    
            /* CUBE */
            var cube_shadow = settings['cube_shadow'];
            var cube_item_shadow = settings['cube_item_shadow'];
            var cube_shadow_offset = parseInt(settings['cube_shadow_offset']);
            var cube_shadow_scale = parseInt(settings['cube_shadow_scale']);
    
            /* COVERFLOW */
            var coverflow_rotate = parseInt(settings['coverflow_rotate']) || 0;
            var coverflow_stretch = parseInt(settings['coverflow_stretch']) || 80;
            var coverflow_depth = parseInt(settings['coverflow_depth']) || 200;
            var coverflow_modifier = parseInt(settings['coverflow_modifier']) || 1;
            var coverflow_shadow = settings['coverflow_shadow'];
    
            /* FLIP */
            var flip_rotate = parseInt(settings['flip_rotate']);
            var flip_shadow = settings['flip_shadow'];
    
            if ('slide' === slide_style) {
                var effect = 'slide';
            } else if ('fade' === slide_style) {
                var effect = 'fade';
                var fadeEffect = {
                    crossFade: cross_fade,
                };
            } else if ('cube' === slide_style) {
                var effect = 'cube';
                var cubeEffect = {
                    shadow: cube_shadow,
                    slideShadows: cube_item_shadow,
                    shadowOffset: cube_shadow_offset,
                    shadowScale: cube_shadow_scale,
                };
            } else if ('coverflow' === slide_style) {
                var effect = 'coverflow';
                var coverflowEffect = {
                    rotate: coverflow_rotate,
                    stretch: coverflow_stretch,
                    depth: coverflow_depth,
                    modifier: coverflow_modifier,
                    slideShadows: coverflow_shadow,
                };
            } else if ('flip' === slide_style) {
                var effect = 'flip';
                var flipEffect = {
                    rotate: flip_rotate,
                    slideShadows: flip_shadow,
                };
            } else {
                var effect = 'slide';
                var fadeEffect = '';
                var cubeEffect = '';
                var coverflowEffect = '';
                var flipEffect = '';
            }
    
            if (vertical === true) {
                var direction = 'vertical';
            } else {
                var direction = 'horizontal';
            }
    
            if (autoplay === true) {
                var autoplay = {
                    delay: autoplay_speed,
                };
            } else {
                var autoplay = '';
            }
    
            var swipeSlide = new Swiper(`.element-ready-carousel-activation[data-id=${widget_id}]`, {
                /*breakpointsInverse:true,
                reverseDirection   : true,
                mousewheelControl  : true*/
                navigation: navigation,
                pagination: pagination,
                scrollbar: scrollbar,
                loop: infinite,
                autoplay: autoplay,
                speed: animation_speed,
                slideToClickedSlide: focusonselect,
                freeModeSticky: true,
                direction: direction,
                grabCursor: true,
                freeMode: false,
                centeredSlides: center_mode,
                effect: effect,
                coverflowEffect: coverflowEffect,
                fadeEffect: fadeEffect,
                flipEffect: flipEffect,
                cubeEffect: cubeEffect,
                slidesPerColumn: rows,
                slidesPerGroup: desktop_item_scroll,
                slidesPerView: desktop_item,
                spaceBetween: slide_item_margin,
                breakpoints: {
                    1024: {
                        slidesPerView: medium_item,
                        spaceBetween: medium_item_margin,
                        slidesPerGroup: medium_item_scroll,
                    },
                    768: {
                        slidesPerView: tablet_item,
                        spaceBetween: tablet_item_margin,
                        slidesPerGroup: tablet_item_scroll,
                    },
                    640: {
                        slidesPerView: mobile_item,
                        spaceBetween: mobile_item_margin,
                        slidesPerGroup: mobile_item_scroll,
                    }
                },
            });

        }
    
    
    
    
        $(window).on('elementor/frontend/init', function() {
            
            elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Image_Carousel_Alt.default', Swiper_Carousel_Script_Handle);
    
           
        });
    })(jQuery);