(function ($) {
    "use strict";

    var edd = {};

    edd.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + '/images/spinner.gif">');
        $(placement).after(spinner_html);
    }

    edd.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    }

    edd.connection_service_handler = function (_this) {
        var self = _this, data, result;

        $('.mailoptin_edd_email_list').empty();
        $('.mailoptin_edd_custom_fields_tags').empty();

        var connection = $(self).val();

        edd.add_spinner('#mo_edd_download_metabox .postbox-header h2');
        if (connection === '') {
            edd.remove_spinner();
            return;
        }

        data = {
            action: 'mo_edd_fetch_lists',
            nonce: moEdd.nonce,
            connection: connection,
            download_id: mailoptin_globals.edd_download_id,
        }
        ;

        $.post(moEdd.ajax_url, data, function (response) {
            edd.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_edd_email_list').html(result);
                edd.connection_email_list_handler($("select[name='mailoptinEDDSelectList']"))
            }

        });
    }

    edd.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinEDDSelectIntegration']").val();
        $('.mailoptin_edd_custom_fields_tags').empty();

        var connection_email_list = $(self).val();

        edd.remove_spinner();
        edd.add_spinner('#mo_edd_download_metabox .postbox-header h2');

        data = {
            action: 'mo_edd_fetch_custom_fields',
            nonce: moEdd.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
            download_id: mailoptin_globals.edd_download_id
        }

        $.post(moEdd.ajax_url, data, function (response) {
            edd.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_edd_custom_fields_tags').html(result);
            }

        });
    };

    edd.init = function () {
        $("select[name='mailoptinEDDSelectIntegration']").change(function () {
            edd.connection_service_handler(this);
        }).change();

        $(document).on('change', "select[name='mailoptinEDDSelectList']", function () {
            edd.connection_email_list_handler(this)
        });
    }

    $(window).on('load', edd.init);

})(jQuery);