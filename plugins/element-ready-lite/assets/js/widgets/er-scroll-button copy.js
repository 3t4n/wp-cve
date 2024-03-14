(function($) {
    
   
    var Scroll_Buttom_Script = function($scope, $) {

        var content = $scope.find('.element__ready__scroll__button').eq(0);
        var settings = content.data('options');
        var scroll_type = settings['scroll_type'];

        if ('scroll_top' == scroll_type) {
            var bodyAnimate = $('html,body');
            var scrollToTop = $('.element__ready__scroll__button.scroll_top');
            scrollToTop.on('click', function(e) {
                bodyAnimate.animate({
                    scrollTop: 0
                }, 700);
                return false;
            });
        }
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Scroll_Button.default', Scroll_Buttom_Script);
    });

})(jQuery);