(function ($) {
    "use strict";

    var learndash = {};

    learndash.connection_service_handler = function () {
        $("tr[id*='mailoptin_learndash_integration_lists']").slideUp();
        $("tr[id*='mailoptin_learndash_double_optin']").slideUp();
        $("tr[id*='mailoptin_learndash_mapped_fields']").slideUp();
        $("tr[id*='mailoptin_learndash_select_tags']").slideUp();
        $("tr[id*='mailoptin_learndash_text_tags']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    learndash.connection_lists_handler = function () {
        $("tr[id*='mailoptin_learndash_mapped_fields']").slideUp();
        $("input[name='save_mailoptin_settings']").click();
    }

    learndash.subscribe_student_handler = function () {
        var subscribe_students = $(this).val();

        if(subscribe_students === 'yes') {
            $("tr[id*='mailoptin_learndash_field_label']").slideDown();
            $("tr[id*='mailoptin_learndash_checkbox_default']").slideDown();
            $("tr[id*='mailoptin_learndash_checkbox_location']").slideDown();
        } else {
            $("tr[id*='mailoptin_learndash_field_label']").slideUp();
            $("tr[id*='mailoptin_learndash_checkbox_default']").slideUp();
            $("tr[id*='mailoptin_learndash_checkbox_location']").slideUp();
        }
    }

    learndash.init = function () {
        $("select[name*='mailoptin_learndash_subscribe_students']").change(learndash.subscribe_student_handler).change();
        $(document).on('change', "select[name*='mailoptin_learndash_integration_connections']", learndash.connection_service_handler);
        $(document).on('change', "select[name*='mailoptin_learndash_integration_lists']", learndash.connection_lists_handler);
    }


    $(window).on('load', learndash.init);

})(jQuery);