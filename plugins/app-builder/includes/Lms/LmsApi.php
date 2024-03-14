<?php


/**
 * class Lms
 *
 * @link       https://appcheap.io
 * @since      2.5.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Lms;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Response;
use WP_REST_Server;

class LmsApi extends LmsPermission {

	public function __construct() {
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
		$this->rest_base = 'lms';
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/reviews', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_reviews' ],
					'args'                => $this->get_collection_params(),
					'permission_callback' => [ $this, 'read_review_permissions_check' ],
				],
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'write_review' ),
					'permission_callback' => [ $this, 'read_review_permissions_check' ],
				]
			]
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/course', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_course' ],
					'args'                => $this->get_collection_params(),
					'permission_callback' => [ $this, 'read_review_permissions_check' ],
				],
			]
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/quizzes', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_quizzes' ],
					'args'                => $this->get_collection_params(),
					'permission_callback' => [ $this, 'read_review_permissions_check' ],
				],
			]
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/quizzes/start', [
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'start_quizzes' ],
					'args'                => $this->get_collection_params(),
					'permission_callback' => [ $this, 'read_review_permissions_check' ],
				],
			]
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/quizzes/end', [
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'end_quizzes' ],
					'args'                => $this->get_collection_params(),
					'permission_callback' => [ $this, 'read_review_permissions_check' ],
				],
			]
		);
	}

	/**
	 * Do Ajax from plugin
	 *
	 * @param $request
	 * @param $ajax_action String action
	 * @param $ajax_action_name String action name
	 *
	 * @return false|string
	 */
	protected function do_ajax_call( $request, $ajax_action, $ajax_action_name ) {
		/**
		 * Create nonce by action name
		 */
		$nonce = wp_create_nonce( $ajax_action );

		/**
		 * Get data and pass to POST/GET method
		 */
		$body = $request->get_params();

		foreach ( $body as $key => $value ) {
			$_POST[ $key ] = $value;
		}

		/**
		 * Assign nonce to POST/GET method
		 */
		$_REQUEST['nonce'] = $nonce;

		/**
		 * Do Ajax Action
		 */
		ob_start();
		wp_set_auth_cookie( get_current_user_id() );
		do_action( $ajax_action_name );
		$data = ob_get_clean();
		wp_clear_auth_cookie();

		return $data;
	}

	/**
	 * Get course reviews
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_REST_Response
	 * @since 2.5.0
	 *
	 */
	public function get_reviews( $request ) {
		return new WP_Error( 'get_reviews_error', __( "The function have not been implemented yet.", "app_builder" ) );
	}

	/**
	 *
	 * Write review
	 *
	 * @param $request
	 *
	 * @return array|void|WP_Error
	 */
	public function write_review( $request ) {
		return new WP_Error( 'write_review_error', __( "The function have not been implemented yet.", "app_builder" ) );
	}

	/**
	 *
	 * Get course info
	 *
	 * @param $request
	 *
	 * @return array|void|WP_Error
	 */
	public function get_course( $request ) {
		return new WP_Error( 'get_course_error', __( "The function have not been implemented yet.", "app_builder" ) );
	}

	/**
	 *
	 * Get quizzes
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function get_quizzes( $request ) {
		return new WP_Error( 'get_quizzes_error', __( "The function have not been implemented yet.", "app_builder" ) );
	}

	/**
	 * User submit quiz
	 *
	 * @param $request
	 *
	 * @return WP_Error
	 */
	public function end_quizzes( $request ) {
		return new WP_Error( 'get_quizzes_error', __( "The function have not been implemented yet.", "app_builder" ) );
	}

	/**
	 * User start quiz
	 *
	 * @param $request
	 *
	 * @return WP_Error
	 */
	public function start_quizzes( $request ) {
		return new WP_Error( 'get_quizzes_error', __( "The function have not been implemented yet.", "app_builder" ) );
	}
}
