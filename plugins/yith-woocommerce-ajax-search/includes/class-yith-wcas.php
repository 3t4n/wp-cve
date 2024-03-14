<?php
/**
 * Main class
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS' ) ) {
	/**
	 * YITH WooCommerce Ajax Search
	 *
	 * @since 2.0.0
	 */
	class YITH_WCAS {

		use YITH_WCAS_Trait_Singleton;


		/**
		 * Single instance of the admin class
		 *
		 * @var YITH_WCAS_Admin|YITH_WCAS_Admin_Premium
		 */
		public $admin;


		/**
		 * Settings
		 *
		 * @var YITH_WCAS_Settings
		 */
		public $settings;

		/**
		 * Indexer object
		 *
		 * @var YITH_WCAS_Data_Index_Indexer
		 */
		public $indexer;

		/**
		 * Indexer object
		 *
		 * @var YITH_WCAS_Search;
		 */
		public $search;

		/**
		 * Constructor
		 *
		 * @since 2.0.0
		 */
		protected function __construct() {
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			add_action( 'plugins_loaded', array( $this, 'load' ), 12 );
			add_action( 'init', array( $this, 'load_text_domain' ), 0 );
			add_action( 'init', array( $this, 'load_compatibility' ), 20 );
			add_action( 'rest_api_init', array( $this, 'register_ywcas_endpoint' ) );
			add_action( 'widgets_init', array( $this, 'register_search_widget') );
			add_action( 'after_setup_theme', array( $this, 'legacy_porto_customizations' ) );
		}


		/**
		 * Load classes
		 *
		 * @return void
		 */
		public function load() {

			if ( ! doing_action( 'plugins_loaded' ) ) {
				_doing_it_wrong( __METHOD__, 'This method should be called only once on plugins loaded!', '2.0.0' );
				return;
			}

			$this->settings =  $this->get_class_name( 'YITH_WCAS_Settings' )::get_instance();

			$indexer_class = $this->get_class_name( 'YITH_WCAS_Data_Index_Indexer' );
			$this->indexer  = new $indexer_class();

			$this->search   = YITH_WCAS_Search::get_instance();
			YITH_WCAS_Data_Index_Lookup::get_instance();
			YITH_WCAS_Data_Index_Updater::get_instance();

			$shortcode_class = $this->get_class_name( 'YITH_WCAS_Shortcode' );
			new $shortcode_class();

			YITH_WCAS_Assets::init();

			YITH_WCAS_Gutenberg_Blocks_Controller::get_instance();

			if( class_exists('YITH_WCAS_Data_Search_Boost') ){
				YITH_WCAS_Data_Search_Boost::get_instance();
			}

			if ( $this->is_admin() ) {
				$admin_name  = $this->get_class_name( 'YITH_WCAS_Admin' );
				$this->admin = new $admin_name();

				if( class_exists('YITH_WCAS_Privacy') ){
					YITH_WCAS_Privacy::get_instance();
				}

				if( class_exists('YITH_WCAS_Admin_Boost') ){
					new YITH_WCAS_Admin_Boost();
				}

			}

			if ( ! yith_wcas_is_fresh_block_installation() ) {
				YITH_WCAS_Legacy_Manager::get_instance();
			}


		}

		/**
		 * Load classes for compatibility
		 *
		 * @return void
		 */
		public function load_compatibility() {

			if ( ! defined( 'YITH_WCAS_PREMIUM' ) ) {
				return;
			}

			// YITH Brands.
			if ( defined( 'YITH_WCBR_PREMIUM_INIT' ) && !class_exists('YITH_WCAS_Brands_Add_On_Support') ) {
				require_once YITH_WCAS_INC . 'compatibility/class-yith-wcas-brands-add-on-support.php';
				YITH_WCAS_Brands_Add_On_Support::get_instance();
			}

			// YITH Brands.
			if ( class_exists( 'YITH_Vendors_Premium' ) && !class_exists('YITH_WCAS_Multi_Vendor_Support') ) {
				require_once YITH_WCAS_INC . 'compatibility/class-yith-wcas-multi-vendor-support.php';
				YITH_WCAS_Multi_Vendor_Support::get_instance();
			}

			if ( function_exists( 'yith_plugin_fw_gutenberg_add_blocks' ) ) {
				if ( defined( 'ELEMENTOR_VERSION' ) && function_exists( 'yith_plugin_fw_register_elementor_widgets' ) ) {
					$blocks = include_once YITH_WCAS_DIR . 'plugin-options/elementor/blocks.php';
					yith_plugin_fw_register_elementor_widgets( $blocks, true );
				}
			}
		}

		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 *
		 * Locales found in:
		 *      - WP_LANG_DIR/yith-woocommerce-ajax-search/yith-woocommerce-ajax-search-LOCALE.mo
		 *      - WP_LANG_DIR/plugins/yith-woocommerce-ajax-search-LOCALE.mo
		 */
		public function load_text_domain() {
			$locale = determine_locale();

			/**
			 * APPLY_FILTERS: plugin_locale
			 *
			 * Filter the locale.
			 *
			 * @param   string  $locale       the locale.
			 * @param   string  $text_domain  The text domain.
			 *
			 * @return string
			 */
			$locale = apply_filters( 'plugin_locale', $locale, 'yith-woocommerce-ajax-search' );
			$suffix = '';
			if ( defined( 'YITH_WCAS_PREMIUM' ) ) {
				$suffix = '-premium';
			}

			unload_textdomain( 'yith-woocommerce-ajax-search' );
			load_textdomain( 'yith-woocommerce-ajax-search', WP_LANG_DIR . '/yith-woocommerce-ajax-search' . $suffix . '/yith-woocommerce-ajax-search-' . $locale . '.mo' );
			load_plugin_textdomain( 'yith-woocommerce-ajax-search', false, plugin_basename( YITH_WCAS_DIR ) . '/languages' );
		}

		/**
		 * Check if exist the premium version of the class
		 *
		 * @param   string $class_name  The class name.
		 *
		 * @return string
		 * @author YITH
		 * @since  2.0.0
		 */
		public function get_class_name( $class_name ) {

			if ( class_exists( $class_name . '_Premium' ) ) {
				$class_name = $class_name . '_Premium';
			}

			return $class_name;
		}

		/**
		 * Check if is admin or not and load the correct class
		 *
		 * @return boolean
		 * @since 1.0.0
		 */
		public function is_admin() {
			$check_ajax    = defined( 'DOING_AJAX' ) && DOING_AJAX;
			$check_context = isset( $_REQUEST['context'] ) && 'frontend' === sanitize_text_field( wp_unslash( $_REQUEST['context'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			return is_admin() && ! ( $check_ajax && $check_context );
		}

		/**
		 * Load Plugin Framework
		 *
		 * @return void
		 * @since  1.0.0
		 * @access public
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Register the rest api for the plugins
		 *
		 * @return void
		 */
		public function register_ywcas_endpoint() {
			require_once YITH_WCAS_INC . 'rest-api/class-yith-wcas-rest-controller.php';
			if ( defined( 'YITH_WCAS_PREMIUM' ) ) {
				require_once YITH_WCAS_INC . 'rest-api/class-yith-wcas-rest-controller-premium.php';
				$controller = new YITH_WCAS_REST_Controller_Premium();
			}else{
				$controller = new YITH_WCAS_REST_Controller();
			}

			$controller->register_routes();
		}

		/**
		 * Register the widget
		 *
		 * @return void
		 */
		public function register_search_widget() {
			require_once YITH_WCAS_INC.'class-yith-wcas-search-widget.php';
			register_widget('YITH_WCAS_Ajax_Search_Widget');
		}

		/**
		 * Init the Porto Theme support
		 *
		 * @return void
		 */
		public function legacy_porto_customizations() {
				require_once YITH_WCAS_INC . 'compatibility/class-yith-wcas-porto-support.php';
				YITH_WCAS_Porto_Support::get_instance();
		}
	}
}
