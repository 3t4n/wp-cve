<?php
/*
Plugin Name:The Events Calendar Countdown Addon
Plugin URI:https://eventscalendaraddons.com/
Description:The Events Calendar CountDown Addon provides the ability to create Beautiful Countdown for <a href="http://wordpress.org/plugins/the-events-calendar/">The Events Calendar (by Modern Tribe)</a> events with just a few clicks.
Version:1.4.8
License:GPL2
Author:Cool Plugins
Author URI:https://coolplugins.net/
License URI:https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain:tecc
*/

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
if ( ! defined( 'TECC_VERSION_CURRENT' ) ) {
	define( 'TECC_VERSION_CURRENT', '1.4.8' );
}

define( 'TECC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TECC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'TECC_JS_DIR' ) ) {
	define( 'TECC_JS_DIR', TECC_PLUGIN_URL . 'assets/js' );
}
if ( ! defined( 'TECC_CSS_URL' ) ) {
	define( 'TECC_CSS_URL', TECC_PLUGIN_URL . 'assets/css' );
}

/**
 * Cool EventsCalendarCountdown main class.
 */


if ( ! class_exists( 'EventsCalendarCountdown' ) ) {

	class EventsCalendarCountdown {

		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			/*** Installation and uninstallation hooks */
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			add_action( 'plugins_loaded', array( $this, 'tecc_check_event_calender_installed' ) );
			$this->tecc_require_files();
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'tecc_settings_page' ) );
		}

		/*
		Check The Event calender is installled or not. If user has not installed yet then show notice
		*/
		public function tecc_check_event_calender_installed() {
			load_plugin_textdomain( 'tecc', false, basename( dirname( __FILE__ ) ) . '/languages/' );
			if ( ! class_exists( 'Tribe__Events__Main' ) || ! defined( 'Tribe__Events__Main::VERSION' ) ) {
				add_action( 'admin_notices', array( $this, 'Install_TECC_Notice' ) );
			}
		}

		public function Install_TECC_Notice() {
			if ( current_user_can( 'activate_plugins' ) ) {
				$url   = 'plugin-install.php?tab=plugin-information&plugin=the-events-calendar&TB_iframe=true';
				$title = __( 'The Events Calendar', 'tribe-events-ical-importer' );

				printf(
					'<div class="error CTEC_Msz"><p>' .
					esc_html( __( '%1$s %2$s', 'tecc1' ) ),
					esc_html( __( 'In order to use our plugin, Please first install the latest version of', 'tecc1' ) ),
					sprintf(
						'<a href="%s" class="thickbox" title="%s">%s</a>',
						esc_url( $url ),
						esc_html( $title ),
						esc_html( $title )
					) . '</p></div>'
				);

			}
		}

		public function tecc_require_files() {
			if ( is_admin() ) {

				require_once __DIR__ . '/admin/events-addon-page/events-addon-page.php';
				cool_plugins_events_addon_settings_page( 'the-events-calendar', 'cool-plugins-events-addon', '📅 Events Addons For The Events Calendar' );

				require_once TECC_PLUGIN_DIR . 'includes/tecc-setting-panel.php';
				require_once TECC_PLUGIN_DIR . 'includes/tecc-feedback-notice.php';
				new teccFeedbackNotice();
			}
			require_once TECC_PLUGIN_DIR . 'includes/tecc-shortcode.php';
			new CountdownShortcode();
			require_once TECC_PLUGIN_DIR . 'includes/tecc-functions.php';

		}

		/*** Add links in plugin list page */
		public function tecc_settings_page( $links ) {
			$links[] = '<a style="font-weight:bold" href="' . esc_url( get_admin_url( null, 'admin.php?page=countdown_for_the_events_calendar' ) ) . '">' . __( 'Settings', 'tecc' ) . '</a>';
			return $links;
		}

			// set settings on plugin activation
		public function activate() {
			update_option( 'tecc-v', TECC_VERSION_CURRENT );
			update_option( 'tecc-type', 'FREE' );
			update_option( 'tecc-installDate', gmdate( 'Y-m-d h:i:s' ) );
			update_option( 'tecc-ratingDiv', 'no' );
		}


	} //class end here
}

$tecc = new EventsCalendarCountdown();
