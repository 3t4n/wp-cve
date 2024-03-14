<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}
/**
 * Plugin Name: Events Calendar by FooEvents
 * Description: Display your events in a stylish calendar on your WordPress website using simple short codes and widgets.
 * Version: 1.7.0
 * Author: FooEvents
 * Plugin URI: https://www.fooevents.com/fooevents-calendar/
 * Author URI: https://www.fooevents.com/
 * Developer: FooEvents
 * Developer URI: https://www.fooevents.com/
 * Text Domain: fooevents-calendar
 *
 * Copyright: © 2009-2023 FooEvents.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

require WP_PLUGIN_DIR . '/fooevents-calendar/config.php';
require WP_PLUGIN_DIR . '/fooevents-calendar/class-fooevents-calendar.php';
require 'vendors/eventbrite/HttpClient.php';

$fooevents_calendar = new FooEvents_Calendar();

/**
 * Delete FooEvents options on uninstall
 */
function uninstall_fooevents_calendar() {

	delete_option( 'globalFooEventsAllDayEvent' );
	delete_option( 'globalFooEventsTwentyFourHour' );

}

register_uninstall_hook( __FILE__, 'uninstall_fooevents_calendar' );
