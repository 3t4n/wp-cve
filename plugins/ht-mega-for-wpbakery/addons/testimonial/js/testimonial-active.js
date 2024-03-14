jQuery(window).on('load', function() {
    jQuery('.htmegavc-testimonial-wrapper.slick-slider').each(function () {
        var $this = jQuery(this);
        testimonialOptions = jQuery(this).data('htmegavc-testimonial');

        var style = testimonialOptions.style  ? parseInt(testimonialOptions.style) : false;
        var autoplay = testimonialOptions.autoplay == 'true' ? true : false;
        var autoplaySpeed = testimonialOptions.autoplay_speed ? parseInt(testimonialOptions.autoplay_speed) : 3000;
        var dots = testimonialOptions.dots === 'true' ? true : false;
        var arrows = testimonialOptions.nav == 'true' ? true : false;
        var loop = testimonialOptions.loop === 'true' ? true : false;
        var nav_type = testimonialOptions.nav_type;
        var prev_icon = testimonialOptions.prev_icon;
        var next_icon = testimonialOptions.next_icon;
        var prev_text = testimonialOptions.prev_text;
        var next_text = testimonialOptions.next_text;
        var columns_on_desktop = testimonialOptions.columns_on_desktop  ? parseInt(testimonialOptions.columns_on_desktop) : '1';
        var columns_on_mobile = testimonialOptions.columns_on_mobile  ? parseInt(testimonialOptions.columns_on_mobile) : '1';
        var columns_on_tablet = testimonialOptions.columns_on_tablet  ? parseInt(testimonialOptions.columns_on_tablet) : '1';

        var prev_content = '';
        var next_content = '';

        if(nav_type == 'nav_type_icon'){
            prev_content = '<i class="'+ prev_icon +'"><i>';
            next_content = '<i class="'+ next_icon +'"><i>';
        } else {
            prev_content = prev_text;
            next_content = next_text;
        }


        if(style == 5){

            jQuery('.htmegavc-testimonial-for').slick({
                autoplay: autoplay,
                autoplaySpeed: autoplaySpeed,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                infinite: loop,
                asNavFor: '.htmegavc-testimonal-nav',
                lazyLoad:  'progressive'
            });

            jQuery('.htmegavc-testimonal-nav').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                asNavFor: '.htmegavc-testimonial-for',
                dots: false,
                arrows: false,
                centerMode: true,
                focusOnSelect: true,
                centerPadding: '0',
                lazyLoad:  'progressive'
            });


        }else if(style == '4'){
            $this.slick({
                slidesToShow: 1,
                autoplay: autoplay,
                autoplaySpeed: autoplaySpeed,
                arrows: false,
                easing: 'ease-in-out',
                dots: dots,
                dotsClass: 'testi-pagination-dots',
                appendDots: jQuery('.testimonial-pagination'),
                lazyLoad:  'progressive'
            });

            jQuery('.testimonial-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                asNavFor: '.testimonal-nav',
                lazyLoad:  'progressive'
            });
        } else {
            $this.slick({
                autoplay: autoplay,
                autoplaySpeed: autoplaySpeed,
                dots: dots,
                arrows: arrows,
                prevArrow: '<button class="htmegavc-testimonial-arrow-prev">'+ prev_content +'</button>',
                nextArrow: '<button class="htmegavc-testimonial-arrow-next">'+ next_content +'</button>',
                infinite: loop,
                slidesToShow: columns_on_desktop,
                centerMode: true,
                centerPadding: '0',
                lazyLoad:  'progressive',
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: columns_on_tablet
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: columns_on_mobile
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: columns_on_mobile
                        }
                    }
                ]
            });
        }
        
    });
});