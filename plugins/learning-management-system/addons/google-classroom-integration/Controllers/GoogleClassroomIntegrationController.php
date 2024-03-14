<?php
/**
 * Google CLassroom Integration class controller.
 *
 * @since 1.8.3
 * @package Masteriyo\RestApi
 * @subpackage Controllers
 */

namespace Masteriyo\Addons\GoogleClassroomIntegration\Controllers;

defined( 'ABSPATH' ) || exit;

use League\OAuth2\Client\Grant\RefreshToken;
use Masteriyo\Addons\GoogleClassroomIntegration\Models\GoogleClassroomSetting;
use Masteriyo\Enums\CourseProgressStatus;
use WP_Error;

use Masteriyo\RestApi\Controllers\Version1\PostsController;
use Masteriyo\Helper\Permission;
use Masteriyo\PostType\PostType;
use WP_REST_Response;

class GoogleClassroomIntegrationController extends PostsController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $rest_base = 'google-classroom';

	/** Object type.
	 *

	 *
	 * @var string
	 */
	protected $object_type = 'google_classroom';

	/**
	 * If object is hierarchical.
	 *
	 * @since 1.8.3
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Permission class.
	 *
	 * @since 1.8.3
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.8.3
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.8.3
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_google_courses' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/students',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_users' ),
					'permission_callback' => array( $this, 'create_users_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);
	}


	/**
	 * Check if a given request has access to read items.
	 *
	 * @since 1.8.3
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! current_user_can( 'get_google_classroom' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you cannot list resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Get the courses from google classroom.
	 *
	 * @since 1.8.3
	 */
	public function get_google_courses( $request ) {

		if ( $request['forced'] ) {
			$google_setting_data = ( new GoogleClassroomSetting() )->get_data();
			$google_provider     = create_google_client( $google_setting_data );

			if ( $google_setting_data['refresh_token'] ) {
				$grant               = new RefreshToken();
				$token               = $google_provider->getAccessToken( $grant, array( 'refresh_token' => $google_setting_data['refresh_token'] ) );
				$data_from_classroom = masteriyo_google_classroom_course_data_insertion( $token, $google_provider );
				update_option( 'masteriyo_google_classroom_data_' . masteriyo_get_current_user_id(), $data_from_classroom );

			}
		} else {
			$data_from_classroom = get_option( 'masteriyo_google_classroom_data_' . masteriyo_get_current_user_id() );
		}

		if ( ! empty( $data_from_classroom['courses'] ) ) {
			$google_courses = array();
			foreach ( $data_from_classroom['courses'] as $google_course ) {
								$post = current(
									get_posts(
										array(
											'post_type'   => 'mto-course',
											'post_status' => 'any',
											'meta_query'  => array(
												array(
													'key' => '_google_classroom_course_id',
													'value' => $google_course['id'],
													'compare' => '==',
												),
											),
										)
									)
								);

				if ( ! empty( $post ) ) {
					$course                          = masteriyo_get_course( $post->ID );
					$google_course['course_status']  = $course->get_status();
					$google_course['course_id']      = $course->get_id();
					$google_course['edit_post_link'] = $course->get_edit_post_link();
					$google_course['permalink']      = $course->get_permalink();
				}
				$google_courses[] = $google_course;
			}
			return $google_courses;
		}

		return array();

	}


	/**
	 * Create and enrolled the users to the given google classroom course.
	 *
	 * @since 1.8.3
	 *
	 */
	public function create_users( $request ) {
		//list down the users from the request
		$data      = $request->get_json_params();
		$course_id = $data['course_id'];

		//iterate over users and see if they exists in the database, if not create the user then enroll the user to the given course,
		try {
			foreach ( $data['students'] as $student ) {
				$user       = get_user_by( 'email', $student['email_address'] );
				$email_name = strstr( $student['email_address'], '@', true );

				if ( ! $user ) {
					$user_id = masteriyo_create_new_user(
						sanitize_email( $student['email_address'] ),
						$email_name,
						sanitize_email( $student['email_address'] ),
						'masteriyo_student',
						array(
							'first_name' => sanitize_text_field( strtolower( $student['given_name'] ) ),
							'last_name'  => sanitize_text_field( strtolower( $student['family_name'] ) ),
						)
					);
					$user    = $user_id->get_id();
				}
				if ( ! $user && ! $course_id ) {
					return;}

				if ( masteriyo_has_course_started( $course_id, $user ) ) {
					continue;
				}
				global $wpdb;

				$table_name = $wpdb->prefix . 'masteriyo_user_items';

				$user_items_data = array(
					'item_id'    => $course_id,
					'user_id'    => $user,
					'item_type'  => 'user_course',
					'date_start' => current_time( 'Y-m-d H:i:s' ),
					'status'     => 'active',
				);

				$wpdb->insert(
					$table_name,
					$user_items_data,
					array( '%d', '%d', '%s', '%s', '%s', '%s' )
				);

				$masteriyo_user_activities_table_name = $wpdb->prefix . 'masteriyo_user_activities';
				$masteriyo_user_activities_data       = array(
					'user_id'         => $user,
					'item_id'         => $course_id,
					'activity_status' => CourseProgressStatus::STARTED,
					'activity_type'   => 'course_progress',
					'created_at'      => current_time( 'Y-m-d H:i:s' ),
					'modified_at'     => current_time( 'Y-m-d H:i:s' ),
				);

				$format = array( '%d', '%d', '%s', '%s' );
				$wpdb->insert( $masteriyo_user_activities_table_name, $masteriyo_user_activities_data, $format );
			}
			$students_response = array(
				'message' => 'Students are successfully created.',
			);
			return new WP_REST_Response( $students_response, 200 );
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}
	}


	/**
	 * Get the google classroom 'schema, conforming to JSON Schema.
	 *
	 * @since 1.8.3
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'Client ID'     => array(
					'description' => __( 'Client Id of google cloud console', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'Client Secret' => array(
					'description' => __( 'Client secret of google cloud console', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}



	/**
	 * Check if a given request has access to create an item.
	 *
	 * @since 1.8.3
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_users_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		if ( current_user_can( 'publish_google_classrooms' ) ) {
			return true;
		}

			return false;
	}
}
