jQuery(function($) {

    wooMP.beginProcessing = function () {
        try {
            Stripe.setPublishableKey(wooMP.publishableKey);
            Stripe.card.createToken({
                number: wooMP.$cardNum.val(),
                exp:    wooMP.$cardExp.val(),
                cvc:    wooMP.$cardCVC.val()
            }, stripeResponseHandler);
        } catch (error) {
            wooMP.handleError(error.message);
        }
    };

    function stripeResponseHandler(status, response) {
        if (response.error) {
            wooMP.catchError(response.error.message);
        } else {
            wooMP.processPayment({
                token: response.id
            });
        }
    }

    wooMP.catchError = function (message, code) {
        switch (code) {
            case 'moto_incorrectly_enabled':
                wooMP.handleError($('#woo-mp-stripe-notice-template-moto-incorrectly-enabled').html(), null, null, true);
                return;
            case 'auth_required_moto_disabled':
            case 'auth_required_moto_enabled':
                var message = code === 'auth_required_moto_disabled'
                    ? $('#woo-mp-stripe-notice-template-auth-required-moto-disabled').html()
                    : $('#woo-mp-stripe-notice-template-auth-required-moto-enabled').html();

                var details = wooMP.noticeSections([{
                    title: 'Notifying Your Customer',
                    HTML:  $('#woo-mp-stripe-notice-template-partial-invoice-instructions').html()
                }]);

                wooMP.handleError(message, null, details, true);

                return;
        }

        switch (message) {

            // Stripe returns this error when an expiration date is very far in the future.
            case "Your card's expiration year is invalid.":
                wooMP.handleError('Sorry, the expiration date is invalid.', wooMP.$cardExp);
                break;
            case "Your card's security code is incorrect.":
                wooMP.handleError('Sorry, the security code is incorrect.', wooMP.$cardCVC);
                break;
            default:
                wooMP.handleError(message);
                break;
        }
    };

});
