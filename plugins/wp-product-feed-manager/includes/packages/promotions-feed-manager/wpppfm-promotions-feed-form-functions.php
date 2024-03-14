<?php
/**
 * Form functions and hooks.
 *
 * @package WP Google Merchant Promotions Feed Manager/Functions
 * @since 2.39.0
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Starts the Google Merchant Promotions Feed page activated through the Google Feed Type selector.
 */
function wpppfm_open_promotions_feed_page() {

	$promotions_page = new WPPPFM_Add_Promotions_Feed_Editor_Page();
	$promotions_page->show();
}
