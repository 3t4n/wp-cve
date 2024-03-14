<?php
/**
 * This file contains the class for rendering the calendar page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that renders the calendar page.
 */
class Nelio_Content_Calendar_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		parent::__construct(
			'nelio-content',
			'nelio-content',
			_x( 'Calendar', 'text', 'nelio-content' ),
			nc_can_current_user_use_plugin()
		);

	}//end __construct()

	// @Overrides
	// phpcs:ignore
	protected function add_page_specific_hooks() {

		remove_all_filters( 'admin_notices' );

		add_filter( 'admin_footer_text', '__return_empty_string', 99 );
		add_filter( 'update_footer', '__return_empty_string', 99 );

	}//end add_page_specific_hooks()

	// @Implements
	// phpcs:ignore
	public function enqueue_assets() {

		$script   = 'NelioContent.initPage( "nelio-content-page", %s );';
		$settings = array(
			'icsLinks'                       => $this->get_ics_links(),
			'focusDay'                       => $this->get_focus_day(),
			'numberOfNonCollapsableMessages' => $this->get_number_of_non_collapsable_messages(),
			'externalCalendars'              => $this->get_external_calendars(),
			'calendarTimeFormat'             => $this->get_calendar_time_format(),
			'simplifyCalendarTime'           => $this->simplify_calendar_time(),
		);

		wp_enqueue_style(
			'nelio-content-calendar-page',
			nelio_content()->plugin_url . '/assets/dist/css/calendar-page.css',
			array( 'nelio-content-components' ),
			nc_get_script_version( 'calendar-page' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-calendar-page', 'calendar-page', true );

		wp_add_inline_script(
			'nelio-content-calendar-page',
			sprintf(
				$script,
				wp_json_encode( $settings ) // phpcs:ignore
			)
		);

	}//end enqueue_assets()

	private function get_ics_links() {

		$ics_secret_key = get_option( 'nc_ics_key', false );
		if ( ! $ics_secret_key ) {
			return false;
		}//end if

		$all_link = add_query_arg(
			array(
				'action' => 'nelio_content_calendar_ics_subscription',
				'key'    => md5( 'all' . $ics_secret_key ),
			),
			admin_url( 'admin-ajax.php' )
		);

		$user_link = add_query_arg(
			array(
				'action' => 'nelio_content_calendar_ics_subscription',
				'user'   => wp_get_current_user()->user_login,
				'key'    => md5( wp_get_current_user()->user_login . $ics_secret_key ),
			),
			admin_url( 'admin-ajax.php' )
		);

		return array(
			'all'  => $all_link,
			'user' => $user_link,
		);

	}//end get_ics_links()

	private function get_focus_day() {

		$date = '';
		if ( isset( $_GET['date'] ) ) { // phpcs:ignore
			$date = sanitize_text_field( $_GET['date'] ); // phpcs:ignore
		}//end if

		$year  = '[0-9]{4}';
		$month = '(0[0-9])|(1[012])';
		$day   = '([0-2][0-9])|(3[01])';

		if ( preg_match( "/^($year)-($month)$/", $date ) ) {
			$date .= '-01';
		}//end if

		if ( ! preg_match( "/^($year)-($month)(-($day))?$/", $date ) ) {
			$date = date_i18n( 'Y-m-d' );
		}//end if

		return $date;

	}//end get_focus_day()

	private function get_number_of_non_collapsable_messages() {
		/**
		 * Filters the number of messages that can’t never be collapsed in any given day in the Editorial Calendar.
		 *
		 * @param number $count number the number of non collapsable messages. Default: 6.
		 */
		return apply_filters( 'nelio_content_number_of_non_collapsable_messages_in_calendar', 6 );
	}//end get_number_of_non_collapsable_messages()

	private function get_external_calendars() {
		$calendars = get_option( 'nc_external_calendars', array() );
		if ( ! is_array( $calendars ) || count( $calendars ) === 0 ) {
			return array();
		}//end if

		return array_values(
			array_filter(
				$calendars,
				function( $cal ) {
					return isset( $cal['url'] ) && ! empty( $cal['url'] ) && isset( $cal['name'] );
				}
			)
		);
	}//end get_external_calendars()

	private function get_calendar_time_format() {
		/**
		 * Time format to use in the calendar.
		 *
		 * @param $format string Default: WordPress’ `time_format` option.
		 *
		 * @since 2.2.1
		 */
		return apply_filters( 'nelio_content_calendar_time_format', get_option( 'time_format' ) );
	}//end get_calendar_time_format()

	private function simplify_calendar_time() {
		/**
		 * Whether the plugin should remove “:00” and the “m” in “am/pm” when displaying times in the calendar. It also all spaces.
		 *
		 * @param $simplify boolean Default: true.
		 *
		 * @since 2.2.1
		 */
		return apply_filters( 'nelio_content_simplify_calendar_time_format', true );
	}//end simplify_calendar_time()
}//end class
