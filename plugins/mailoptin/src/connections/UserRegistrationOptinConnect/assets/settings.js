(function ($) {
    "use strict";

    var user_registration_optin = {};

    user_registration_optin.connection_service_handler = function () {
        $("tr[id*='mailoptin_user_registration_optin_integration_lists']").slideUp();
        $("tr[id*='mailoptin_user_registration_optin_double_optin']").slideUp();
        $("tr[id*='mailoptin_user_registration_optin_mapped_fields']").slideUp();
        $("tr[id*='mailoptin_user_registration_optin_select_tags']").slideUp();
        $("tr[id*='mailoptin_user_registration_optin_text_tags']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    user_registration_optin.connection_lists_handler = function () {
        $("tr[id*='mailoptin_user_registration_optin_mapped_fields']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    user_registration_optin.subscribe_user_handler = function () {
        var subscribe_users = $(this).val();

        if(subscribe_users === 'yes') {
            $("tr[id*='mailoptin_user_registration_optin_subscription_registration_message']").slideDown();
        } else {
            $("tr[id*='mailoptin_user_registration_optin_subscription_registration_message']").slideUp();
        }
    }

    user_registration_optin.init = function () {
        $("select[name*='mailoptin_user_registration_optin_subscribe_users']").change(user_registration_optin.subscribe_user_handler).change();
        $(document).on('change', "select[name*='mailoptin_user_registration_optin_integration_connections']", user_registration_optin.connection_service_handler);
        $(document).on('change', "select[name*='mailoptin_user_registration_optin_integration_lists']", user_registration_optin.connection_lists_handler);
    }


    $(window).on('load', user_registration_optin.init);

})(jQuery);