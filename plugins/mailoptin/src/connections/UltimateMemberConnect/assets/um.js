(function ($) {
    "use strict";

    var um = {};

    um.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + '/images/spinner.gif">');
        $(placement).after(spinner_html);
    };

    um.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    };

    um.connection_service_handler = function (_this) {
        var self = _this, data, result;

        $('.mailoptin_um_email_list').empty();
        $('.mailoptin_um_custom_fields_tags').empty();

        var connection = $(self).val();

        um.add_spinner('#mo_um_form_metabox .postbox-header h2');
        if (connection === '') {
            um.remove_spinner();
            return;
        }

        data = {
            action: 'mo_um_fetch_lists',
            nonce: moUm.nonce,
            connection: connection,
            form_id: mailoptin_globals.um_form_id,
        }
        ;

        $.post(moUm.ajax_url, data, function (response) {
            um.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_um_email_list').html(result);
                um.connection_email_list_handler($("select[name='mailoptinUMSelectList']"))
            }

        });
    };

    um.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinUMSelectIntegration']").val();
        $('.mailoptin_um_custom_fields_tags').empty();

        var connection_email_list = $(self).val();

        um.remove_spinner();
        um.add_spinner('#mo_um_form_metabox .postbox-header h2');

        data = {
            action: 'mo_um_fetch_custom_fields',
            nonce: moUm.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
            form_id: mailoptin_globals.um_form_id
        };

        $.post(moUm.ajax_url, data, function (response) {
            um.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_um_custom_fields_tags').html(result);
            }

        });
    };

    um.init = function () {
        $("select[name='mailoptinUMSelectIntegration']").change(function () {
            um.connection_service_handler(this);
        }).change();

        $(document).on('change', "select[name='mailoptinUMSelectList']", function () {
            um.connection_email_list_handler(this)
        });
    };

    $(window).on('load', um.init);

})(jQuery);