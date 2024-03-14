(function ($) {
    "use strict";

    var um = {};

    um.connection_service_handler = function () {
        $("tr[id*='mailoptin_ultimatemember_integration_lists']").slideUp();
        $("tr[id*='mailoptin_ultimatemember_double_optin']").slideUp();
        $("tr[id*='mailoptin_ultimatemember_mapped_fields']").slideUp();
        $("tr[id*='mailoptin_ultimatemember_select_tags']").slideUp();
        $("tr[id*='mailoptin_ultimatemember_text_tags']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    };

    um.connection_lists_handler = function () {
        $("tr[id*='mailoptin_ultimatemember_mapped_fields']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    };

    um.subscribe_customer_handler = function () {
        var subscribe_customers = $(this).val();

        if(subscribe_customers === 'yes') {
            $("tr[id*='mailoptin_ultimatemember_field_label']").slideDown();
            $("tr[id*='mailoptin_ultimatemember_checkbox_default']").slideDown();
        } else {
            $("tr[id*='mailoptin_ultimatemember_field_label']").slideUp();
            $("tr[id*='mailoptin_ultimatemember_checkbox_default']").slideUp();
        }
    };

    um.init = function () {
        $("select[name*='mailoptin_ultimatemember_subscribe_customers']").change(um.subscribe_customer_handler).change();
        $(document).on('change', "select[name*='mailoptin_ultimatemember_integration_connections']", um.connection_service_handler);
        $(document).on('change', "select[name*='mailoptin_ultimatemember_integration_lists']", um.connection_lists_handler);
    };


    $(window).on('load', um.init);

})(jQuery);