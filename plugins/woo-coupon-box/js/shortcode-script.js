'use strict';
jQuery(document).ready(function () {
    shortcodeInitButton();
});

function isValidEmailAddress(emailAddress) {
    var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
    return pattern.test(emailAddress);
}

function shortcodeInitButton() {
    jQuery('.wcbwidget-button').unbind('click').on('click', function () {
        var shortcodeContainer = jQuery(this).parent().parent().parent().parent().parent().parent();

        var buttonSC = jQuery(this),
            email = shortcodeContainer.find('.wcbwidget-email').val();

        let showCouponcode = buttonSC.data('show_coupon');

        shortcodeContainer.find('.wcbwidget-email').removeClass('wcbwidget-invalid-email');
        shortcodeContainer.find('.wcbwidget-warning-message').html('');
        if (isValidEmailAddress(email)) {
            if (wcb_widget_params.wcb_gdpr_checkbox && shortcodeContainer.find('.wcbwidget-gdpr-checkbox').prop('checked') !== true) {
                shortcodeContainer.find('.wcbwidget-warning-message').html('*Please agree with the term and condition.');
                shortcodeContainer.find('.wcbwidget-gdpr-checkbox').focus();
                return false;
            }
            shortcodeContainer.find('.wcbwidget-email').removeClass('wcbwidget-invalid-email');
            buttonSC.addClass('wcbwidget-adding');
            buttonSC.unbind();

            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: wcb_widget_params.ajaxurl,
                data: {
                    action: 'wcb_widget_subscribe',
                    email: email,
                    show_coupon: showCouponcode
                },
                success: function (response) {
                    buttonSC.removeClass('wcbwidget-adding');
                    if (response.status === 'subscribed') {
                        shortcodeContainer.find('.wcbwidget-newsletter').html(response.message);
                        if (showCouponcode) {
                            shortcodeContainer.find('.wcbwidget-newsletter').append(response.code);
                        }
                        var currentTime = parseInt(wcb_widget_params.wcb_current_time),
                            wcb_expire_subscribed = currentTime + parseInt(wcb_widget_params.wcb_expire_subscribed);
                        setCookie('woo_coupon_box', currentTime, wcb_expire_subscribed);
                    } else {
                        shortcodeContainer.find('.wcbwidget-warning-message').html(response.warning);
                        shortcodeContainer.find('.wcbwidget-email').addClass('wcbwidget-invalid-email').focus();
                        shortcodeInitButton();
                    }
                },
                error: function (data) {
                    buttonSC.removeClass('wcbwidget-adding');
                    shortcodeInitButton();
                    setCookie('woo_coupon_box', '', -1);
                }
            });
        } else {
            if (!email) {
                shortcodeContainer.find('.wcbwidget-warning-message').html('*Please enter your email and subscribe.');

            } else {
                shortcodeContainer.find('.wcbwidget-warning-message').html('*Invalid email! Please enter a valid email and subscribe.');

            }
            shortcodeContainer.find('.wcbwidget-email').addClass('wcbwidget-invalid-email').focus();
            buttonSC.removeClass('wcbwidget-adding');
        }
    })
}

function setCookie(cname, cvalue, expire) {
    let d = new Date();
    d.setTime(d.getTime() + (expire * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
/**
 * Set Cookie
 * @param cname
 * @returns {*}
 */
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return '';
}
