<?php
/**
 * Plugin Name: LINE Pay for WooCommerce
 * Framework Name: Artisan Workshop FrameWork for WooCommerce
 * Framework Version : 2.0.9
 * Author: Artisan Workshop
 * Author URI: https://wc.artws.info/
 * Text Domain: linepay-wc
 *
 * @category JP4WC_Framework
 * @author Artisan Workshop
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

return apply_filters(
    'jp4wc_framework_config',
    array(
        'description_check_pattern' => __( 'Please check it if you want to use %s.', 'linepay-wc' ),
        'description_payment_pattern' => __( 'Please check it if you want to use the payment method of %s.', 'linepay-wc' ),
        'description_input_pattern' => __( 'Please input %s.', 'linepay-wc' ),
        'description_select_pattern' => __( 'Please select one from these as %s.', 'linepay-wc' ),
        'support_notice_01' => __( 'Need support?', 'linepay-wc' ),
        'support_notice_02' => __( 'If you are having problems with this plugin, talk about them in the <a href="%s" target="_blank" title="Pro Version">Support forum</a>.', 'linepay-wc' ),
        'support_notice_03' => __( 'If you need professional support, please consider about <a href="%1$s" target="_blank" title="Site Construction Support service">Site Construction Support service</a> or <a href="%2$s" target="_blank" title="Maintenance Support service">Maintenance Support service</a>.', 'linepay-wc' ),
        'pro_notice_01' => __( 'Pro version', 'linepay-wc' ),
        'pro_notice_02' => __( 'The pro version is available <a href="%s" target="_blank" title="Support forum">here</a>.', 'linepay-wc' ),
        'pro_notice_03' => __( 'The pro version includes support for bulletin boards. Please consider purchasing the pro version.', 'linepay-wc' ),
        'update_notice_01' => __( 'Finished Latest Update, WordPress and WooCommerce?', 'linepay-wc' ),
        'update_notice_02' => __( 'One the security, latest update is the most important thing. If you need site maintenance support, please consider about <a href="%s" target="_blank" title="Support forum">Site Maintenance Support service</a>.', 'linepay-wc' ),
        'community_info_01' => __( 'Where is the study group of Woocommerce in Japan?', 'linepay-wc' ),
        'community_info_02' => __( '<a href="%s" target="_blank" title="Tokyo WooCommerce Meetup">Tokyo WooCommerce Meetup</a>.', 'linepay-wc' ),
        'community_info_03' => __( '<a href="%s" target="_blank" title="Kansai WooCommerce Meetup">Kansai WooCommerce Meetup</a>.', 'linepay-wc' ),
        'community_info_04' => __('Join Us!', 'linepay-wc' ),
        'author_info_01' => __( 'Created by', 'linepay-wc' ),
        'author_info_02' => __( 'WooCommerce Doc in Japanese', 'linepay-wc' ),
        'framework_version' => JP4WC_LINEPAY_FRAMEWORK_VERSION,
    )
);
