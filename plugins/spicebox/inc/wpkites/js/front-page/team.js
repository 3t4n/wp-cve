// HOMEPAGE TEAM
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

jQuery(document).ready(function () {
    switch (team_settings.team_nav_style) {
        case 'bullets':
            team_settings.team_nav_style_nav = false;
            team_settings.team_nav_style_dot = true;
            break;
        case 'navigation':
            team_settings.team_nav_style_nav = true;
            team_settings.team_nav_style_dot = false;
            break;
        case 'both':
            team_settings.team_nav_style_nav = true;
            team_settings.team_nav_style_dot = true;
            break;
        default:
            team_settings.team_nav_style_nav = false;
            team_settings.team_nav_style_dot = true;
    }

    jQuery('#team-carousel').owlCarousel({ 
        navigation: true, // Show next and prev buttons
        autoplay: 3000,
        smartSpeed: team_settings.team_smooth_speed,
        autoplayTimeout: team_settings.team_animation_speed,
        autoplayHoverPause: true,
        loop: true, // loop is true up to 1199px screen.
        nav: team_settings.team_nav_style_nav,
        margin: 30, // margin 10px till 960 breakpoint
        autoHeight: true,
        responsiveClass: true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
        items: 3,
        dots: team_settings.team_nav_style_dot,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            200: {items: 1},
            480: {items: 1},
            678: {items: 2},
            960: {items: 3}
        },
        rtl: jQuery.bol_return(team_settings.rtl)
    });
});