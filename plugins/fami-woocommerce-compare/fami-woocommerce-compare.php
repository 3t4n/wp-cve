<?php
/**
 * Plugin Name: Fami WooCommerce Compare
 * Plugin URI: https://compare.famithemes.com
 * Description: The <strong>Fami WooCommerce Compare</strong> is a plugin for WooCommerce to use to compare all types of products. You can customize all the information, product attributes you want to compare. The plugin provides a visual comparison interface with popup displays when compared and has a comparison page with convenient carousel even when used on mobile devices. Not only is it easy for end users, but the plugin is also designed to facilitate easy customization for developers.
 * Version: 1.0.5
 * Author: Fami Themes
 * Author URI: https://profiles.wordpress.org/zankover/
 * Text Domain: fami-woocommerce-compare
 * Domain Path: /languages/
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'famiWcCompare' ) ) {
	
	class  famiWcCompare {
		
		public         $version = '1.0.5';
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof famiWcCompare ) ) {
				
				self::$instance = new famiWcCompare;
				register_activation_hook( __FILE__, array( self::$instance, 'plugin_activation' ) );
				register_deactivation_hook( __FILE__, array( self::$instance, 'plugin_deactivation' ) );
				self::$instance->setup_constants();
				add_action( 'init', array( self::$instance, 'init' ), 0 );
				add_action( 'plugins_loaded', array( self::$instance, 'plugins_loaded' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array(
					self::$instance,
					'add_action_links'
				) );
				self::$instance->includes();
				
			}
			
			return self::$instance;
		}
		
		public function plugin_activation() {
		
		}
		
		public function plugin_deactivation() {
		
		}
		
		public function init() {
			load_plugin_textdomain( 'fami-woocommerce-compare', false, FAMI_WCP_URL . 'languages' );
		}
		
		public function plugins_loaded() {
			if ( ! function_exists( 'WC' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'install_woocommerce_admin_notice' ) );
			}
		}
		
		public function setup_constants() {
			define( 'FAMI_WCP_URL', plugin_dir_url( __FILE__ ) );
			define( 'FAMI_WCP_PATH', plugin_dir_path( __FILE__ ) );
		}
		
		public function includes() {
			require_once FAMI_WCP_PATH . 'includes/menu-scripts-styles.php';
			require_once FAMI_WCP_PATH . 'includes/class.fami-wc-helper.php';
			
			if ( $this->is_admin() ) {
				require_once FAMI_WCP_PATH . 'includes/backend.php';
			}
			
			require_once FAMI_WCP_PATH . 'includes/class.fami-wc-frontend.php';
			new Fami_Woocompare_Frontend();
			
		}
		
		public function add_action_links( $links ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=fami-wccp' ) . '">' . esc_html__( 'Settings', 'fami-woocommerce-compare' ) . '</a>';
			
			return $links;
		}
		
		public function is_admin() {
			$is_ajax  = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			$is_admin = ( is_admin() || $is_ajax && isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'admin' );
			
			return $is_admin;
		}
		
		public function is_frontend() {
			$is_ajax       = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			$context_check = isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend';
			
			return (bool) ( ! is_admin() || ( $is_ajax && $context_check ) );
		}
		
		
		public function install_woocommerce_admin_notice() {
			?>
            <div class="error">
                <p><?php esc_html_e( 'Fami WooCommerce Compare is enabled but not effective. It requires WooCommerce in order to work.', 'fami-woocommerce-compare' ); ?></p>
            </div>
			<?php
		}
		
	}
}

if ( ! function_exists( 'fami_wccp_init' ) ) {
	function fami_wccp_init() {
		return famiWcCompare::instance();
	}
	
	fami_wccp_init();
}