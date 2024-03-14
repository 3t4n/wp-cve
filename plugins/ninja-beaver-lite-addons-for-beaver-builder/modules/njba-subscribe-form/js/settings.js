(function ($) {
    FLBuilder.registerModuleHelper('njba-subscribe-form', {
        rules: {
            button_text: {
                required: true
            },
            button_font_size: {
                required: true,
                number: true
            },
            button_padding: {
                required: true,
                number: true
            },
            button_border_radius: {
                required: true,
                number: true
            },
            service: {
                required: true
            }
        },
        init: function () {
            const form = $('.fl-builder-settings'),
                action = form.find('select[name=success_action]');
            this._actionChanged();
            action.on('change', this._actionChanged);
            this._typeChanged();
            $('.fl-builder-settings select[name="layout"]').on('change', this._typeChanged);
        },
        submit: function () {
            const form = $('.fl-builder-settings'),
                service = form.find('.fl-builder-service-select'),
                serviceVal = service.val(),
                account = form.find('.fl-builder-service-account-select'),
                list = form.find('.fl-builder-service-list-select');
            if (0 === account.length) {
                FLBuilder.alert(FLBuilderStrings.subscriptionModuleConnectError);
                return false;
            } else if ('' == account.val() || 'add_new_account' == account.val()) {
                FLBuilder.alert(FLBuilderStrings.subscriptionModuleAccountError);
                return false;
            } else if ((0 === list.length || '' == list.val()) && 'email-address' != serviceVal && 'sendy' != serviceVal) {
                if ('drip' == serviceVal || 'hatchbuck' == serviceVal) {
                    FLBuilder.alert(FLBuilderStrings.subscriptionModuleTagsError);
                } else {
                    FLBuilder.alert(FLBuilderStrings.subscriptionModuleListError);
                }
                return false;
            }
            return true;
        },
        _actionChanged: function () {
            const form = $('.fl-builder-settings'),
                action = form.find('select[name=success_action]').val(),
                url = form.find('input[name=success_url]');
            url.rules('remove');
            if ('redirect' == action) {
                url.rules('add', { required: true });
            }
        },
        _typeChanged: function () {
            const selector = '#fl-builder-settings-section-form_bg_setting, #fl-builder-settings-section-form_box_shadow, #fl-field-box_border_radius, #fl-field-form_border_radius';
            if ($('.fl-builder-settings select[name="box_type"]').val() === 'welcome_gate') {
                $(selector).hide();
            } else {
                $(selector).show();
            }
        },
    });
})(jQuery);
