(function ($) {
    $(document).ready(function () {
        // init jQuery UI Tabs

        $( ".dtheme-cmb2-tabs" )
            .tabs({
                activate: function( event, ui ) {
                    $(document).trigger('xl_cmb2_options_tabs_activated',[event, ui ]);
                }
            });
            //.addClass('ui-tabs-vertical ui-helper-clearfix');
    });
})(jQuery);

jQuery.noConflict();