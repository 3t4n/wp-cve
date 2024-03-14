<?php
/**
 * Plugin Name:       Clariti
 * Description:       Push WordPress updates to clariti.com in realtime.
 * Author:            Clariti
 * Author URI:        https://clariti.com
 * Text Domain:       clariti
 * Domain Path:       /languages
 * Version:           1.2.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 *
 * @package         Clariti
 */

define( 'CLARITI_PLUGIN_FILE', __FILE__ );

/**
 * Integration points with WordPress.
 */
add_action( 'admin_menu', array( 'Clariti\Admin', 'action_admin_menu' ) );
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( 'Clariti\Admin', 'filter_plugin_action_links' ) );
add_action( 'rest_index', array( 'Clariti\REST_API', 'filter_rest_index' ) );
add_action( 'rest_api_init', array( 'Clariti\REST_API', 'register_routes' ) );
/**
 * Updates Clariti when various modification behaviors are performed.
 */
add_action( 'admin_post_clear_secret', array( 'Clariti\Notifier', 'clear_secret_option' ) );
add_action( 'added_option', array( 'Clariti\Notifier', 'action_added_option' ), 10, 2 );
add_action( 'updated_option', array( 'Clariti\Notifier', 'action_updated_option' ), 10, 3 );
add_action( 'transition_post_status', array( 'Clariti\Notifier', 'action_transition_post_status' ), 10, 3 );
add_action( 'wp_trash_post', array( 'Clariti\Notifier', 'action_wp_trash_post' ) );
add_action( 'before_delete_post', array( 'Clariti\Notifier', 'action_before_delete_post' ) );
add_action( 'created_term', array( 'Clariti\Notifier', 'action_created_term' ), 10, 3 );
add_action( 'edited_term', array( 'Clariti\Notifier', 'action_edited_term' ), 10, 3 );
add_action( 'delete_term', array( 'Clariti\Notifier', 'action_delete_term' ), 10, 3 );
add_action( 'wp_insert_comment', array( 'Clariti\Notifier', 'action_wp_insert_comment' ), 10, 2 );
add_action( 'transition_comment_status', array( 'Clariti\Notifier', 'action_transition_comment_status' ), 10, 3 );
add_action( 'updated_postmeta', array( 'Clariti\Notifier', 'action_updated_postmeta' ), 10, 3 );

/**
 * Integration points with other plugins.
 */
add_action(
	'tbf_after_post_operation_execution',
	array(
		'Clariti\Integrations\The_Blog_Fixer',
		'action_tbf_after_post_operation_execution',
	),
	10,
	2
);

/**
 * Retrieve this plugin's version number as specified in the plugin header.
 *
 * @return string The plugin version.
 */
function clariti_get_plugin_version() {
	static $plugin_version = '';

	if ( $plugin_version ) {
		return $plugin_version;
	}

	$plugin_data    = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
	$plugin_version = $plugin_data['Version'];

	return $plugin_version;
}

/**
 * Gets the slugs of the supported post types.
 *
 * @return array
 */
function clariti_get_supported_post_types() {
	$post_types = get_post_types( array(), 'object' );
	$skipped    = array(
		'nav_menu_item',
		'wp_block',
		'wp_template',
		'wp_template_part',
		'wp_navigation',
	);
	$supported  = array();
	foreach ( $post_types as $post_type ) {
		if ( in_array( $post_type->name, $skipped, true ) ) {
			continue;
		}
		// Has to public=true && show_in_rest=true.
		if ( empty( $post_type->public ) || empty( $post_type->show_in_rest ) ) {
			continue;
		}
		// Has to support 'title' and 'editor'.
		if ( ! post_type_supports( $post_type->name, 'title' ) || ! post_type_supports( $post_type->name, 'editor' ) ) {
			continue;
		}

		$supported[] = $post_type->name;
	}
	$supported = apply_filters( 'clariti_supported_post_types', $supported );
	sort( $supported );
	return $supported;
}

/**
 * Register the class autoloader
 */
spl_autoload_register(
	function ( $class_name ) {
		$class = ltrim( $class_name, '\\' );
		if ( 0 !== stripos( $class, 'Clariti\\' ) ) {
			return;
		}

		$parts = explode( '\\', $class );
		array_shift( $parts ); // Don't need "Clariti".
		$last    = array_pop( $parts ); // File should be 'class-[...].php'.
		$last    = 'class-' . $last . '.php';
		$parts[] = $last;
		$file    = __DIR__ . '/inc/' . str_replace( '_', '-', strtolower( implode( '/', $parts ) ) );
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);
