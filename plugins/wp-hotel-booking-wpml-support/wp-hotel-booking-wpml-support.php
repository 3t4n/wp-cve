<?php
/**
 * Plugin Name: WP Hotel Booking WPML Support
 * Plugin URI: http://thimpress.com/
 * Description: Multi language CMS support
 * Author: ThimPress
 * Version: 1.8.3
 * Requires at least: 5.8
 * Tested up to: 6.2
 * Requires PHP: 7.0
 * Author URI: http://thimpress.com
 * Tags: wphb
 */

define( 'HOTELBOOKING_WMPL_DIR', plugin_dir_path( __FILE__ ) );
define( 'HOTELBOOKING_WMPL_URI', plugins_url( '', __FILE__ ) );
define( 'HOTELBOOKING_WMPL_VER', '1.8.3' );

if ( ! class_exists( 'WP_Hotel_Booking_Wpml_Support' ) ) {
	/**
	 * Class WP_Hotel_Booking_Wpml_Support.
	 */
	class WP_Hotel_Booking_Wpml_Support {

		/**
		 * @var bool
		 */
		public $is_hotel_active = false;

		/**
		 * WP_Hotel_Booking_Wpml_Support constructor.
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'is_hotel_active' ) );
		}

		/**
		 * Check required plugins active.
		 */
		public function is_hotel_active() {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			if ( ( class_exists( 'TP_Hotel_Booking' ) && is_plugin_active( 'tp-hotel-booking/tp-hotel-booking.php' ) ) || ( is_plugin_active( 'wp-hotel-booking/wp-hotel-booking.php' ) && class_exists( 'WP_Hotel_Booking' ) ) ) {
				$this->is_hotel_active = true;
			}

			if ( ! $this->is_hotel_active || ! class_exists( 'SitePress' ) ) {
				add_action( 'admin_notices', array( $this, 'add_notices' ) );
			} else {
				require_once HOTELBOOKING_WMPL_DIR . 'inc/class-hbwp-support.php';
			}

			$this->load_text_domain();
		}

		/**
		 * Load text domain.
		 */
		public function load_text_domain() {
			$default     = WP_LANG_DIR . '/plugins/wp-hotel-booking-wpml-support-' . get_locale() . '.mo';
			$plugin_file = HOTELBOOKING_WMPL_DIR . '/languages/wp-hotel-booking-wpml-support-' . get_locale() . '.mo';
			$file        = false;
			if ( file_exists( $default ) ) {
				$file = $default;
			} else {
				$file = $plugin_file;
			}
			if ( $file ) {
				load_textdomain( 'wp-hotel-booking-wpml-support', $file );
			}
		}

		/**
		 * Require WPML.
		 */
		public function add_notices() { ?>
            <div class="error">
                <p><?php echo wp_kses( __( 'Please install and active <a href="https://wpml.org/" target="_blank"><strong>WPML Multilingual CMS</strong></a> plugin if you use <strong>WP Hotel Booking WPML Support</strong> add-on.', 'wp-hotel-booking' ), array(
						'a'      => array(
							'href'   => array(),
							'target' => array()
						),
						'strong' => array()
					) ); ?></p>
            </div>
			<?php
		}
	}
}

new WP_Hotel_Booking_Wpml_Support();
