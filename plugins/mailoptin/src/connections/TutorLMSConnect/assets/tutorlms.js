(function ($) {
    "use strict";

    var tutorlms = {};

    tutorlms.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + '/images/spinner.gif">');
        $(placement).after(spinner_html);
    };

    tutorlms.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    };

    tutorlms.connection_service_handler = function (_this) {
        var self = _this, data, result;

        $('.mailoptin_tutorlms_email_list').empty();
        $('.mailoptin_tutorlms_custom_fields_tags').empty();

        var connection = $(self).val();

        tutorlms.add_spinner('#mo_tutorlms_course_metabox .postbox-header h2');
        if (connection === '') {
            tutorlms.remove_spinner();
            return;
        }

        data = {
            action: 'mo_tutorlms_fetch_lists',
            nonce: moTutorLMS.nonce,
            connection: connection,
            course_id: mailoptin_globals.tutorlms_course_id,
        };

        $.post(moTutorLMS.ajax_url, data, function (response) {
            tutorlms.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_tutorlms_email_list').html(result);
                tutorlms.connection_email_list_handler($("select[name='mailoptinTutorLMSSelectList']"))
            }

        });
    };

    tutorlms.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinTutorLMSSelectIntegration']").val();
        $('.mailoptin_tutorlms_custom_fields_tags').empty();

        var connection_email_list = $(self).val();

        tutorlms.remove_spinner();
        tutorlms.add_spinner('#mo_tutorlms_course_metabox .postbox-header h2');

        data = {
            action: 'mo_tutorlms_fetch_custom_fields',
            nonce: moTutorLMS.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
            course_id: mailoptin_globals.tutorlms_course_id
        }

        $.post(moTutorLMS.ajax_url, data, function (response) {
            tutorlms.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_tutorlms_custom_fields_tags').html(result);
            }

        });
    };


    tutorlms.init = function () {
        $("select[name='mailoptinTutorLMSSelectIntegration']").change(function () {
            tutorlms.connection_service_handler(this);
        }).change();

        $(document).on('change', "select[name='mailoptinTutorLMSSelectList']", function () {
            tutorlms.connection_email_list_handler(this)
        });
    };

    $(window).on('load', tutorlms.init);

})(jQuery);