<?php
/**
 * Plugin Name: Fami Sales Popup
 * Plugin URI: https://woocommerce.famithemes.com/
 * Description: <strong>Fami Sales Popup</strong> is an influential selling tool which helps to boost your sales. Built with the concept of social proof, the app displays purchase activities on your store via real-time notification popups. When customers know what other people are buying from your store, it creates a positive influence and motivates them to buy your products.
 * Author: Fami Themes
 * Author URI: https://famithemes.com
 * Version: 2.0.0
 * Text Domain: famisp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'famiSalesPopup' ) ) {
	
	class  famiSalesPopup {
		
		public         $version = '2.0.0'; // 1.2.0
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof famiSalesPopup ) ) {
				
				self::$instance = new famiSalesPopup;
				self::$instance->setup_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'plugins_loaded' ) );
				self::$instance->includes();
				add_action( 'after_setup_theme', array( self::$instance, 'after_setup_theme' ) );
				
			}
			
			return self::$instance;
		}
		
		public function after_setup_theme() {
			
		}
		
		public function setup_constants() {
			$this->define( 'FAMISP_VERSION', $this->version );
			$this->define( 'FAMISP_URI', plugin_dir_url( __FILE__ ) );
			$this->define( 'FAMISP_PATH', plugin_dir_path( __FILE__ ) );
		}
		
		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name  Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
		
		public function includes() {
			require_once FAMISP_PATH . 'includes/helpers.php';
			require_once FAMISP_PATH . 'includes/menu-scripts-styles.php';
			require_once FAMISP_PATH . 'includes/load-products-data.php';
			require_once FAMISP_PATH . 'includes/backend.php';
			require_once FAMISP_PATH . 'includes/frontend.php';
		}
		
		public function plugins_loaded() {
			load_plugin_textdomain( 'famisp', false, FAMISP_URI . 'languages' );
			if ( ! class_exists( 'WooCommerce' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'install_woocommerce_admin_notice' ) );
			}
		}
		
		public function install_woocommerce_admin_notice() {
			?>
            <div class="error">
                <p><?php esc_html_e( 'Fami WooCommerce Compare is enabled but not effective. It requires WooCommerce in order to work.', 'famisp' ); ?></p>
            </div>
			<?php
		}
		
	}
}

if ( ! function_exists( 'famisp_init' ) ) {
	function famisp_init() {
		return famiSalesPopup::instance();
	}
	
	famisp_init();
}