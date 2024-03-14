<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.kybernetik-services.com/
 * @since      1.0.0
 *
 * @package    WP_Sitemaps_Config
 * @subpackage WP_Sitemaps_Config/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Sitemaps_Config
 * @subpackage WP_Sitemaps_Config/includes
 * @author     Kybernetik Services <wordpress@kybernetik.com.de>
 */
if ( ! class_exists( 'WP_Sitemaps_Config_Activator' ) ) {
class WP_Sitemaps_Config_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		// if the current WordPress version is lower than 5.5
		if ( version_compare( get_bloginfo( 'version' ), 5.5, '<' ) ) {
			// deactivate the plugin
            deactivate_plugins( WP_SITEMAPS_CONFIG_BASENAME );
			// quit WordPress and show message
			/* translators: 1: Current WordPress version, 2: Plugin name, 3: Required WordPress version. */
			$text_1 = '<strong>Error:</strong> Current WordPress version (%1$s) does not meet minimum requirements for %2$s. The plugin requires WordPress %3$s.';
			$text_2 = 'WordPress &rsaquo; Error';
			wp_die( 
				sprintf(
					_x( $text_1, 'plugin' ),
					get_bloginfo( 'version' ),
					WP_SITEMAPS_CONFIG_NAME,
					'5.5'
				), // message
				esc_html__( $text_2 ),
				array( 'back_link' => true ) // show 'Back' link
			);
		}

		// check for existence of the XML functionality
		if ( ! class_exists( 'SimpleXMLElement' ) ) {
			$text = 'WordPress &rsaquo; Error';
			wp_die(
				sprintf(
					/* translators: %s: extension name 'SimpleXML' */
					__( '<strong>Error:</strong> The plugin requires the %s PHP extension.', 'wp-sitemap-config' ),
					'SimpleXML'
				),
				esc_html__( $text ),
				array(
					'response' => 501, // "Not implemented".
					'back_link' => true, // show 'Back' link
				)
			);
		}
		
		/* // if settings for this plugin are not available, then store the default settings
		if ( ! get_option( WP_SITEMAPS_CONFIG_OPTION_NAME ) ) {
			add_option(
				WP_SITEMAPS_CONFIG_OPTION_NAME,
				array(
					'add_changefreq' => 0,
					'add_lastmod' => 0,
					'add_priority' => 0,
					'remove_all_sitemaps' => 0,
					'remove_provider_posts' => 0,
					'remove_provider_taxonomies' => 0,
					'remove_provider_users' => 0,
					'remove_sitemap_posts_page' => 0,
					'remove_sitemap_posts_post' => 0,
					'remove_sitemap_taxonomies_category' => 0,
					'remove_sitemap_taxonomies_post_format' => 0,
					'remove_sitemap_taxonomies_post_tag' => 0,
				)
			);
		}*/

		// store the flag into the db to trigger the display of a message after activation
		set_transient( WP_SITEMAPS_CONFIG_TRANSIENT_PLUGIN_ACTIVATED, '1', 60 );
	}

}
}
