jQuery(function ($) {

    const ecffw_form_builder = {
        
        init: function () {
            let fields = ecffwObject.fields;
            let options = ecffwObject.options;
            let formData = $("#ecffw-form-builder-json").text();

            let formBuilder = $("#ecffw-editor").formBuilder($.extend({fields, formData}, options));

            this.event_listeners(formBuilder);
        },

        event_listeners: function (formBuilder) {
            $(document).on("click", "#ecffw-save", function () {
                $("#ecffw-form-builder-json").text(formBuilder.formData);
            });
            document.addEventListener('fieldAdded', function () {
                $("#ecffw-form-builder-json").text(formBuilder.formData).trigger("change");
            });
            document.addEventListener('fieldRemoved', function () {
                $("#ecffw-form-builder-json").text(formBuilder.formData).trigger("change");
            });
        },
    }

    ecffw_form_builder.init();

});
