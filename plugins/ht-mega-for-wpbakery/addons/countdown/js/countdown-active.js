jQuery(window).load(function() {
    jQuery('.htmegavc-countdown-wrapper [data-countdown]').each(function () {
        var $this = jQuery(this);
        countdownoptions = jQuery(this).data('countdown');

        finalDate = countdownoptions.htmegadate;
        $this.countdown(finalDate, function (event) {
            var finalTime, daysTime, hours, minutes, second;

            if( countdownoptions.lavelhide == 'yes' ){
                daysTime = '<span class="ht-count days"><span class="count-inner"><span class="time-count">%-D</span> </span></span>';
                hours = '<span class="ht-count hour"><span class="count-inner"><span class="time-count">%-H</span> </span></span>';
                minutes = '<span class="ht-count minutes"><span class="count-inner"><span class="time-count">%M</span> </span></span>';
                second = '<span class="ht-count second"><span class="count-inner"><span class="time-count">%S</span> </span></span>';
            }else{
                daysTime = '<span class="ht-count days"><span class="count-inner"><span class="time-count">%-D</span> <p>'+countdownoptions.label_days+ '</p></span></span>';
                hours = '<span class="ht-count hour"><span class="count-inner"><span class="time-count">%-H</span> <p>'+countdownoptions.label_hours+ '</p></span></span>';
                minutes = '<span class="ht-count minutes"><span class="count-inner"><span class="time-count">%M</span> <p>'+countdownoptions.label_minutes+ '</p></span></span>';
                second = '<span class="ht-count second"><span class="count-inner"><span class="time-count">%S</span> <p>'+countdownoptions.label_seconds+ '</p></span></span>';
            }

            // Total default target time
            if ( countdownoptions.hide_day == 'true' ){
                daysTime = '';
            }
            if ( countdownoptions.hide_hour == 'true' ){
                hours = '';
            }
            if ( countdownoptions.hide_minute == 'true' ){
                minutes = '';
            }
            if ( countdownoptions.hide_second == 'true' ){
                second = '';
            }

            finalTime = daysTime + hours + minutes + second;

            $this.html(event.strftime( finalTime ));
      });
    });

});