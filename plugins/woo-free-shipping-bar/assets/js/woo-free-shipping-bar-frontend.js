/**
 * Developed by VillaTheme
 */
"use strict";
jQuery(document).ready(function () {
    var wspb = woocommerce_free_shipping_bar;
    wspb.init();
    wspb.add_to_cart();

    // jQuery(document.body).on('updated_checkout', function(){
    //     wspb.init();
    //     wspb.add_to_cart();
    // });
});
/**
 * Class Free Shipping Bar
 * @type {{init_delay: number, display_time: number, init: woocommerce_free_shipping_bar.init, bar_show: woocommerce_free_shipping_bar.bar_show, bar_hide: woocommerce_free_shipping_bar.bar_hide, gift_box_hide: woocommerce_free_shipping_bar.gift_box_hide, gift_box_show: woocommerce_free_shipping_bar.gift_box_show, add_to_cart: woocommerce_free_shipping_bar.add_to_cart}}
 */
var woocommerce_free_shipping_bar = {
    init_delay: 0,
    display_time: 10,
    timeout_display: 0,
    timeout_init: 0,
    init: function () {
        this.timeout_init = setTimeout(function () {
            woocommerce_free_shipping_bar.bar_show();
            woocommerce_free_shipping_bar.gift_box_hide();
        }, this.init_delay * 1000);
        /*Close button*/
        jQuery('#wfspb-close').click(function () {
            woocommerce_free_shipping_bar.bar_hide();
            woocommerce_free_shipping_bar.gift_box_show();
        });

        /*Gift box icon*/
        jQuery('.wfspb-gift-box').on('click', function () {
            woocommerce_free_shipping_bar.gift_box_hide();
            woocommerce_free_shipping_bar.bar_show();
            woocommerce_free_shipping_bar.clear_time_init();
        });
    },
    bar_show: function () {
        jQuery('#wfspb-top-bar').fadeIn(500);
        this.timeout_display = setTimeout(function () {
            woocommerce_free_shipping_bar.bar_hide();
            woocommerce_free_shipping_bar.gift_box_show();
        }, this.display_time * 1000);
    },
    bar_hide: function () {
        jQuery('#wfspb-top-bar').fadeOut(500);
    },
    gift_box_hide: function () {
        jQuery('.wfspb-gift-box').addClass('wfsb-hidden');

    },
    gift_box_show: function () {
        jQuery('.wfspb-gift-box').removeClass('wfsb-hidden');
    },
    clear_time_init: function () {
        clearTimeout(this.timeout_init);
    },
    clear_time_display: function () {
        clearTimeout(this.timeout_display);
    },
    add_to_cart: function () {
// update total amount after click add_to_cart
        jQuery(document).ajaxComplete(function (event, jqxhr, settings) {
            var ajax_link = settings.url;
            var data_opts = settings.data;

            if (ajax_link && ajax_link != 'undefined' && ajax_link.search(/wc-ajax=add_to_cart/i) >= 0 ||
                ajax_link.search(/wc-ajax=remove_from_cart/i) >= 0 || ajax_link.search(/wc-ajax=get_refreshed_fragments/i) >= 0 ||
                ajax_link.search(/wc-ajax=update_order_review/i) >= 0 || ajax_link.search(/admin-ajax\.php/i) >= 0) {
                if (ajax_link.search(/admin-ajax\.php/i) >= 0) {
                    if (data_opts && data_opts != 'undefined' && data_opts.search(/action=basel_ajax_add_to_cart/i) >= 0) {
                        console.log('Use Basel theme');
                    } else {
                        return;
                    }
                }
                var time_disappear = jQuery('#wfspb-top-bar').attr('data-time-disappear');
                var show_giftbox = jQuery('#wfspb-gift-box').attr('data-display');
                jQuery.ajax({
                    url: _wfsb_params.ajax_url,
                    cache: false,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'wfspb_added_to_cart',
                        nonce: _wfsb_params.nonce,
                    },
                    success: function (response) {
                        if (ajax_link.search(/wc-ajax=get_refreshed_fragments/i) >= 0) {
                            if (parseInt(response.total_percent) == 0) {
                                return;
                            }
                        }
                        woocommerce_free_shipping_bar.clear_time_display();
                        woocommerce_free_shipping_bar.bar_show();
                        woocommerce_free_shipping_bar.gift_box_hide();

                        jQuery("#wfspb-main-content").html(response.message_bar);
                        jQuery("#wfspb-current-progress").animate({width: response.total_percent + '%'}, 1000);
                        jQuery("#wfspb-label").html(parseInt(response.total_percent) + '%');
                        if (parseInt(response.total_percent) >= 100) {
                            jQuery('#wfspb-progress').fadeOut(500);
                        } else {
                            jQuery('#wfspb-progress').show();
                        }
                    }
                });
            }
        });
    }
}