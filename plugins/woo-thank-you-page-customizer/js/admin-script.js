'use strict';
jQuery(document).ready(function ($) {

    $('.vi-ui.vi-ui-coupon.tabular.menu .item').vi_tab();
    $('.vi-ui.vi-ui-main.tabular.menu .item').vi_tab({
        history: true,
        historyType: 'hash'
    });

    /*Setup tab*/
    let tabs,
        tabEvent = false,
        initialTab = 'general',
        navSelector = '.vi-ui.vi-ui-main.menu',
        navFilter = function (el) {
            // return $(el).attr('href').replace(/^#/, '');
        },
        panelSelector = '.vi-ui.vi-ui-main.tab',
        panelFilter = function () {
            $(panelSelector + ' a').filter(function () {
                return $(navSelector + ' a[title=' + $(this).attr('title') + ']').size() != 0;
            });
        };

    // Initializes plugin features
    $.address.strict(false).wrap(true);

    if ($.address.value() == '') {
        $.address.history(false).value(initialTab).history(true);
    }

    // Address handler
    $.address.init(function (event) {

        // Adds the ID in a lazy manner to prevent scrolling
        $(panelSelector).attr('id', initialTab);

        panelFilter();

        // Tabs setup
        tabs = $('.vi-ui.vi-ui-main.menu')
            .vi_tab({
                history: true,
                historyType: 'hash'
            })

        // Enables the plugin for all the tabs
        $(navSelector + ' a').click(function (event) {
            tabEvent = true;
            // $.address.value(navFilter(event.target));
            tabEvent = false;
            return true;
        });

    });
    $('.ui-sortable').sortable({
        placeholder: 'wtyp-place-holder',
    });
    $('.vi-ui.checkbox').checkbox();
    $('.vi-ui.dropdown').dropdown();
    handleDropdownSelect();

    function handleDropdownSelect() {
        /*select coupon*/
        switch (jQuery('#coupon_type').val()) {
            case 'unique':
                jQuery('.coupon-custom').hide();
                jQuery('.coupon-existing').hide();
                break;
            case 'existing':
                jQuery('.coupon-unique').hide();
                jQuery('.coupon-custom').hide();
                break;
            case 'custom':
                jQuery('.coupon-unique').hide();
                jQuery('.coupon-existing').hide();
                jQuery('.coupon-email-restriction').hide();
                break;
            default:
                jQuery('.coupon-unique').hide();
                jQuery('.coupon-existing').hide();
                jQuery('.coupon-email-restriction').hide();
                jQuery('.coupon-custom').hide();
        }
        jQuery('.coupon-select').dropdown({
            onChange: function (val) {
                switch (val) {
                    case 'unique':
                        jQuery('.coupon-unique').show();
                        jQuery('.coupon-custom').hide();
                        jQuery('.coupon-existing').hide();
                        jQuery('.coupon-email-restriction').show();
                        break;
                    case 'existing':
                        jQuery('.coupon-unique').hide();
                        jQuery('.coupon-custom').hide();
                        jQuery('.coupon-existing').show();
                        jQuery('.coupon-email-restriction').show();
                        break;
                    case 'custom':
                        jQuery('.coupon-unique').hide();
                        jQuery('.coupon-custom').show();
                        jQuery('.coupon-existing').hide();
                        jQuery('.coupon-email-restriction').hide();
                        break;
                    default:
                        jQuery('.coupon-unique').hide();
                        jQuery('.coupon-existing').hide();
                        jQuery('.coupon-email-restriction').hide();
                        jQuery('.coupon-custom').hide();
                }
            }
        });

    }
    /*ajax search*/
    $(".search-product-parent").select2({
        closeOnSelect: false,
        placeholder: "Please fill in your  product title",
        ajax: {
            url: "admin-ajax.php?action=wtyp_search_product_parent",
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
        minimumInputLength: 2
    });
    $(".search-product").select2({
        placeholder: "Please fill in your  product title",
        ajax: {
            url: "admin-ajax.php?action=wtyp_search_product",
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
        minimumInputLength: 2
    });
    $(".search-category").select2({
        placeholder: "Please fill in your category title",
        ajax: {
            url: "admin-ajax.php?action=wtyp_search_cate",
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
        minimumInputLength: 2
    });
    $(".search-coupon").select2({
        placeholder: "Type coupon code here",
        ajax: {
            url: "admin-ajax.php?action=wtyp_search_coupon",
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
        minimumInputLength: 2
    });
    $('#existing_coupon').on("select2:selecting", function (e) {
        $('#coupon_unique_amount').val(e.params.args.data.coupon_data.coupon_amount);
        $('#coupon_unique_discount_type').parent().dropdown('set selected', e.params.args.data.coupon_data.coupon_discount_type);
    });
    $('body').click(function () {
        $('.iris-picker').hide();
    });

    /*email shortcodes list*/
    $('.woocommerce-thank-you-page-available-shortcodes-shortcut').on('click', function () {
        wtypc_disable_scroll()
        $('.woocommerce-thank-you-page-available-shortcodes-container').removeClass('woocommerce-thank-you-page-hidden');
    });
    $('.woocommerce-thank-you-page-available-shortcodes-items-close').on('click', function () {
        $('.woocommerce-thank-you-page-available-shortcodes-overlay').click();
    });
    $('.woocommerce-thank-you-page-available-shortcodes-overlay').on('click', function () {
        wtypc_enable_scroll()
        $('.woocommerce-thank-you-page-available-shortcodes-container').addClass('woocommerce-thank-you-page-hidden');
    });
    $('.woocommerce-thank-you-page-available-shortcodes-item-copy').on('click', function () {
        $(this).parent().find('input').select();
        document.execCommand("copy");
    });

    /*preview email*/
    $('.preview-emails-html-overlay').on('click', function () {
        $('.preview-emails-html-container').addClass('preview-html-hidden');
        wtypc_enable_scroll()
    });
    $('.woocommerce-thank-you-page-preview-emails-button').on('click', function () {
        $(this).html('Please wait...');
        $.ajax({
            url: wtypc_params_admin.url,
            type: 'GET',
            dataType: 'JSON',
            data: {
                action: 'wtypc_preview_emails',
                heading: $('#coupon-email-heading').val(),
                content: tinyMCE.get('coupon_email_content') ? tinyMCE.get('coupon_email_content').getContent() : $('#coupon_email_content').val(),
            },
            success: function (response) {
                $('.woocommerce-thank-you-page-preview-emails-button').html('Preview emails');
                if (response) {
                    $('.preview-emails-html').html(response.html);
                    wtypc_disable_scroll()
                    $('.preview-emails-html-container').removeClass('preview-html-hidden');
                    if (response.css) {
                        $('#woocommerce-thank-you-page-admin-inline-css').html(response.css);
                    }
                }
            },
            error: function (err) {
                $('.woocommerce-thank-you-page-preview-emails-button').html('Preview emails');
            }
        })
    });
    function wtypc_enable_scroll() {
        let scrollTop = parseInt($('html').css('top'));
        $('html').removeClass('wtypc-noscroll');
        $('html,body').scrollTop(-scrollTop);
    }

    function wtypc_disable_scroll() {
        if ($(document).height() > $(window).height()) {
            let scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop(); // Works for Chrome, Firefox, IE...
            $('html').addClass('wtypc-noscroll').css('top', -scrollTop);
        }
    }
});

