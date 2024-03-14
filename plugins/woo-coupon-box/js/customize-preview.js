'use strict';
(function ($) {
    wp.customize('woo_coupon_box_params[wcb_layout]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-coupon-box').removeClass('wcb-md-show').removeClass('wcb-current-layout');
            jQuery('.wcb-coupon-box-' + newval).addClass('wcb-md-show').addClass('wcb-current-layout');
        });
    });
    /*Button close*/
    wp.customize('woo_coupon_box_params[wcb_button_close]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-md-close').attr('class', 'wcb-md-close ' + newval);
            jQuery('.woo_coupon_box_params-wcb_button_close label').removeClass('wcb-radio-icons-active');
            jQuery('.woo_coupon_box_params-wcb_button_close .' + newval).parent().addClass('wcb-radio-icons-active');
        });
    });
    wp.customize('woo_coupon_box_params[wcb_button_close_color]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-md-close').css({'color': newval});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_button_close_bg_color]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-md-close').css({'background-color': newval});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_button_close_size]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-md-close').css({'font-size': newval + 'px'});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_button_close_width]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-md-close').css({'width': newval + 'px', 'line-height': newval + 'px'});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_button_close_border_radius]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-md-close').css({'border-radius': newval + 'px'});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_button_close_position_x]', function (value) {
        value.bind(function (newval) {
            newval = -newval;
            $('.wcb-coupon-box .wcb-md-close').css({'right': newval + 'px'});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_button_close_position_y]', function (value) {
        value.bind(function (newval) {
            newval = -newval;
            $('.wcb-coupon-box .wcb-md-close').css({'top': newval + 'px'});
        });
    });

    wp.customize('woo_coupon_box_params[wcb_view_mode]', function (value) {
        value.bind(function (newval) {
            if (newval === '1') {
                $('.wcb-view-before-subscribe').show();
                $('.wcb-view-after-subscribe').hide();
            } else {
                $('.wcb-view-before-subscribe').hide();
                $('.wcb-view-after-subscribe').show();
            }
        });
    });

    wp.customize('woo_coupon_box_params[wcb_title]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-header.wcb-view-before-subscribe .wcb-coupon-box-title').html(newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_title_after_subscribing]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-header.wcb-view-after-subscribe .wcb-coupon-box-title').html(newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_bg_header]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-header').css('backgroundColor', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_color_header]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-header').css('color', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_title_size]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-header').css({
                'font-size': newval + 'px',
                'line-height': newval + 'px'
            });
        });
    });

    wp.customize('woo_coupon_box_params[wcb_title_space]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-header').css({
                'padding-top': newval + 'px',
                'padding-bottom': newval + 'px'
            });
        });
    });

    wp.customize('woo_coupon_box_params[wcb_message]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-coupon-message-before-subscribe').html(newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_border_radius]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box-1 .wcb-md-content').css({'border-radius': newval + 'px'});
            $('.wcb-coupon-box-2 .wcb-content-wrap-child').css({'border-radius': newval + 'px'});
            $('.wcb-coupon-box-3 .wcb-md-content').css({'border-radius': newval + 'px'});
            $('.wcb-coupon-box-4 .wcb-md-content').css({'border-radius': newval + 'px'});
            $('.wcb-coupon-box-5 .wcb-content-wrap-child').css({'border-radius': newval + 'px'});
        });
    });


    wp.customize('woo_coupon_box_params[wcb_message_after_subscribe]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-coupon-message-after-subscribe').html(newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_message_size]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body .wcb-coupon-message').css('font-size', newval + 'px');
        });
    });
    wp.customize('woo_coupon_box_params[wcb_color_message]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body .wcb-coupon-message').css('color', newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_message_align]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body .wcb-coupon-message').css('text-align', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_body_bg]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body').css('backgroundColor', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_body_bg_img]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body').css('background-image', 'url(' + newval + ')');
        });
    });


    wp.customize('woo_coupon_box_params[wcb_body_bg_img_repeat]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body').css('background-repeat', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_body_bg_img_size]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body').css('background-size', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_body_bg_img_position]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body').css('background-position', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_body_text_color]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-modal-body').css('color', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_button_text]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-newsletter span.wcb-button').html(newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_button_bg_color]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-newsletter span.wcb-button').css('backgroundColor', newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_button_border_radius]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-newsletter span.wcb-button').css('border-radius', newval + 'px');
        });
    });
    wp.customize('woo_coupon_box_params[wcb_email_input_border_radius]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-newsletter input.wcb-email').css('border-radius', newval + 'px');
        });
    });
    wp.customize('woo_coupon_box_params[wcb_email_button_space]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-newsletter input.wcb-email').css('margin-right', newval + 'px');
        });
    });

    wp.customize('woo_coupon_box_params[wcb_button_text_color]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-newsletter span.wcb-button').css('color', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_footer_text]', function (value) {
        value.bind(function (newval) {
            $('.wcb-footer-text').html(newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_footer_text_after_subscribe]', function (value) {
        value.bind(function (newval) {
            $('.wcb-footer-text-after-subscribe').html(newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_show_coupon]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.wcb-coupon-content').show();
            } else {
                $('.wcb-coupon-content').hide();
            }
        });
    });

    wp.customize('woo_coupon_box_params[wcb_gdpr_checkbox]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.wcb-gdpr-field').show();
            } else {
                $('.wcb-gdpr-field').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_gdpr_checkbox_checked]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.wcb-gdpr-checkbox').prop('checked', true);
            } else {
                $('.wcb-gdpr-checkbox').prop('checked', false);
            }
        });
    });

    wp.customize('woo_coupon_box_params[wcb_gdpr_message]', function (value) {
        value.bind(function (newval) {
            $('.wcb-gdpr-message').html(newval);
        });
    });

    wp.customize('woo_coupon_box_params[alpha_color_overlay]', function (value) {
        value.bind(function (newval) {
            $('body .wcb-md-overlay').css('background', newval);
        });
    });


    wp.customize('woo_coupon_box_params[wcb_popup_type]', function (value) {
        value.bind(function (newval) {
            var xr = $('.wcb-coupon-box').attr('class').split(' ').slice(-2);
            $('.wcb-coupon-box').removeClass(xr[0] + ' ' + xr[1]).addClass(newval);
            setTimeout(function () {
                $('.wcb-coupon-box').addClass('wcb-md-show');
            }, 1000);

        });
    });

    wp.customize('woo_coupon_box_params[wcb_color_follow_us]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-text-follow-us').css('color', newval);
        });
    });

    wp.customize('woo_coupon_box_params[wcb_follow_us]', function (value) {
        value.bind(function (newval) {
            $('.wcb-coupon-box .wcb-text-follow-us').html(newval);
        });
    });


    /*Social*/
    wp.customize('woo_coupon_box_params[wcb_social_icons_size]', function (value) {
        value.bind(function (newval) {
            $('.wcb-social-icon').css({'font-size': newval + 'px', 'line-height': newval + 'px'});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_target]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-social-button').attr('target', newval);
        });
    });
    //facebook
    wp.customize('woo_coupon_box_params[wcb_social_icons_facebook_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-facebook-follow').attr('href', '//facebook.com/' + newval);
            if (newval) {
                $('.wcb-facebook-follow').show();
            } else {
                $('.wcb-facebook-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_facebook_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-facebook-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_facebook_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-facebook-follow span').css({'color': newval});
        });
    });

//twitter
    wp.customize('woo_coupon_box_params[wcb_social_icons_twitter_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-twitter-follow').attr('href', '//twitter.com/' + newval);
            if (newval) {
                $('.wcb-twitter-follow').show();
            } else {
                $('.wcb-twitter-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_twitter_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-twitter-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_twitter_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-twitter-follow span').css({'color': newval});
        });
    });
//pinterest
    wp.customize('woo_coupon_box_params[wcb_social_icons_pinterest_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-pinterest-follow').attr('href', '//pinterest.com/' + newval);
            if (newval) {
                $('.wcb-pinterest-follow').show();
            } else {
                $('.wcb-pinterest-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_pinterest_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-pinterest-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_pinterest_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-pinterest-follow span').css({'color': newval});
        });
    });
//instagram
    wp.customize('woo_coupon_box_params[wcb_social_icons_instagram_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-instagram-follow').attr('href', '//instagram.com/' + newval);
            if (newval) {
                $('.wcb-instagram-follow').show();
            } else {
                $('.wcb-instagram-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_instagram_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-instagram-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_instagram_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-instagram-follow span').css({'color': newval});
        });
    });
//dribbble
    wp.customize('woo_coupon_box_params[wcb_social_icons_dribbble_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-dribbble-follow').attr('href', '//dribbble.com/' + newval);
            if (newval) {
                $('.wcb-dribbble-follow').show();
            } else {
                $('.wcb-dribbble-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_dribbble_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-dribbble-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_dribbble_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-dribbble-follow span').css({'color': newval});
        });
    });
//tumblr
    wp.customize('woo_coupon_box_params[wcb_social_icons_tumblr_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-tumblr-follow').attr('href', '//tumblr.com/' + newval);
            if (newval) {
                $('.wcb-tumblr-follow').show();
            } else {
                $('.wcb-tumblr-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_tumblr_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-tumblr-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_tumblr_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-tumblr-follow span').css({'color': newval});
        });
    });
//google
    wp.customize('woo_coupon_box_params[wcb_social_icons_google_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-google-follow').attr('href', '//plus.google.com/' + newval);
            if (newval) {
                $('.wcb-google-follow').show();
            } else {
                $('.wcb-google-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_google_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-google-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_google_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-google-follow span').css({'color': newval});
        });
    });

    //vkontakte
    wp.customize('woo_coupon_box_params[wcb_social_icons_vkontakte_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-vkontakte-follow').attr('href', '//vk.com/' + newval);
            if (newval) {
                $('.wcb-vkontakte-follow').show();
            } else {
                $('.wcb-vkontakte-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_vkontakte_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-vkontakte-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_vkontakte_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-vkontakte-follow span').css({'color': newval});
        });
    });
//linkedin
    wp.customize('woo_coupon_box_params[wcb_social_icons_linkedin_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-linkedin-follow').attr('href', '//linkedin.com/in/' + newval);
            if (newval) {
                $('.wcb-linkedin-follow').show();
            } else {
                $('.wcb-linkedin-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_linkedin_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-linkedin-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_linkedin_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-linkedin-follow span').css({'color': newval});
        });
    });
//youtube
    wp.customize('woo_coupon_box_params[wcb_social_icons_youtube_url]', function (value) {
        value.bind(function (newval) {
            $('.wcb-youtube-follow').attr('href', newval);
            if (newval) {
                $('.wcb-youtube-follow').show();
            } else {
                $('.wcb-youtube-follow').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_youtube_select]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-youtube-follow span').attr('class', 'wcb-social-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_social_icons_youtube_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-youtube-follow span').css({'color': newval});
        });
    });

    wp.customize('woo_coupon_box_params[wcb_custom_css]', function (value) {
        value.bind(function (newval) {
            jQuery('#woo-coupon-box-custom-css').html(newval);
        });
    });
    /*popup icon*/
    wp.customize('woo_coupon_box_params[wcb_popup_icon]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-coupon-box-small-icon').attr('class', 'wcb-coupon-box-small-icon ' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_popup_icon_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                if (jQuery('.wcb-coupon-box-small-icon-wrap').hasClass('wcb-coupon-box-small-icon-position-bottom-left') || jQuery('.wcb-coupon-box-small-icon-wrap').hasClass('wcb-coupon-box-small-icon-position-top-left')) {
                    jQuery('.wcb-coupon-box-small-icon-wrap').removeClass('wcb-coupon-box-small-icon-hide-left');
                } else {
                    jQuery('.wcb-coupon-box-small-icon-wrap').removeClass('wcb-coupon-box-small-icon-hide-right');
                }
            } else {
                if (jQuery('.wcb-coupon-box-small-icon-wrap').hasClass('wcb-coupon-box-small-icon-position-bottom-left') || jQuery('.wcb-coupon-box-small-icon-wrap').hasClass('wcb-coupon-box-small-icon-position-top-left')) {
                    jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hide-left');
                } else {
                    jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hide-right');
                }
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_popup_icon_position]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-coupon-box-small-icon-wrap').removeClass('wcb-coupon-box-small-icon-position-top-right').removeClass('wcb-coupon-box-small-icon-position-bottom-right').removeClass('wcb-coupon-box-small-icon-position-top-left').removeClass('wcb-coupon-box-small-icon-position-bottom-left').addClass('wcb-coupon-box-small-icon-position-' + newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_popup_icon_mobile]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.wcb-coupon-box-small-icon-wrap').removeClass('wcb-coupon-box-small-icon-hidden-mobile');
            } else {
                jQuery('.wcb-coupon-box-small-icon-wrap').addClass('wcb-coupon-box-small-icon-hidden-mobile');
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_popup_icon_size]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-coupon-box-small-icon').css({'font-size': newval + 'px','line-height': newval + 'px'});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_popup_icon_border_radius]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-coupon-box-small-icon-wrap').css({'border-radius': newval + 'px'});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_popup_icon_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-coupon-box-small-icon').css('color', newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_popup_icon_bg_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-coupon-box-small-icon-wrap').css('background-color', newval);
        });
    });
    /* Google recaptcha */
    window.addEventListener('load', function () {
        if (woo_coupon_box_design_params.wcb_recaptcha == 1) {
            if (woo_coupon_box_design_params.wcb_recaptcha_version == 2) {
                reCaptchaV2Onload();
            }else {
                reCaptchaV3Onload();
                jQuery('.wcb-recaptcha-field').hide();
            }
        }else {
            jQuery('.wcb-recaptcha-field').hide();
        }
    });
    function reCaptchaV3Onload() {
        grecaptcha.ready(function() {
            grecaptcha.execute(woo_coupon_box_design_params.wcb_recaptcha_site_key, {action: 'homepage'}).then(function(token) {
            });
        });
    }
    function reCaptchaV2Onload() {
        if (jQuery('.wcb-recaptcha-field').length==0 ) {
            return true;
        }
        let container = jQuery('.wcb-recaptcha-field');
        if (container.find('.wcb-recaptcha').length==0 || container.find('.wcb-recaptcha iframe').length  ) {
            return true;
        }
        grecaptcha.render('wcb-recaptcha', {

            'sitekey' : woo_coupon_box_design_params.wcb_recaptcha_site_key,

            // 'callback' : validateRecaptcha,
            //
            // 'expired-callback' : expireRecaptcha,

            'theme' : woo_coupon_box_design_params.wcb_recaptcha_secret_theme,

            'isolated' : false
        });
        let old_width = jQuery('.wcb-coupon-box-3 .wcb-recaptcha > div').width();
        let parent_width = jQuery('.wcb-coupon-box-3 .wcb-recaptcha').width();
        jQuery('.wcb-coupon-box-3 .wcb-recaptcha > div').css({transform : 'scale('+parent_width/old_width+',1)'});
    }

    /* button 'no, thanks' */
    wp.customize('woo_coupon_box_params[wcb_no_thank_button_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.wcb-md-close-never-reminder-field').show();
            } else {
                jQuery('.wcb-md-close-never-reminder-field').hide();
            }
        });
    });
    wp.customize('woo_coupon_box_params[wcb_no_thank_button_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-md-close-never-reminder').html(newval);
        });
    });
    wp.customize('woo_coupon_box_params[wcb_no_thank_button_border_radius]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-md-close-never-reminder').css({'border-radius': newval+'px'});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_no_thank_button_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-md-close-never-reminder').css({'color': newval});
        });
    });
    wp.customize('woo_coupon_box_params[wcb_no_thank_button_bg_color]', function (value) {
        value.bind(function (newval) {
            jQuery('.wcb-md-close-never-reminder').css({'background': newval});
        });
    });
})(jQuery);