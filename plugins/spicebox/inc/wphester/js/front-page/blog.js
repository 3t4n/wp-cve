//RLT check function
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
    switch (newz_settings.newz_nav_style) {
        case 'bullets':
            newz_settings.newz_nav_style_nav = false;
            newz_settings.newz_nav_style_dot = true;
            break;
        case 'navigation':
            newz_settings.newz_nav_style_nav = true;
            newz_settings.newz_nav_style_dot = false;
            break;
        case 'both':
            newz_settings.newz_nav_style_nav = true;
            newz_settings.newz_nav_style_dot = true;
            break;
        default:
            newz_settings.newz_nav_style_nav = false;
            newz_settings.newz_nav_style_dot = true;
    }
    jQuery("#blog-carousel1").owlCarousel({
            navigation : true, // Show next and prev buttons
            autoplay: 3000,        
            smartSpeed: newz_settings.smoothSpeed,
            autoplayTimeout: newz_settings.animationSpeed,
            autoplayHoverPause: true,
            loop:true, // loop is true up to 1199px screen.
            nav:newz_settings.newz_nav_style_nav, // is true across all sizes
            margin:20, // margin 10px till 960 breakpoint
            responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
            autoHeight: true,
            items: 3,
            dots: newz_settings.newz_nav_style_dot,
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
            responsive:{
                100:{ items:1 },
                480:{ items:1 },
                768:{ items:2 },
                1000:{ items:3 }
            },
            rtl: jQuery.bol_return(newz_settings.rtl)
        });
	});