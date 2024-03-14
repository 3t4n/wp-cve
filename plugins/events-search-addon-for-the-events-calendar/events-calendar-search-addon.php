<?php
/**
 * Plugin Name: The Events Calendar Search Addon
 * Description: A simple events search box to find any event quickly for The Events Calendar Free Plugin (by MODERN TRIBE) - <strong>[events-calendar-search placeholder="Search Events" show-events="5" disable-past-events="false" layout="medium" content-type="advance" ]</strong>
 * Plugin URI: https://eventscalendaraddons.com/
 * Version: 1.2.8
 * Requires at least: 4.5
 * Tested up to: 6.3.1
 * Requires PHP: 5.6
 * Stable tag: 1.2.8
 * Author: Cool Plugins
 * Author URI: https://coolplugins.net
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ecsa
 * Domain Path: languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( defined( 'ECSA_VERSION' ) ) {
	return;
}

define( 'ECSA_VERSION', '1.2.8' );
define( 'ECSA_FILE', __FILE__ );
define( 'ECSA_PATH', plugin_dir_path( ECSA_FILE ) );
define( 'ECSA_URL', plugin_dir_url( ECSA_FILE ) );

register_activation_hook( ECSA_FILE, array( 'EventsCalendarSearchAddon', 'activate' ) );
register_deactivation_hook( ECSA_FILE, array( 'EventsCalendarSearchAddon', 'deactivate' ) );


/*
|--------------------------------------------------------------------------
|  Class EventsCalendarSearchAddon
|--------------------------------------------------------------------------
*/
if ( ! class_exists( 'EventsCalendarSearchAddon' ) ) :
	final class EventsCalendarSearchAddon {

		private static $instance = null;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}


		/*
		|--------------------------------------------------------------------------
		|  Add action and shortcode.
		|--------------------------------------------------------------------------
		*/
		private function __construct() {
			add_action( 'plugins_loaded', array( $this, 'ecsa_check_event_calender_installed' ) );
			add_action( 'plugins_loaded', array( $this, 'includes' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'ecsa_register_scripts' ) );
			add_shortcode( 'events-calendar-search', array( $this, 'ecsa_shortcode' ) );
			add_action( 'wp_ajax_ecsa_search_data', 'ecsa_get_searchdata' );
			add_action( 'wp_ajax_nopriv_ecsa_search_data', 'ecsa_get_searchdata' );
		}


		/*
		|--------------------------------------------------------------------------
		|  Check The Event calender is installled or not. If user has not installed yet then show notice
		|--------------------------------------------------------------------------
		*/

		public function ecsa_check_event_calender_installed() {
			if ( ! class_exists( 'Tribe__Events__Main' ) or ! defined( 'Tribe__Events__Main::VERSION' ) ) {
				 add_action( 'admin_notices', array( $this, 'ecsa_Install_ECT_Notice' ) );
			}
		}

		public function ecsa_Install_ECT_Notice() {
			if ( current_user_can( 'activate_plugins' ) ) {
				$url   = 'plugin-install.php?tab=plugin-information&plugin=the-events-calendar&TB_iframe=true';
				$title = __( 'The Events Calendar', 'tribe-events-ical-importer' );
					printf(
						'<div class="error CTEC_Msz"><p>' .
						esc_html( __( '%1$s %2$s', 'tecc1' ) ),
						wp_kses_post( __( 'In order to use <strong> The Events Calendar Search Addon </strong>plugin, Please first install the latest version of', 'tecc1' ) ),
						sprintf(
							'<a href="%s" class="thickbox" title="%s">%s</a>',
							esc_url( $url ),
							esc_html( $title ),
							esc_html( $title ),
						) . '</p></div>'
					);

			}
		}


		/*
		|--------------------------------------------------------------------------
		| Events Search Addon Shortcode
		|--------------------------------------------------------------------------
		*/
		public function ecsa_shortcode( $attributes, $content = null ) {
			$attributes = shortcode_atts(
				array(
					'placeholder'         => '',
					'show-events'         => '',
					'disable-past-events' => '',
					'content-type'           => '',
					'layout'              => '',
				),
				$attributes,
				'ecsa'
			);

			$placeholder   = ( ( $attributes['placeholder'] != '' ) ? $attributes['placeholder'] : __( 'Search Events', 'ecsa' ) );
			$show_events   = ( ( $attributes['show-events'] != '' ) ? $attributes['show-events'] : '10' );
			$disable_past  = ( ( $attributes['disable-past-events'] != '' ) ? $attributes['disable-past-events'] : 'false' );
			$layout        = ( ( $attributes['layout'] != '' ) ? $attributes['layout'] : 'medium' );
			$content_type = ( ( $attributes['content-type'] != '' ) ? $attributes['content-type'] : 'advance' );
		
			$generate_html = ecsa_generate_html( $placeholder, $show_events, $disable_past, $content_type, $layout );
		
			return $generate_html;

		}


		/*
		|--------------------------------------------------------------------------
		| Register scripts and styles
		|--------------------------------------------------------------------------
		*/
		public function ecsa_register_scripts() {
			if ( ! is_admin() ) {
				$nonceVal= wp_create_nonce('ajax-nonce');
				wp_register_style( 'ecsa-styles', ECSA_URL . 'assets/css/ecsa-styles.min.css', ECSA_VERSION, 'all' );
				wp_register_script( 'ecsa-typeahead', ECSA_URL . 'assets/js/typeahead.bundle.min.js', array( 'jquery' ), ECSA_VERSION, true );
				wp_register_script( 'ecsa-handlebars', ECSA_URL . 'assets/js/handlebars-v4.0.11.js', array( 'jquery' ), ECSA_VERSION, true );
				wp_register_script( 'ecsa-script', ECSA_URL . 'assets/js/ecsa-script.min.js', array( 'jquery' ), ECSA_VERSION, true );
				wp_localize_script(
					'ecsa-script',
					'ecsaSearch',
					array(
						'prefetchUpcomingUrl' => admin_url( 'admin-ajax.php?action=ecsa_search_data&display=upcoming&nonce_val='.$nonceVal ),
						'prefetchPastUrl'     => admin_url( 'admin-ajax.php?action=ecsa_search_data&display=past&nonce_val='.$nonceVal ),
						
					)
				);
				wp_register_style( 'ecsa-styles', ECSA_URL . 'assets/css/ecsa-styles.min.css', false, 'all' );

			}
		}


		/*
		|----------------------------------------------------------------------------
		| Load plugin function files here.
		|----------------------------------------------------------------------------
		*/
		public function includes() {
			load_plugin_textdomain( 'ecsa', false, basename( dirname( __FILE__ ) ) . '/languages/' );
			if ( is_admin() ) {

				require_once __DIR__ . '/admin/events-addon-page/events-addon-page.php';
				cool_plugins_events_addon_settings_page( 'the-events-calendar', 'cool-plugins-events-addon', '📅 Events Addons For The Events Calendar' );

				require_once __DIR__ . '/includes/ecsa-feedback-notice.php';
				new ecsaFeedbackNotice();
			}
			require_once __DIR__ . '/includes/ecsa-functions.php';
			require_once __DIR__ . '/includes/ecsa-widget.php';
		}

		/*
		|----------------------------------------------------------------------------
		| Run when activate plugin.
		|----------------------------------------------------------------------------
		*/
		public static function activate() {
			update_option( 'ecsa-v', ECSA_VERSION );
			update_option( 'ecsa-type', 'FREE' );
			update_option( 'ecsa-installDate', gmdate( 'Y-m-d h:i:s' ) );
			update_option( 'ecsa-ratingDiv', 'no' );
		}


		/*
		|----------------------------------------------------------------------------
		| Run when de-activate plugin.
		|----------------------------------------------------------------------------
		*/
		public static function deactivate() {
		}

	}

	function EventsCalendarSearchAddon() {
		return EventsCalendarSearchAddon::get_instance();
	}

	$GLOBALS['EventsCalendarSearchAddon'] = EventsCalendarSearchAddon();

endif;
