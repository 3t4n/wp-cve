<?php

defined('ABSPATH') || die('No direct script access allowed!');
$google_alanytics = get_option('wpms_google_alanytics');
$profile = WpmsGaTools::getSelectedProfile($google_alanytics['profile_list'], $google_alanytics['tableid_jail']);
if ($profile[4] === 'GA4') {
    if (empty($profile[8])) {
        die();
    } else {
        $measurement_id = $profile[8];
    }
    //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- add tracking script to header ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_html($measurement_id, 'wp-meta-seo') ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        // send default page_view event
        <?php if ($this->ga_tracking['wpmsga_dash_remarketing']) { ?>
        gtag('config', '<?php echo esc_html($measurement_id, 'wp-meta-seo') ?>');
        <?php } else { ?>
        // disabled advertising features
        gtag('config', '<?php echo esc_html($measurement_id, 'wp-meta-seo') ?>', {'allow_google_signals': false});
        <?php } ?>
    </script>

    <?php
}