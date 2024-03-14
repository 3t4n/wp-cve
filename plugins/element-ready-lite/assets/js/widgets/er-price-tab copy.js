(function($) {

    /*--------------------------------
      Price  TABS ACTIVE
    ----------------------------------*/
    var Element_Ready_Price_Tabs_Script = function($scope, $) {

        var tabs_area      = $scope.find('.tabs__area');
        var get_id         = tabs_area.attr('id');
        var tabs_id        = $('#' + get_id);
        var tab_active     = tabs_id.find('.tab__nav a');
        var tab_active_nav = tabs_id.find('.tab__nav li');
        var tab_items      = tabs_id.find('.single__tab__item');

        tab_active.on('click', function(event) {

            $(tab_active_nav).removeClass('active');
            $(this).parent().addClass('active');
            tab_items.hide();
            tab_items.removeClass('active');
            $($(this).attr('href')).fadeIn(700);
            $($(this).attr('href')).addClass('active');
            event.preventDefault();
        });

    }

    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Price_Tabs_Widget.default', Element_Ready_Price_Tabs_Script);
       
    });
})(jQuery);