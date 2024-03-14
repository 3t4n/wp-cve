jQuery(document).ready(function($) {

    jQuery(document).bind("contextmenu",function(e){

        jQuery('#ts_pct_wrapper').fadeIn('fast', function(){
            jQuery(".ts_pct_modal").animate({
                opacity: 1
            });

        });
        return false;
    });

    jQuery(".close-ts-pct-modal").on("click", function(){
        jQuery(".ts_pct_modal").animate({
            opacity: 0
        }, 500, function() {
            jQuery("#ts_pct_wrapper" ).fadeOut("fast");
        });

    });

});
