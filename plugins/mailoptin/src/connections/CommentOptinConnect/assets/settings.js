(function ($) {
    "use strict";

    var comment_optin = {};

    comment_optin.connection_service_handler = function () {
        $("tr[id*='mailoptin_comment_optin_integration_lists']").slideUp();
        $("tr[id*='mailoptin_comment_optin_double_optin']").slideUp();
        $("tr[id*='mailoptin_comment_optin_mapped_fields']").slideUp();
        $("tr[id*='mailoptin_comment_optin_select_tags']").slideUp();
        $("tr[id*='mailoptin_comment_optin_text_tags']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    comment_optin.connection_lists_handler = function () {
        $("tr[id*='mailoptin_comment_optin_mapped_fields']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    comment_optin.subscribe_user_handler = function () {
        var subscribe_users = $(this).val();

        if (subscribe_users === 'yes') {
            $("tr[id*='mailoptin_comment_optin_subscription_registration_message']").slideDown();
        } else {
            $("tr[id*='mailoptin_comment_optin_subscription_registration_message']").slideUp();
        }
    }

    comment_optin.init = function () {
        $("select[name*='mailoptin_comment_optin_subscribe_users']").change(comment_optin.subscribe_user_handler).change();
        $(document).on('change', "select[name*='mailoptin_comment_optin_integration_connections']", comment_optin.connection_service_handler);
        $(document).on('change', "select[name*='mailoptin_comment_optin_integration_lists']", comment_optin.connection_lists_handler);
    }


    $(window).on('load', comment_optin.init);

})(jQuery);