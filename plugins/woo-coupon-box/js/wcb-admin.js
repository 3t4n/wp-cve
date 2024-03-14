'use strict';
jQuery(document).ready(function () {

    jQuery('.vi-ui.tabular.menu .item').vi_tab({
        history: true,
        historyType: 'hash'
    });

    jQuery('.vi-ui.checkbox').checkbox();
    jQuery('.vi-ui.dropdown').dropdown();

    handleDropdownSelect();

    function handleDropdownSelect() {
        /*select recaptcha version */
        jQuery('.wcb_recaptcha_version').dropdown({
            onChange: function (val) {
                if (val==2){
                    jQuery('.wcb-recaptcha-v2-wrap').show();
                    jQuery('.wcb-recaptcha-v3-wrap').hide();
                } else {
                    jQuery('.wcb-recaptcha-v2-wrap').hide();
                    jQuery('.wcb-recaptcha-v3-wrap').show();
                }
            }
        });
        /*select coupon*/
        switch (jQuery('#wcb_coupon_select').val()) {
            case 'unique':
                jQuery('.wcb-coupon-custom').hide();
                jQuery('.wcb-coupon-existing').hide();
                break;
            case 'existing':
                jQuery('.wcb-coupon-unique').hide();
                jQuery('.wcb-coupon-custom').hide();
                break;
            case 'custom':
                jQuery('.wcb-coupon-unique').hide();
                jQuery('.wcb-coupon-existing').hide();
                jQuery('.wcb-coupon-email-restriction').hide();
                break;
            default:
                jQuery('.wcb-coupon-unique').hide();
                jQuery('.wcb-coupon-existing').hide();
                jQuery('.wcb-coupon-email-restriction').hide();
                jQuery('.wcb-coupon-custom').hide();
        }
        jQuery('.wcb-coupon-select').dropdown({
            onChange: function (val) {
                switch (val) {
                    case 'unique':
                        jQuery('.wcb-coupon-unique').show();
                        jQuery('.wcb-coupon-custom').hide();
                        jQuery('.wcb-coupon-existing').hide();
                        jQuery('.wcb-coupon-email-restriction').show();
                        break;
                    case 'existing':
                        jQuery('.wcb-coupon-unique').hide();
                        jQuery('.wcb-coupon-custom').hide();
                        jQuery('.wcb-coupon-existing').show();
                        jQuery('.wcb-coupon-email-restriction').show();
                        break;
                    case 'custom':
                        jQuery('.wcb-coupon-unique').hide();
                        jQuery('.wcb-coupon-custom').show();
                        jQuery('.wcb-coupon-existing').hide();
                        jQuery('.wcb-coupon-email-restriction').hide();
                        break;
                    default:
                        jQuery('.wcb-coupon-unique').hide();
                        jQuery('.wcb-coupon-existing').hide();
                        jQuery('.wcb-coupon-email-restriction').hide();
                        jQuery('.wcb-coupon-custom').hide();
                }
            }
        });
        /*select popup trigger*/
        switch (jQuery('#wcb_select_popup').val()) {
            case 'time':
                jQuery('.wcb-popup-time').show();
                jQuery('.wcb-popup-scroll').hide();
                break;
            case 'scroll':
                jQuery('.wcb-popup-time').hide();
                jQuery('.wcb-popup-scroll').show();
                break;
            case 'exit':
                jQuery('.wcb-popup-time').hide();
                jQuery('.wcb-popup-scroll').hide();
                break;
            default:
                jQuery('.wcb-popup-time').show();
                jQuery('.wcb-popup-scroll').show();
        }
        jQuery('.wcb-select-popup').dropdown({
            onChange: function (val) {
                if (val === 'time') {
                    jQuery('.wcb-popup-time').show();
                    jQuery('.wcb-popup-scroll').hide();
                } else if (val === 'scroll') {
                    jQuery('.wcb-popup-time').hide();
                    jQuery('.wcb-popup-scroll').show();
                } else if (val === 'exit') {
                    jQuery('.wcb-popup-time').hide();
                    jQuery('.wcb-popup-scroll').hide();
                } else {
                    jQuery('.wcb-popup-time').show();
                    jQuery('.wcb-popup-scroll').show();
                }
            }
        });

        jQuery('#wcb_expire_subscribed').on('change', function () {
            jQuery('.wcb_expire_subscribed_value').html(jQuery(this).val());
        })
    }

    /*ajax search*/
    jQuery(".search-product").select2({
        closeOnSelect: false,
        placeholder: "Please fill in your  product title",
        ajax: {
            url: "admin-ajax.php?action=wcb_search_product",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });
    jQuery(".search-category").select2({
        closeOnSelect: false,
        placeholder: "Please fill in your category title",
        ajax: {
            url: "admin-ajax.php?action=wcb_search_cate",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });
    jQuery(".search-coupon").select2({
        placeholder: "Type coupon code here",
        ajax: {
            url: "admin-ajax.php?action=wcb_search_coupon",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });
    jQuery(".wcb-ac-search-list").select2({
        placeholder: "Type list name",
        ajax: {
            url: "admin-ajax.php?action=wcb_search_active_campaign_list",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        allowClear: true
    });

    /*design button "shop now"*/
    var buttonShopNow = jQuery('.wcb-button-shop-now');
    jQuery('#wcb_button_shop_now_title').on('keyup', function () {
        buttonShopNow.html(jQuery(this).val());
    });
    jQuery('#wcb_button_shop_now_url').on('keyup', function () {
        buttonShopNow.attr('href', jQuery(this).val());
    });
    jQuery('#wcb_button_shop_now_size').on('change', function () {
        buttonShopNow.css('font-size', jQuery(this).val() + 'px');
    });
    /*Color picker*/
    jQuery('#wcb_button_shop_now_color').iris({
        change: function (event, ui) {
            jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
            buttonShopNow.css({'color': ui.color.toString()});
        },
        hide: true,
        border: true
    }).click(function (event) {
        event.stopPropagation();
        jQuery('.iris-picker').hide();
        jQuery(this).closest('td').find('.iris-picker').show();
    });
    jQuery('#wcb_button_shop_now_bg_color').iris({
        change: function (event, ui) {
            jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
            buttonShopNow.css({'background-color': ui.color.toString()});
        },
        hide: true,
        border: true
    }).click(function (event) {
        event.stopPropagation();
        jQuery('.iris-picker').hide();
        jQuery(this).closest('td').find('.iris-picker').show();
    });

    jQuery('body').click(function () {
        jQuery('.iris-picker').hide();
    });
    /*preview email*/
    jQuery('.preview-emails-html-overlay').on('click', function () {
        jQuery('.preview-emails-html-container').addClass('preview-html-hidden');
    });
    jQuery('.wcb-preview-emails-button').on('click', function () {
        jQuery(this).html('Please wait...');
        let language = jQuery(this).data()['wcb_language'];
        jQuery.ajax({
            url: woo_coupon_box_params_admin.url,
            type: 'GET',
            dataType: 'JSON',
            data: {
                action: 'wcb_preview_emails',
                heading: jQuery('#wcb_email_heading' + language).val(),
                content: tinyMCE.get('wcb_email_content' + language) ? tinyMCE.get('wcb_email_content' + language).getContent() : jQuery('#wcb_email_content' + language).val(),
                button_shop_url: jQuery('#wcb_button_shop_now_url').val(),
                button_shop_size: jQuery('#wcb_button_shop_now_size').val(),
                button_shop_color: jQuery('#wcb_button_shop_now_color').val(),
                button_shop_bg_color: jQuery('#wcb_button_shop_now_bg_color').val(),
                button_shop_title: jQuery('#wcb_button_shop_now_title' + language).val(),
            },
            success: function (response) {
                jQuery('.wcb-preview-emails-button[data-wcb_language="' + language + '"]').html('Preview emails');
                if (response) {
                    jQuery('.preview-emails-html').html(response.html);
                    jQuery('.preview-emails-html-container').removeClass('preview-html-hidden');
                }
            },
            error: function (err) {
                jQuery('.wcb-preview-emails-button').html('Preview emails');
            }
        })
    });
});
