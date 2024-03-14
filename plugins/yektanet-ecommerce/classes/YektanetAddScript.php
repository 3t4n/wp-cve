<?php

class YektanetAddScript
{
    public function __construct()
    {
        add_action('wp_head', function () {
            ?>
            <script>
                !function (t, e, n) {
                    const d = new Date();
                    d.setTime(d.getTime() + (4 * 24 * 60 * 60 * 1000));
                    let expires = "expires=" + d.toUTCString();
                    t.yektanetAnalyticsObject = n
                    t[n] = t[n] || function () {
                        t[n].q.push(arguments)
                    }
                    t[n].q = t[n].q || [];
                    var a = new Date
                    var app_id = '<?php echo get_option('yektanet_app_id', true); ?>';
                    r = a.getFullYear().toString() + "0" + a.getMonth() + "0" + a.getDate() + "0" + a.getHours()
                    c = e.getElementsByTagName("script")[0]
                    s = e.createElement("script");
                    s.id = "ua-script-" + app_id;
                    s.dataset.analyticsobject = n;
                    s.async = 1;
                    s.type = "text/javascript";
                    s.src = "https://cdn.yektanet.com/rg_woebegone/scripts_v4/" + app_id + "/complete.js?v=" + r
                    c.parentNode.insertBefore(s, c)
                }(window, document, "yektanet");
            </script>
            <?php
        });

        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_script( 'yektanet_highcharts_main', 'https://code.highcharts.com/highcharts.js', array(), '1.1.4' );
            wp_enqueue_script( 'yektanet_highcharts_exporting', 'https://code.highcharts.com/modules/exporting.js', array(), '1.1.4' );
            wp_enqueue_script( 'yektanet_highcharts_export_data', 'https://code.highcharts.com/modules/export-data.js', array(), '1.1.4' );
            wp_enqueue_script( 'yektanet_highcharts_accessibility', 'https://code.highcharts.com/modules/accessibility.js', array(), '1.1.4' );
            wp_enqueue_script( 'yektanet_ajax_script', plugin_dir_url(__DIR__) . '/assets/js/ajax.js', array('jquery'), '1.1.4' );
        });
    }
}