(function ($) {
    "use strict";

    var woo = {};

    woo.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + 'images/spinner.gif">');
        $(placement).after(spinner_html);
    };

    woo.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    };

    woo.connection_service_handler = function (_this) {
        var self = _this, data, result;

        $('.mailoptin_woomem_email_list').empty();
        $('.mailoptin_woomem_custom_fields_tags').empty();

        var connection = $(self).val();

        woo.add_spinner(self);

        if (connection === '') {
            woo.remove_spinner();
            return;
        }

        data = {
            action: 'mo_woomem_fetch_lists',
            nonce: moWooMem.nonce,
            connection: connection,
            plan_id: mailoptin_globals.woo_mem_plan_id
        };

        $.post(moWooMem.ajax_url, data, function (response) {
            woo.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_woomem_email_list').html(result);
                woo.connection_email_list_handler($("select[name='mailoptinWooMemSelectList']"))
            }
        });
    };

    woo.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinWooMemSelectIntegration']").val();
        $('.mailoptin_woomem_custom_fields_tags').empty();

        var connection_email_list = $(self).val();
        woo.remove_spinner();
        woo.add_spinner(self);

        data = {
            action: 'mo_woomem_fetch_custom_fields',
            nonce: moWooMem.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
            plan_id: mailoptin_globals.woo_mem_plan_id
        };

        $.post(moWooMem.ajax_url, data, function (response) {
            woo.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_woomem_custom_fields_tags').html(result);
            }

        });
    };

    woo.init = function () {
        $("select[name='mailoptinWooMemSelectIntegration']").change(function () {
            woo.connection_service_handler(this);
        }).change();

        $(document).on('change', "select[name='mailoptinWooMemSelectList']", function () {
            woo.connection_email_list_handler(this)
        });
    };

    $(window).on('load', woo.init);

})(jQuery);