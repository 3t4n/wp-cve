<?php
/**
 * Plugin Name: Ajax Load More for Relevanssi
 * Plugin URI: http://connekthq.com/plugins/ajax-load-more/extensions/relevanssi/
 * Description: An Ajax Load More extension that adds compatibility with Relevanssi.
 * Text Domain: ajax-load-more-for-relevanssi
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: https://connekthq.com
 * Version: 1.0.2
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 *
 * @package ALM_Relevanssi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *  Installation hook.
 */
function alm_relevanssi_install() {
	if ( ! is_plugin_active( 'ajax-load-more/ajax-load-more.php' ) ) {
		set_transient( 'alm_relevanssi_admin_notice', true, 5 );
	}
}
register_activation_hook( __FILE__, 'alm_relevanssi_install' );

/**
 * Display admin notice if plugin does not meet the requirements.
 */
function alm_relevanssi_admin_notice() {
	$slug   = 'ajax-load-more';
	$plugin = $slug . '-for-relevanssi';
	// Ajax Load More Notice.
	if ( get_transient( 'alm_relevanssi_admin_notice' ) ) {
		$install_url = get_admin_url() . '/update.php?action=install-plugin&plugin=' . $slug . '&_wpnonce=' . wp_create_nonce( 'install-plugin_' . $slug );
		$message     = '<div class="error">';
		$message    .= '<p>You must install and activate the core Ajax Load More plugin before using the Ajax Load More Relevanssi extension.</p>';
		$message    .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, 'Install Ajax Load More Now' ) . '</p>';
		$message    .= '</div>';
		echo wp_kses_post( $message );
		delete_transient( 'alm_relevanssi_admin_notice' );
	}
}
add_action( 'admin_notices', 'alm_relevanssi_admin_notice' );


if ( ! class_exists( 'ALM_Relevanssi' ) ) :

	/**
	 * Initiate the class.
	 *
	 * @author ConnektMedia <darren@connekthq.com>
	 */
	class ALM_Relevanssi {

		/**
		 * Construct Relevanssi class.
		 */
		public function __construct() {
			add_filter( 'alm_relevanssi', [ &$this, 'alm_relevanssi_get_posts' ], 10, 1 );
		}

		/**
		 * Get relevanssi search results and return post ids in post__in wp_query param.
		 *
		 * @param  array $args The query arguments.
		 * @return array       The modified args.
		 * @since  1.0
		 */
		public function alm_relevanssi_get_posts( $args ) {
			if ( function_exists( 'relevanssi_do_query' ) ) {

				$old_posts_per_page     = $args['posts_per_page'];
				$old_offset             = $args['offset'];
				$args['orderby']        = 'relevance';
				$args['posts_per_page'] = -1; // We need to get all search results for this to work.
				$args['offset']         = 0; // We don't want an offset (ALM handles this).

				$wp_query = new WP_Query( $args );

				// Core Relevanssi Filter.
				$wp_query = apply_filters( 'relevanssi_modify_wp_query', $wp_query ); // phpcs:ignore

				// Run query.
				relevanssi_do_query( $wp_query );

				// Empty posts array.
				$posts = [];

				// Loop all results and pull post IDs.
				// 'fields' => 'ids' is not working in some environments.
				if ( $wp_query->posts ) {
					foreach ( $wp_query->posts as $result ) {
						$posts[] = $result->ID;
					}
				}

				if ( ! empty( $posts ) ) {
					$args['post__in']       = $posts; // $relevanssi_query->posts array.
					$args['orderby']        = 'post__in'; // Override orderby to relevance.
					$args['search']         = $args['s']; // Set custom `search` arg to pass back to ALM.
					$args['s']              = ''; // Reset 's' term value.
					$args['posts_per_page'] = $old_posts_per_page; // Reset 'posts_per_page' before sending data back.
					$args['offset']         = $old_offset; // Reset 'offset' before sending data back.
				}

				return $args;
			}
		}
	}

	/**
	 * ALM_Relevanssi
	 * The main function responsible for returning the one true ALM_Relevanssi Instance.
	 *
	 * @since 1.0
	 * @author ConnektMedia <darren@connekthq.com>
	 */
	function alm_relevanssi() {
		global $alm_relevanssi;

		if ( ! isset( $alm_relevanssi ) ) {
			$alm_relevanssi = new ALM_Relevanssi();
		}

		return $alm_relevanssi;
	}
	alm_relevanssi();

endif;
