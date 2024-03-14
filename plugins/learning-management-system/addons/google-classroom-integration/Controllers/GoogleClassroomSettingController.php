<?php
/**
 * Google classroom setting controller class.
 *
 * @since 1.8.3
 *
 * @package Masteriyo\Addons\GoogleClassroom\RestApi
 */

namespace Masteriyo\Addons\GoogleClassroomIntegration\Controllers;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Addons\GoogleClassroomIntegration\Models\GoogleClassroomSetting;
use Masteriyo\RestApi\Controllers\Version1\CrudController;


use WP_Error;

/**
 * GoogleClassroom Controller class.
 */
class GoogleClassroomSettingController extends CrudController {
	/**
	 * Endpoint namespace.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Post type.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $post_type = 'mto-google_classroom';

	/**
	 * Route base.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $rest_base = 'google-classroom/settings';

	/**
	 * Object type.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $object_type = 'google-classroom-setting';

	/**
	 * Register routes.
	 *
	 * @since 1.8.3
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_google_classroom_setting' ),
					'permission_callback' => array( $this, 'get_google_classroom_setting_permission_check' ),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'save_google_classroom_setting' ),
					'permission_callback' => array( $this, 'save_google_classroom_setting_permission_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/validate',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'validate_settings' ),
					'permission_callback' => array( $this, 'validate_settings_permission_check' ),
				),
			)
		);
	}

	/**
	 * Check if a given request has access to create an item.
	 *
	 * @since 1.8.3
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function save_google_classroom_setting_permission_check( $request ) {
		if ( current_user_can( 'publish_google_classrooms' ) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Check if tha given request has access to create an Item.
	 *
	 * @since 1.8.3
	 */
	public function get_google_classroom_setting_permission_check( $request ) {
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
	 * Check if a given request has access to check validate.
	 *
	 * @since 1.8.3
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function validate_settings_permission_check( $request ) {
		return current_user_can( 'edit_google_classrooms' );
	}

	/**
	 * Return validate
	 *
	 * @since 1.8.3
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function validate_settings() {
		if ( ! masteriyo_is_google_classroom_credentials_set() ) {
			return new WP_Error(
				'google_classroom_credentials_empty',
				__( 'Google credentials are not set', 'masteriyo' ),
				array(
					'status' => 400,
				)
			);
		}

		$setting = new GoogleClassroomSetting();

		return rest_ensure_response( $setting->get_data() );
	}

	/**
	 * Provides the google classroom setting data(client_id, client_secret, account_id)  data
	 *
	 * @since 1.8.3
	 *
	 * @return WP_Error|array
	 */
	public function get_google_classroom_setting() {
		return ( new GoogleClassroomSetting() )->get_data();
	}

	/**
	 * Add google classroom client details to user meta.
	 *
	 * @since 1.8.3
	 *
	 * @param  $request $request Full details about the request.
	 * @return WP_Error|array
	 */
	public function save_google_classroom_setting( $request ) {
		$client_id     = isset( $request['client_id'] ) ? sanitize_text_field( $request['client_id'] ) : '';
		$client_secret = isset( $request['client_secret'] ) ? sanitize_text_field( $request['client_secret'] ) : '';
		$access_code   = isset( $request['access_code'] ) && $request['access_code'] ?? false;
		$setting       = new GoogleClassroomSetting();

		if ( $setting->get( 'client_id' ) !== $request['client_id'] || $setting->get( 'client_secret' ) !== $request['client_secret'] || '' === $request['refresh_token'] ) {
			$setting->set( 'refresh_token', '' );
			$setting->set( 'access_token', '' );

			update_option( 'masteriyo_google_classroom_data_' . masteriyo_get_current_user_id(), array() );
		}
		$setting->set( 'client_id', $client_id );
		$setting->set( 'client_secret', $client_secret );

		if ( masteriyo_is_current_user_admin() ) {
			update_option( 'masteriyo_google_classroom_access_code_enabled', $access_code );

			$setting->set( 'access_code', $access_code );
		}

		$setting->save();

		return rest_ensure_response( $setting->get_data() );
	}

	/**
	 * Checks if a given request has access to get items.
	 *
	 * @since 1.8.3
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_google_classrooms_setting_permission_check( $request ) {
		return current_user_can( 'edit_google_classrooms' );
	}

	/**
	 * Get the google_classroom_settings'schema, conforming to JSON Schema.
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
				'client_id'     => array(
					'description' => __( 'Client Id', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'client_secret' => array(
					'description' => __( 'Client Secret', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'access_code'   => array(
					'description' => __( 'Access Code', 'masteriyo' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
