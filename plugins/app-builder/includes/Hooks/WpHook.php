<?php

/**
 * class WpHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      1.2.0
 *
 */

namespace AppBuilder\Hooks;

use WP_Comment;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

class WpHook {

	public function __construct() {
		$args       = array(
			'public' => true,
		);
		$post_types = get_post_types( $args );
		foreach ( $post_types as $post_type ) {
			add_filter( "rest_prepare_$post_type", array( $this, 'add_acf_fields_to_post_type' ), 10, 3 );
		}
	}

	/**
	 *
	 * Add afc_fields fields to post type
	 *
	 * @param $response
	 * @param $post
	 * @param $request
	 *
	 * @return mixed|void
	 */

	public function add_acf_fields_to_post_type( $response, $post, $request ) {
		$data = $response->get_data();

		if ( isset( $data['acf'] ) && function_exists( 'get_field_objects' ) ) {
			if ( ! empty( $data['acf'] ) ) {
				$data['afc_fields'] = get_field_objects( $data['id'] );
			} else {
				unset( $data['acf'] );
			}
		}

		$response->set_data( $data );
		return $response;
	}

}
