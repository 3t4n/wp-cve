(function($) {

    var Timeline_Roadmap_Script_Handle_Data = function($scope, $) {
        var roadmap_content = $scope.find('.element__ready__roadmap__activation').eq(0);
        var settings = roadmap_content.data('settings');
        var random_id = settings['random_id']
        var content = settings['content'];
        var eventsPerSlide = settings['eventsPerSlide'] ? parseInt(settings['eventsPerSlide']) : 4;
        var slide = settings['slide'] ? parseInt(settings['slide']) : 1;
        var prevArrow = settings['prevArrow'] ? settings['prevArrow'] : '<i class="ti ti-left"></i>';
        var nextArrow = settings['nextArrow'] ? settings['nextArrow'] : '<i class="ti ti-right"></i>';
        var orientation = settings['orientation'] ? settings['orientation'] : 'auto';

        $('#element__ready__roadmap__timeline__' + random_id).roadmap(content, {
            eventsPerSlide: eventsPerSlide,
            slide: slide,
            prevArrow: prevArrow,
            nextArrow: nextArrow,
            orientation: orientation,
            eventTemplate: '<div class="single__roadmap__event event">' + '<div class="roadmap__event__icon">####ICON###</div>' + '<div class="roadmap__event__title">####TITLE###</div>' + '<div class="roadmap__event__date">####DATE###</div>' + '<div class="roadmap__event__content">####CONTENT###</div>' + '</div>'
        });
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Timeline_Roadmap_Widget.default', Timeline_Roadmap_Script_Handle_Data);
    });

})(jQuery);