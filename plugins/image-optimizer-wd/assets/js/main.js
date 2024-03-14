
jQuery(document).ready(function(){
    jQuery(".iowd-analyze-input-button").on("click", function () {
        if ( !jQuery(this).hasClass("iowd-disable-button") ) {
            iowd_get_google_score('');
        }
    });
    /* If there is no score of home page run google score and get homepage score */
    if ( iowd.home_speed_status === '0' ) {
        iowd_get_google_score(iowd.home_url);
    }
    else {
        /* Draw score circle if it is Image Optimization page */
        if( jQuery(".iowd-score-circle.iowd_circle_with_bg").length > 0 ) {
            if (typeof iowd.home_speed_status.desktop_score != "undefined") {
                jQuery(".iowd-score-circle").each(function(){
                    iowd_draw_score_circle(jQuery(this));
                });
            }
        }
    }

    jQuery(document).on('change', '.iowd-analyze-input', function () {
        analize_input_change();
    });
    jQuery('.iowd-install-optimize').click(function() {
        iowd_install_activate_plugin(jQuery(this));
    });
});

/**
 * Draw circle on given score.
 * @param that
 */
function iowd_draw_score_circle(that) {
    var score = parseInt(jQuery(that).data('score'));
    var size = parseInt(jQuery(that).data('size'));
    var thickness = parseInt(jQuery(that).data('thickness'));
    var color = score <= 49 ? "rgb(253, 60, 49)" : (score >= 90 ? "rgb(12, 206, 107)" : "rgb(255, 164, 0)");
    var background_color = score <= 49 ? "#FD3C311A" : (score >= 90 ? "#22B3391A" : "#fd3c311a");
    if ( jQuery(that).hasClass('iowd_circle_with_bg') ) {
        jQuery(that).css('background-color',background_color);
    }
    jQuery(that).parent().find('.iowd-load-time').html(jQuery(that).data('loading-time'));
    var _this = that;
    jQuery(_this).circleProgress({
        value: score / 100,
        size: size,
        startAngle: -Math.PI / 4 * 2,
        lineCap: 'round',
        emptyFill: "rgba(255, 255, 255, 0)",
        thickness: thickness,
        fill: {
            color: color
        }
    }).on('circle-animation-progress', function (event, progress) {
        if (score != 0) {
            content = Math.round(score * progress);
            jQuery(that).find('.iowd-score-circle-animated').html(content).css({"color": color});
            jQuery(that).find('canvas').html(Math.round(score * progress));
        }
    });
}

function analize_input_change() {
    var iowd_analyze_input = jQuery(".iowd-analyze-input");
    iowd_analyze_input.removeClass("iowd-analyze-input-error");
    jQuery(".iowd-analyze-input-button").removeClass("iowd-disable-button");
    jQuery(".iowd-analyze-input-container .iowd-error-msg").remove();
    var domain = iowd.home_url.replace(/^https?:\/\/|www./g, '');
    var url = iowd_analyze_input.val();
    var page_public = iowd_analyze_input.data('page-public');

    var error = false;
    var error_msg = '';
    if( url == '' ) {
        error = true;
        error_msg = iowd.enter_page_url;
    }
    else if ( !isUrlValid(url) ) {
        error = true;
        error_msg = iowd.wrong_url;
    }
    else if ( !url.includes(domain) ) {
        error = true;
        error_msg = iowd.wrong_domain_url;
    }
    else if ( page_public === 0 ) {
        error = true;
        error_msg = iowd.page_is_not_public;
    }


    if ( error === true ) {
        jQuery(".iowd-analyze-input-button").addClass("iowd-disable-button");
        jQuery(".iowd-analyze-input").addClass("iowd-analyze-input-error");
        jQuery(".iowd-analyze-input").after('<p class="iowd-error-msg">' + error_msg + '</p>');
    }
}

/**
 * Check if value is URL
 *
 * @param url string
 *
 * @return bool
 */
function isUrlValid(url) {
    if (typeof url == 'undefined' || url == '') {
        return false;
    }
    if ( url.indexOf("http") !== 0 && url.indexOf("www.") !== 0) {
        return false;
    }
    regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
    if (regexp.test(url)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Run ajax action and count google score.
 *
 * @param that object
 * @param url string
 */
function iowd_get_google_score( url ) {
    jQuery(".iowd-error-msg").remove();
    if (url === '') {
        if (jQuery('.iowd-analyze-input-button').hasClass("iowd-disable-button")) {
            return false;
        }
        jQuery('.iowd-analyze-input-button').addClass('iowd-disable-button');
        jQuery('.iowd-analyze-input-button').addClass("iowd-loading-button");
        jQuery('.iowd-analyze-input-button').append('<span class="iowd-loader"></span>');
        url = jQuery(".iowd-analyze-input").val();
    }

    if (!isUrlValid(url)) {
        jQuery(".iowd-analyze-input").after('<p class="iowd-error-msg">' + iowd.wrong_url + '</p>');
        jQuery(".iowd-analyze-input-button").removeClass('iowd-loading-button');
        jQuery(".iowd-analyze-input-button span").remove(".iowd-loader");
        return;
    }
    if ( jQuery(".speed_circle_loader").length === 0 ) {
        jQuery(".iowd-score-circle").after("<div class='speed_circle_loader'></div>");
    }
    jQuery(".iowd-score-circle").addClass("iowd-hidden");
    jQuery(".iowd_score_info").removeClass("iowd-hidden");
    jQuery(".iowd_load_time").text("-");
    jQuery(".iowd-reanalyze-button").addClass("iowd-hidden");
    jQuery.ajax({
        type: 'POST',
        url: iowd.ajax_url,
        dataType: 'json',
        data: {
            action: "iowd_get_google_page_speed",
            iowd_url: url,
            speed_ajax_nonce: iowd.speed_ajax_nonce
        }
    }).success(function(res){
        jQuery(".speed_circle_loader").remove();
        jQuery(".iowd-last-analyzed-page").text(url);
        jQuery(".iowd-last-analyzed-date").text(res['last_analyzed_time']);
        jQuery(".iowd-sc-mobile .iowd_load_time").text(res['mobile_loading_time']);
        jQuery(".iowd-sc-desktop .iowd_load_time").text(res['desktop_loading_time']);
        jQuery(".iowd-sc-mobile .iowd-score-circle").data("score", res['mobile_score']);
        jQuery(".iowd-sc-desktop .iowd-score-circle").data("score", res['desktop_score']);
        jQuery(".iowd-score-circle").removeClass("iowd-hidden");
        jQuery(".iowd-score-circle").each(function(){
            iowd_draw_score_circle(jQuery(this));
        });
    }).error(function(){
        google_speed_error_result('');
    }).complete(function(){
        jQuery(".iowd-analyze-input-button.iowd-loading-button span").remove(".iowd-loader");
        jQuery('.iowd-analyze-input-button.iowd-disable-analyze').removeClass('iowd-disable-analyze');
        jQuery(".iowd-analyze-input-button.iowd-loading-button").removeClass('iowd-loading-button');
    });
}

/* Case when counting of score returns error. */
function google_speed_error_result( msg ) {
    if ( msg !== '' ) {
        iowd.something_wrong = msg;
    }
    jQuery(".iowd-analyze-input").after('<p class="iowd-error-msg">' + iowd.something_wrong + '</p>');
    jQuery(".iowd-analyze-input-button").removeClass('iowd-loading-button');
    jQuery(".iowd-analyze-input-button span").remove(".iowd-loader");
    jQuery(".speed_circle_loader").remove();
    jQuery(".iowd-score-circle").removeClass("iowd-hidden");
}

/**
 * Install/activate the plugin.
 *
 * @param that object
 */
function iowd_install_activate_plugin( that ) {
    if ( jQuery(that).hasClass("iowd-disable-button") ) {
        return;
    }
    jQuery(".iowd-button").addClass('iowd-disable-button');
    jQuery(that).addClass("iowd-loading-button");
    jQuery(that).append('<span class="iowd-loader"></span>');

    jQuery.ajax( {
        url: ajaxurl,
        type: "POST",
        data: {
            action: "iowd_install_booster",
            task: "iowd_install_booster",
            optimize: "optimize",
            speed_ajax_nonce: iowd.speed_ajax_nonce
        },
        success: function() {
            //TODO
            window.location.href = iowd.booster_admin_url;
            return;
        },
        error: function() {
            jQuery(".iowd-button").removeClass('iowd-disable-button');
            jQuery(that).removeClass("iowd-loading-button");
            jQuery(that).find(".iowd-loader").remove();
        },
    });
}