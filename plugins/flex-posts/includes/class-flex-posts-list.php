<?php
/**
 * Flex Posts List Widget
 *
 * @package Flex Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Flex Posts List widget class
 */
class Flex_Posts_List extends Flex_Posts_Widget {

	/**
	 * Set base ID, name & options
	 */
	public function __construct() {
		parent::__construct(
			'flex-posts-list',
			esc_html__( 'Flex Posts', 'flex-posts' ),
			array(
				'description' => esc_html__( 'Displays posts list.', 'flex-posts' ),
			)
		);

		$this->enqueue();
	}

	/**
	 * Get form fields
	 */
	public function get_fields() {
		return apply_filters( 'flex_posts_list_fields', parent::get_fields() );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $instance Saved values from database.
	 */
	public function front( $instance ) {
		flex_posts_display( $instance );
	}
}
