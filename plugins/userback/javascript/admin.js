jQuery(document).ready(function($) {
    Userback_Admin = {
        _default: {
            is_active:      0,
            role:           [0],
            page:           [0],
            access_token:   ''
        },


        init: function() {
            this.bindEvents();

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            // fetch data
            $.post(ajaxurl, {action: 'get_userback'}, $.proxy(function(response) {
                data = $.extend({}, this._default, response.data);

                this.populatePageData(response.page);
                this.setDefaultValue(data);
            }, this));
        },

        bindEvents: function() {
            $('form.setting').on('submit', $.proxy(this.save, this));
        },

        populatePageData: function(data) {
            var select = $('[name="rp-page"]');

            $.each(data, function(index, page) {
                $('<option>').val(page.ID).text(page.post_title || '(no title)').appendTo(select);
            });
        },

        setDefaultValue: function(data) {
            data = $.extend({}, this._default, data);

            $('[name="rp-is-active"]').prop('checked', data.is_active);
            $('[name="rp-role"]').val(data.role);
            $('[name="rp-page"]').val(data.page);
            $('[name="rp-access-token"]').val(data.access_token);
        },

        save: function(e) {
            e.preventDefault();

            var data = this.getSettings();

            // trim
            data.access_token = $.trim(data.access_token);

            // CSRF protection
            var csrf_token = $('[name="userback_plugin_nonce"]').val();

            $('[name="rp-access-token"]').val(data.access_token);

            $('#save').prop('disabled', true);

            $('.save-success').remove();

            $.post(ajaxurl, {action: 'save_userback', data: data, csrf_token: csrf_token}, function(response) {
                $('#save').prop('disabled', false);
                $('<span>').addClass('save-success').text('Saved!').insertAfter($('#save'));
            });
        },

        getSettings: function() {
            return {
                is_active      : $('[name="rp-is-active"]').prop('checked') ? 1 : 0,
                role           : !$('[name="rp-role"]').val() || $.inArray(0, $('[name="rp-role"]').val()) !== -1 ? [0] : $('[name="rp-role"]').val(),
                page           : !$('[name="rp-page"]').val() || $.inArray(0, $('[name="rp-page"]').val()) !== -1 ? [0] : $('[name="rp-page"]').val(),
                access_token   : $('[name="rp-access-token"]').val() ? $('[name="rp-access-token"]').val().replace(/['";']/g, '') : ''
            };
        }
    };

    Userback_Admin.init();
});