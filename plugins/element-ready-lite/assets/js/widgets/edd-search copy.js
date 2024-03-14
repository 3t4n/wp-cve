(function($) {
    
    /*---------------------------------------
        EDD CATEGORY SEARCH FEATURES SELECT
    -----------------------------------------*/
    var Element_Ready_Edd_Search_Widget_Script = function($scope, $) {
        var select_Ids = $('select');
        $('select').addClass('wide');
        select_Ids.niceSelect();
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Search_Widget.default', Element_Ready_Edd_Search_Widget_Script);
    });

})(jQuery);