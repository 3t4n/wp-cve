(function (api, $) {
    "use strict";

    var mo = {};

    mo.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + 'images/spinner.gif">');
        $(placement).after(spinner_html);
    };

    mo.remove_spinner = function () {
        $('.mo-spinner.fetch-email-list').remove();
    };

    mo.hide_show_segment_select_chosen = function () {
        var segments_select_obj = $("select[data-customize-setting-link*='MoosendConnect_segments'] option");

        if (segments_select_obj.length === 0) {
            $("div#customize-theme-controls li[id*='MoosendConnect_segments']").hide()
        }
    };

    mo.fetch_segments = function () {
        $("select[data-customize-setting-link*='connection_email_list']").change(function (e) {
            var list_id = this.value;

            if ($("select[data-customize-setting-link*='connection_service']").val() !== 'MoosendConnect') return;

            $("div#customize-theme-controls li[id*='MoosendConnect_segments']").hide();

            mo.add_spinner(this);

            $.post(ajaxurl, {
                action: 'mailoptin_customizer_fetch_moosend_segments',
                list_id: list_id,
                security: $("input[data-customize-setting-link*='[ajax_nonce]']").val()
                },
                function (response) {
                    if (_.isObject(response) && 'success' in response && 'data' in response) {
                        var moosend_segments_choosen = $("select[data-customize-setting-link*='MoosendConnect_segments']");

                        moosend_segments_choosen.html(response.data);

                        moosend_segments_choosen.trigger('chosen:updated');

                        if (response.data !== '') {
                            $("div#customize-theme-controls li[id*='MoosendConnect_segments']").show();
                        }

                        mo.remove_spinner();
                    }
                }
            );
        });
    }


    $(window).on('load', function () {
        mo.hide_show_segment_select_chosen();
        mo.fetch_segments();
    });

})(wp.customize, jQuery);