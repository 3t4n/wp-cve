(function($){
"use strict";
	var googlemap_elem = $('.htmegavc-sliderarea');
	googlemap_elem.each(function () {

	    var thumbnailscarousel_elem_for = jQuery(this).find('.htmegavc-thumbgallery-for').eq(0);
	    var thumbnailscarousel_elem_nav = jQuery(this).find('.htmegavc-thumbgallery-nav').eq(0);

        var settings = thumbnailscarousel_elem_for.data('settings');
        var arrows = settings['arrows'];
        var arrow_prev_txt = settings['arrow_prev_txt'];
        var arrow_next_txt = settings['arrow_next_txt'];
        var autoplay = settings['autoplay'];
        var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
        var animation_speed = parseInt(settings['animation_speed']) || 300;
        var pause_on_hover = settings['pause_on_hover'];
        var center_mode = settings['center_mode'];
        var center_padding = parseInt(settings['center_padding']) || 50;
        var center_padding = center_padding.toString();
        var display_columns = parseInt(settings['display_columns']) || 1;
        var scroll_columns = parseInt(settings['scroll_columns']) || 1;
        var tablet_width = parseInt(settings['tablet_width']) || 800;
        var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 1;
        var tablet_scroll_columns = parseInt(settings['tablet_scroll_columns']) || 1;
        var mobile_width = parseInt(settings['mobile_width']) || 480;
        var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;
        var mobile_scroll_columns = parseInt(settings['mobile_scroll_columns']) || 1;

        // Slider Thumbnails
        var navsettings = thumbnailscarousel_elem_nav.data('navsettings');
        var navarrows = navsettings['navarrows'];
        var navarrow_prev_txt = navsettings['navarrow_prev_txt'];
        var navarrow_next_txt = navsettings['navarrow_next_txt'];
        // var navdots = navsettings['navdots'];
        var navvertical = navsettings['navvertical'];
        var navautoplay = navsettings['navautoplay'];
        var navautoplay_speed = parseInt(navsettings['navautoplay_speed']) || 3000;
        var navanimation_speed = parseInt(navsettings['navanimation_speed']) || 300;
        var navpause_on_hover = navsettings['navpause_on_hover'];
        var navcenter_mode = navsettings['navcenter_mode'];
        var navcenter_padding = parseInt(navsettings['navcenter_padding']) || 50;
        var navcenter_padding = navcenter_padding.toString();
        var navdisplay_columns = parseInt(navsettings['navdisplay_columns']) || 1;
        var navscroll_columns = parseInt(navsettings['navscroll_columns']) || 1;
        var navtablet_width = parseInt(navsettings['navtablet_width']) || 800;
        var navtablet_display_columns = parseInt(navsettings['navtablet_display_columns']) || 1;
        var navtablet_scroll_columns = parseInt(navsettings['navtablet_scroll_columns']) || 1;
        var navmobile_width = parseInt(navsettings['navmobile_width']) || 480;
        var navmobile_display_columns = parseInt(navsettings['navmobile_display_columns']) || 1;
        var navmobile_scroll_columns = parseInt(navsettings['navmobile_scroll_columns']) || 1;

        thumbnailscarousel_elem_for.slick({
            arrows: arrows,
            prevArrow: '<button class="htmegavc-carosul-prev"><i class="'+arrow_prev_txt+'"></i></button>',
            nextArrow: '<button class="htmegavc-carosul-next"><i class="'+arrow_next_txt+'"></i></button>',
            asNavFor:thumbnailscarousel_elem_nav,
            infinite: true,
            autoplay: autoplay,
            autoplaySpeed: autoplay_speed,
            speed: animation_speed,
            fade: false,
            pauseOnHover: pause_on_hover,
            slidesToShow: display_columns,
            slidesToScroll: scroll_columns,
            centerMode: center_mode,
            centerPadding: center_padding,
            responsive: [
                {
                    breakpoint: tablet_width,
                    settings: {
                        slidesToShow: tablet_display_columns,
                        slidesToScroll: tablet_scroll_columns
                    }
                },
                {
                    breakpoint: mobile_width,
                    settings: {
                        slidesToShow: mobile_display_columns,
                        slidesToScroll: mobile_scroll_columns
                    }
                }
            ]
        });

        thumbnailscarousel_elem_nav.slick({
            slidesToShow: navdisplay_columns,
            slidesToScroll: navscroll_columns,
            asNavFor: thumbnailscarousel_elem_for,
            // dots: navdots,
            autoplay: navautoplay,
            autoplaySpeed: navautoplay_speed,
            centerMode: navcenter_mode,
            centerPadding: navcenter_padding,
            responsive: [
                {
                    breakpoint: navtablet_width,
                    settings: {
                        slidesToShow: navtablet_display_columns,
                        slidesToScroll: navtablet_scroll_columns
                    }
                },
                {
                    breakpoint: navmobile_width,
                    settings: {
                        slidesToShow: navmobile_display_columns,
                        slidesToScroll: navmobile_scroll_columns
                    }
                }
            ],
            infinite: true,
            focusOnSelect: true,
            vertical: navvertical,
            arrows: navarrows,
            prevArrow: '<button class="htmegavc-carosul-prev"><i class="'+navarrow_prev_txt+'"></i></button>',
            nextArrow: '<button class="htmegavc-carosul-next"><i class="'+navarrow_next_txt+'"></i></button>',
            draggable: true,
            verticalSwiping: true,
        });


	});

})(jQuery);