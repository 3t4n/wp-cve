jQuery(document).ready(function($) {
	'use strict'; 

    let form = $('form.checkout'),
        params = wc_montonio_inline_cc,
        stripePublicKey = null,
        stripeClientSecret = null,
        uuid = null,
        embeddedPayment = '',
        isCompleted = false;


    $(document).on('updated_checkout', function(){        
        if ($('input[value="wc_montonio_card"]').is(':checked')) {
            setTimeout(function() { 
                initializeOrder();
            }, 200);
        }
    });

    $(document).on( 'change', 'input[value="wc_montonio_card"]', function() {
        initializeOrder();
    });

    function initializeOrder() {
        if ($('#montonio-card-form').hasClass('paymentInitilized')) {
            return false;
        }

        $('#montonio-card-form').addClass('loading').block({
            message: null,
            overlayCSS: {
                background: 'transparent',
                opacity: 0.6
            }
        });

        let data = {
            'action': 'get_payment_intent',
            'method': 'cardPayments',
            'sandbox_mode': params.sandbox_mode
        };

        $.post(woocommerce_params.ajax_url, data, function(response) {       
            if (response.success === true) {
                stripePublicKey = response.data.stripePublicKey;
                stripeClientSecret = response.data.stripeClientSecret;
                uuid = response.data.uuid;

                initializePayment();
            } else {
                $('#montonio-card-form').removeClass('loading').unblock();
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
            targetId: 'montonio-card-form',
        });

        $('#montonio-card-form').addClass('paymentInitilized').removeClass('loading').unblock();

        embeddedPayment.on('change', event => { 
            isCompleted = event.isCompleted;
        });
    }

    form.on('checkout_place_order', function() {
        if ($('input[value="wc_montonio_card"]').is(':checked')) {
            $('body').addClass('wc-montonio-cc-processing');
        } else {
            $('body').removeClass('wc-montonio-cc-processing');
        }
    });

    $(document).ajaxComplete( function() {
        if ($('input[value="wc_montonio_card"]').is(':checked') && $('body').hasClass('wc-montonio-cc-processing') && !$('#montonio-card-form').is(':empty')) {
            window.stop();
        }
    });

    form.on('checkout_place_order_success', function() {       
        if ($('input[value="wc_montonio_card"]').is(':checked') && !$('#montonio-card-form').is(':empty')) {
            if(isCompleted == false) {
                confirmPayment(false);
                $.scroll_to_notices( $('#payment_method_wc_montonio_card') );
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
                $('body').removeClass('wc-montonio-cc-processing');
            }
        }
    }
});

function isEmpty(value) {
    return (value == null || (typeof value === "string" && value.trim().length === 0));
}