jQuery(function() {
    var ism_Disabled = jQuery('#ism_scaleEnabled_1'); // second option: custom size
    var ism_Custom = jQuery('#ism_scaleEnabled_2'); // second option: custom size
    var ism_Default = jQuery('#ism_scaleEnabled_3'); // second option: custom size
    var ism_CustomValue = jQuery("#ism_customSize");
    var ism_Value = jQuery("#ism_scaleEnabled_2").prop("defaultValue");

    var ism_update = function() {
        if (ism_Disabled.prop('checked')) {
            ism_CustomValue.prop("value", "");
            ism_CustomValue.prop("placeholder", "Unlimited");
            ism_CustomValue.prop("disabled", true);
        }
        if (ism_Custom.prop('checked')) {
            ism_CustomValue.prop("placeholder", ism_CustomValue.val());
            ism_CustomValue.prop("value", ism_CustomValue.val());
            ism_CustomValue.prop("disabled", false);
        }
        if (ism_Default.prop('checked')) {
            ism_CustomValue.prop("placeholder", "2560");
            ism_CustomValue.prop("value", "2560");
            ism_CustomValue.prop("disabled", true);
        }
    }
    jQuery('table.form-table fieldset').change(function() {
        ism_update();
    });
    ism_update();
});