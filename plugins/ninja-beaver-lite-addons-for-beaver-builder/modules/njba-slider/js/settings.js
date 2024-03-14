(function ($) {
    FLBuilder.registerModuleHelper('njba-slider', {
        rules: {
            'photo': {
                required: true
            }
        },
        init: function () {
        }
    });
    FLBuilder.registerModuleHelper('njba_sliderspanel_form', {
        init: function () {
            let form = $('.fl-builder-settings'),
                img_src = $('.fl-builder-settings-section').find('#fl-field-photo img').attr('src'),
                n = img_src.lastIndexOf('-150x150');
            img_src = img_src.slice(0, n) + img_src.slice(n).replace('-150x150', '');
            form.find('.njba-draggable-section').append('<img src="' + img_src + '" />');
        },

    });
})(jQuery);

