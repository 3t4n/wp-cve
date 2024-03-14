/* review remind me later */
jQuery(document).on('click', '#remind-me-later', function (e) {
    e.preventDefault();
    var data = {
        action: 'iat_remind_me_later'
    }
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (res) {
            var res = JSON.parse(res);
            if (res.flg == '1') {
                jQuery('.review-notice').hide();
            } else {
                alert(res.message);
            }
        }
    });
});

/* review do not show again */
jQuery(document).on('click', '#do-not-show-again', function (e) {
    e.preventDefault();
    var data = {
        action: 'iat_do_not_show_again'
    }
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (res) {
            var res = JSON.parse(res);
            if (res.flg == '1') {
                jQuery('.review-notice').hide();
            } else {
                alert(res.message);
            }
        }
    });

});