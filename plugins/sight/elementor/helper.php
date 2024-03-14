<?php
/**
 * Elementor Integration Help.
 *
 * @package Sight
 */

namespace Sight_Elementor;

/**
 * Elementor Control Point
 *
 * @since 1.0.0
 */
class Sight_Elementor_Helper {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'wp_ajax_handler_custom_posts', array( $this, 'handler_custom_posts' ) );
		add_action( 'wp_ajax_nopriv_handler_custom_posts', array( $this, 'handler_custom_posts' ) );
		add_action( 'wp_ajax_handler_post_title', array( $this, 'handler_post_title' ) );
		add_action( 'wp_ajax_nopriv_handler_post_title', array( $this, 'handler_post_title' ) );
	}

	/**
	 * Get custom posts.
	 */
	public function handler_custom_posts() {

		$posts = array();

		$more = false;

		$search_results = new \WP_Query(
			array(
				'post_status'         => 'publish',
				'post_type'           => 'post',
				'ignore_sticky_posts' => 1,
				's'                   => sanitize_text_field( $_REQUEST['q'] ),
				'paged'               => sanitize_text_field( $_REQUEST['paged'] ),
				'posts_per_page'      => sanitize_text_field( $_REQUEST['posts_per_page'] ),
			)
		);

		if ( $search_results->have_posts() ) {
			while ( $search_results->have_posts() ) {
				$search_results->the_post();

				$posts[] = array(
					'id'   => get_the_ID(),
					'text' => get_the_title(),
				);

				$more = true;
			}
		}

		wp_send_json( array(
			'results' => $posts,
			'more'    => $more,
		) );
	}

	/**
	 * Get post title.
	 */
	public function handler_post_title() {
		$post_id = sanitize_text_field( $_REQUEST['post_id'] );

		if ( $post_id ) {
			echo esc_html( get_the_title( $post_id ) );
		}

		die();
	}
}

new Sight_Elementor_Helper();
