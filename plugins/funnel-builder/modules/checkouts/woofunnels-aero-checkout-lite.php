<?php
if ( ! function_exists( 'wfacp_pro_dependency' ) ) {

	/**
	 * Function to check if wcct_finale pro version is loaded and activated or not?
	 * @return bool True|False
	 */
	function wfacp_pro_dependency() {
		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		$is_funnel_pro = in_array( 'funnel-builder-pro/funnel-builder-pro.php', $active_plugins, true ) || array_key_exists( 'funnel-builder-pro/funnel-builder-pro.php', $active_plugins );

		$is_funnel_basic = in_array( 'funnel-builder-basic/funnel-builder-basic.php', $active_plugins, true ) || array_key_exists( 'funnel-builder-basic/funnel-builder-basic.php', $active_plugins );
		$is_aero_pro = in_array( 'woofunnels-aero-checkout/woofunnels-aero-checkout.php', $active_plugins, true ) || array_key_exists( 'woofunnels-aero-checkout/woofunnels-aero-checkout.php', $active_plugins );

		return $is_aero_pro || $is_funnel_basic || $is_funnel_pro;
	}
}

if ( wfacp_pro_dependency() ) {
	return;
}

if ( ! class_exists( 'WFACP_Core' ) ):
	#[AllowDynamicProperties]

 final class WFACP_Core {

		private static $ins = null;
		private static $_registered_entity = [];
		public $is_dependency_exists = true;
		private $dir = '';

		private $url = '';
		/**
		 * @var WFACP_Template_loader
		 */
		public $template_loader;

		/**
		 * @var WFACP_public
		 */
		public $public;
		/**
		 * @var WFACP_Order_pay
		 */
		public $pay;
		/**
		 * @var WFACP_Customizer
		 */
		public $customizer;


		/**
		 * @var WFACP_Template_Importer
		 */
		public $importer;

		/**
		 * @var WFACP_Reporting
		 */
		public $reporting;

		/**
		 * @var WFACP_Embed_Form_loader
		 */
		public $embed_forms;

		/**
		 * @var WFACP_Role_Capability
		 */
		public $role;

		/**
		 * Using protected method no one create new instance this class
		 * WFACP_Core constructor.
		 */
		protected function __construct() {

			$this->definition();
			$this->do_dependency_check();
			/**
			 * Initiates and loads FunnelKit start file
			 */
			if ( true === $this->is_dependency_exists ) {

				/**
				 * Loads common file
				 */
				$this->load_commons();
			}
		}

		private function definition() {


			define( 'WFACP_VERSION', '3.12.2' );
			define( 'WFACP_MIN_WP_VERSION', '4.9' );
			define( 'WFACP_MIN_WC_VERSION', '3.3' );
			define( 'WFACP_SLUG', 'wfacp' );
			define( 'WFACP_TEXTDOMAIN', 'woofunnels-aero-checkout' );
			define( 'WFACP_FULL_NAME', 'Aero: Custom WooCommerce Checkout Pages Lite' );
			define( 'WFACP_PLUGIN_FILE', __FILE__ );
			define( 'WFACP_PLUGIN_DIR', __DIR__ );
			define( 'WFACP_WEB_FONT_PATH', __DIR__ . '/assets/google-web-fonts' );
			define( 'WFACP_TEMPLATE_COMMON', plugin_dir_path( WFACP_PLUGIN_FILE ) . '/public/template-common' );
			define( 'WFACP_BUILDER_DIR', plugin_dir_path( WFACP_PLUGIN_FILE ) . 'builder' );
			define( 'WFACP_TEMPLATE_DIR', plugin_dir_path( WFACP_PLUGIN_FILE ) . '/public/templates' );
			define( 'WFACP_PLUGIN_URL', untrailingslashit( plugin_dir_url( WFACP_PLUGIN_FILE ) ) );
			define( 'WFACP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			define( 'WFACP_TEMPLATE_UPLOAD_DIR', WP_CONTENT_DIR . '/uploads/wfacp_templates/' );
			( defined( 'WFACP_IS_DEV' ) && true === WFACP_IS_DEV ) ? define( 'WFACP_VERSION_DEV', time() ) : define( 'WFACP_VERSION_DEV', WFACP_VERSION );

			$this->dir = plugin_dir_path( __FILE__ );
			$this->url = untrailingslashit( plugin_dir_url( __FILE__ ) );
		}

		private function do_dependency_check() {
			include_once WFACP_PLUGIN_DIR . '/woo-includes/woo-functions.php';
			if ( ! wfacp_is_woocommerce_active() ) {
				$this->is_dependency_exists = false;
			}
		}

		private function load_commons() {

			require WFACP_PLUGIN_DIR . '/includes/class-wfacp-common-helper.php';
			require WFACP_PLUGIN_DIR . '/includes/class-wfacp-common.php';
			require WFACP_PLUGIN_DIR . '/includes/class-wfacp-optimizations.php';
			require WFACP_PLUGIN_DIR . '/includes/class-compatibilities.php';


			require WFACP_PLUGIN_DIR . '/includes/class-wfacp-ajax-controller.php';


			$this->importer_files();
			WFACP_Common::init();
			$this->load_hooks();
		}

		private function load_hooks() {
			/**
			 * Initialize Localization
			 */
			add_action( 'init', array( $this, 'localization' ) );
			add_action( 'plugins_loaded', array( $this, 'load_classes' ), 1 );
			add_action( 'plugins_loaded', array( $this, 'register_classes' ), 2 );
			add_action( 'activated_plugin', array( $this, 'maybe_flush_permalink' ) );
			add_action( 'wfacp_before_loaded', [ $this, 'init_elementor' ] );
		}

		/**
		 * @return null|WFACP_Core
		 */
		public static function get_instance() {
			if ( is_null( self::$ins ) ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		public static function register( $short_name, $class ) {

			if ( ! isset( self::$_registered_entity[ $short_name ] ) ) {
				self::$_registered_entity[ $short_name ] = $class;
			}
		}

		public function localization() {
			load_plugin_textdomain( 'woofunnels-aero-checkout', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		public function load_classes() {


			require WFACP_PLUGIN_DIR . '/includes/functions.php';
			require WFACP_PLUGIN_DIR . '/admin/class-wfacp-admin.php';

			if ( is_admin() ) {
				require WFACP_PLUGIN_DIR . '/admin/includes/class-bwf-admin-settings.php';
			}

			// Import and Export Classes
			require WFACP_PLUGIN_DIR . '/admin/class-wfacp-exporter.php';
			require WFACP_PLUGIN_DIR . '/admin/class-wfacp-importer.php';

			require WFACP_PLUGIN_DIR . '/includes/class-dynamic-merge-tags.php';
			require WFACP_PLUGIN_DIR . '/builder/customizer/class-wfacp-customizer.php';
			require WFACP_PLUGIN_DIR . '/includes/class-embed-form-loader.php';
			require WFACP_PLUGIN_DIR . '/includes/class-wfacp-template-loader.php';
			require WFACP_PLUGIN_DIR . '/public/class-wfacp-public.php';
			require WFACP_PLUGIN_DIR . '/includes/class-order-pay.php';
			require WFACP_PLUGIN_DIR . '/includes/class-mobile-detect.php';
			require WFACP_PLUGIN_DIR . '/includes/class-wfacp-reporting.php';
			require WFACP_PLUGIN_DIR . '/includes/class-wfacp-role-capability.php';

		}

		public function register_classes() {
			do_action( 'wfacp_before_loaded' );
			$load_classes = self::get_registered_class();
			if ( is_array( $load_classes ) && count( $load_classes ) > 0 ) {
				foreach ( $load_classes as $access_key => $class ) {
					$this->$access_key = $class::get_instance();
				}

				$this->remove_embed_form();
				do_action( 'wfacp_loaded' );
			}
		}

		public static function get_registered_class() {
			return self::$_registered_entity;
		}

		public function maybe_flush_permalink( $plugin ) {
			if ( 'woocommerce/woocommerce.php' !== $plugin ) {
				return;
			}
			update_option( 'bwf_needs_rewrite', 'yes', true );
		}


		private function remove_embed_form() {
			if ( class_exists( 'WFACPEF_Core' ) ) {
				$embed_form_instance = WFACPEF_Core();
				remove_action( 'wfacp_loaded', [ $embed_form_instance, 'wfacp_loaded' ] );

			}
		}


		private function importer_files() {
			require WFACP_PLUGIN_DIR . '/importer/interface-import-export.php';
			require WFACP_PLUGIN_DIR . '/importer/class-wfacp-template-importer.php';
			require WFACP_PLUGIN_DIR . '/importer/class-wfacp-customizer-embed-form-importer.php';

			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				include_once WFACP_PLUGIN_DIR . '/importer/class-wfacp-elementor-importer.php';
			}
			add_action( 'wp_loaded', [ $this, 'load_divi_importer' ], 150 );
			do_action( 'wfacp_importer' );
			require WFACP_PLUGIN_DIR . '/importer/class-wfacp-gutenberg-importer.php';
		}

		public function load_divi_importer() {

			$response = WFACP_Common::check_builder_status( 'divi' );
			if ( true === $response['found'] && empty( $response['error'] ) ) {
				require WFACP_PLUGIN_DIR . '/importer/class-wfacp-divi-importer.php';
			}

			$response = WFACP_Common::check_builder_status( 'oxy' );

			if ( true === $response['found'] && empty( $response['error'] ) ) {

				require WFACP_PLUGIN_DIR . '/importer/class-wfacp-oxy-importer.php';
			}

		}

		/**
		 * @param $path
		 * Return plugin full path
		 */
		public function dir( $path = '' ) {
			if ( empty( $path ) ) {
				$this->dir;
			}
			$dir = $this->dir . $path;
			if ( file_exists( $dir ) ) {
				return $dir;
			}

			return $this->dir;
		}

		/**
		 * @param $path
		 * Return plugin full path
		 */
		public function url( $path = '' ) {
			if ( empty( $path ) ) {
				$this->url;
			}
			$url = $this->url . $path;

			return $url;
		}

		public function init_elementor() {

			add_post_type_support( 'wfacp_checkout', 'elementor' );
			require_once WFACP_PLUGIN_DIR . '/builder/elementor/class-wfacp-elementor.php';
			require_once WFACP_PLUGIN_DIR . '/builder/divi/class-wfacp-divi.php';
			if ( ! WFACP_Common::is_customizer() ) {
				require_once WFACP_PLUGIN_DIR . '/builder/oxygen/class-wfacp-oxy.php';
			}
			require_once WFACP_PLUGIN_DIR . '/builder/gutenberg/class-wfacp-gutenberg.php';
		}

		/**
		 * to avoid unserialize of the current class
		 */
		public function __wakeup() {
			throw new ErrorException( 'WFACP_Core can`t converted to string' );
		}

		/**
		 * to avoid serialize of the current class
		 */
		public function __sleep() {
			throw new ErrorException( 'WFACP_Core can`t converted to string' );
		}

		/**
		 * To avoid cloning of current class
		 */
		protected function __clone() {
		}

	}
endif;

if ( ! function_exists( 'WFACP_Core' ) ) {
	function WFACP_Core() {

		return WFACP_Core::get_instance();
	}
}

WFACP_Core();

