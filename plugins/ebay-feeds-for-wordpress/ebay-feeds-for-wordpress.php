<?php
/*
Plugin Name:  WP eBay Product Feeds
Plugin URI:   https://www.winwar.co.uk/plugins/ebay-feeds-wordpress/?utm_source=plugin-link&utm_medium=plugin&utm_campaign=ebayfeedsforwordpress
Description:  Former eBay Feeds for WordPress. Parser of ebay RSS feeds to display on WordPress posts, widgets and pages.
Version:      3.4.3
Author:       Winwar Media
Author URI:   https://www.winwar.co.uk/?utm_source=author-link&utm_medium=plugin&utm_campaign=ebayfeedsforwordpress
Text Domain:  ebay-feeds-for-wordpress
*/

define( "EBFW_PLUGIN_VERSION", "3.4.3" );

define( 'EBAYFEEDSFORWORDPRESS_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'EBAYFEEDSFORWORDPRESS_PLUGIN_URL', plugins_url( '', __FILE__ ) );


define( "EBFW_PLUGIN_NAME", "WP eBay Product Feeds" );
define( "EBFW_PLUGIN_TAGLINE", __( "Former eBay Feeds for WordPress. Parser of ebay RSS feeds to display on WordPress posts, widgets and pages.", "ebay-feeds-for-wordpress" ) );
define( "EBFW_PLUGIN_URL", "https://winwar.co.uk/plugins/ebay-feeds-wordpress/" );
define( "EBFW_EXTEND_URL", "https://wordpress.org/extend/plugins/ebay-feeds-for-wordpress/" );
define( "EBFW_AUTHOR_TWITTER", "rhyswynne" );
define( "EBFW_DONATE_LINK", "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F852ZPEANV7C6" );

require_once( EBAYFEEDSFORWORDPRESS_PLUGIN_PATH . '/inc/core.php' );

/**
 * Function to call to initialise the plugin.
 *
 * @return void
 */
function ebay_feeds_for_wordpress_initialise_plugin() {
	// non-admin functions
	add_action( 'init', 'ebay_feeds_for_wordpress_addbuttons', 10 );
	add_action( 'plugins_loaded', 'ebay_feeds_for_wordpress_textdomain' );
	add_action( 'plugins_loaded', 'ebay_feeds_for_wordpress_check_for_gutenberg', 50 );
	add_action( 'plugins_loaded', 'ebay_feeds_for_wordpress_add_shortcode', 10 );
	add_action( 'wp_head', 'ebayfeedsforwordpress_set_max_image_width' );
	add_filter( 'wp_ebay_product_feed_url', 'wp_ebay_product_feed_debug_ar_urls', 10, 1 );

	// Admin Functions
	if ( is_admin() ) { // admin actions

		add_action( 'admin_menu', 'ebay_feeds_for_wordpress_menus' );
		add_action( 'admin_init', 'ebay_feeds_for_wordpress_options_process' );
		add_action( 'admin_init', 'ebay_feeds_for_wordpress_add_admin_stylesheet' );

	}

	$disabletosearcheinges = get_option( 'ebay-feeds-hide-results-from-search-engines' );

	if ( $disabletosearcheinges ) {
		add_filter( 'wp_ebay_product_feed_bots', 'ebay_feeds_for_wordpress_hide_from_search', 10, 1 );
	}
} add_action( 'plugins_loaded', 'ebay_feeds_for_wordpress_initialise_plugin', 1 );

/**
 * Add the shortcode when plugin is loaded.
 *
 * This is removed on the premium version of the product.
 *
 * @return void
 */
function ebay_feeds_for_wordpress_add_shortcode() {
	add_shortcode( 'ebayfeedsforwordpress', 'ebayfeedsforwordpress_shortcode' );
}

add_filter( 'wp_feed_cache_transient_lifetime', 'ebay_feeds_for_wordpress_set_feed_cache_time', 99, 1 );
add_filter( 'wp_feed_cache_transient_lifetime', 'ebay_feeds_for_wordpress_set_feed_cache_time', 100, 1 );

// Debug Functions
/* if ( 1 == get_option( 'ebay-feed-for-wordpress-flush-cache' ) ) {
	add_filter( 'wp_feed_cache_transient_lifetime', 'ebay_feeds_for_wordpress_set_feed_cache_time', 99, 1 );
	add_filter( 'wp_feed_cache_transient_lifetime', 'ebay_feeds_for_wordpress_set_feed_cache_time', 100, 1 );
} */

// Gutenberg Support
function ebay_feeds_for_wordpress_check_for_gutenberg() {
	if ( function_exists( 'register_block_type' ) ) {
		//wp_die( "In here?");
		add_action( 'enqueue_block_editor_assets', 'ebay_feeds_for_wordpress_enqueue_block_editor_assets', 10 );
		add_action( 'init', 'ebay_feeds_for_wordpress_init_gutenberg_block', 10 );
	}
}


// Installation Function
register_activation_hook( __FILE__, 'ebay_feeds_for_wordpress_install' );


/**
 * Preset options on install
 *
 * @return void
 */
function ebay_feeds_for_wordpress_install() {
	add_option( 'ebay-feeds-for-wordpress-default', 'https://www.auctionrequest.com/arfeed.php?uid=rhy5ee4d2c7e425&keyword=%22Ferrari%22&sortOrder=BestMatch&programid=15&campaignid=5336886189&listingType1=All&descriptionSearch=true&categoryId1=18180' );
	add_option( 'ebay-feeds-for-wordpress-default-number', 3 );
	add_option( 'ebay-feeds-for-wordpress-link', 0 );
	add_option( 'ebay-feeds-for-wordpress-link-open-blank', 0 );
	add_option( 'ebay-feed-for-wordpress-flush-cache', 0 );
}


/**
 * Load the Textdomain and set it
 *
 * Must be in the main wrapper, for...y'know...reasons
 * @return void
 */
function ebay_feeds_for_wordpress_textdomain() {
	$plugin_dir = basename( dirname( __FILE__ ) );
	load_plugin_textdomain( 'ebay-feeds-for-wordpress', false, $plugin_dir .'/languages' );
}
