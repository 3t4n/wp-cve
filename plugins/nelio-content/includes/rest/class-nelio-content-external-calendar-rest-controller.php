<?php
/**
 * This file contains REST endpoints to work with external calendars.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.1.0
 */

defined( 'ABSPATH' ) || exit;

use function Nelio_Content\Helpers\flow;

class Nelio_Content_External_Calendar_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.1.0
	 * @access protected
	 * @var    Nelio_Content_Author_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_External_Calendar_REST_Controller the single instance of this class.
	 *
	 * @since  2.1.0
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
	 * @since  2.1.0
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
			'/external-calendars',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_external_calendars' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_external_calendar' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'url' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_external_calendar' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'url'  => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
						'name' => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => flow( 'trim', 'nc_is_not_empty' ),
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'remove_external_calendar' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'url' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/external-calendar/events',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_events' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'url' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
					),
				),
			)
		);

	}//end register_routes()

	/**
	 * Returns the external calendar list.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_external_calendars() {
		return new WP_REST_Response( get_option( 'nc_external_calendars', array() ), 200 );
	}//end get_external_calendars()

	/**
	 * Creates a new calendar.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function create_external_calendar( $request ) {

		$url      = $request['url'];
		$response = wp_remote_request( $url, array( 'method' => 'GET' ) );

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Error while processing calendar.', 'text', 'nelio-content' )
			);
		}//end if

		if ( ! $this->is_ics_content( $response ) ) {
			return new WP_Error(
				'no-ics-url',
				_x( 'Provided URL doesn’t contain an ICS calendar', 'text', 'nelio-content' )
			);
		}//end if

		$calendar = array(
			'url'  => $url,
			'name' => $this->get_name( $url, $response ),
		);

		$this->save_external_calendar( $calendar );
		return new WP_REST_Response( $calendar, 200 );
	}//end create_external_calendar()

	/**
	 * Renames the given calendar.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function update_external_calendar( $request ) {

		$url  = $request['url'];
		$name = trim( $request['name'] );

		$calendar = $this->get_external_calendar( $url );
		if ( empty( $calendar ) ) {
			return new WP_Error(
				'calendar-not-found',
				_x( 'Calendar not found.', 'text', 'nelio-content' )
			);
		}//end if

		$calendar['name'] = $name;
		$this->save_external_calendar( $calendar );
		return new WP_REST_Response( $calendar, 200 );
	}//end update_external_calendar()

	/**
	 * Removes the given calendar.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function remove_external_calendar( $request ) {

		$url      = $request['url'];
		$calendar = $this->get_external_calendar( $url );
		if ( empty( $calendar ) ) {
			return new WP_REST_Response( true, 200 );
		}//end if

		$calendars = get_option( 'nc_external_calendars', array() );
		$calendars = array_filter(
			$calendars,
			function ( $calendar ) use ( $url ) {
				return $calendar['url'] !== $url;
			}
		);
		update_option( 'nc_external_calendars', array_values( $calendars ) );

		return new WP_REST_Response( true, 200 );

	}//end remove_external_calendar()

	/**
	 * Gets the ical body of the specified URL.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_events( $request ) {
		$url      = $request['url'];
		$response = wp_remote_request( $url, array( 'method' => 'GET' ) );

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Error while processing calendar.', 'text', 'nelio-content' )
			);
		}//end if

		if ( ! $this->is_ics_content( $response ) ) {
			return new WP_Error(
				'no-ics-url',
				_x( 'Provided URL doesn’t contain an ICS calendar', 'text', 'nelio-content' )
			);
		}//end if

		return new WP_REST_Response( $response['body'], 200 );
	}//end get_events()

	private function get_external_calendar( $url ) {
		$calendar = get_option( 'nc_external_calendars', array() );
		foreach ( $calendar as $calendar ) {
			if ( $calendar['url'] === $url ) {
				return $calendar;
			}//end if
		}//end foreach
		return false;
	}//end get_external_calendar()

	private function save_external_calendar( $calendar ) {
		$calendars = get_option( 'nc_external_calendars', array() );
		if ( ! $this->get_external_calendar( $calendar['url'] ) ) {
			array_push( $calendars, $calendar );
		}//end if

		foreach ( $calendars as $key => $existing_cal ) {
			if ( $existing_cal['url'] === $calendar['url'] ) {
				$calendars[ $key ] = $calendar;
			}//end if
		}//end foreach

		update_option( 'nc_external_calendars', $calendars );
	}//end save_external_calendar()

	private function is_ics_content( $response ) {
		return 0 === strpos( $response['body'], 'BEGIN:VCALENDAR' );
	}//end is_ics_content()

	private function get_name( $url, $response ) {
		$count = 30;
		$lines = array_map( 'trim', explode( "\n", $response['body'], $count + 1 ) );

		$count = min( $count, count( $lines ) );
		foreach ( $lines as $line ) {
			if ( 0 === strpos( $line, 'X-WR-CALNAME:' ) ) {
				return str_replace( 'X-WR-CALNAME:', '', $line );
			}//end if
		}//end foreach

		$url = preg_replace( '/^https?:\/\//', '', $url );
		$url = preg_replace( '/\/.*$/', '', $url );
		return $url;
	}//end get_name()
}//end class
