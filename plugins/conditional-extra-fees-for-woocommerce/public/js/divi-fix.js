(function ($) {
    'use strict';

    jQuery(function ($) {
        updateCheckboxes();

        jQuery(document).on("change", '.pi-cefw-optional-fees', function () {
            updateCheckboxes();
        });
    });

    function updateCheckboxes() {
        if (jQuery(".et_pb_wc_checkout_payment_info form.checkout").length > 0) {
            jQuery('.pi-cefw-optional-fees', document).each(function () {
                var name = jQuery(this).attr('name');
                if (jQuery(this).is(':checked')) {
                    if (jQuery(".et_pb_wc_checkout_payment_info form.checkout input[name='" + name + "']").length == 0) {
                        jQuery("<input name='" + name + "' type='hidden' value='1'>").prependTo(".et_pb_wc_checkout_payment_info form.checkout");
                    }
                } else {
                    jQuery(".et_pb_wc_checkout_payment_info form.checkout input[name='" + name + "']").remove();
                }
            });
        }
    }

    jQuery(document).on('init_checkout', function () {
        updateCheckboxes();
    });


})(jQuery);