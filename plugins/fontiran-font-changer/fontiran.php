<?php
/**
 * Plugin Name:       فونت ایران
 * Plugin URI:        https://yektadg.ir
 * Description:       نصب فونت و مدیریت آسان تایپوگرافی سایت
 * Version:           3
 * Author:            YektaDg
 * Author URI:        https://yektaDg.ir
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! defined( 'FIRAN_VERSION' ) ) {
	define( 'FIRAN_VERSION', '2.0' );
}


define( 'FIRAN_URL', plugin_dir_url( __FILE__ ) );
define( 'FIRAN_DATA_URL', wp_upload_dir()['baseurl'] . '/' );
define( 'FIRAN_PATH', plugin_dir_path(__FILE__) );
define('FIRAN_DATA', wp_upload_dir()['basedir'] . '/');
define( 'FIRAN_FONTS_URL', FIRAN_DATA_URL .'fonts' );


/**
 * Class FONTITAN
 *
 * Main Plugin class. Acts as a loader of everything else and intializes the plugin
 */
if ( ! class_exists( 'WP_Fontiran' ) ) {
	class WP_Fontiran {

		/**
		 * Plugin instance
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Admin main class
		 *
		 * @var WP_Fontiran_Admin
		 */
		public $admin;

		/**
		 * @var WP_Fontiran_Core
		 */
		public $core;


		/**
		 * Return the plugin instance
		 *
		 * @return WP_Fontiran
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * WP_Fontiran constructor.
		 */
		public function __construct() {
			$this->includes();
			$this->init();
		}
		
		
		/**
		 * Load needed files for the plugin
		 */
		private function includes() {
			
			require_once FIRAN_PATH .'helpers/functions.php';
			if ( is_admin() ) {
				include_once( FIRAN_PATH . 'core/class-admin.php' );
			}
		}
		
		
		/**
		 * Initialize the plugin
		 */
		private function init() {
			
			if ( is_admin() ) {
				$this->admin = new Fontiran_Admin();	
			} else {
				add_action('wp_head',array($this,'fi_enqueue_style'),99999);
			}
			
			
			
			do_action( 'wp_fontiran_loaded' );
		}
		
		
		public function fi_enqueue_style () {
			
			if(file_exists(FIRAN_DATA . 'fontiran_front.css')) {
				wp_enqueue_style("fontiran", FIRAN_DATA_URL . 'fontiran.css');
				wp_enqueue_style("fontiran_fonts", FIRAN_DATA_URL . 'fontiran_front.css');
			}
			
		}
		
		
		
	}

	
}

if ( ! function_exists( 'wp_fontiran' ) ) {
function wp_fontiran() {
	return WP_Fontiran::get_instance();
}
add_action( 'plugins_loaded', 'wp_fontiran' );
}

