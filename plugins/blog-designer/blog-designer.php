<?php
/**
 * Plugin Name: Blog Designer
 * Plugin URI: https://wordpress.org/plugins/blog-designer/
 * Description: To make your blog design more pretty, attractive and colorful.
 * Version: 3.1.5
 * Author: Solwin Infotech
 * Author URI: https://www.solwininfotech.com/
 * Requires at least: 5.6
 * Tested up to: 6.4.2
 * Text Domain: blog-designer
 * Domain Path: /languages/
 *
 * @package Blog Designer
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'BLOGDESIGNER_URL', plugin_dir_url( __FILE__ ) );
define( 'BLOGDESIGNER_DIR', plugin_dir_path( __FILE__ ) );
register_activation_hook( __FILE__, 'bd_plugin_activate' );
register_deactivation_hook( __FILE__, 'bd_update_optin' );
add_action( 'admin_head', 'bd_upgrade_link_css' );
add_action( 'plugins_loaded', 'bd_load_language_files' );
add_action( 'vc_before_init', 'bd_add_vc_support' );

/**
 * Gutenberg block for blog designer shortcode
 */
if ( function_exists( 'register_block_type' ) ) {
	require_once BLOGDESIGNER_DIR . 'includes/blog_designer_block/index.php';
}

/**
 * Add support for visual composer
 */
function bd_add_vc_support() {
	vc_map(
		array(
			'name'                    => esc_html__( 'Blog Designer', 'blog-designer' ),
			'base'                    => 'wp_blog_designer',
			'class'                   => 'blog_designer_section',
			'show_settings_on_create' => false,
			'category'                => esc_html__( 'Content', 'blog-designer' ),
			'icon'                    => 'blog_designer_icon',
			'description'             => __( 'Custom Blog Layout', 'blog-designer' ),
		)
	);
}
/**
 * Add css for upgrade link
 */
function bd_upgrade_link_css() {
	echo '<style>.row-actions a.bd_upgrade_link { color: #4caf50; }</style>';
}
/**
 * Include admin getting started page
 */
function bd_getting_started() {
	include_once 'includes/getting-started.php';
}
/**
 * Loads plugin textdomain
 */
function bd_load_language_files() {
	load_plugin_textdomain( 'blog-designer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Deactivate pro version when lite version is activated
 */
function bd_plugin_activate() {
	if ( is_plugin_active( 'blog-designer-pro/blog-designer-pro.php' ) ) {
		deactivate_plugins( '/blog-designer-pro/blog-designer-pro.php' );
	}
	add_option( 'bd_plugin_do_activation_redirect', true );
}
/**
 * Delete optin on deactivation of plugin
 */
function bd_update_optin() {
	update_option( 'bd_is_optin', '' );
}
$version = get_bloginfo( 'version' );
if ( $version > 3.8 ) {
	/**
	 * Display rating of plugin
	 *
	 * @param type $args args.
	 */
	function bd_custom_star_rating( $args = array() ) {
		$plugins  = '';
		$response = '';
		$args     = array(
			'author' => 'solwininfotech',
			'fields' => array(
				'downloaded'   => true,
				'downloadlink' => true,
			),
		);

		// Make request and extract plug-in object. Action is query_plugins.
		$response = wp_remote_get(
			'http://api.wordpress.org/plugins/info/1.0/',
			array(
				'body' => array(
					'action'  => 'query_plugins',
					'request' => maybe_serialize( (object) $args ),
				),
			)
		);
		if ( ! is_wp_error( $response ) ) {
			$returned_object = maybe_unserialize( wp_remote_retrieve_body( $response ) );
			$plugins         = $returned_object->plugins;
		}
		$current_slug = 'blog-designer';
		if ( $plugins ) {
			foreach ( $plugins as $plugin ) {
				if ( $plugin->slug == $current_slug ) {
					$rating = $plugin->rating * 5 / 100;
					if ( $rating > 0 ) {
						$args = array(
							'rating' => $rating,
							'type'   => 'rating',
							'number' => $plugin->num_ratings,
						);
						wp_star_rating( $args );
					}
				}
			}
		}
	}
}
require_once BLOGDESIGNER_DIR . 'admin/class-blog-designer-lite-admin.php';
require_once BLOGDESIGNER_DIR . 'admin/class-blog-designer-lite-settings.php';
require_once BLOGDESIGNER_DIR . 'admin/class-blog-designer-lite-ticker-settings.php';
require_once BLOGDESIGNER_DIR . 'admin/class-blog-designer-lite-template.php';
require_once BLOGDESIGNER_DIR . 'public/class-blog-designer-lite-public.php';
require_once BLOGDESIGNER_DIR . 'public/class-blog-designer-scroll-widget.php';
