(function($) {

    var Element_Ready_Progressbar_Script = function() {
        $('.element__ready__prgressbar').each(function() {
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
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Progress_Roadmap_Widget.default', Element_Ready_Progressbar_Script);
    });

})(jQuery);