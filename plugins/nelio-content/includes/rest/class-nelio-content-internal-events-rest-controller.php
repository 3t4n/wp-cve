<?php
/**
 * This file contains REST endpoints to work with internal events.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      2.5.1
 */

defined( 'ABSPATH' ) || exit;

class Nelio_Content_Internal_Events_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.5.1
	 * @access protected
	 * @var    Nelio_Content_Author_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Internal_Events_REST_Controller the single instance of this class.
	 *
	 * @since  2.5.1
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
	 * @since  2.5.1
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
			'/internal-events',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_events' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

	}//end register_routes()

	/**
	 * Gets the ical body of the specified URL.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_events( $request ) {

		/**
		 * Filters the internal events to show in the calendar.
		 *
		 * @param array  $events The list of internal events to show in the
		 * calendar. Each event has the following attributes:
		 *  - id: string
		 *  - date: string
		 *  - title: string
		 *  - type: string
		 *  - start: string Optional
		 *  - end: string Optional
		 *  - description: string Optional
		 *  - color: string Optional
		 *  - backgroundColor: string Optional
		 *  - editLink: string Optional
		 *  - isDayEvent: boolean Optional
		 *
		 * @since 2.5.1
		 */
		$events = apply_filters( 'nelio_content_internal_events', array() );

		return new WP_REST_Response( $events, 200 );
	}//end get_events()
}//end class
