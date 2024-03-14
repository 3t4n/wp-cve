(function ($) {
    "use strict";

    var llms = {};

    llms.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + '/images/spinner.gif">');
        $(placement).after(spinner_html);
    }

    llms.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    }

    llms.connection_service_handler = function (_this) {
        var self = _this, data, result;

        $('.mailoptin_llms_email_list').empty();
        $('.mailoptin_llms_custom_fields_tags').empty();

        var connection = $(self).val();

        llms.add_spinner('#mo_llms_sidebar_metabox .postbox-header h2');
        if (connection === '') {
            llms.remove_spinner();
            return;
        }

        data = {
            action: 'mo_llms_fetch_lists',
            nonce: moLLMS.nonce,
            connection: connection,
            post_id: mailoptin_globals.llms_post_id,
        };

        $.post(moLLMS.ajax_url, data, function (response) {
            llms.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_llms_email_list').html(result);
                llms.connection_email_list_handler($("select[name='mailoptinLLMSSelectList']"))
            }

        });
    }

    llms.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinLLMSSelectIntegration']").val();
        $('.mailoptin_llms_custom_fields_tags').empty();

        var connection_email_list = $(self).val();

        llms.remove_spinner();
        llms.add_spinner('#mo_llms_sidebar_metabox .postbox-header h2');

        data = {
            action: 'mo_llms_fetch_custom_fields',
            nonce: moLLMS.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
            post_id: mailoptin_globals.llms_post_id
        }

        $.post(moLLMS.ajax_url, data, function (response) {
            llms.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_llms_custom_fields_tags').html(result);
            }

        });
    };

    llms.init = function () {
        $("select[name='mailoptinLLMSSelectIntegration']").change(function () {
            llms.connection_service_handler(this);
        }).change();

        $(document).on('change', "select[name='mailoptinLLMSSelectList']", function () {
            llms.connection_email_list_handler(this)
        });
    }

    $(window).on('load', llms.init);

})(jQuery);