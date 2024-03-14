<?php
/**
 * Plugin Name: Taxonomy Term Listing - Visual Composer Addon
 * Plugin URI: https://wordpress.org/plugins/taxonomy-term-listing-visual-composer-addon/
 * Description: Creates nested list of categories
 * Author: Manisha Makhija
 * Author URI: https://profiles.wordpress.org/manishamakhija
 * Text Domain: taxonomy-term-listing-visual-composer-addon
 * Domain path: /languages
 * Version: 1.6
 *
 * @package WordPress
 * @subpackage Visual_Composer
 */

// If check WordPress installed OR not.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If check visual composer installed OR not.
if ( defined( 'WPB_VC_VERSION' ) ) {
	require_once 'taxonomy-listing.php';
}

/**
 * Checking for Visual Composer activation.
 */
function taxonomy_listing_init_addons() {
	// If check visual composer installed OR not.
	if ( ! defined( 'WPB_VC_VERSION' ) && ! defined( 'VCV_VERSION' ) ) {
		add_action( 'admin_notices', function() {
			echo '<div class="error"><p>' . __( 'The <strong>Taxonomy Term Listing Visual Composer addon </strong> requires <strong>Visual Composer</strong> installed and activated.', 'taxonomy-term-listing-visual-composer-addon' ) . '</p></div>';
		} );
	}
}
add_action( 'admin_init', 'taxonomy_listing_init_addons' );

/**
 * Register activate hook.
 */
function taxonomy_listing_addon_activate() {
	// write code here.
}
register_activation_hook( __FILE__, 'taxonomy_listing_addon_activate' );

/**
 * Register deactivate hook.
 */
function taxonomy_listing_addon_deactivate() {
	// write code here.
	delete_site_option( 'vcv-hubElements' );
}
register_deactivation_hook( __FILE__, 'taxonomy_listing_addon_deactivate' );

/**
 * Register uninstall hook.
 */
function taxonomy_listing_addon_uninstall() {
	// write code here.
	delete_site_option( 'vcv-hubElements' );
}
register_uninstall_hook( __FILE__, 'taxonomy_listing_addon_uninstall' );

/**
 * Load plugin textdomain.
 */
function taxonomy_listing_addon_init() {
	load_plugin_textdomain( 'taxonomy-term-listing-visual-composer-addon', false, basename( dirname( __FILE__ ) ) . '/languages' );
	if ( class_exists( 'Taxonomy_Term_Addon' ) ) {
		$vc_term = new Taxonomy_Term_Addon();
		$vc_term->_instance();
	}
	taxonomy_listing_register_vc_element();
}
add_action( 'plugins_loaded', 'taxonomy_listing_addon_init' );

/**
 * Visual composer register element.
 */
function taxonomy_listing_register_vc_element() {

	// If check visual composer constant.
	if ( defined( 'VCV_VERSION' ) ) {

		/**
		 * Register new VC add on.
		 *
		 * @param object $api \VisualComposer\Modules\Api\Factory.
		 */
		function taxonomy_listing_vc_add_on( $api ) {
			$elements_register = array(
				'taxonomyListing',
			);
			$plugin_base_url = rtrim( plugins_url( basename( __DIR__ ) ), '\\/' );

			/**
			 * VC element api.
			 *
			 * @var \VisualComposer\Modules\Elements\ApiController $elements_api
			 */
			$elements_api = $api->elements;
			foreach ( $elements_register as $tag ) {
				$manifect_json = __DIR__ . '/elements/' . $tag . '/manifest.json';
				$element_base_url = $plugin_base_url . '/elements/' . $tag;
				$elements_api->add( $manifect_json, $element_base_url );
			}
		}
		add_action( 'vcv:api', 'taxonomy_listing_vc_add_on' );

		/**
		 * Add REST API support to an already registered taxonomy.
		 *
		 * @param array  $args Taxonomy supports.
		 * @param string $taxonomy_name Taxonomy slug.
		 * @return array Taxonomy supports.
		 */
		function taxonomy_listing_tax_args( $args, $taxonomy_name ) {
			$args['show_in_rest'] = true;
			return $args;
		}
		add_filter( 'register_taxonomy_args', 'taxonomy_listing_tax_args', 10, 2 );
	}
}
