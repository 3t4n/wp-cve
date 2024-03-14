(function($) {
    
    /*---------------------------------------
        FIRST WORD BLOCK CSS
    -----------------------------------------*/
   
    var Element_Ready_Flip_Box_Script = function($scope, $) {
        var elem = $scope.find('.element__ready__flipbox__activation').eq(0);
        var settings = elem.data('options');
        var random_id = elem.attr('id');
        var actve_id = settings['actve_id'];
        var flip_axis = settings['flip_axis'];
        var flip_trigger = settings['flip_trigger'];
        var flip_reverse = settings['flip_reverse'];
        var flip_transition = settings['flip_transition'];

        var activatation_id = $('#' + random_id);
        var inner_wrap_class = activatation_id.find('.flip__box__main__wrap');

        inner_wrap_class.flip({
            axis: flip_axis,
            trigger: flip_trigger,
            reverse: flip_reverse,
            speed: flip_transition,
            front: '.flip__box__front__part',
            back: '.flip__box__back__part'
        });
    }
    
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Flip_Box_Widget.default', Element_Ready_Flip_Box_Script);
    });

})(jQuery);