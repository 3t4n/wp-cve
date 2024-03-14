'use strict';
jQuery(document).ready(function () {
    popup_hide();
    delay_show_modal();
    init_button();
    jQuery('.wcb-email').on('keypress', function (e) {
        jQuery('#vi-md_wcb').find('.wcb-warning-message-wrap').css({'visibility': 'hidden', 'opacity': 0});
        if (jQuery(this).focus() && e.keyCode === 13) {
            jQuery('.wcb-button').click();
        }
    });
    jQuery('.wcb-gdpr-checkbox').on('click', function () {
        if (jQuery(this).prop('checked')) {
            jQuery(this).removeClass('wcb-warning-checkbox');
        } else {
            jQuery(this).addClass('wcb-warning-checkbox');
        }
    });
    callCouponBoxAgain();
    jQuery('.wcb-coupon-box-small-icon-close').on('click',function (e) {
        e.stopPropagation();
        if (wcb_params.wcb_popup_position === 'left') {
            jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hide-left');
        } else {
            jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hide-right');
        }
        var currentTime = parseInt(wcb_params.wcb_current_time),
            wcb_expire = parseInt(wcb_params.wcb_expire);
        setCookie('woo_coupon_box', 'closed:' + currentTime, wcb_expire);
    })
});

function callCouponBoxAgain() {
    jQuery('.wcb-coupon-box-small-icon-wrap').on('click', function () {
        if (wcb_params.wcb_popup_type && !jQuery('#vi-md_wcb').hasClass(wcb_params.wcb_popup_type)) {
            jQuery('#vi-md_wcb').addClass(wcb_params.wcb_popup_type);
        }
        jQuery(document).on('keyup', closeOnEsc);
        jQuery('#vi-md_wcb').addClass('wcb-md-show');
       wcb_disable_scroll()
        if (wcb_params.wcb_popup_position === 'left') {
            jQuery(this).addClass('wcb-coupon-box-small-icon-hide-left');
        } else {
            jQuery(this).addClass('wcb-coupon-box-small-icon-hide-right');
        }
    })
}

function popup_hide() {
    jQuery('.wcb-md-close').on('click', function () {
        jQuery('#vi-md_wcb').find('.wcb-warning-message').html('');
        jQuery('#vi-md_wcb').find('.wcb-warning-message-wrap').css({'visibility': 'hidden', 'opacity': 0});
        if (!jQuery('#vi-md_wcb').hasClass('wcb-subscribed')) {

            /*Popup icons to call coupon box again*/
            if (wcb_params.wcb_popup_position == 'left') {
                jQuery('.wcb-coupon-box-small-icon-wrap').removeClass('wcb-coupon-box-small-icon-hide-left');
            } else {
                jQuery('.wcb-coupon-box-small-icon-wrap').removeClass('wcb-coupon-box-small-icon-hide-right');
            }

        }
        jQuery('#vi-md_wcb').removeClass('wcb-md-show');
        wcb_enable_scroll();
        jQuery(document).unbind('keyup', closeOnEsc);

    });
    jQuery('.wcb-md-overlay').on('click', function () {
        jQuery('.wcb-md-close').click();
    });

}

function closeOnEsc(e) {
    if (jQuery('#vi-md_wcb').hasClass('wcb-md-show') && e.keyCode === 27) {
        jQuery('.wcb-md-close').click();
    }
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
    return pattern.test(emailAddress);
}
function validateRecaptcha(response) {
    if (response){
        jQuery('.wcb-recaptcha-field #wcb-g-validate-response').val(response);
    }
    // if (!response){
    //     return false;
    // }
    // jQuery.ajax({
    //    url: wcb_params.ajaxurl,
    //     type: 'POST',
    //     dataType: 'json',
    //    data: {
    //        action:'wcb_email',
    //        g_validate_response:response,
    //    },
    //     beforeSend: function () {
    //         // console.log(response);
    //     },
    //     success:function (res) {
    //         if (res.status =='success' ) {
    //             jQuery('.wcb-recaptcha-field #wcb-g-validate-response').val(1);
    //         }else {
    //             console.log(res);
    //             if (wcb_params.wcb_recaptcha_version==2) {
    //                 grecaptcha.reset(reCaptchaV2Onload());
    //             }else {
    //                 jQuery('.wcb-recaptcha-field').show();
    //                 jQuery('.wcb-recaptcha-field .wcb-recaptcha').addClass('wcb-invalid-email').html('<p style="">'+res.warning+'</p>');
    //             }
    //         }
    //     },
    //     error: function (err) {
    //         console.log(err);
    //     }
    // });
}
function expireRecaptcha() {
    jQuery('.wcb-recaptcha-field #wcb-g-validate-response').val(null);
}
function reCaptchaV3Onload() {
    grecaptcha.ready(function() {
        grecaptcha.execute(wcb_params.wcb_recaptcha_site_key, {action: 'homepage'}).then(function(token){
            validateRecaptcha(token);
        })
    });
}
function reCaptchaV2Onload() {
    if (jQuery.find('.wcb-recaptcha').length==0 || jQuery.find('.wcb-recaptcha iframe').length  ) {
        return true;
    }
    grecaptcha.render('wcb-recaptcha', {

        'sitekey' : wcb_params.wcb_recaptcha_site_key,

        'callback' : validateRecaptcha,

        'expired-callback' : expireRecaptcha,

        'theme' : wcb_params.wcb_recaptcha_secret_theme,

        'isolated' : false
    });
}
function init_button() {
    var container = jQuery('#vi-md_wcb');
    window.addEventListener('load', function () {
        if (wcb_params.wcb_recaptcha == 1) {
            if (wcb_params.wcb_recaptcha_version == 2) {
                reCaptchaV2Onload();
            }else {
                reCaptchaV3Onload();
                container.find('.wcb-recaptcha-field').hide();
            }
        }
    });
    container.find('.wcb-md-close-never-reminder').unbind('click').on('click',function () {
        if (wcb_params.wcb_never_reminder_enable == 1) {
            var currentTime = parseInt(wcb_params.wcb_current_time);
            setCookie('woo_coupon_box', 'closed:' + currentTime, 10*365*24*60*60);
            jQuery('.wcb-md-close').click();
            if (wcb_params.wcb_popup_position == 'left') {
                jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hide-left');
            } else {
                jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hide-right');
            }
        }else {
            jQuery('.wcb-md-close').click();
        }
    });
    container.find('.wcb-button').unbind('click').on('click', function () {
        var button = jQuery(this),
            email = container.find('.wcb-email').val(),
            g_validate_response = container.find('.wcb-recaptcha-field #wcb-g-validate-response').val();
        container.find('.wcb-email').removeClass('wcb-invalid-email');
        container.find('.wcb-recaptcha-field #wcb-recaptcha').removeClass('wcb-warning-checkbox');
        container.find('.wcb-warning-message-wrap').css({'visibility': 'hidden', 'opacity': 0});
        container.find('.wcb-gdpr-checkbox').removeClass('wcb-warning-checkbox');
        if (isValidEmailAddress(email)) {
            if (wcb_params.wcb_gdpr_checkbox && container.find('.wcb-gdpr-checkbox').prop('checked') !== true) {
                container.find('.wcb-gdpr-checkbox').addClass('wcb-warning-checkbox').focus();
                return false;
            }
            if (wcb_params.wcb_recaptcha==2 && wcb_params.wcb_recaptcha_site_key && !g_validate_response){
                container.find('.wcb-recaptcha-field #wcb-recaptcha').addClass('wcb-warning-checkbox').focus();
                return false;
            }
            container.find('.wcb-email').removeClass('wcb-invalid-email');
            button.addClass('wcb-adding');
            button.unbind();

            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: wcb_params.ajaxurl,
                data: {
                    action: 'wcb_email',
                    email: email,
                    g_validate_response:g_validate_response,
                },
                success: function (response) {
                    button.removeClass('wcb-adding');
                    if (response.status === 'subscribed') {
                        if (wcb_params.wcb_title_after_subscribing) {
                            container.find('.wcb-coupon-box-title').html(wcb_params.wcb_title_after_subscribing);
                        }else {
                            container.find('.wcb-coupon-box-title').html('').hide();
                        }

                        if (wcb_params.wcb_show_coupon && response.code) {
                            container.find('.wcb-coupon-content').html(response.code);
                            container.find('.wcb-coupon-treasure').focus(function () {
                                jQuery(this).select();
                            });
                        }
                        container.find('.wcb-coupon-box-newsletter').html(response.thankyou);
                        container.find('.wcb-coupon-message').html(response.message);

                        container.addClass('wcb-subscribed');
                        var currentTime = parseInt(response.wcb_current_time),
                            wcb_expire_subscribed = parseInt(wcb_params.wcb_expire_subscribed);
                        setCookie('woo_coupon_box', 'subscribed:' + currentTime, wcb_expire_subscribed);
                    } else {
                        container.find('.wcb-warning-message').html(response.warning);
                        container.find('.wcb-warning-message-wrap').css({'visibility': 'visible', 'opacity': 1});
                        if (response.g_validate_response){
                            container.find('.wcb-recaptcha-field #wcb-recaptcha').addClass('wcb-warning-checkbox').focus();
                        } else {
                            container.find('.wcb-email').addClass('wcb-invalid-email').focus();
                        }
                        init_button();
                    }
                },
                error: function (data) {
                    button.removeClass('wcb-adding');
                    init_button();
                    setCookie('woo_coupon_box', '', -1);
                }
            });
        } else {
            if (!email) {
                container.find('.wcb-warning-message').html(wcb_params.wcb_empty_email_warning);
                container.find('.wcb-warning-message-wrap').css({'visibility': 'visible', 'opacity': 1});

            } else {
                container.find('.wcb-warning-message').html(wcb_params.wcb_invalid_email_warning);
                container.find('.wcb-warning-message-wrap').css({'visibility': 'visible', 'opacity': 1});

            }
            container.find('.wcb-email').addClass('wcb-invalid-email').focus();
            button.removeClass('wcb-adding');
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

function delay_show_modal() {
    if (!getCookie('woo_coupon_box')&&!jQuery('#vi-md_wcb').hasClass('wcb-md-show')) {

        setTimeout(function () {
            couponBoxShow();
        }, wcb_params.wcb_popup_time * 1000);
    }

}

function couponBoxShow() {
    if (!jQuery('#vi-md_wcb').hasClass('wcb-md-show')) {
        if (wcb_params.wcb_popup_type && !jQuery('#vi-md_wcb').hasClass(wcb_params.wcb_popup_type)) {
            jQuery('#vi-md_wcb').addClass(wcb_params.wcb_popup_type);
        }
        jQuery(document).on('keyup', closeOnEsc);
        var currentTime = parseInt(wcb_params.wcb_current_time),
            wcb_expire =  parseInt(wcb_params.wcb_expire);
        // var currentTime=parseInt(wcb_params.wcb_current_time);
        jQuery('#vi-md_wcb').addClass('wcb-md-show');
       wcb_disable_scroll()
        setCookie('woo_coupon_box', 'shown:' + currentTime, wcb_expire);
        if (wcb_params.wcb_popup_position == 'left') {
            jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hide-left');
        } else {
            jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hide-right');
        }
    }

}
function wcb_enable_scroll() {
    let scrollTop = parseInt(jQuery('html').css('top'));
    jQuery('html').removeClass('wcb-html-scroll');
    jQuery('html,body').scrollTop(-scrollTop);
}

function wcb_disable_scroll() {
    if (jQuery(document).height() > jQuery(window).height()) {
        let scrollTop = (jQuery('html').scrollTop()) ? jQuery('html').scrollTop() : jQuery('body').scrollTop(); // Works for Chrome, Firefox, IE...
        jQuery('html').addClass('wcb-html-scroll').css('top', -scrollTop);
    }
}