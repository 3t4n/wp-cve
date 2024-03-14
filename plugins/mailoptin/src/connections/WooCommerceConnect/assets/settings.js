(function ($) {
    "use strict";

    var woo = {};

    woo.connection_service_handler = function () {
        $("tr[id*='mailoptin_woocommerce_integration_lists']").slideUp();
        $("tr[id*='mailoptin_woocommerce_double_optin']").slideUp();
        $("tr[id*='mailoptin_woocommerce_mapped_fields']").slideUp();
        $("tr[id*='mailoptin_woocommerce_select_tags']").slideUp();
        $("tr[id*='mailoptin_woocommerce_text_tags']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    woo.connection_lists_handler = function () {
        $("tr[id*='mailoptin_woocommerce_mapped_fields']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    woo.subscribe_customer_handler = function () {
        var subscribe_customers = $(this).val();

        if(subscribe_customers === 'yes') {
            $("tr[id*='mailoptin_woocommerce_field_label']").slideDown();
            $("tr[id*='mailoptin_woocommerce_checkbox_default']").slideDown();
            $("tr[id*='mailoptin_woocommerce_checkbox_location']").slideDown();
        } else {
            $("tr[id*='mailoptin_woocommerce_field_label']").slideUp();
            $("tr[id*='mailoptin_woocommerce_checkbox_default']").slideUp();
            $("tr[id*='mailoptin_woocommerce_checkbox_location']").slideUp();
        }
    }

    woo.init = function () {
        $("select[name*='mailoptin_woocommerce_subscribe_customers']").change(woo.subscribe_customer_handler).change();
        $(document).on('change', "select[name*='mailoptin_woocommerce_integration_connections']", woo.connection_service_handler);
        $(document).on('change', "select[name*='mailoptin_woocommerce_integration_lists']", woo.connection_lists_handler);
    }


    $(window).on('load', woo.init);

})(jQuery);