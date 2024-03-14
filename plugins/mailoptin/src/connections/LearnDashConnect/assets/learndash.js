(function ($) {
    "use strict";

    var learndash = {};

    learndash.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + '/images/spinner.gif">');
        $(placement).after(spinner_html);
    }

    learndash.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    }

    learndash.connection_service_handler = function(_this) {
        var self = _this, data, result;

        $('.mailoptin_learndash_email_list').empty();
        $('.mailoptin_learndash_custom_fields_tags').empty();

        var connection = $(self).val();

        learndash.add_spinner('#mo_learndash_course_metabox .postbox-header h2');
        if (connection === '') {
            learndash.remove_spinner();
            return;
        }

        data = {
            action: 'mo_learndash_fetch_lists',
            nonce: moLearnDash.nonce,
            connection: connection,
            course_id: mailoptin_globals.learndash_course_id,
        }
;

        $.post(moLearnDash.ajax_url, data, function (response) {
            learndash.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_learndash_email_list').html(result);
                learndash.connection_email_list_handler($("select[name='mailoptinLearnDashSelectList']"))
            }

        });
    }

    learndash.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinLearnDashSelectIntegration']").val();
        $('.mailoptin_learndash_custom_fields_tags').empty();

        var connection_email_list = $(self).val();

        learndash.remove_spinner();
        learndash.add_spinner('#mo_learndash_course_metabox .postbox-header h2');

        data = {
            action: 'mo_learndash_fetch_custom_fields',
            nonce: moLearnDash.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
            course_id: mailoptin_globals.learndash_course_id
        }

        $.post(moLearnDash.ajax_url, data, function (response) {
            learndash.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_learndash_custom_fields_tags').html(result);
            }

        });
    };


    learndash.init = function () {
        $("select[name='mailoptinLearnDashSelectIntegration']").change(function () {
            learndash.connection_service_handler(this);
        }).change();

        $(document).on('change', "select[name='mailoptinLearnDashSelectList']", function () {
            learndash.connection_email_list_handler(this)
        });
    }


    $(window).on('load', learndash.init);


})(jQuery);