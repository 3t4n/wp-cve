<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

$measure_id = $this->gaDisconnect['wpms_gg_service_tracking_id'];
//phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- add tracking script to header ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=
<?php echo esc_html($measure_id, 'wp-meta-seo') ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?php echo esc_html($measure_id, 'wp-meta-seo') ?>');
</script>
<!--End WPMS Google Analytics 4 property tracking js code-->