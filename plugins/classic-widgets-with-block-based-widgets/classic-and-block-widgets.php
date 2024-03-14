<?php
/**
 * Plugin Name: Classic Widgets with Block-based Widgets
 * Plugin URI: https://www.secretsofgeeks.com/
 * Description: Restore the classic widgets screen as a new menu item without replacing new block-based widgets.
 * Version: 1.0.1
 * Author: 5um17
 * Author URI: https://www.secretsofgeeks.com
 * Text Domain: classic-widgets-with-block-based-widgets
 *
 * @package CBW
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'CLASSIC_AND_BLOCK_WIDGETS_FILENAME' ) ) {
	define( 'CLASSIC_AND_BLOCK_WIDGETS_FILENAME', plugin_basename( __FILE__ ) );
}

add_action(
	'plugins_loaded',
	function () {
		if ( cbw_request_uri_contain( '/wp-admin/widgets.php' ) ) {
			// If this is widget.php screen, set or delete transient based on cw query string.
			! empty( $_GET['cw'] ) ? set_transient( 'classic_and_block_widgets', true, HOUR_IN_SECONDS ) : delete_transient( 'classic_and_block_widgets' );
		}

		// Add actual filters to disable block based widgets based on transients.
		if ( get_transient( 'classic_and_block_widgets' ) ) {
			add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
			add_filter( 'use_widgets_block_editor', '__return_false' );
		}

		// Add the new menu under appearance.
		add_action(
			'admin_menu',
			function() {
				add_submenu_page( 'themes.php', __( 'Classic Widgets', 'classic-widgets-with-block-based-widgets' ), __( 'Classic Widgets', 'classic-widgets-with-block-based-widgets' ), 'edit_theme_options', 'widgets.php?cw=1', null, 3 );

				// Handle current menu class.
				add_filter(
					'submenu_file',
					function( $submenu_file ) {
						if ( cbw_request_uri_contain( 'widgets.php?cw=1' ) ) {
							global $self;
							$self = 'widgets.php?cw=1';
						}
						return $submenu_file;
					}
				);
			}
		);

		// Plugin row meta data.
		add_filter(
			'plugin_row_meta',
			function ( $links, $file ) {
				if ( CLASSIC_AND_BLOCK_WIDGETS_FILENAME !== $file ) {
					return $links;
				}

				if ( is_array( $links ) ) {
					$links[] = '<a href="https://wordpress.org/plugins/search/5um17/" target="_blank">'
					. __( 'More Plugins', 'classic-widgets-with-block-based-widgets' )
					. '</a>';
				}
				return $links;
			},
			10,
			2
		);

		// Classic widgets link in plugin row actions.
		add_filter(
			'plugin_action_links_' . CLASSIC_AND_BLOCK_WIDGETS_FILENAME,
			function ( $links ) {
				if ( is_array( $links ) ) {
					$links[] = '<a href="' . admin_url( 'widgets.php?cw=1' ) . '">'
					. __( 'Classic Widgets', 'classic-widgets-with-block-based-widgets' )
					. '</a>';
				}

				return $links;
			}
		);
	}
);

/**
 * Check if REQUEST_URI exist and contains the given value.
 *
 * @since 1.0
 * @param string $needle Needle to search.
 * @return boolean true if needle found else false.
 */
function cbw_request_uri_contain( $needle ) {
	if ( ! empty( $_SERVER['REQUEST_URI'] ) && false !== strpos( $_SERVER['REQUEST_URI'], $needle ) ) {
		return true;
	}

	return false;
}
