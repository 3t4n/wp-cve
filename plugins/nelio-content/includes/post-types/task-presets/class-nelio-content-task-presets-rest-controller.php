<?php
/**
 * This file contains the class that defines REST API endpoints for
 * managing task presets.
 *
 * @since 3.2.0
 */

defined( 'ABSPATH' ) || exit;

use Nelio_Content\Zod\Zod as Z;

use function Nelio_Content\Helpers\find;

class Nelio_Content_Task_Presets_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since 3.2.0
	 * @var   Nelio_Content_Task_Presets_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Task_Presets_REST_Controller the single instance of this class.
	 *
	 * @since 3.2.0
	 */
	public static function instance() {
		self::$instance = is_null( self::$instance ) ? new self() : self::$instance;
		return self::$instance;
	}//end instance()

	/**
	 * Hooks into WordPress.
	 *
	 * @since 3.2.0
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
			'/task-presets',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_task_presets' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'presets' => array(
							'required'              => true,
							'validate_task_presets' => array( $this, 'validate_task_presets' ),
							'sanitize_callback'     => array( $this, 'sanitize_task_presets' ),
						),
					),
				),
			)
		);

	}//end register_routes()

	public function validate_task_presets( $presets ) {
		$schema = Z::array( Nelio_Content_Task_Preset::schema() );
		$result = $schema->safe_parse( $presets );
		return $result['success'] ? true : new WP_Error( 'parse-error', $result['error'] );
	}//end validate_task_presets()

	public function sanitize_task_presets( $presets ) {
		return array_map( fn( $p ) => Nelio_Content_Task_Preset::parse( $p ), $presets );
	}//end sanitize_task_presets()

	public function update_task_presets( $request ) {
		$presets = $request->get_param( 'presets' );

		$error = find( $presets, 'is_wp_error' );
		if ( is_wp_error( $error ) ) {
			return $error;
		}//end if

		$presets = array_map( fn( $p ) => $p->save(), $presets );

		$old = get_posts(
			array(
				'fields'      => 'ids',
				'post_type'   => 'nc_task_preset',
				'post_status' => 'draft',
				'exclude'     => wp_list_pluck( $presets, 'ID' ), // phpcs:ignore
				'numberposts' => 50,
			)
		);
		array_map( 'wp_delete_post', $old );

		return new WP_REST_Response( array_map( fn( $p ) => $p->json(), $presets ), 200 );
	}//end update_task_presets()

}//end class
