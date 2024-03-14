(function ($) {
    $(document).ready(function () {
        //Show/Hide the Key/Value Group
        $("#nsa_wpfbp_metabox_event").change(function () {
            if ($(this).val() !== "") {
                $("#nsa_wpfbp_metabox_event_values_repeat").show();

            } else {
                $("#nsa_wpfbp_metabox_event_values_repeat").hide();
            }
        });
        $("#nsa_wpfbp_metabox_event").change();




        //Show/Hide Category Tracking group
        $("[name='nsa_wpfbp_category_tracking_track_all_terms']").change(function () {
            if ($(this).val() == 'false') {
                $('#nsa_wpfbp_category_tracking_term_tracking_repeat').show();

            } else {
                $('#nsa_wpfbp_category_tracking_term_tracking_repeat').hide();

            }
        });
        if (typeof $("[name='nsa_wpfbp_category_tracking_track_all_terms']:checked").val() === "undefined") {
            $("[name='nsa_wpfbp_category_tracking_track_all_terms'][value='true']").attr("checked", "checked");
        }
        $("[name='nsa_wpfbp_category_tracking_track_all_terms']:checked").change();




        //Show/Hide Key Tracking group
        $("[name='nsa_wpfbp_key_tracking_track_all_keys']").change(function () {
            if ($(this).val() == 'false') {
                $('#nsa_wpfbp_key_tracking_key_tracking_repeat').show();

            } else {
                $('#nsa_wpfbp_key_tracking_key_tracking_repeat').hide();

            }
        });
        if (typeof $("[name='nsa_wpfbp_key_tracking_track_all_keys']:checked").val() === "undefined") {
            $("[name='nsa_wpfbp_key_tracking_track_all_keys'][value='true']").attr("checked", "checked");
        }
        $("[name='nsa_wpfbp_key_tracking_track_all_keys']:checked").change();




        //Validate any input with attribute 'data-validation-regexp'
        $('input[data-validation-regexp]').on("change", function () {
            var exp = $(this).attr('data-validation-regexp').split("/");
            var re = new RegExp(exp[1], exp[2]);

            if (re.test($(this).val()) === false) {
                $('#errorfor-' + $(this).attr("id")).removeClass("hidden");

            } else {
                $('#errorfor-' + $(this).attr("id")).addClass("hidden");

            }

        });


        $('input[data-validation-regexp]').after(function () {
            return "<span class='nsaerror hidden' id='errorfor-"+$(this).attr("id")+"'>Invalid value.</span>"
        });



        //Update Exclusion Checkbox Values
        $("[name='nsa_wpfbp_general_tracking_exclusion[]']").change(function(e) {
            if (jQuery(e.currentTarget).val() == 'none' && jQuery("[name='nsa_wpfbp_general_tracking_exclusion[]'][value='none']:checked").length > 0) {
                $("[name='nsa_wpfbp_general_tracking_exclusion[]'][value!='none']").removeAttr('checked');
            }
            if ($("[name='nsa_wpfbp_general_tracking_exclusion[]'][value!='none']:checked").length > 0) $("[name='nsa_wpfbp_general_tracking_exclusion[]'][value='none']").removeAttr('checked');
        });
    })
})(jQuery);