(function ($) {
    "use strict";

    var gwp = {};

    gwp.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + '/images/spinner.gif">');
        $(placement).after(spinner_html);
    }

    gwp.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    }

    gwp.connection_service_handler = function (_this) {
        var self = _this, data, result;

        $('.mailoptin_gwp_email_list').empty();
        $('.mailoptin_gwp_custom_fields_tags').empty();

        var connection = $(self).val();

        gwp.add_spinner('#mo_gwp_form_metabox .postbox-header h2');
        if (connection === '') {
            gwp.remove_spinner();
            return;
        }

        data = {
            action: 'mo_gwp_fetch_lists',
            nonce: moGwp.nonce,
            connection: connection,
            form_id: mailoptin_globals.gwp_form_id,
        }
        ;

        $.post(moGwp.ajax_url, data, function (response) {
            gwp.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_gwp_email_list').html(result);
                gwp.connection_email_list_handler($("select[name='mailoptinGWPSelectList']"))
            }

        });
    }

    gwp.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinGWPSelectIntegration']").val();
        $('.mailoptin_gwp_custom_fields_tags').empty();

        var connection_email_list = $(self).val();

        gwp.remove_spinner();
        gwp.add_spinner('#mo_gwp_download_metabox .postbox-header h2');

        data = {
            action: 'mo_gwp_fetch_custom_fields',
            nonce: moGwp.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
            form_id: mailoptin_globals.gwp_form_id
        }

        $.post(moGwp.ajax_url, data, function (response) {
            gwp.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_gwp_custom_fields_tags').html(result);
            }

        });
    };

    gwp.init = function () {
        $("select[name='mailoptinGWPSelectIntegration']").change(function () {
            gwp.connection_service_handler(this);
        }).change();

        $(document).on('change', "select[name='mailoptinGWPSelectList']", function () {
            gwp.connection_email_list_handler(this)
        });
    }

    $(window).on('load', gwp.init);

})(jQuery);