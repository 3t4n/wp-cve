(function($) {

    var GS_Behance = function( $scope, $ ) {
        var $widget = $scope.find('.gs_beh_area');
        if ( ! $widget.length ) return;
        $(document).trigger( 'gsbeh:scripts:reprocess' );
    }
    
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/gs-behance.default', GS_Behance );
    });

})(jQuery);