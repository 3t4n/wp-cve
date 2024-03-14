<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
?>
<!--WPMS Google Analytics classic tracking js code - https://wordpress.org/plugins/wp-meta-seo/ -->
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?php echo esc_html($this->gaDisconnect['wpms_gg_service_tracking_id']); ?>']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        if ('https:' === document.location.protocol) {
            ga.src = 'https://ssl.google-analytics.com/ga.js';
        } else {
            ga.src = 'http://www.google-analytics.com/ga.js';
        }
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
</script>
<!--End WPMS Google Analytics classic tracking js code-->