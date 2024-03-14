(function ($) {
    "use strict";

    var memberpress = {};

    memberpress.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + '/images/spinner.gif">');
        $(placement).after(spinner_html);
    }

    memberpress.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    }

    memberpress.connection_service_handler = function (_this) {
        var self = _this, data, result;

        $('.mailoptin_memberpress_email_list').empty();
        $('.mailoptin_memberpress_custom_fields_tags').empty();

        var connection = $(self).val();

        memberpress.add_spinner(self);
        if (connection === '') {
            memberpress.remove_spinner();
            return;
        }

        data = {
            action: 'mo_memberpress_settings_fetch_lists',
            nonce: moMemberPress.nonce,
            connection: connection,
        }
        ;

        $.post(moMemberPress.ajax_url, data, function (response) {
            memberpress.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_memberpress_email_list').html(result);
                memberpress.connection_email_list_handler($("select[name='mailoptinMemberPressSelectList']"))
            }

        });
    }

    memberpress.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinMemberPressSelectIntegration']").val();
        $('.mailoptin_memberpress_custom_fields_tags').empty();

        var connection_email_list = $(self).val();

        memberpress.remove_spinner();
        memberpress.add_spinner('#mo_memberpress_metabox .postbox-header h2');

        data = {
            action: 'mo_memberpress_settings_fetch_custom_fields',
            nonce: moMemberPress.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
        }

        $.post(moMemberPress.ajax_url, data, function (response) {
            memberpress.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_memberpress_custom_fields_tags').html(result);
            }

        });
    };

    memberpress.subscribe_members_handler = function () {
        var subscribe_member = $(this).val();

        if (subscribe_member === 'yes') {
            $('#mailoptin-memberpress-optin-field-label').slideDown();
            $('#mailoptin-memberpress-optin-checkbox-default').slideDown();
        } else {
            $('#mailoptin-memberpress-optin-field-label').slideUp();
            $('#mailoptin-memberpress-optin-checkbox-default').slideUp();
        }
    }

    memberpress.is_mailoptin_enabled = function () {
        if ($('input[name="mailoptinMemberPressEnabled"]').is(":checked")) {
            $('div#mo_memberpress_hidden_area').show();
        } else {
            $('div#mo_memberpress_hidden_area').hide();
        }

        $('input[name="mailoptinMemberPressEnabled"]').on('click', function () {
            $('div#mo_memberpress_hidden_area').slideToggle('fast');
        })
    }

    memberpress.init = function () {
        memberpress.is_mailoptin_enabled();
        $("#mo_memberpress_hidden_area select[name='mailoptinMemberPressSubscribeMembers']").change(memberpress.subscribe_members_handler).change();
        $("#mo_memberpress_hidden_area select[name='mailoptinMemberPressSelectIntegration']").change(function () {
            memberpress.connection_service_handler(this);
        }).change();

        $(document).on('change', "#mo_memberpress_hidden_area select[name='mailoptinMemberPressSelectList']", function () {
            memberpress.connection_email_list_handler(this)
        });
    }

    $(window).on('load', memberpress.init);

})(jQuery);