(function($) {
    
      /*---------------------------------------
        EDD CATEGORY SEARCH FEATURES SELECT
    -----------------------------------------*/
    var Element_Ready_Tooltip_Modal_Script = function($scope, $) {
       
        var tooltip_content = $('.element__ready__toltip__modal');
        if (tooltip_content.length > 0) {

            var window_width = $(window).width();
            if (window_width > 767) {

                tooltip_content.tooltipster({
                    animation: 'fade',
                    delay: 10,
                    contentAsHTML: true,
                    interactive: false,
                    maxWidth: 400,
                    theme: ['tooltipster-noir', 'noir-customized'],
                });
            }
        }
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Products_Widget.default', Element_Ready_Tooltip_Modal_Script);
    });

})(jQuery);