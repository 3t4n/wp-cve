(function($) {
    
    var Element_Ready_Counter_Circle_Widget_Script_Handle = function($scope, $) {

        var circle_progressbar = $scope.find('.element__ready__progress__counter').eq(0);
        var settings = circle_progressbar.data('settings');
        var random_id = parseInt(settings['random_id']);
        var figure_display = settings['figure_display'];
        var end_fill = settings['end_fill'] ? parseInt(settings['end_fill']) : 50;
        var unit_output_text = settings['unit_output_text'] ? settings['unit_output_text'] : '%';
        var main_background = settings['main_background'];
        var fill_color = settings['fill_color'];
        var progress_fill_color = settings['progress_fill_color'];
        var progress_width = settings['progress_width'] ? parseInt(settings['progress_width']) : 10;
        var empty_fill_opacity = settings['empty_fill_opacity'] ? settings['empty_fill_opacity'] : 0.3;
        var animation_duration = settings['animation_duration'] ? parseInt(settings['animation_duration']) : 2;

        var active_progressbar = $("#element__ready__progress__counter__" + random_id + "");
        active_progressbar.svgprogress({
            figure: figure_display,
            endFill: end_fill,
            unitsOutput: "<span class='suffix__unit__text'>" + unit_output_text + "</span>",
            emptyFill: fill_color,
            progressFill: progress_fill_color,
            progressWidth: progress_width,
            background: main_background,
            emptyFillOpacity: empty_fill_opacity,
            animationDuration: animation_duration,
        });
        active_progressbar.each(function() {
            active_progressbar.appear(function() {
                active_progressbar.trigger('redraw');
            });
        });
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Counter_Circle_Widget.default', Element_Ready_Counter_Circle_Widget_Script_Handle);
    });

})(jQuery);