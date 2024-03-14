(function ($) {
    "use strict";

    var pmpro = {};

    pmpro.connection_service_handler = function () {
        $("tr[id*='mailoptin_pmpro_integration_lists']").slideUp();
        $("tr[id*='mailoptin_pmpro_double_optin']").slideUp();
        $("tr[id*='mailoptin_pmpro_mapped_fields']").slideUp();
        $("tr[id*='mailoptin_pmpro_select_tags']").slideUp();
        $("tr[id*='mailoptin_pmpro_text_tags']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    pmpro.connection_lists_handler = function () {
        $("tr[id*='mailoptin_pmpro_mapped_fields']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    pmpro.subscribe_member_handler = function () {

        var subscribe_members = $(this).val();

        if (subscribe_members === 'yes') {
            $("tr[id*='mailoptin_pmpro_optin_checkbox_label_row']").slideDown();
        } else {
            $("tr[id*='mailoptin_pmpro_optin_checkbox_label_row']").slideUp();
        }
    }

    pmpro.init = function () {
        $("select[name*='mailoptin_pmpro_subscribe_method']").change(pmpro.subscribe_member_handler).change();
        $(document).on('change', "select[name*='mailoptin_pmpro_integration_connections']", pmpro.connection_service_handler);
        $(document).on('change', "select[name*='mailoptin_pmpro_integration_lists']", pmpro.connection_lists_handler);
    }

    $(window).on('load', pmpro.init);

})(jQuery);