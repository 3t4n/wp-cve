<?php
/**
 * This file contains the class that defines REST API endpoints for
 * managing Nelio Content custom fields and placeholders.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      2.5.0
 */

defined( 'ABSPATH' ) || exit;

class Nelio_Content_Placeholders_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.5.0
	 * @access protected
	 * @var    Nelio_Content_Placeholders_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Placeholders_REST_Controller the single instance of this class.
	 *
	 * @since  2.5.0
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
			'/custom-fields',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_custom_fields' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/custom-placeholders',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_custom_placeholders' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

	}//end register_routes()

	/**
	 * Retrieves information about the custom fields supported.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_custom_fields() {

		$post_helper   = Nelio_Content_Post_Helper::instance();
		$custom_fields = $post_helper->get_supported_custom_fields_in_templates();

		return new WP_REST_Response( $custom_fields, 200 );

	}//end get_custom_fields()

	/**
	 * Retrieves information about the custom placeholders supported.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_custom_placeholders() {

		$post_helper         = Nelio_Content_Post_Helper::instance();
		$custom_placeholders = $post_helper->get_supported_custom_placeholders_in_templates();

		return new WP_REST_Response( $custom_placeholders, 200 );

	}//end get_custom_placeholders()

}//end class
