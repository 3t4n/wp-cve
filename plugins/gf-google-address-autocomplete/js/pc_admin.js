jQuery(document).ready(function ($) {
    $(document).bind(
        "gform_load_field_settings",
        function (event, field, form) {
            if ($("#pc_field_autocomplete_value").is(":checked")) {
                $(".gfautosingle_setting").show();
            } else {
                $(".gfautosingle_setting").hide();
            }
        }
    );

    $(document).on("change", "#pc_field_autocomplete_value", function () {
        $(".gfautosingle_setting").slideToggle();
    });
});
