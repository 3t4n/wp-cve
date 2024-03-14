<?php
/**
 * Plugin Name: Duplicate Taxonomy Term
 * Description: Copy term of any type with a click!
 * Version: 1.0.2
 * Author: Sebastian Pisula
 * Author URI: https://profiles.wordpress.org/sebastianpisula/
 * Text Domain: duplicate-term
 * Domain Path: /languages/
 * Requires at least: 3.1
 * Tested up to: 6.2
 * Requires PHP: 7.0
 *
 * @package DuplicateTerm
 */

namespace DuplicateTerm;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_Error;
use WP_Term;

/**
 * Class Plugin
 */
class Plugin {
	/**
	 *
	 */
	public function add_hooks() {
		add_action( 'admin_head', [ $this, 'add_row_actions' ] );
		add_action( 'admin_post_ic-duplicate-term', [ $this, 'process_duplicate_term' ] );
		add_action( 'admin_notices', [ $this, 'add_admin_notice' ] );
	}

	/**
	 *
	 */
	public function add_row_actions() {
		$screen = get_current_screen();

		if ( $screen->base !== 'edit-tags' ) {
			return;
		}

		foreach ( get_taxonomies() as $taxonomy ) {
			add_filter( "{$taxonomy}_row_actions", [ $this, 'add_tag_row_action' ], 10, 2 );
		}
	}

	/**
	 * Display notice.
	 */
	public function add_admin_notice() {
		if ( isset( $_GET['duplicated'] ) && $_GET['duplicated'] === 'true' ) {
			echo '<div class="notice notice-success"><p>' . __( 'Item copied.' ) . '</p></div>';
		}
	}

	/**
	 *
	 */
	public function process_duplicate_term() {
		check_admin_referer( 'ic-duplicate-term' );

		$term_id  = (int) filter_input( INPUT_GET, 'term_id' );
		$taxonomy = filter_input( INPUT_GET, 'taxonomy' );

		$term = $this->duplicate_term( $term_id, $taxonomy );

		if ( is_wp_error( $term ) ) {
			wp_die( $term->get_error_message() );
		}

		$url = wp_get_referer();
		$url = add_query_arg( 'duplicated', 'true', $url );
		wp_safe_redirect( $url );
		die();
	}

	/**
	 * @param string[] $actions .
	 * @param WP_Term  $tag     .
	 *
	 * @return array
	 */
	public function add_tag_row_action( $actions, $tag ) {
		$actions['duplocate'] = sprintf( "<a href=\"%s\">%s</a>", esc_url( $this->get_duplicate_term_url( $tag ) ), __( 'Clone', 'duplicate-term' ) );

		return $actions;
	}

	/**
	 * @param WP_term $tag .
	 *
	 * @return string
	 */
	private function get_duplicate_term_url( $tag ) {
		return wp_nonce_url( add_query_arg( [
			'term_id'  => $tag->term_id,
			'taxonomy' => $tag->taxonomy,
			'action'   => 'ic-duplicate-term',
		], admin_url( 'admin-post.php' ) ), 'ic-duplicate-term' );
	}

	/**
	 * @param string $name     .
	 * @param string $taxonomy .
	 * @param int    $parent   .
	 *
	 * @return string
	 */
	private function get_new_term_name( $name, $taxonomy, $parent ) {
		$i = 1;

		do {
			$new_name = sprintf( __( "%s (Clone %d)", 'duplicate-term' ), $name, $i ++ );
		} while ( term_exists( $new_name, $taxonomy, $parent ) );

		return $new_name;
	}

	/**
	 * @param int    $term_id  .
	 * @param string $taxonomy .
	 *
	 * @return WP_Error|WP_Term
	 */
	private function duplicate_term( $term_id, $taxonomy ) {
		global $wpdb;

		$term = get_term( $term_id, $taxonomy );

		if ( is_wp_error( $term ) ) {
			return $term;
		}

		$new_term = wp_insert_term( $this->get_new_term_name( $term->name, $term->taxonomy, $term->parent ), $term->taxonomy, array(
			'description' => $term->description,
			'parent'      => $term->parent,
		) );

		if ( is_wp_error( $new_term ) ) {
			return $new_term;
		}

		$sql = $wpdb->prepare( sprintf( "INSERT INTO %s (`term_id`, `meta_key`, `meta_value`) SELECT %%d, `meta_key`, `meta_value`  FROM %s WHERE `term_id` = %%d", $wpdb->termmeta, $wpdb->termmeta ), $new_term['term_id'], $term_id );
		$wpdb->query( $sql );

		return get_term( $new_term['term_id'], $taxonomy );
	}
}

( new Plugin() )->add_hooks();
