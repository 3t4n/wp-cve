(function ($) {
    'use strict';
    $(function () {
         jQuery(".wt-crp-container #setting-error-settings_updated:eq(1)").hide();

//        $("select").select2();
//
//        $("select").on("select2:select", function (evt) {
//            var element = evt.params.data.element;
//            var $element = $(element);
//
//            $element.detach();
//            $(this).append($element);
//            $(this).trigger("change");
//        });
    });
    
    // Copy to clipboard promo code
    jQuery(document).ready(function(){
        jQuery('.wt_rp_copy_content').click(function(e){
            e.preventDefault();
            navigator.clipboard.writeText('PROMO30');
            jQuery('.wt_rp_copied').show();
            jQuery('.wt_rp_copied').css('color','green');
            setTimeout(function() { $(".wt_rp_copied").hide(); }, 500);
        });
    });
})(jQuery);
