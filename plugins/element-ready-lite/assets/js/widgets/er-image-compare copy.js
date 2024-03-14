(function($) {
         
    
    var Element_Ready_Image_Compare_Script = function($scope, $) {
        var element = $scope.find('.element__ready__image__compare__wrap').eq(0);
        var settings = element.data('options');
        var id = element.attr('id');
        var activation_id = $('#' + id);

        var default_offset_pct = settings['default_offset_pct'] ? settings['default_offset_pct'] : 0.5;
        var orientation = settings['orientation'] ? settings['orientation'] : 'horizontal';
        var before_label = settings['before_label'] ? settings['before_label'] : 'before';
        var after_label = settings['after_label'] ? settings['after_label'] : 'after';
        var no_overlay = settings['no_overlay'];
        var move_slider_on_hover = settings['move_slider_on_hover'];
        var move_with_handle_only = settings['move_with_handle_only'];
        var click_to_move = settings['click_to_move'];

        activation_id.twentytwenty({
            default_offset_pct: default_offset_pct,
            orientation: orientation,
            before_label: before_label,
            after_label: after_label,
            no_overlay: no_overlay,
            move_slider_on_hover: move_slider_on_hover,
            move_with_handle_only: move_with_handle_only,
            click_to_move: click_to_move
        });
    }
    
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Image_Compare_Widget.default', Element_Ready_Image_Compare_Script);
    });

})(jQuery);