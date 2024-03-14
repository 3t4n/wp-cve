<?php
/**
 * Installation Class
 * Handles to manage installation process of plugin.
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Lswss_Install {

	/**
	 * Plugin Setup on Activation
	 * Does the initial setup.
	 * 
	 * @since 1.0
	 */
	public static function install() {

		// Get plugin settings
		$lswss_version = get_option('lswss_version');

		// Update plugin version
		if( empty( $lswss_version ) ) {

			// Plugin DB Version
			update_option( 'lswss_version', '1.1' );
		}

		// Deactivate premium version
		if( is_plugin_active('logo-showcase-with-slick-slider-pro/logo-showcase-with-slick-slider-pro.php') ) {
			add_action( 'update_option_active_plugins', array( 'Lswss_Install', 'lswss_deactivate_pro_version' ) );
		}

		// Register post type function
		lswss_register_post_type();

		// Clear the permalinks
		flush_rewrite_rules();

		/**
		 * Get All Old Logo Showcase
		 * If any old logo showcase found then enable the 'Update Database' menu.
		 */
		$prefix	= LSWSS_META_PREFIX; // Taking metabox prefix
		$args 	= array(
						'post_type' 		=> LSWSS_POST_TYPE,
						'post_status'		=> array( 'any', 'inherit', 'trash' ),
						'fields'			=> 'ids',
						'posts_per_page' 	=> 1,
						'cache_results'		=> false,
						'meta_query'		=> array(
													array(
														'key'		=> $prefix.'sett',
														'value'		=> '',
														'compare'	=> 'NOT EXISTS',
													),
												)
					);
		$logo_query = new WP_Query( $args );

		if ( $logo_query->have_posts() ) {
			update_option( 'lswss_version', '1.0' );
		}
	}

	/**
	 * Deactivate Premium Plugin
	 * 
	 * @since 1.0
	 */
	public static function lswss_deactivate_pro_version() {
		deactivate_plugins('logo-showcase-with-slick-slider-pro/logo-showcase-with-slick-slider-pro.php', true);
	}
}