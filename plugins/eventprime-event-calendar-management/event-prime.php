<?php
/**
 * Plugin Name: EventPrime – Modern Events Calendar, Bookings and Tickets
 * Plugin URI: https://theeventprime.com
 * Description: Beginner-friendly Events Calendar plugin to create free as well as paid Events. Includes Event Types, Event Sites & Performers too.
 * Version: 3.4.4
 * Author: Metagauss
 * Author URI: https://profiles.wordpress.org/metagauss/
 * Text Domain: eventprime-event-calendar-management
 * Domain Path: /languages
 * Requires at least: 4.8
 * Tested up to: 6.4
 * Requires PHP: 5.6
 *
 * @package EventPrime
 */

defined('ABSPATH') || exit();

if( ! defined( 'EP_PLUGIN_FILE' ) ) {
    define( 'EP_PLUGIN_FILE', __FILE__ );
}

// Load composer autoloader file
require dirname( EP_PLUGIN_FILE ) . '/vendor/autoload.php';

// Include the main EventPrime class
if( ! class_exists( 'EventPrime', false ) ) {
    include_once dirname( EP_PLUGIN_FILE ) . '/includes/class-eventprime.php';
}

/**
 * Returns the main instance of EventPrime
 *
 * @since 3.0.0
 * @return EventPrime
 */
function EP() {
    return EventPrime::instance();
}

EP();
