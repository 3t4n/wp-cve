(function($) {
    
     
    var player;
    var Element_Ready_Sticky_Video_Script = function($scope, $) {
        var element = $scope.find('.sticky__video__wrap').eq(0);
        element.find('iframe').attr('class', 'sticky-container__object');
        var options = element.data('options');
        var active_id = element.attr('id');
        new StickyVideo(active_id);
    }
    
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Sticky_Video_Widget.default', Element_Ready_Sticky_Video_Script);
    });

})(jQuery);