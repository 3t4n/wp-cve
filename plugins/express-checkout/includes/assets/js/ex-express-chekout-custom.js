jQuery(function ($) {
    if (ex_express_checkout.is_page_name == "single_product_page") {
        jQuery(".single_add_to_cart_button.paypal_checkout_button").click(function () {
            if (!$('.woocommerce-variation-add-to-cart').hasClass('woocommerce-variation-add-to-cart-disabled')) {
                $('.cart').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
                var ec_action = $(this).data('action');
                if (typeof ec_action === 'undefined' || ec_action === null) {
                    ec_action = $(this).attr("href");
                }
                $('form.cart').attr('action', ec_action);
                $(this).attr('disabled', 'disabled');
                $('form.cart').submit();
                return false;
            }
        });

    } else if (ex_express_checkout.is_page_name == "checkout_page") {
        jQuery("a.single_add_to_cart_button").click(function () {
            block_div_to_payment();
        });
        var is_set_class = $("div").is(".express-provided-address");
        if (is_set_class) {
            jQuery('#express_checkout_button_chekout_page').hide();
            jQuery('#express_checkout_button_text').hide();
            jQuery('.woocommerce-message').hide();
            jQuery('.woocommerce-info').hide();
        }

    } else if (ex_express_checkout.is_page_name == "cart_page") {
        jQuery("a.single_add_to_cart_button.paypal_checkout_button").click(function () {
            block_div_to_payment();
        });
    }

    function block_div_to_payment() {
        var ex_class = "";
        if (ex_express_checkout.is_page_name == 'cart_page') {
            ex_class = "div.wc-proceed-to-checkout";
        } else if (ex_express_checkout.is_page_name == 'single_product_page') {
            ex_class = "form.cart";
        } else if (ex_express_checkout.is_page_name == 'checkout_page') {
            ex_class = "div.woocommerce";
        }
        if (ex_class.toString().length > 0) {
            $(ex_class).block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        }
    }
});