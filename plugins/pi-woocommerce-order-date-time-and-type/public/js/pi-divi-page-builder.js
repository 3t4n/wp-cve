(function ($) {
    'use strict';


    jQuery(document).on('init_checkout', function () {
        if (jQuery(".et_pb_wc_checkout_payment_info form.checkout").length > 0) {
            /** we need to prepend this as Divi do not add hidden field in checkout form */
            jQuery("<input name='pi_system_delivery_date' type='hidden'>").prependTo(".et_pb_wc_checkout_payment_info form.checkout");

            jQuery("input[name='pi_system_delivery_date']").trigger('change');
            jQuery("input[name='pickup_location']").trigger('change');
            jQuery("select[name='pickup_location']").trigger('change');

            /**
             * There is bug in Divi Js it captures first radio button as value (without change event) even though selected is other button. we can't use trigger change as it causes pare reload loop
             */
            var delivery_type = jQuery('input[type="radio"][name="pi_delivery_type"]:checked').val();
            jQuery('input[type="hidden"][name="pi_delivery_type"]').val(delivery_type);
        }
    })


})(jQuery);