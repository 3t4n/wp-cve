;(function (global, $) {
    $(document).ready(
        function ($) {
            let activeRequest = false;
            let completed = false;
            setTimeout(ajax, 10);

            function ajax() {
                if ((activeRequest) || (iub_js_vars['radar_status'] === 'completed')) {
                    return;
                }
                activeRequest = true;
                $.ajax(
                    {
                        type: "post",
                        dataType: "json",
                        url: iub_js_vars['site_url'] + "/wp-admin/admin-ajax.php",
                        data: {
                            action: "radar_percentage_reload",
                            iub_nonce: iub_js_vars['iub_radar_percentage_reload_nonce']
                        },
                        success: function (result) {
                            if (result.status === 'timeout') {
                                activeRequest = false;
                                setTimeout(ajax, (parseInt(result.data) * 1000));
                                return
                            }

                            if (result.status === 'progress') {
                                activeRequest = false;
                                completed = true;
                                setTimeout(ajax, 10000);
                                return
                            }

                            if (result.status === 'error') {
                                if (typeof handleAlertDiv === 'function') {
                                    // Call the function
                                    handleAlertDiv?.(result.message);
                                }
                            }

                            let action = "frontpage_main_box";
                            let iub_nonce = iub_js_vars['iub_frontpage_main_box_nonce'];
                            let target_div = $('#frontpage-main-box')


                            if ($('#iubenda-compliance-status').length) {
                                action = "dashboard_compliance";
                                iub_nonce = iub_js_vars['iub_dashboard_compliance_nonce'];
                                target_div = $('#iubenda-compliance-status').find('.inside');
                            }
                            if (result.status === 'complete' && completed) {
                                $.ajax(
                                    {
                                        type: "post",
                                        url: iub_js_vars['site_url'] + "/wp-admin/admin-ajax.php",
                                        data: {
                                            action: action,
                                            iub_nonce: iub_nonce
                                        },
                                        success: function (response) {
                                            target_div.html(response)
                                            document.querySelectorAll(".circularBar").forEach(
                                                function (el) {
                                                    $(el).attr('data-perc', result.data.percentage);
                                                    circularBar(el);
                                                }
                                            );
                                        },
                                    }
                                );
                            }
                        }
                    }
                )
            }
        }
    );
}(window, jQuery));
