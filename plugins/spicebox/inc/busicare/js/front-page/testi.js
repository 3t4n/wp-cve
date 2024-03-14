// HOMEPAGE TESTIMONIAL
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

    if (testimonial_settings.slide_items == 1) {
        jQuery(testimonial_settings.design_id).owlCarousel({
            rtl:testimonial_settings.rtl,
            navigation: true, // Show next and prev buttons
            autoplay: 3000,
            smartSpeed: 1000,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            loop: true, // loop is true up to 1199px screen.
            nav: testimonial_settings.testimonial_nav_style_nav,
            margin: 30, // margin 10px till 960 breakpoint
            autoHeight: true,
            responsiveClass: true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
            items: 1,
            dots: testimonial_settings.testimonial_nav_style_dot,
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
            responsive: {
                200: {items: 1},
                480: {items: 1},
                768: {items: 1},
                1000: {items: 1}
            },
            rtl: jQuery.bol_return(testimonial_settings.rtl)
        });
    } else {
        jQuery(testimonial_settings.design_id).owlCarousel({
            rtl:testimonial_settings.rtl,
            navigation: true, // Show next and prev buttons
            autoplay: 3000,
            smartSpeed: 1000,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            loop: true, // loop is true up to 1199px screen.
            nav: testimonial_settings.testimonial_nav_style_nav,
            margin: 30, // margin 10px till 960 breakpoint
            autoHeight: true,
            responsiveClass: true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
            items: 1,
            dots: testimonial_settings.testimonial_nav_style_dot,
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
            responsive: {
                200: {items: 1},
                480: {items: 1},
                768: {items: testimonial_settings.slide_items},
                1000: {items: testimonial_settings.slide_items}
            },
            rtl: jQuery.bol_return(testimonial_settings.rtl)
        });
    }
 }); 