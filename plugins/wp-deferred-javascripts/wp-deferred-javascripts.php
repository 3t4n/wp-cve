<?php
/*
 * Plugin Name: WP deferred javaScript
 * Plugin URI: http://www.screenfeed.fr
 * Description: This plugin defer the loading of all javascripts added by the way of <code>wp_enqueue_script()</code>, using LABJS.
 * Version: 2.0.5
 * Author: Willy Bahuaud, Daniel Roch, Grégory Viguier
 * Author URI: https://wabeo.fr/wp-deferred-js-authors.html
 * License: GPLv3
 * License URI: http://www.screenfeed.fr/gpl-v3.txt
 * Text Domain: wp-deferred-javascripts
 * Domain Path: /languages/
 * Stable tag: 2.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}


define( 'WDJS_VERSION',    '2.0.5' );
define( 'WDJS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WDJS_PLUGIN_FILE', __FILE__ );


if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

	// !List everybody, so no one will be jalous. :)

	add_filter( 'plugin_row_meta', 'wdjs_plugin_row_meta', 10, 2 );

	function wdjs_plugin_row_meta( $plugin_meta, $plugin_file ) {

		if ( plugin_basename( __FILE__ ) === $plugin_file ) {
			$pos     = false;
			$links   = array();
			$authors = array(
				array( 'name' => 'Willy Bahuaud',   'url' => 'http://wabeo.fr' ),
				array( 'name' => 'Grégory Viguier', 'url' => 'http://www.screenfeed.fr/' ),
				array( 'name' => 'Daniel Roch',     'url' => 'http://www.seomix.fr' ),
			);

			if ( ! empty( $plugin_meta ) ) {
				$search = '"http://wabeo.fr/wp-deferred-js-authors.html"';
				foreach ( $plugin_meta as $i => $meta ) {
					if ( strpos( $meta, $search ) !== false ) {
						$pos = $i;
						break;
					}
				}
			}

			foreach( $authors as $author ) {
				$links[] = sprintf( '<a href="%s">%s</a>', $author['url'], $author['name'] );
			}

			$links = sprintf( __( 'By %s' ), wp_sprintf( '%l', $links ) );

			if ( $pos !== false ) {
				$plugin_meta[ $pos ] = $links;
			}
			else {
				$plugin_meta[] = $links;
			}
		}

		return $plugin_meta;
	}

	include( plugin_dir_path( __FILE__ ) . 'inc/admin.php' );

}
elseif ( ! defined( 'XMLRPC_REQUEST' ) && ! defined( 'DOING_CRON' ) && ! is_admin() ) {

	include( plugin_dir_path( __FILE__ ) . 'inc/frontend.php' );

}

/**/