(function ($) {
    'use strict';
    $(function () {
        if ($('#ship-to-different-address-checkbox').length) {
            $('#ship-to-different-address-checkbox').prop('checked', true);
        }
        $(".ppcp_edit_billing_address").click(function () {
            $('body').trigger('update_checkout');
            $('.ppcp_billing_details').hide();
            $('.woocommerce-billing-fields').show();
        });
        $(".ppcp_edit_shipping_address").click(function () {
            $('body').trigger('update_checkout');
            $('.ppcp_shipping_details').hide();
            $('.woocommerce-shipping-fields').show();
            $('#ship-to-different-address').show();
            $('.woocommerce-additional-fields').show();
        });
        if ($('#place_order').length) {
            $('html, body').animate({
                scrollTop: ($('#place_order').offset().top - 500)
            }, 1000);
        }
    });
})(jQuery);