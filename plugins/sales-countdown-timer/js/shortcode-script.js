let count = 0;
let vi_sctr_x;
// vi_sctr_x = setInterval(function () {
//     count++;
// }, 1000);

jQuery(document).ready(function ($) {
//sales countdown timer
    'use strict';
    sctv_run_countdown();
    let variation_form = jQuery('body').find('.variations_form').closest('form');
    if (variation_form.length) {
        variation_form.on("show_variation", function (event, variation) {
            sctv_run_countdown();
        });
    }
    //flatsome
    jQuery(document).on('append.infiniteScroll', function (event, response, path, items) {
        sctv_run_countdown();
    });
    //ajaxComplete
    jQuery(document).on('ajaxComplete', function (event, jqxhr, settings) {
        sctv_run_countdown();
        return false;
    });
});
//compatibale vs Elementor
jQuery(window).on('elementor/frontend/init', function () {
    'use strict';
    if (window.elementor) {
        elementorFrontend.hooks.addAction('frontend/element_ready/sales-countdown-timer.default', function () {
            if (jQuery('.woo-sctr-value-bar').length > 0) {
                jQuery('.woo-sctr-value-bar').each(function () {
                    jQuery(this).css({'transform': 'rotate(' + jQuery(this).data('deg') + 'deg)'});
                });
            }
            sctv_run_countdown();
        });
    }
});

function sctv_run_countdown() {
    clearInterval(vi_sctr_x);
    let distance, date, hours, minutes, seconds, i;
    let dates_deg, hours_deg, minutes_deg, seconds_deg;
    // Update the countdown every 1 second
    let wooCountdown = jQuery('.woo-sctr-shortcode-wrap-wrap'),
        time_end_parent = jQuery('.woo-sctr-shortcode-wrap-wrap .woo-sctr-shortcode-data-end_time');
    let current_time = Date.now();
    distance = time_end_parent.map(function () {
        let time_expire, countdown_time_start = jQuery(this).data('countdown_time_start') || 0,
            countdown_time_end = jQuery(this).data('countdown_time_end') || 0;
        if (countdown_time_start == 0 || countdown_time_end == 0) {
            return 0;
        }
        countdown_time_start = new Date(countdown_time_start.replace(' ', 'T') + 'Z');
        countdown_time_start = countdown_time_start.valueOf();
        countdown_time_end = new Date(countdown_time_end.replace(' ', 'T') + 'Z');
        countdown_time_end = countdown_time_end.valueOf();
        time_expire = countdown_time_end > current_time ? countdown_time_end - current_time : 0;
        if (time_expire) {
            time_expire = Math.round(time_expire / 1000);
        }
        if (countdown_time_start < (current_time - 1000)) {
            let container = jQuery(this).parent(),
                date_container = container.find('.woo-sctr-shortcode-countdown-date'),
                hour_container = container.find('.woo-sctr-shortcode-countdown-hour'),
                minute_container = container.find('.woo-sctr-shortcode-countdown-minute'),
                second_container = container.find('.woo-sctr-shortcode-countdown-second');
            date = Math.floor(time_expire / 86400);
            hours = Math.floor((time_expire % (86400)) / (3600));
            minutes = Math.floor((time_expire % (3600)) / (60));
            seconds = Math.floor((time_expire % (60)));
            seconds_deg = seconds * 6;
            if (seconds_deg < 180) {
                second_container.find('.woo-sctr-progress-circle').removeClass('woo-sctr-over50');
                second_container.find('.woo-sctr-first50-bar').hide();
            } else {
                second_container.find('.woo-sctr-progress-circle').addClass('woo-sctr-over50');
                second_container.find('.woo-sctr-first50-bar').show();
            }
            second_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + seconds_deg + 'deg)'});
            /**/
            second_container.find('.woo-sctr-shortcode-countdown-second-value-1').html((seconds > 0) ? ("0" + (seconds - 1)).slice(-2) : '59');
            second_container.find('.woo-sctr-shortcode-countdown-second-value-2').html(("0" + seconds).slice(-2));
            minutes_deg = (minutes > 0 ? (minutes - 1) : 59) * 6;
            if (minutes_deg < 180) {
                minute_container.find('.woo-sctr-progress-circle').removeClass('woo-sctr-over50');
                minute_container.find('.woo-sctr-first50-bar').hide();
            } else {
                minute_container.find('.woo-sctr-progress-circle').addClass('woo-sctr-over50');
                minute_container.find('.woo-sctr-first50-bar').show();
            }
            minute_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + minutes_deg + 'deg)'});
            minute_container.find('.woo-sctr-shortcode-countdown-minute-value-1').html((minutes > 0) ? ("0" + (minutes - 1)).slice(-2) : '59');
            minute_container.find('.woo-sctr-shortcode-countdown-minute-value-2').html(("0" + minutes).slice(-2));

            hours_deg = (hours > 0 ? (hours - 1) : 23) * 15;
            if (hours_deg < 180) {
                hour_container.find('.woo-sctr-progress-circle').removeClass('woo-sctr-over50');
                hour_container.find('.woo-sctr-first50-bar').hide();
            } else {
                hour_container.find('.woo-sctr-progress-circle').addClass('woo-sctr-over50');
                hour_container.find('.woo-sctr-first50-bar').show();
            }
            hour_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + hours_deg + 'deg)'});
            hour_container.find('.woo-sctr-shortcode-countdown-hour-value-1').html((hours > 0) ? ("0" + (hours - 1)).slice(-2) : '23');
            hour_container.find('.woo-sctr-shortcode-countdown-hour-value-2').html(("0" + hours).slice(-2));
            dates_deg = date > 0 ? (date - 1) : 0;
            if (dates_deg < 180) {
                date_container.find('.woo-sctr-progress-circle').removeClass('woo-sctr-over50');
                date_container.find('.woo-sctr-first50-bar').hide();
            } else {
                date_container.find('.woo-sctr-progress-circle').addClass('woo-sctr-over50');
                date_container.find('.woo-sctr-first50-bar').show();
            }
            date_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + dates_deg + 'deg)'});
            date_container.find('.woo-sctr-shortcode-countdown-date-value-1').html((date > 0) ? ("0" + (date - 1)).slice(-2) : '00');
            date_container.find('.woo-sctr-shortcode-countdown-date-value-2').html(("0" + date).slice(-2));
            if (date < 100) {
                date = ("0" + date).slice(-2);
                if (date == 0) {
                    container.find('.woo-sctr-shortcode-countdown-date').hide();
                    container.find('.woo-sctr-shortcode-wrap-wrap').find('.woo-sctr-shortcode-countdown-time-separator').eq(0).hide();
                }
            }
            date_container.find('.woo-sctr-shortcode-countdown-date-value').html(date);
            hour_container.find('.woo-sctr-shortcode-countdown-hour-value').html(("0" + hours).slice(-2));
            minute_container.find('.woo-sctr-shortcode-countdown-minute-value').html(("0" + minutes).slice(-2));
            second_container.find('.woo-sctr-shortcode-countdown-second-value').html(("0" + seconds).slice(-2));
        }
        return time_expire;
    });
    vi_sctr_x = setInterval(function () {
        for (i = 0; i < wooCountdown.length; i++) {
            let container = wooCountdown.eq(i),
                date_container = container.find('.woo-sctr-shortcode-countdown-date'),
                hour_container = container.find('.woo-sctr-shortcode-countdown-hour'),
                minute_container = container.find('.woo-sctr-shortcode-countdown-minute'),
                second_container = container.find('.woo-sctr-shortcode-countdown-second');
            date = Math.floor(distance[i] / 86400);
            hours = Math.floor((distance[i] % (86400)) / (3600));
            minutes = Math.floor((distance[i] % (3600)) / (60));
            seconds = Math.floor((distance[i] % (60)));
            seconds_deg = seconds * 6;
            if (seconds_deg < 180) {
                second_container.find('.woo-sctr-progress-circle').removeClass('woo-sctr-over50');
                second_container.find('.woo-sctr-first50-bar').hide();
            } else {
                second_container.find('.woo-sctr-progress-circle').addClass('woo-sctr-over50');

                second_container.find('.woo-sctr-first50-bar').show();
            }
            second_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + seconds_deg + 'deg)'});
            /**/
            second_container.find('.woo-sctr-shortcode-countdown-second-value-container-2').addClass('transition');
            setTimeout(function () {
                second_container.find('.woo-sctr-shortcode-countdown-second-value-container-2').removeClass('transition');
                second_container.find('.woo-sctr-shortcode-countdown-second-value-1').html((seconds > 0) ? ("0" + (seconds - 1)).slice(-2) : '59');
                second_container.find('.woo-sctr-shortcode-countdown-second-value-2').html(("0" + seconds).slice(-2));
            }, 500);
            if (seconds == 0 && (minutes > 0 || hours > 0 || date > 0)) {
                minutes_deg = (minutes > 0 ? (minutes - 1) : 59) * 6;
                if (minutes_deg < 180) {
                    minute_container.find('.woo-sctr-progress-circle').removeClass('woo-sctr-over50');
                    minute_container.find('.woo-sctr-first50-bar').hide();
                } else {
                    minute_container.find('.woo-sctr-progress-circle').addClass('woo-sctr-over50');
                    minute_container.find('.woo-sctr-first50-bar').show();
                }
                setTimeout(function () {
                    minute_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + minutes_deg + 'deg)'});
                    minute_container.find('.woo-sctr-shortcode-countdown-minute-value-container-2').addClass('transition');
                    setTimeout(function () {
                        minute_container.find('.woo-sctr-shortcode-countdown-minute-value-container-2').removeClass('transition');
                        minute_container.find('.woo-sctr-shortcode-countdown-minute-value-1').html((minutes > 0) ? ("0" + (minutes - 1)).slice(-2) : '59');
                        minute_container.find('.woo-sctr-shortcode-countdown-minute-value-2').html(("0" + minutes).slice(-2));
                    }, 500);
                }, 1000);

                if (minutes == 0 && (hours > 0 || date > 0)) {
                    hours_deg = (hours > 0 ? (hours - 1) : 23) * 15;
                    if (hours_deg < 180) {
                        hour_container.find('.woo-sctr-progress-circle').removeClass('woo-sctr-over50');
                        hour_container.find('.woo-sctr-first50-bar').hide();
                    } else {
                        hour_container.find('.woo-sctr-progress-circle').addClass('woo-sctr-over50');
                        hour_container.find('.woo-sctr-first50-bar').show();
                    }
                    setTimeout(function () {
                        hour_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + hours_deg + 'deg)'});
                        hour_container.find('.woo-sctr-shortcode-countdown-hour-value-container-2').addClass('transition');
                        setTimeout(function () {
                            hour_container.find('.woo-sctr-shortcode-countdown-hour-value-container-2').removeClass('transition');
                            hour_container.find('.woo-sctr-shortcode-countdown-hour-value-1').html((hours > 0) ? ("0" + (hours - 1)).slice(-2) : '23');
                            hour_container.find('.woo-sctr-shortcode-countdown-hour-value-2').html(("0" + hours).slice(-2));

                        }, 500);
                    }, 1000);

                    if (hours == 0 && date > 0) {
                        dates_deg = date > 0 ? (date - 1) : 0;
                        if (dates_deg < 180) {
                            date_container.find('.woo-sctr-progress-circle').removeClass('woo-sctr-over50');
                            date_container.find('.woo-sctr-first50-bar').hide();
                        } else {
                            date_container.find('.woo-sctr-progress-circle').addClass('woo-sctr-over50');
                            date_container.find('.woo-sctr-first50-bar').show();
                        }
                        setTimeout(function () {
                            date_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + dates_deg + 'deg)'});
                            date_container.find('.woo-sctr-shortcode-countdown-date-value-container-2').addClass('transition');
                            setTimeout(function () {
                                date_container.find('.woo-sctr-shortcode-countdown-date-value-container-2').removeClass('transition');
                                date_container.find('.woo-sctr-shortcode-countdown-date-value-1').html((date > 0) ? ("0" + (date - 1)).slice(-2) : '00');
                                date_container.find('.woo-sctr-shortcode-countdown-date-value-2').html(("0" + date).slice(-2));

                            }, 500);
                        }, 1000);

                    }
                }
            }
            if (date < 100) {
                date = ("0" + date).slice(-2);
                // if (date == 0) {
                //     container.find('.woo-sctr-shortcode-countdown-date').hide();
                //     container.find('.woo-sctr-shortcode-countdown-time-separator').eq(0).hide();
                // }
            }
            date_container.find('.woo-sctr-shortcode-countdown-date-value').html(date);
            hour_container.find('.woo-sctr-shortcode-countdown-hour-value').html(("0" + hours).slice(-2));
            minute_container.find('.woo-sctr-shortcode-countdown-minute-value').html(("0" + minutes).slice(-2));
            second_container.find('.woo-sctr-shortcode-countdown-second-value').html(("0" + seconds).slice(-2));
            distance[i]--;
            if (distance[i] < 0) {
                clearInterval(vi_sctr_x);
                // window.location.href = window.location.href;
                window.location.reload();
            }
        }
    }, 1000);
}
