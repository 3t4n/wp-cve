jQuery(document).ready(function($) {
	'use strict'; 

    let form = $('form.checkout'),
        params = wc_montonio_inline_blik,
        stripePublicKey = null,
        stripeClientSecret = null,
        uuid = null,
        embeddedPayment = '',
        isCompleted = false;

    $(document).on('updated_checkout', function(){        
        if ($('input[value="wc_montonio_blik"]').is(':checked')) {
            setTimeout(function() { 
                initializeOrder();
            }, 200);
        }
    });

    $(document).on( 'change', 'input[value="wc_montonio_blik"]', function() {
        initializeOrder();
    });

    function initializeOrder() {
        if ($('#montonio-blik-form').hasClass('paymentInitilized')) {
            return false;
        }

        $('#montonio-blik-form').addClass('loading').block({
            message: null,
            overlayCSS: {
                background: 'transparent',
                opacity: 0.6
            }
        });

        let data = {
            'action': 'get_payment_intent',
            'method': 'blik',
            'sandbox_mode': params.sandbox_mode
        };

        $.post(woocommerce_params.ajax_url, data, function(response) {        
            if (response.success === true) {
                stripePublicKey = response.data.stripePublicKey;
                stripeClientSecret = response.data.stripeClientSecret;
                uuid = response.data.uuid;

                initializePayment();
            } else {
                $('#montonio-blik-form').removeClass('loading').unblock();
            }
        });
    }

    async function initializePayment() {     
        embeddedPayment = await Montonio.Checkout.EmbeddedPayments.initializePayment({
            stripePublicKey: stripePublicKey,
            stripeClientSecret: stripeClientSecret,
            paymentIntentUuid: uuid, 
            locale: params.locale,
            country: form.find('[name=billing_country]').val(),
            targetId: 'montonio-blik-form',
        });

        $('#montonio-blik-form').addClass('paymentInitilized').removeClass('loading').unblock();

        embeddedPayment.on('change', event => { 
            isCompleted = event.isCompleted;
        });
    }

    form.on('checkout_place_order', function() {
        if ($('input[value="wc_montonio_blik"]').is(':checked')) {
            $('body').addClass('wc-montonio-blik-processing');
        } else {
            $('body').removeClass('wc-montonio-blik-processing');
        }
    });

    $(document).ajaxComplete( function() {
        if ($('input[value="wc_montonio_blik"]').is(':checked') && $('body').hasClass('wc-montonio-blik-processing') && !$('#montonio-blik-form').is(':empty')) {
            window.stop();
        }
    });

    form.on('checkout_place_order_success', function() {       
        if ($('input[value="wc_montonio_blik"]').is(':checked') && !$('#montonio-blik-form').is(':empty')) {
            if(isCompleted == false) {
                confirmPayment(false);
                $.scroll_to_notices( $('#payment_method_wc_montonio_blik') );
            } else {
                confirmPayment();
            }
        }
    });

    async function confirmPayment(redirect = true) {
        try {
            let sandboxMode = false;

            if (params.sandbox_mode == 'yes') {
                sandboxMode = true;
            }

            const result = await embeddedPayment.confirmPayment(sandboxMode);

            window.location.replace(result.returnUrl);
        } catch (error) {
            if (redirect) {
                window.location.replace(encodeURI(params.return_url + '&error-message=' + error.message));
            } else {
                form.removeClass('processing').unblock();
                $('body').removeClass('wc-montonio-blik-processing');
            }
        }
    }
});

function isEmpty(value) {
    return (value == null || (typeof value === "string" && value.trim().length === 0));
}