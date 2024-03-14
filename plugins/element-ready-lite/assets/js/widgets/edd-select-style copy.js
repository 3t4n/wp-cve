(function($) {
    
    var Element_Ready_Select_Style_Script = function($scope, $) {
        var select_Ids = $('.edd_price_options select,.single-widgets select,.download__filter__edd__sorting select,.download__orderby__shoring__filter select,select');
        $('select').addClass('wide');
        select_Ids.niceSelect();
        $('.nice-select').after('<div class="clearfix"></div>');
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Products_Widget.default', Element_Ready_Select_Style_Script);
    });

})(jQuery);