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
if(team_settings.team_nav_style=="bullets")
{
jQuery(team_settings.teamcarouselid).owlCarousel({
            rtl:team_settings.rtl,
            navigation : true, // Show next and prev buttons        
            autoplay: true,
            autoplayTimeout:3000,
            autoplayHoverPause: true,
            smartSpeed: 1000,        
        
            loop:true, // loop is true up to 1199px screen.
            nav:false, // is true across all sizes
            margin:30, // margin 10px till 960 breakpoint
            
            responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
            //items: 5,
            dots: true,
            navText: ["<i class='fa fa-arrow-left'></i>","<i class='fa fa-arrow-right'></i>"],
            responsive:{
                100:{ items:1 },
                480:{ items:1 },
                768:{ items:2 },
                1000:{ items:3 }
            },
                        rtl: jQuery.bol_return(team_settings.rtl)
        }); 
}
else if(team_settings.team_nav_style=="navigation")
{
jQuery(team_settings.teamcarouselid).owlCarousel({
            rtl:team_settings.rtl,
            navigation : true, // Show next and prev buttons        
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            smartSpeed: 1000,        
        
            loop:true, // loop is true up to 1199px screen.
            nav:true, // is true across all sizes
            margin:30, // margin 10px till 960 breakpoint
            
            responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
            //items: 5,
            dots: false,
            navText: ["<i class='fa fa-arrow-left'></i>","<i class='fa fa-arrow-right'></i>"],
            responsive:{
                100:{ items:1 },
                480:{ items:1 },
                768:{ items:2 },
                1000:{ items:3 }
            },
                        rtl: jQuery.bol_return(team_settings.rtl)
        }); 
}
else
{
    jQuery(team_settings.teamcarouselid).owlCarousel({
            rtl:team_settings.rtl,
            navigation : true, // Show next and prev buttons        
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            smartSpeed: 1000,        
        
            loop:true, // loop is true up to 1199px screen.
            nav:true, // is true across all sizes
            margin:30, // margin 10px till 960 breakpoint
            
            responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
            //items: 5,
            dots: true,
            navText: ["<i class='fa fa-arrow-left'></i>","<i class='fa fa-arrow-right'></i>"],
            responsive:{
                100:{ items:1 },
                480:{ items:1 },
                768:{ items:2 },
                1000:{ items:3 }
            },
                        rtl: jQuery.bol_return(team_settings.rtl)
        }); 
}   
}); 