(function ($) {
    "use strict";

    var tutorlms = {};

    tutorlms.connection_service_handler = function () {
        $("tr[id*='mailoptin_tutorlms_integration_lists']").slideUp();
        $("tr[id*='mailoptin_tutorlms_double_optin']").slideUp();
        $("tr[id*='mailoptin_tutorlms_mapped_fields']").slideUp();
        $("tr[id*='mailoptin_tutorlms_select_tags']").slideUp();
        $("tr[id*='mailoptin_tutorlms_text_tags']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    tutorlms.connection_lists_handler = function () {
        $("tr[id*='mailoptin_tutorlms_mapped_fields']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    tutorlms.subscribe_student_handler = function () {
        var subscribe_students = $(this).val();

        if(subscribe_students === 'yes') {
            $("tr[id*='mailoptin_tutorlms_field_label']").slideDown();
            $("tr[id*='mailoptin_tutorlms_checkbox_default']").slideDown();
            $("tr[id*='mailoptin_tutorlms_checkbox_location']").slideDown();
        } else {
            $("tr[id*='mailoptin_tutorlms_field_label']").slideUp();
            $("tr[id*='mailoptin_tutorlms_checkbox_default']").slideUp();
            $("tr[id*='mailoptin_tutorlms_checkbox_location']").slideUp();
        }
    }

    tutorlms.init = function () {
        $("select[name*='mailoptin_tutorlms_subscribe_students']").change(tutorlms.subscribe_student_handler).change();
        $(document).on('change', "select[name*='mailoptin_tutorlms_integration_connections']", tutorlms.connection_service_handler);
        $(document).on('change', "select[name*='mailoptin_tutorlms_integration_lists']", tutorlms.connection_lists_handler);
    }


    $(window).on('load', tutorlms.init);

})(jQuery);