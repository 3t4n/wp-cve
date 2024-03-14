(function($) {

    var Timeline_Script_Handle_Data = function($scope, $) {

        var timeline_content = $scope.find('.element__ready__timeline__activation').eq(0);
        var settings = timeline_content.data('settings');
        var timeline_id = settings['timeline_id'];
        var mode = settings['mode'];
        var horizontal_start_postion = settings['horizontal_start_postion'];
        var vertical_start_postion = settings['vertical_start_postion'];
        var force_vartical_in = settings['force_vartical_in'] ? parseInt(settings['force_vartical_in']) : 700;
        var move_item = settings['move_item'] ? parseInt(settings['move_item']) : 1;
        var start_index = settings['start_index'] ? parseInt(settings['start_index']) : 0;
        var vartical_trigger = settings['vartical_trigger'] ? settings['vartical_trigger'] : "15%";
        var show_item = settings['show_item'] ? parseInt(settings['show_item']) : 3;

        try {
            $('#element__ready__timeline__' + timeline_id + ' .timeline').timeline({
                forceVerticalMode: force_vartical_in,
                horizontalStartPosition: horizontal_start_postion,
                mode: mode,
                moveItems: move_item,
                startIndex: start_index,
                verticalStartPosition: vertical_start_postion,
                verticalTrigger: vartical_trigger,
                visibleItems: show_item,
            });
        } catch (err) {
            $('#element__ready__timeline__' + timeline_id + ' .timeline').timeline({
                forceVerticalMode: force_vartical_in,
                horizontalStartPosition: horizontal_start_postion,
                moveItems: move_item,
                startIndex: start_index,
                verticalStartPosition: vertical_start_postion,
                verticalTrigger: vartical_trigger,
                visibleItems: show_item,
            });
        }
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Timeline_Widget.default', Timeline_Script_Handle_Data);
    });

})(jQuery);