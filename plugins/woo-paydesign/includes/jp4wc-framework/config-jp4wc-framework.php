<?php
/**
 * Plugin Name: Metaps Payment for WooCommerce
 * Framework Name: Artisan Workshop FrameWork for WooCommerce
 * Framework Version : 2.0.12
 * Author: Artisan Workshop
 * Author URI: https://wc.artws.info/
 * Text Domain: woo-paydesign
 *
 * @category JP4WC_Framework
 * @author Artisan Workshop
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

return apply_filters(
    'm4wc_framework_config',
    array(
        'description_check_pattern' => __( 'Please check it if you want to use %s.', 'woo-paydesign' ),
        'description_payment_pattern' => __( 'Please check it if you want to use the payment method of %s.', 'woo-paydesign' ),
        'description_input_pattern' => __( 'Please input %s.', 'woo-paydesign' ),
        'description_select_pattern' => __( 'Please select one from these as %s.', 'woo-paydesign' ),
        'support_notice_01' => __( 'Need support?', 'woo-paydesign' ),
        'support_notice_02' => __( 'If you are having problems with this plugin, talk about them in the <a href="%s" target="_blank" title="Pro Version">Support forum</a>.', 'woo-paydesign' ),
        'support_notice_03' => __( 'If you need professional support, please consider about <a href="%1$s" target="_blank" title="Site Construction Support service">Site Construction Support service</a> or <a href="%2$s" target="_blank" title="Maintenance Support service">Maintenance Support service</a>.', 'woo-paydesign' ),
        'pro_notice_01' => __( 'Pro version', 'woo-paydesign' ),
        'pro_notice_02' => __( 'The pro version is available <a href="%s" target="_blank" title="Support forum">here</a>.', 'woo-paydesign' ),
        'pro_notice_03' => __( 'The pro version includes support for bulletin boards. Please consider purchasing the pro version.', 'woo-paydesign' ),
        'update_notice_01' => __( 'Finished Latest Update, WordPress and WooCommerce?', 'woo-paydesign' ),
        'update_notice_02' => __( 'One the security, latest update is the most important thing. If you need site maintenance support, please consider about <a href="%s" target="_blank" title="Support forum">Site Maintenance Support service</a>.', 'woo-paydesign' ),
        'community_info_01' => __( 'Where is the study group of Woocommerce in Japan?', 'woo-paydesign' ),
        'community_info_02' => __( '<a href="%s" target="_blank" title="Tokyo WooCommerce Meetup">Tokyo WooCommerce Meetup</a>.', 'woo-paydesign' ),
        'community_info_03' => __( '<a href="%s" target="_blank" title="Kansai WooCommerce Meetup">Kansai WooCommerce Meetup</a>.', 'woo-paydesign' ),
        'community_info_04' => __('Join Us!', 'woo-paydesign' ),
        'author_info_01' => __( 'Created by', 'woo-paydesign' ),
        'author_info_02' => __( 'WooCommerce Doc in Japanese', 'woo-paydesign' ),
        'framework_version' => '1.3.0',
    )
);
