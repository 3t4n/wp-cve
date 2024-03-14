(function($) {
    
    /*---------------------------------------
        FIRST WORD BLOCK CSS
    -----------------------------------------*/
   
    var Element_Ready_DataTable_Handaler = function($scope, $) {

        var content   = $scope.find('.element__ready__datatable').eq(0);
        var settings  = content.data('options');
        var id        = settings['id'];
        var paging    = settings['show_pagi'];
        var searching = settings['show_searching'];
        var ordering  = settings['ordering'];
        var info      = settings['info'];

        $('.element__ready__datatable__' + id).DataTable({
            paging: paging,
            searching: searching,
            ordering: ordering,
            pageLength: 50,
            "info": info,
        });
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Data_Table_Widget.default', Element_Ready_DataTable_Handaler);
    });

})(jQuery);