jQuery(document).ready(function() {
    if (typeof(elementor) === "undefined") {
        return;
    }
    elementor.hooks.addAction('panel/open_editor/widget/form', function(panel, model, view) {
        var checkbox_existing = "false";
        model.attributes.settings.attributes.form_fields.each(function(e) {
            if (e.attributes.field_type === "sg-email-marketing-checkbox") {
                checkbox_existing = "true";
            }
        });
        model.attributes.settings.controls.sgwpmail_elementor_forms_checkbox_enabled_bool.value = checkbox_existing;
        model.attributes.settings.attributes.sgwpmail_elementor_forms_checkbox_enabled_bool = checkbox_existing;
    });
    elementor.channels.editor.on('section:activated', (e, panelView) => {
        if (e !== "section_sgwpmail") {
            return;
        }

        fields = elementor.getCurrentElement().model.attributes.settings.attributes.form_fields;
        var checkbox_existing = "false";

        fields.each(function(e) {
            if (e.attributes.field_type === "sg-email-marketing-checkbox") {
                checkbox_existing = "true";
            }
        })
        elementor.getCurrentElement().model.attributes.settings.attributes.sgwpmail_elementor_forms_checkbox_enabled_bool = checkbox_existing;
        elementor.getCurrentElement().model.attributes.settings.controls.sgwpmail_elementor_forms_checkbox_enabled_bool.value = checkbox_existing;

        if (checkbox_existing !== "false") {
            panelView.$childViewContainer.find('.elementor-control-sgwpmail_elementor_forms_checkbox_enabled').show()
            panelView.$childViewContainer.find('.elementor-control-sgwpmail_elementor_forms_checkbox_disabled').hide()
        } else {
            panelView.$childViewContainer.find('.elementor-control-sgwpmail_elementor_forms_checkbox_disabled').show()
            panelView.$childViewContainer.find('.elementor-control-sgwpmail_elementor_forms_checkbox_enabled').hide()
        }
    });
})