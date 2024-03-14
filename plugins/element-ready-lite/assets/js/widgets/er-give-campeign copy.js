(function($) {
  
 
    var Element_Ready_Give_Campains_Widget_Script = function() {
        $('.campain__prgressbar').each(function() {
            $(this).appear(function() {
                $(this).find('.count__bar').animate({
                    width: $(this).attr('data-percent')
                }, 1000);
                var percent = $(this).attr('data-percent');
                $(this).find('.count').html('<span>' + percent + '</span>');
            });
        });
    }


    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Give_Campains_Widget.default', Element_Ready_Give_Campains_Widget_Script);
    });

})(jQuery);