(function ($) {
    'use strict';
    $(function () {
        $('form.checkout').on('click', 'input[name="payment_method"]', function () {
            var isPPEC = $(this).is('#payment_method_ppcp_paypal_checkout');
            var togglePPEC = isPPEC ? 'show' : 'hide';
            $('#paypal-button-container').animate({opacity: togglePPEC, height: togglePPEC, padding: togglePPEC}, 230);
        });
    });
})(jQuery);