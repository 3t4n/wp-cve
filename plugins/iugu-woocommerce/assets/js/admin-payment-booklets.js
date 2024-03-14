/*jshint devel: true */
(function($) {
    'use strict';

    $(function() {
        $('#woocommerce_iugu-payment-booklets_pass_interest').on('change', function() {
            var fields = $('#woocommerce_iugu-payment-booklets_interest_rate_on_installment_1, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_2, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_3, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_4, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_5, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_6, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_7, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_8, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_9, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_10, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_11, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_12, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_13, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_14, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_15, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_16, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_17, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_18, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_19, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_20, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_21, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_22, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_23, ' +
                    '#woocommerce_iugu-payment-booklets_interest_rate_on_installment_24')
                .closest('tr');
            if ($(this).is(':checked')) {
                fields.show();
            } else {
                fields.hide();
            }

        }).change();
        $('#woocommerce_iugu-payment-booklets_origin_installment').on('change', function() {
            var fields = $('#woocommerce_iugu-payment-booklets_iugu_number_installments_general')
                .closest('tr');
            if ($(this).val() == 'general') {
                fields.show();
            } else {
                fields.hide();
            }

        }).change();
    });

}(jQuery));