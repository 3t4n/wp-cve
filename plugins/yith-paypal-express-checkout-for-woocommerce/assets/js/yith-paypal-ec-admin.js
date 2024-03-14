(function ($) {
    $('#woocommerce_yith-paypal-ec_payment_action').on('change', function () {
        var value = $(this).val();

        if ('authorization' == value) {
            $('#woocommerce_yith-paypal-ec_instant_payments').closest('tr').hide();
        } else {
            $('#woocommerce_yith-paypal-ec_instant_payments').closest('tr').show();
        }
    }).change();

    $('#woocommerce_yith-paypal-ec_ipn_notification').on('change', function () {
        if (true == $(this).is(':checked')) {
            $('#woocommerce_yith-paypal-ec_ipn_notification_email').closest('tr').show();
        } else {
            $('#woocommerce_yith-paypal-ec_ipn_notification_email').closest('tr').hide();
        }
    }).change();

    $('#woocommerce_yith-paypal-ec_env').on('change', function () {
        var value = $(this).val(),
            sandbox = $('h3.sandbox'),
            live = $('h3.live');

        if ('live' == value) {
            sandbox.hide().next().hide().next().hide();
            live.show().next().show().next().show();
        } else if ('sandbox' == value) {
            sandbox.show().next().show().next().show();
            live.hide().next().hide().next().hide();
        }

    }).change();

    $('#woocommerce_yith-paypal-ec_on_checkout').on('change', function () {
        if ($(this).is(':checked')) {
            $('#woocommerce_yith-paypal-ec_gateway_description').closest('tr').show();
        } else {
            $('#woocommerce_yith-paypal-ec_gateway_description').closest('tr').hide();
        }
    }).change();
}(jQuery));