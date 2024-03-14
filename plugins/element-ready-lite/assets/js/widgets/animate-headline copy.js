(function($) {

    var Animate_Headline_Script = function($scope, $) {

        var headline_content = $scope.find('.element__ready__animate__heading__activation').eq(0);
        var settings         = headline_content.data('settings');
        var wrap_id          = headline_content.attr('id');
        var active_wrap      = $('#' + wrap_id);
        var random_id        = settings['random_id'];
        var animate_type     = settings['animate_type'];
        
        active_wrap.animatedHeadline({
            animationType: animate_type
        });
    }


    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Animate_Headline.default', Animate_Headline_Script);
       
    });
})(jQuery);