<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

if ( ! class_exists( 'WFOPP_Core' ) ) {

	/**
	 * Class WFOPP_Core
	 */
	#[AllowDynamicProperties]

  class WFOPP_Core {

		/**
		 * @var null
		 */
		public static $_instance = null;

		/**
		 * @var WFOPP_Admin
		 */
		public $admin;

		/**
		 * @var WFFN_Optin_Pages
		 */
		public $optin_pages;

		/**
		 * @var WFFN_Optin_TY_Pages
		 */
		public $optin_ty_pages;

		/**
		 * @var WFFN_Optin_Form_Controllers
		 */
		public $form_controllers;

		/**
		 * @var WFFN_Optin_Form_Fields_Controller
		 */
		public $form_fields;

		/**
		 * @var WFFN_Optin_Actions
		 */
		public $optin_actions;


		/** @var WFFN_WP_User_AutoLogin */
		public $autologin = null;

		/**
		 * @var array
		 */
		private static $_registered_entity = array(
			'active'   => array(),
			'inactive' => array(),
		);

		/**
		 * WFOPP_Core constructor.
		 */
		public function __construct() {
			/**
			 * Load important variables and constants
			 */
			$this->define_plugin_properties();

			/**
			 * Loads hooks
			 */
			$this->load_hooks();

		}

		/**
		 * Defining constants
		 */
		public function define_plugin_properties() {
			define( 'WFOPP_PLUGIN_FILE', __FILE__ );
			define( 'WFOPP_PLUGIN_DIR', __DIR__ );
			define( 'WFOPP_PLUGIN_URL', untrailingslashit( plugin_dir_url( WFOPP_PLUGIN_FILE ) ) );
		}

		/**
		 * Load classes on plugins_loaded hook
		 */
		public function load_hooks() {
			/**
			 * Initialize Localization
			 */
			add_action( 'init', array( $this, 'localization' ) );
			add_action( 'plugins_loaded', array( $this, 'load_classes' ), 1 );
			add_action( 'plugins_loaded', array( $this, 'register_classes' ), 1 );
			add_action( 'plugins_loaded', array( $this, 'load_optin_core_classes' ), 2 );
			add_action( 'wffn_core_modules_loaded', array( $this, 'load_modules' ) );
			add_action( 'wffn_loaded', array( $this, 'load_steps' ) );
		}

		public function localization() {
			load_plugin_textdomain( 'funnel-builder', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/**
		 *
		 */
		public function load_classes() {

			/**
			 * Loads all the admin
			 */
			$this->load_admin();

			$this->load_includes();

			$this->load_optin_actions();

			$this->load_optin_form_controllers();

			$this->load_optin_form_fields();
		}

		/**
		 * Loads the admin
		 */
		public function load_admin() {
			include_once __DIR__ . '/admin/class-wfopp-admin.php';
			include_once __DIR__ . '/admin/db/class-wfopp-db-tables.php';
		}

		/**
		 * Load includes folder
		 */
		public function load_includes() {
			require __DIR__ . '/merge-tags/class-bwf-optin-tags.php';
		}

		public function load_modules() {
			require __DIR__ . '/modules/optin-pages/class-wffn-optin-pages.php';
			require __DIR__ . '/modules/optin-ty-pages/class-wffn-optin-ty-pages.php';
		}

		/**
		 * Include optin form actions
		 */
		public function load_optin_actions() {
			require __DIR__ . '/modules/optin-pages/includes/class-wffn-optin-action.php';
			require __DIR__ . '/modules/optin-pages/includes/class-wffn-optin-actions.php';
		}

		/**
		 * Include optin form controllers
		 */
		public function load_optin_form_controllers() {
			require __DIR__ . '/modules/optin-pages/includes/class-wffn-optin-form-controller.php';
			require __DIR__ . '/modules/optin-pages/includes/class-wffn-optin-form-controllers.php';
		}

		/**
		 * Include optin form fields
		 */
		public function load_optin_form_fields() {
			require __DIR__ . '/modules/optin-pages/includes/class-wffn-optin-form-field-interface.php';
			require __DIR__ . '/modules/optin-pages/includes/class-wffn-optin-form-field.php';
			require __DIR__ . '/modules/optin-pages/includes/class-wffn-optin-form-fields-controller.php';
		}

		public function load_optin_core_classes() {
			include_once __DIR__ . '/admin/db/class-wffn-db-optin.php';
		}

		/**
		 * @return WFOPP_Core|null
		 */
		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Register classes
		 */
		public function register_classes() {

			$load_classes = self::get_registered_class();
			if ( is_array( $load_classes ) && count( $load_classes ) > 0 ) {
				foreach ( $load_classes as $access_key => $class ) {

					$this->$access_key = $class::get_instance();
				}

				do_action( 'wfopp_loaded' );

			}
		}

		/**
		 * @return mixed
		 */
		public static function get_registered_class() {
			return self::$_registered_entity['active'];
		}

		public static function register( $short_name, $class ) {

			self::$_registered_entity['active'][ $short_name ] = $class;

		}

		/**
		 * Includes steps files
		 *
		 */
		public function load_steps() {
			foreach ( glob( plugin_dir_path( WFOPP_PLUGIN_FILE ) . 'steps/*/class-*.php' ) as $steps_file_name ) {
				require_once( $steps_file_name ); //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			}
		}
	}
}
if ( ! function_exists( 'WFOPP_Core' ) ) {
	/**
	 * @return WFOPP_Core|null
	 */
	function WFOPP_Core() {  //@codingStandardsIgnoreLine
		return WFOPP_Core::get_instance();
	}
}

$GLOBALS['WFOPP_Core'] = WFOPP_Core();
