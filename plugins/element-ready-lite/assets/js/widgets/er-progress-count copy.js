(function($) {
         
    var Element_Ready_Progress_Script = function($scope, $) {
        var element = $scope.find('.element__ready__progressbar__activation').eq(0);
        var settings = element.data('options');
        var id = element.attr('id');
        var activation_id = $('#' + id);

        var percentage = settings['percentage'] ? settings['percentage'] : 70;
        var unit = settings['unit'] ? settings['unit'] : '%';
        var height = settings['height'] ? settings['height'] + 'px' : '10px';
        var radius = settings['radius'] ? settings['radius'] + 'px' : '0px';
        var ShowProgressCount = settings['ShowProgressCount'];
        var animation = settings['animation'];
        var duration = settings['duration'] ? settings['duration'] : 1000;
        var fillBackgroundColor = settings['fillBackgroundColor'] ? settings['fillBackgroundColor'] : '#3498db';
        var backgroundColor = settings['backgroundColor'] ? settings['backgroundColor'] : '#EEEEEE';

        activation_id.appear(function() {
            var active = $(this);
            active.easyBar({
                percentage: percentage,
                unit: unit,
                ShowProgressCount: ShowProgressCount,
                animation: animation,
                duration: duration,
                fillBackgroundColor: fillBackgroundColor,
                backgroundColor: backgroundColor,
                radius: radius,
                height: height,
                width: '100%',
            });
        });
    }
    
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Progress_Widget.default', Element_Ready_Progress_Script);
    });

})(jQuery);