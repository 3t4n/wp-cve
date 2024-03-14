<?php
/**
 * Plugin Name: Ajax Load More for SearchWP
 * Plugin URI: http://connekthq.com/plugins/ajax-load-more/extensions/searchwp/
 * Description: An Ajax Load More extension that adds compatibility with SearchWP
 * Text Domain: ajax-load-more-for-searchwp
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: https://connekthq.com
 * Version: 1.0.2
 * License: GPL
 * Copyright: Darren Cooney & Connekt Media
 *
 * @package ALM_SearchWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *  Installation hook.
 */
function alm_searchwp_install() {
	if ( ! is_plugin_active( 'ajax-load-more/ajax-load-more.php' ) ) {
		set_transient( 'alm_searchwp_admin_notice', true, 5 );
	}
}
register_activation_hook( __FILE__, 'alm_searchwp_install' );

/**
 * Display admin notice if plugin does not meet the requirements.
 */
function alm_searchwp_admin_notice() {
	$slug   = 'ajax-load-more';
	$plugin = $slug . '-for-searchwp';
	// Ajax Load More Notice.
	if ( get_transient( 'alm_searchwp_admin_notice' ) ) {
		$install_url = get_admin_url() . '/update.php?action=install-plugin&plugin=' . $slug . '&_wpnonce=' . wp_create_nonce( 'install-plugin_' . $slug );
		$message     = '<div class="error">';
		$message    .= '<p>You must install and activate the core Ajax Load More plugin before using the Ajax Load More SearchWP extension.</p>';
		$message    .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, 'Install Ajax Load More Now' ) . '</p>';
		$message    .= '</div>';
		echo wp_kses_post( $message );
		delete_transient( 'alm_searchwp_admin_notice' );
	}
}
add_action( 'admin_notices', 'alm_searchwp_admin_notice' );

if ( ! class_exists( 'ALM_SearchWP' ) ) :

	/**
	 * ALM SearchWP Class.
	 *
	 * @author ConnektMedia <darren@connekthq.com>
	 * @since 1.0
	 */
	class ALM_SearchWP {

		/**
		 * Construct class.
		 *
		 * @author ConnektMedia <darren@connekthq.com>
		 * @since 1.0
		 */
		public function __construct() {
			add_filter( 'alm_searchwp', [ &$this, 'alm_searchwp_get_posts' ], 10, 2 );
		}

		/**
		 * Get searchwp search results and return post ids in post__in wp_query param
		 *
		 * @param  array  $args   The current query arguments.
		 * @param  string $engine The search engine slug.
		 * @return array  $args
		 * @author ConnektMedia <darren@connekthq.com>
		 * @since  1.0
		 */
		public function alm_searchwp_get_posts( $args, $engine ) {

			if ( class_exists( 'SWP_Query' ) ) {

				if ( empty( $engine ) || ! isset( $engine ) ) {
					$engine = 'default'; // set search engine.
				}

				$term = sanitize_text_field( $args['s'] );

				$swp_query = new SWP_Query(
					[
						'engine'         => $engine,
						's'              => $term,
						'fields'         => 'ids',
						'posts_per_page' => -1,
					]
				);

				if ( ! empty( $swp_query->posts ) ) {
					$args['post__in'] = $swp_query->posts;
					$args['orderby']  = 'post__in'; // override orderby to relevance.
					$args['search']   = $term; // Reset 's' term value.
					$args['s']        = ''; // Reset 's' term value.
				}

				return $args;
			}
		}

	}

	/**
	 * Highlight a search term using SearchWP Highlighter.
	 *
	 * @param  string $haystack The string to search through.
	 * @param  array  $args The ALM arguements.
	 * @return string
	 * @author ConnektMedia <darren@connekthq.com>
	 * @since  1.1
	 */
	function alm_searchwp_highlight( $haystack = '', $args = [] ) {

		if ( ! class_exists( '\SearchWP\Highlighter' ) || empty( $haystack ) || empty( $args ) ) {
			return;
		}

		global $post;
		$highlighter = new \SearchWP\Highlighter();
		$needle      = isset( $args['search'] ) ? sanitize_text_field( $args['search'] ) : '';
		return $highlighter->apply( $haystack, $needle );
	}

	/**
	 * The main function responsible for returning the one true ALM_SearchWP Instance.
	 *
	 * @return object $alm_searchwp
	 * @author ConnektMedia <darren@connekthq.com>
	 * @since 1.0
	 */
	function alm_searchwp() {
		global $alm_searchwp;
		if ( ! isset( $alm_searchwp ) ) {
			$alm_searchwp = new ALM_SearchWP();
		}
		return $alm_searchwp;
	}
	alm_searchwp();

endif;
