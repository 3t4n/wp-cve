'use strict';
jQuery(document).ready(function ($) {
    $('.woocommerce-thank-you-page-coupon__code-code').focus(function () {
        $(this).select();
    })
    $('.woocommerce-thank-you-page-coupon__code-copy-code').on('click', function () {
        $(this).parent().parent().find('.woocommerce-thank-you-page-coupon__code-code').select();
        document.execCommand("copy");
        alert(woocommerce_thank_you_page_customizer_params.copied_message)
    })
    sendCouponButton();

    function sendCouponButton() {
        $('.woocommerce-thank-you-page-coupon__code-mail-me').on('click', function () {
            let button = $(this);
            button.unbind().addClass('wtypc-sending-email');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: woocommerce_thank_you_page_customizer_params.url,
                data: {
                    action: woocommerce_thank_you_page_customizer_params.action,
                    shortcodes: woocommerce_thank_you_page_customizer_params.shortcodes,
                    coupon_code: button.parent().parent().find('.woocommerce-thank-you-page-coupon__code-code').val(),
                    nonce: woocommerce_thank_you_page_customizer_params.nonce,
                },
                success: function (response) {
                    button.removeClass('wtypc-sending-email');
                    sendCouponButton();
                    if (response.hasOwnProperty('message') && response.message) {
                        alert(response.message);
                    }
                },
                error: function (err) {
                    button.removeClass('wtypc-sending-email');
                    sendCouponButton();
                    console.log(err);
                }
            })
        })
    }
});
