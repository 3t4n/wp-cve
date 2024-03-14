<?php
/**
 * This file contains some generic REST functions we might need.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

use function Nelio_Content\Helpers\flow;

class Nelio_Content_Generic_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Author_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Generic_REST_Controller the single instance of this class.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	/**
	 * Hooks into WordPress.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

	}//end init()

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {

		register_rest_route(
			nelio_content()->rest_namespace,
			'/social/reset',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'reset_auto_social_messages' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/social/pause-publication',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'pause_publication' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'paused' => array(
							'required'          => true,
							'type'              => 'boolean',
							'validate_callback' => 'nc_can_be_bool',
							'sanitize_callback' => 'nc_bool',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/notifications/comment',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'run_new_comment_action' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'authorId' => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'comment'  => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
						'date'     => array(
							'required'          => true,
							'type'              => 'date',
							'validate_callback' => 'nc_is_datetime',
						),
						'postId'   => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/notifications/task',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'run_update_task_action' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'assigneeId' => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'assignerId' => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'completed'  => array(
							'required'          => true,
							'type'              => 'boolean',
							'validate_callback' => 'nc_can_be_bool',
							'sanitize_callback' => 'nc_bool',
						),
						'dateDue'    => array(
							'required'          => true,
							'type'              => 'date',
							'validate_callback' => 'nc_is_datetime',
						),
						'isNewTask'  => array(
							'required'          => true,
							'type'              => 'boolean',
							'validate_callback' => 'nc_can_be_bool',
							'sanitize_callback' => 'nc_bool',
						),
						'postId'     => array(
							'required'          => false,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'task'       => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/settings/update-profiles',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_profiles' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'profiles' => array(
							'required'          => true,
							'type'              => 'boolean',
							'validate_callback' => 'nc_can_be_bool',
							'sanitize_callback' => 'nc_bool',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/plugin/clean',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'clean_plugin' ),
					'permission_callback' => array( $this, 'check_if_user_can_deactivate_plugin' ),
					'args'                => array(
						'_nonce' => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => flow( 'trim', 'nc_is_not_empty' ),
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
					),
				),
			)
		);

	}//end register_routes()

	public function check_if_user_can_deactivate_plugin() {
		return current_user_can( 'deactivate_plugin', nelio_content()->plugin_file );
	}//end check_if_user_can_deactivate_plugin()

	/**
	 * Pauses or resumes social publication.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function reset_auto_social_messages() {
		$sharer = Nelio_Content_Auto_Sharer::instance();
		$sharer->reset();
		return new WP_REST_Response( true, 200 );
	}//end reset_auto_social_messages()

	/**
	 * Pauses or resumes social publication.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function pause_publication( $request ) {

		$is_paused = $request['paused'];

		// Note. Use error_logs for logging this function or you won't see anything.
		$data = array(
			'method'  => 'PUT',
			'timeout' => apply_filters( 'nelio_content_request_timeout', 30 ),
			'headers' => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'    => wp_json_encode(
				array(
					'url'                        => home_url(),
					'timezone'                   => nc_get_timezone(),
					'language'                   => nc_get_language(),
					'isMessagePublicationPaused' => $is_paused,
				)
			),
		);

		$url    = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		$result = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $result );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		$result    = json_decode( $result['body'] );
		$is_paused = $result->isMessagePublicationPaused; // phpcs:ignore

		return new WP_REST_Response( $is_paused, 200 );

	}//end pause_publication()

	/**
	 * Runs an action so that post followers can be notified when a new comment has been added to a post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function run_new_comment_action( $request ) {

		$comment = array(
			'author'  => $request['authorId'],
			'comment' => $request['comment'],
			'date'    => $request['date'],
			'post'    => $request['postId'],
		);

		/**
		 * It runs when an editorial comment has been created.
		 *
		 * @param array $comment the comment.
		 * @param int   $user    the user who created the comment.
		 *
		 * @since 2.0.0
		 */
		do_action( 'nelio_content_after_create_editorial_comment', $comment, get_current_user_id() );

		return new WP_REST_Response( true, 200 );
	}//end run_new_comment_action()

	/**
	 * Runs an action so that users related to a task can know it’s been created or updated.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function run_update_task_action( $request ) {

		$task = array(
			'assigneeId' => $request['assigneeId'],
			'assignerId' => $request['assignerId'],
			'completed'  => $request['completed'],
			'dateDue'    => $request['dateDue'],
			'postId'     => isset( $request['postId'] ) ? $request['postId'] : 0,
			'task'       => $request['task'],
		);

		if ( $request['isNewTask'] ) {
			/**
			 * It runs when an editorial task has been created.
			 *
			 * @param array $task the task.
			 * @param int   $user the user who created the task.
			 *
			 * @since 2.0.0
			 */
			do_action( 'nelio_content_after_create_editorial_task', $task, get_current_user_id() );
		} else {
			/**
			 * It runs when an editorial task has been updated.
			 *
			 * @param array $task the task.
			 * @param int   $user the user who updated the task.
			 * @param array $task the task.
			 *
			 * @since 2.0.0
			 */
			do_action( 'nelio_content_after_update_editorial_task', $task, get_current_user_id() );
		}//end if

		return new WP_REST_Response( true, 200 );
	}//end run_update_task_action()

	/**
	 * Updates the setting that track whether the site has any connected social profiles.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function update_profiles( $request ) {

		$has_profiles = $request['profiles'];
		update_option( 'nc_has_social_profiles', $has_profiles );
		return new WP_REST_Response( $has_profiles, 200 );

	}//end update_profiles()

	/**
	 * Cleans the plugin. If a reason is provided, it tells our cloud what happened.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function clean_plugin( $request ) {

		$nonce = $request['_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'nelio_content_clean_plugin_data_' . get_current_user_id() ) ) {
			return new WP_Error( 'invalid-nonce' );
		}//end if

		// 1. Clean cloud.
		$reason = ! empty( $request['reason'] ) ? $request['reason'] : 'none';
		$data   = array(
			'method'    => 'DELETE',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'body'      => wp_json_encode( array( 'reason' => $reason ) ),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $result );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Clean database.
		global $wpdb;

		$wpdb->query( // phpcs:ignore
			"DELETE FROM $wpdb->postmeta
			WHERE meta_key LIKE '_nc_%'"
		);

		$wpdb->delete( // phpcs:ignore
			$wpdb->posts,
			array( 'post_type' => 'nc_reference' )
		);

		$wpdb->query( // phpcs:ignore
			"DELETE FROM $wpdb->options
			WHERE option_name LIKE 'nc_%' OR
			      option_name LIKE 'nelio_content_%' OR
			      option_name LIKE 'nelio-content_%'"
		);

		return new WP_REST_Response( true, 200 );

	}//end clean_plugin()

}//end class
