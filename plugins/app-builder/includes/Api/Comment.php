<?php

/**
 * Register Comment API
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 * @author     ngocdt
 *
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

class Comment {

	/**
	 * Registers a REST API route
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		add_filter( 'rest_prepare_comment', array( $this, 'rest_prepare_comment' ), 10, 3 );
		add_filter( 'rest_preprocess_comment', array( $this, 'rest_preprocess_comment' ), 10, 2 );
	}

	/**
	 * @param \WP_REST_Response $response
	 * @param \WP_Comment $comment
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function rest_prepare_comment( \WP_REST_Response $response, \WP_Comment $comment, \WP_REST_Request $request ): \WP_REST_Response {
		$post = get_post( $comment->comment_post_ID );
		if ( $post ) {
			$response->data['post_data'] = $post;
		}

		return $response;
	}

	/**
	 * Add filter captcha comment post
	 *
	 * @param $prepared_comment
	 * @param $request
	 *
	 * @return mixed|void
	 */
	public function rest_preprocess_comment( $prepared_comment, $request ) {
		$validate = apply_filters( 'app_builder_validate_form_data', true, $request, 'CommentPost' );
		if ( is_wp_error( $validate ) ) {
			return $validate;
		}

		return $prepared_comment;
	}
}
