<?php

/**
 * class Hooks
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      2.9.0
 *
 */

namespace AppBuilder\Lms\MasterStudy;

class Hooks {
	public function __construct() {
		/**
		 * Add REST API support to an already registered post type.
		 */
		add_filter( 'register_post_type_args', [ $this, 'post_type_args' ], 10, 2 );

		/**
		 * Render shortcode
		 */
		add_action( 'rest_api_init', function () {
			register_rest_field(
				'stm-courses',
				'content',
				array(
					'get_callback'    => [ $this, 'render_shortcode' ],
					'update_callback' => null,
					'schema'          => null,
				)
			);

			register_rest_field(
				'stm-courses',
				'excerpt',
				array(
					'get_callback'    => [ $this, 'render_shortcode' ],
					'update_callback' => null,
					'schema'          => null,
				)
			);
		} );

		/**
		 * Add REST API support to an already registered taxonomy.
		 */
		add_filter( 'register_taxonomy_args', [ $this, 'taxonomy_args' ], 10, 2 );
	}

	public function post_type_args( $args, $post_type ) {

		if ( 'stm-courses' === $post_type ) {
			$args['show_in_rest']          = true;
			$args['rest_base']             = 'courses';
			$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
		}

		if ( 'stm-lessons' === $post_type ) {
			$args['show_in_rest']          = true;
			$args['rest_base']             = 'lessons';
			$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
		}

		return $args;
	}

	public function render_shortcode( $object, $field_name, $request ) {
		global $post;

		if ( class_exists( '\WPBMap' ) ) {
			\WPBMap::addAllMappedShortcodes();
		}

		$post = get_post( $object['id'] );

		$output = array();

		//Apply the_content's filter, one of them interpret shortcodes
		switch ( $field_name ) {
			case 'content':
				$output['rendered'] = apply_filters( 'the_content', $post->post_content );
				break;
			case 'excerpt':
				$output['rendered'] = apply_filters( 'the_excerpt', $post->post_excerpt );
				break;
		}

		$output['protected'] = false;

		return $output;
	}

	public function taxonomy_args( $args, $taxonomy_name ) {

		if ( 'stm_lms_course_taxonomy' === $taxonomy_name ) {
			$args['show_in_rest']          = true;
			$args['rest_base']             = 'course-categories';
			$args['rest_controller_class'] = 'WP_REST_Terms_Controller';
		}

		return $args;
	}
}
