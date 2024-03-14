(function($) {
    
     /*---------------------------------
        COUNTDOWN CIRCLE TIMER
    ----------------------------------*/
    var Element_Ready_Countdown_Circle_Timer_Script = function($scope, $) {

        var countdown_time_circle = $scope.find('.element__ready__circle__countdown').eq(0);
        var settings = countdown_time_circle.data('settings');
        var random_id = parseInt(settings['random_id']);
        var animation = settings['animation'];
        var start_angle = parseInt(settings['start_angle']);
        var circle_bg_color = settings['circle_bg_color'];
        var counter_width = settings['counter_width'];
        var bg_width = settings['bg_width'];
        var days_circle_color = settings['days_circle_color'];
        var hours_circle_color = settings['hours_circle_color'];
        var minutes_circle_color = settings['minutes_circle_color'];
        var seconds_circle_color = settings['seconds_circle_color'];
        var circle_days_title = settings['circle_days_title'];
        var circle_days_show = settings['circle_days_show'] == 'yes' ? true : false;
        var circle_hours_title = settings['circle_hours_title'];
        var circle_hours_show = settings['circle_hours_show'] == 'yes' ? true : false;
        var circle_mins_title = settings['circle_mins_title'];
        var circle_mins_show = settings['circle_mins_show'] == 'yes' ? true : false;
        var circle_sec_title = settings['circle_sec_title'];
        var circle_sec_show = settings['circle_sec_show'] == 'yes' ? true : false;

        var countdown = $("#element__ready__circle__countdown__" + random_id + "");

        createTimeCicles();

        $(window).on("resize", windowSize);

        function windowSize() {
            countdown.TimeCircles().destroy();
            createTimeCicles();
            countdown.on("webkitAnimationEnd mozAnimationEnd oAnimationEnd animationEnd", function() {
                countdown.removeClass("animated fadeIn");
            });
        }

        function createTimeCicles() {
            countdown.addClass("animated fadeIn");
            countdown.TimeCircles({
                animation: "" + animation + "",
                /*smooth , ticks*/
                circle_bg_color: "" + circle_bg_color + "",
                use_background: true,
                fg_width: counter_width,
                /*0.01 to 0.15*/
                bg_width: bg_width,
                start_angle: start_angle,
                time: {
                    Days: { color: "" + days_circle_color + "", text: circle_days_title.replace(/-/g, " "), show: circle_days_show },
                    Hours: { color: "" + hours_circle_color + "", text: circle_hours_title.replace(/-/g, " "), show: circle_hours_show },
                    Minutes: { color: "" + minutes_circle_color + "", text: circle_mins_title.replace(/-/g, " "), show: circle_mins_show },
                    Seconds: { color: "" + seconds_circle_color + "", text: circle_sec_title.replace(/-/g, " "), show: circle_sec_show },
                }
            });
            countdown.on("webkitAnimationEnd mozAnimationEnd oAnimationEnd animationEnd", function() {
                countdown.removeClass("animated fadeIn");
            });
        }
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Countdown_Circle_Widget.default', Element_Ready_Countdown_Circle_Timer_Script);
    });

})(jQuery);