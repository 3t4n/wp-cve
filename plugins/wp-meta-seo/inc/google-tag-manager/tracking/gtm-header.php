<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
?>
<!--WPMSGA Google Tag Manager Header - https://wordpress.org/plugins/wp-meta-seo/ -->
<script> (function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start':
                new Date().getTime(), event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer',
        '<?php echo esc_html($this->gaDisconnect['wpms_gg_service_tracking_id'], 'wp-meta-seo'); ?>');</script>
<!--End WPMSGA Google Tag Manager Header - https://wordpress.org/plugins/wp-meta-seo/ -->