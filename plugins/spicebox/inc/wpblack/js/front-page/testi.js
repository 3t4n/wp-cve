if (!jQuery.bol_return) {
    jQuery.extend({
        bol_return: function (tmp_vl) {
            if (tmp_vl == 1) {
                return true;
            }
            return false;
        }

    });
}
jQuery(document).ready(function() {        
       /* ---------------------------------------------- /*
         * Home section height
         /* ---------------------------------------------- */
 switch (testimonial_settings.testimonial_nav_style) {
        case 'bullets':
            testimonial_settings.testimonial_nav_style_nav = false;
            testimonial_settings.testimonial_nav_style_dot = true;
            break;
        case 'navigation':
            testimonial_settings.testimonial_nav_style_nav = true;
            testimonial_settings.testimonial_nav_style_dot = false;
            break;
        case 'both':
            testimonial_settings.testimonial_nav_style_nav = true;
            testimonial_settings.testimonial_nav_style_dot = true;
            break;
        default:
            testimonial_settings.testimonial_nav_style_nav = false;
            testimonial_settings.testimonial_nav_style_dot = true;
    }

        function buildHomeSection(homeSection) {
            if (homeSection.length > 0) {
                if (homeSection.hasClass('home-full-height')) {
                    homeSection.height(jQuery(window).height());
                } else {
                    homeSection.height(jQuery(window).height() * 0.85);
                }
            }
        }
    // Testimonial Carousel
    jQuery('#testimonial-carousel').owlCarousel({
                    autoplay: 3000,     
                    smartSpeed: testimonial_settings.smoothSpeed,
                    autoplayTimeout: testimonial_settings.animationSpeed,
                    autoplayHoverPause: true,
                    animateIn: testimonial_settings.animation,
                    smartSpeed: testimonial_settings.smoothSpeed,
                    loop:true,
                    items: testimonial_settings.slide_items,
                    margin: 0,
                    nav: testimonial_settings.testimonial_nav_style_nav,
                    navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
                    dots: testimonial_settings.testimonial_nav_style_dot,
                    autoHeight: true,
                    responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.            
                    responsive: {
                        0:{
                            items:1,
                            autoWidth: false
                        },
                        400:{
                            items:1,
                            autoWidth: false
                        },
                        600:{
                            items:1,
                            autoWidth: false
                        },
                        1000:{
                            items:1,
                            autoWidth: false
                        },
                        1200:{
                            items: 1,
                            autoWidth: false
                        }
                    },
                rtl: jQuery.bol_return(testimonial_settings.rtl)
    });
    jQuery(testimonial_settings.design_id).owlCarousel({
                //navigation : true, // Show next and prev buttons 
                autoplay: 3000,     
                smartSpeed: testimonial_settings.smoothSpeed,
                autoplayTimeout: testimonial_settings.animationSpeed,
                autoplayHoverPause: true,
                animateIn: testimonial_settings.animation,
                smartSpeed: testimonial_settings.smoothSpeed,

                loop:true, // loop is true up to 1199px screen.
                nav:testimonial_settings.testimonial_nav_style_nav, // is true across all sizes
                margin:0, // margin 10px till 960 breakpoint
                autoHeight: true,
                responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
                items: testimonial_settings.slide_items,
                dots: testimonial_settings.testimonial_nav_style_dot,
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                
                responsive:{ 
                    100:{ items:1 },
                    480:{ items:1 },
                    768:{ items:1 },
                    1000:{ items:1 }                
                }
        });    

    }); 
