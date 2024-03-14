<?php
/*
 * Plugin Name: Disable Comments on Media Attachments
 * Plugin URI: https://asif.im/
 * Description: Disable Comments on Media Attachments Pages
 * Version: 0.2.2
 * Author: M Asif Rahman
 * Author URI: https://asif.im/
 * License: GPLv3
 * Text Domain: disable-comments-on-attachments
 * Min WP Version: 2.5.0
 * Max WP Version: 5.4
 */

define("WPDEV_PLUGIN_PATH",plugin_dir_path(__FILE__)); #with trailing slash (/)

/**
 * This function allows you to track usage of your plugin
 * Place in your main plugin file
 * Refer to https://wpdeveloper.net/support for help
 */
if( ! class_exists( 'DCMA_Plugin_Tracker') ) {
    include_once( WPDEV_PLUGIN_PATH . 'tracking/class-plugin-usage-tracker.php' );
}
if( ! function_exists( 'dcoa_start_plugin_tracking' ) ) {
    function dcoa_start_plugin_tracking() {
        $tracker = DCMA_Plugin_Tracker::get_instance( __FILE__, [
			'opt_in'       => true,
			'goodbye_form' => true,
			'item_id'      => '1550c146d285339a202b'
		] );
		$tracker->set_notice_options(array(
			'notice' => __( 'Want to help make <strong>Disable Comments on Media Attachments</strong> even more awesome? Help us understand our user better.', 'disable-comments-on-attachments' ),
			'extra_notice' => __( 'We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress & PHP version, plugins & themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, I promise.', 'disable-comments-on-attachments' ),
		));
		$tracker->init();
    }
    dcoa_start_plugin_tracking();
}


include_once( WPDEV_PLUGIN_PATH . 'wpdev-dashboard-widget.php');

function disable_comments_on_attachments( $open, $post_id = null) {
    $post = get_post( $post_id );
    if( $post->post_type == 'attachment' ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'disable_comments_on_attachments', 10 , 2 );