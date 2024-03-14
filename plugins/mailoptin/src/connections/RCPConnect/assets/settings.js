(function ($) {
    "use strict";

    var rcp = {};

    rcp.connection_service_handler = function () {
        $("tr[id*='mailoptin_rcp_integration_lists']").slideUp();
        $("tr[id*='mailoptin_rcp_double_optin']").slideUp();
        $("tr[id*='mailoptin_rcp_mapped_fields']").slideUp();
        $("tr[id*='mailoptin_rcp_select_tags']").slideUp();
        $("tr[id*='mailoptin_rcp_text_tags']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    rcp.connection_lists_handler = function () {
        $("tr[id*='mailoptin_rcp_mapped_fields']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    rcp.subscribe_member_handler = function () {

        var subscribe_members = $(this).val();

        if (subscribe_members === 'yes') {
            $("tr[id*='mailoptin_rcp_optin_checkbox_label_row']").slideDown();
        } else {
            $("tr[id*='mailoptin_rcp_optin_checkbox_label_row']").slideUp();
        }
    }

    rcp.init = function () {
        $("select[name*='mailoptin_rcp_subscribe_method']").change(rcp.subscribe_member_handler).change();
        $(document).on('change', "select[name*='mailoptin_rcp_integration_connections']", rcp.connection_service_handler);
        $(document).on('change', "select[name*='mailoptin_rcp_integration_lists']", rcp.connection_lists_handler);
    }

    $(window).on('load', rcp.init);

})(jQuery);