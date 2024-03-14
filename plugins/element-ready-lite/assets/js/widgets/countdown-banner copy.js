(function($) {

    var general__countdown_banner = function($scope) {

        var $target     = $scope.find('.element--ready--lite--countdown');
        let date        = $target.data('date');
        let time        = $target.data('time');
        let _label_day  = $target.data('day');
        let _label_hour = $target.data('hour');
        let _label_min  = $target.data('min');
        let _label_sec  = $target.data('sec');
    
        var countDownDate = new Date(date +' '+ time).getTime();
        let label_days  = _label_day;
        let label_hours = _label_hour;
        let label_min   = _label_min;
        let label_sec   = _label_sec;
        
        var x = setInterval(function() {
        var now      = new Date().getTime();
        var distance = countDownDate - now;
        var days     = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours    = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes  = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds  = Math.floor((distance % (1000 * 60)) / 1000);
    
        $target.find('.element--ready--lite--day .er-countdown--num').text(days);
        $target.find('.element--ready--lite--hour .er-countdown--num').text(hours);
        $target.find('.element--ready--lite--min .er-countdown--num').text(minutes);
        $target.find('.element--ready--lite--sec .er-countdown--num').text(seconds);
    
        $target.find('.element--ready--lite--day .er-countdown--word').text(label_days);
        $target.find('.element--ready--lite--hour .er-countdown--word').text(label_hours);
        $target.find('.element--ready--lite--min .er-countdown--word').text(label_min);
        $target.find('.element--ready--lite--sec .er-countdown--word').text(label_sec);
       
            if (distance < 0) {
              
                clearInterval(x);
                $target.find('.element--ready--lite--day .er-countdown--num').text(0);
                $target.find('.element--ready--lite--hour .er-countdown--num').text(0);
                $target.find('.element--ready--lite--min .er-countdown--num').text(0);
                $target.find('.element--ready--lite--sec .er-countdown--num').text(0);

            }
        }, 1000);
       
    
    }


    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/element--ready--lite--countdown.default', general__countdown_banner);
       
    });
})(jQuery);