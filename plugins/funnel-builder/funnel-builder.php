<?php
/**
 * Plugin Name: FunnelKit Funnel Builder
 * Plugin URI: https://funnelkit.com/wordpress-funnel-builder/
 * Description: Create high-converting sales funnels on WordPress that look professional by following a well-guided step-by-step process.
 * Version: 3.2.4
 * Author: FunnelKit
 * Author URI: https://funnelkit.com
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: funnel-builder
 * Elementor tested up to: 3.17.0
 *
 * Requires at least: 5.4.0
 * Tested up to: 6.4.3
 * Requires PHP: 7.4
 * WooFunnels: true
 *
 * Funnel Builder is free software.
 * You can redistribute it and/or modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Funnel Builder is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Funnel Builder. If not, see <http://www.gnu.org/licenses/>.
 */

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

if ( ! class_exists( 'WFFN_Core' ) ) {

	/**
	 * Class WFFN_Core
	 */
	#[AllowDynamicProperties]
	class WFFN_Core {

		/**
		 * @var null
		 */
		public static $_instance = null;

		/**
		 * @var array
		 */
		private static $_registered_entity = array(
			'active'   => array(),
			'inactive' => array(),
		);

		/**
		 * @var WFFN_Admin
		 */
		public $admin;

		public $assets;

		/**
		 * @var WFFN_Steps
		 */
		public $steps;

		/**
		 * @var WFFN_Substeps
		 */
		public $substeps;
		/**
		 * @var WFFN_Data
		 */
		public $data;

		/**
		 * @var WFFN_Logger
		 */
		public $logger;

		/**
		 * @var WFFN_Importer
		 */
		public $import;

		/**
		 * @var WFFN_Remote_Template_Importer
		 */
		public $remote_importer;

		/**
		 * @var WFFN_Page_Builder_Manager
		 */
		public $page_builders;

		/**
		 * @var WFFN_Landing_Pages
		 */
		public $landing_pages;

		/**
		 * @var WFFN_Funnel_Contacts
		 *
		 */
		public $wffn_contacts;

		/**
		 * @var WFFN_Thank_You_WC_Pages
		 */
		public $thank_you_pages;

		/** @var WFFN_Funnels_DB */
		public $funnels_db = null;

		/** @var WFFN_Template_Importer */
		public $importer = null;

		/** @var WFFN_Public */
		public $public = null;

		/** @var WFFN_WP_User_AutoLogin */
		public $autologin = null;

		/** @var WFFN_Role_Capability */
		public $role = null;

		/** @var WFFN_Admin_Notifications */
		public $admin_notifications = null;

		/**
		 * WFFN_Core constructor.
		 */
		public function __construct() {
			/**
			 * Load important variables and constants
			 */
			$this->define_plugin_properties();
			require_once( __DIR__ . '/start.php' );
			require __DIR__ . '/includes/wffn-functions.php';
			add_action( 'plugins_loaded', array( 'WooFunnel_Loader', 'include_core' ), - 1 );

			/**
			 * Loads hooks
			 */
			$this->load_hooks();


		}

		/**
		 * Defining constants
		 */
		public function define_plugin_properties() {

			define( 'WFFN_VERSION', '3.2.4' );
			define( 'WFFN_BWF_VERSION', '1.10.12.04' );

			define( 'WFFN_MIN_WC_VERSION', '3.5.0' );
			define( 'WFFN_MIN_WP_VERSION', '5.4.0' );
			define( 'WFFN_DB_VERSION', '3.3.4' );
			define( 'WFFN_SLUG', 'wffn' );
			define( 'WFFN_PLUGIN_FILE', __FILE__ );
			define( 'WFFN_PLUGIN_DIR', __DIR__ );
			define( 'WFFN_PLUGIN_URL', untrailingslashit( plugin_dir_url( WFFN_PLUGIN_FILE ) ) );
			define( 'WFFN_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			define( 'WFFN_TEMPLATE_UPLOAD_DIR', WP_CONTENT_DIR . '/uploads/wffn_templates/' );
			( defined( 'WFFN_IS_DEV' ) && true === WFFN_IS_DEV ) ? define( 'WFFN_VERSION_DEV', time() ) : define( 'WFFN_VERSION_DEV', WFFN_VERSION );
			( ! defined( 'WFFN_REACT_ENVIRONMENT' ) ) ? define( 'WFFN_REACT_ENVIRONMENT', 1 ) : '';

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


			register_activation_hook( __FILE__, [ $this, 'plugin_activation_hook' ] );

			if ( ! class_exists( 'WFACP_Core' ) ) {

				require __DIR__ . '/modules/checkouts/woofunnels-aero-checkout-lite.php';
			}
			if ( ! class_exists( 'WFOPP_Core' ) ) {
				require __DIR__ . '/modules/optins/woofunnels-optins.php';
			}
			add_action( 'plugins_loaded', [ $this, 'init_oxygen' ], 10 );
			add_action( 'wp_loaded', [ $this, 'load_divi_importer' ], 150 );
			add_action( 'activated_plugin', array( $this, 'check_activation' ) );

		}

		/**
		 *
		 */
		public function load_classes() {
			/**
			 * Loads all the admin
			 */
			$this->load_autoloader();
			$this->load_admin();

			$this->load_includes();

			$this->load_modules();

			$this->load_steps();

			$this->load_commons();

			$this->load_analytics();

		}

		/**
		 * Loads the admin
		 */
		public function load_admin() {
			include_once __DIR__ . '/admin/class-wffn-admin.php';
			include_once __DIR__ . '/admin/class-bwf-admin-breadcrumbs.php';
			include_once __DIR__ . '/admin/class-bwf-admin-settings.php';
			include_once __DIR__ . '/admin/class-wffn-page-builder-manager.php';
			include_once __DIR__ . '/admin/class-wffn-admin-notifications.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-funnels.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-steps.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-substeps.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-funnel-settings.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-api-update-user-preference.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-tools.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-licenses.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-funnel-canvas.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-user-preferences.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-notifications.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-get-recipes.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-setup.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-store-checkout.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-funnel-modules.php';
			include_once __DIR__ . '/admin/rest-api-helpers/class-wffn-rest-api-helpers.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-optin-api-endpoint.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-checkout-api-endpoint.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-wizard.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-funnel-contacts.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-funnel-orders.php';
			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-api-dashboard-endpoint.php';


			/*
			 * 	Order Bump Preview
			 */

			include_once __DIR__ . '/admin/rest-api/class-wfob-bump-rest-api.php';

			/***Global settings***/

			include_once __DIR__ . '/admin/rest-api/class-wffn-rest-global-settings.php';

			/**Global Header */
			include_once __DIR__ . '/admin/includes/class-wffn-header.php';

			/**Automation Recipes Loader */
			include_once __DIR__ . '/admin/class-bwfan-recipe-loader.php';
		}

		/**
		 * Load includes folder
		 */
		public function load_includes() {

			require __DIR__ . '/includes/class-wffn-logger.php';
			require __DIR__ . '/includes/class-wffn-ajax-controller.php';
			require __DIR__ . '/includes/class-wffn-session-handler.php';
			require __DIR__ . '/includes/class-wffn-data.php';
			require __DIR__ . '/includes/class-wffn-funnels-db.php';
			require __DIR__ . '/includes/class-wffn-public.php';
			require __DIR__ . '/includes/class-wffn-funnel.php';
			require __DIR__ . '/includes/class-wffn-woofunnels-support.php';
			require __DIR__ . '/merge-tags/class-bwf-contact-tags.php';
			require __DIR__ . '/includes/class-wffn-rest-controller.php';
			require __DIR__ . '/includes/class-wffn-role-capability.php';
		}

		/**
		 * Include Modules (Landing & thankyou)
		 */
		public function load_modules() {
			require __DIR__ . '/includes/class-wffn-module-common.php';
			require __DIR__ . '/modules/landing-pages/class-wffn-landing-pages.php';
			require __DIR__ . '/modules/thankyou-pages/class-wffn-thank-you-wc-pages.php';
			do_action( 'wffn_core_modules_loaded' );
		}

		/**
		 * Include steps and substeps
		 */
		public function load_steps() {
			require __DIR__ . '/includes/class-wffn-step-base.php';
			require __DIR__ . '/includes/class-wffn-step.php';
			require __DIR__ . '/includes/class-wffn-steps.php';
			require __DIR__ . '/includes/class-wffn-substep.php';
			require __DIR__ . '/includes/class-wffn-substeps.php';
		}

		/**
		 * Includes common functions.
		 */
		public function load_commons() {

			require __DIR__ . '/includes/class-wffn-common.php';

			require __DIR__ . '/importer/interface-import-export.php';
			require __DIR__ . '/importer/class-wffn-template-importer.php';
			require __DIR__ . '/importer/class-wffn-background-importer.php';
			require __DIR__ . '/admin/db/wffn-updater-functions.php';

			include_once __DIR__ . '/admin/class-wffn-importer.php';

			$response = WFFN_Common::check_builder_status( 'elementor' );
			if ( true === $response['found'] && empty( $response['error'] ) ) {
				require __DIR__ . '/importer/class-wffn-elementor-importer.php';
			}

			require __DIR__ . '/importer/class-wffn-wp-editor-importer.php';
			require __DIR__ . '/compatibilities/class-wffn-plugin-compatibilities.php';
			require __DIR__ . '/includes/class-wffn-conversion-tracking-migrator.php';
			WFFN_Common::init();
		}


		public function load_divi_importer() {

			$response = WFFN_Common::check_builder_status( 'divi' );

			if ( true === $response['found'] && empty( $response['error'] ) ) {
				require __DIR__ . '/importer/class-wffn-divi-importer.php';
			}
			$response = WFFN_Common::check_builder_status( 'oxy' );
			if ( true === $response['found'] && empty( $response['error'] ) ) {
				require __DIR__ . '/importer/class-wffn-oxygen-importer.php';
			}

			require __DIR__ . '/importer/class-wffn-gutenberg-importer.php';
		}

		public function load_analytics() {
			if ( class_exists( 'WFACP_Core' ) && wffn_is_wc_active() && ! class_exists( 'WFACP_Contacts_Analytics' ) ) {
				require_once __DIR__ . '/contact-analytics/class-wfacp-contacts-analytics.php';
			}
			if ( class_exists( 'WFOPP_Core' ) && ! class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				require_once __DIR__ . '/contact-analytics/class-wffn-optin-contacts-analytics.php';
			}
			if ( class_exists( 'WFOB_Core' ) && wffn_is_wc_active() && ! class_exists( 'WFOB_Contacts_Analytics' ) ) {
				require_once __DIR__ . '/contact-analytics/class-wfob-contacts-analytics.php';
			}
			if ( class_exists( 'WFOCU_Core' ) && wffn_is_wc_active() && ! class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				require_once __DIR__ . '/contact-analytics/class-wfocu-contacts-analytics.php';
			}
		}

		/**
		 * @return WFFN_Core|null
		 */
		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		public function localization() {
			load_plugin_textdomain( 'funnel-builder', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
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
				do_action( 'wffn_loaded' );
			}
		}

		/**
		 * @return mixed
		 */
		public static function get_registered_class() {
			return self::$_registered_entity['active'];
		}

		public static function register( $short_name, $class ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

			self::$_registered_entity['active'][ $short_name ] = $class;

		}

		/**
		 * @return WFFN_Funnels_DB
		 */
		public function get_dB() {
			if ( empty( $this->funnels_db ) ) {
				$class            = apply_filters( 'wffn_funnels_db_class', 'WFFN_Funnels_DB' );
				$this->funnels_db = new $class();
			}

			return $this->funnels_db;
		}

		public function plugin_activation_hook() {
			update_option( 'bwf_needs_rewrite', 'yes', true );

			/** Save the plugin first version when the plugin was activated, if not already present */
			$first_version = get_option( 'wffn_first_v', '' );
			if ( empty( $first_version ) ) {
				update_option( 'wffn_first_v', WFFN_VERSION, false );

			}
		}

		public function get_plugin_url() {
			return untrailingslashit( plugin_dir_url( WFFN_PLUGIN_FILE ) );
		}

		public function load_autoloader() {
			spl_autoload_register( array( $this, 'autoload' ) );
		}

		public function autoload( $class_name ) {
			if ( false !== strpos( $class_name, 'WFFN_' ) ) {
				if ( file_exists( WFFN_PLUGIN_DIR . '/includes/' . 'class-' . $this->slugify_classname( $class_name ) . '.php' ) ) {
					require_once WFFN_PLUGIN_DIR . '/includes/' . 'class-' . $this->slugify_classname( $class_name ) . '.php';  // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
				}

			}
		}

		public function slugify_classname( $class_name ) {
			$classname = sanitize_title( $class_name );
			$classname = str_replace( '_', '-', $classname );

			return $classname;
		}

		public function init_oxygen() {
			if ( class_exists( 'OxygenElement' ) ) {
				require_once __DIR__ . '/includes/class-abstract-wffn-oxygen-fields.php';
			}
		}

		/**
		 * Added redirection on plugin activation
		 *
		 * @param $plugin
		 */
		public function check_activation( $plugin ) {

			if ( $plugin === plugin_basename( WFFN_PLUGIN_FILE ) ) {
				$pro_first_active = get_option( 'fk_fb_active_date', [] );
				if ( empty( $pro_first_active ) || ! isset( $pro_first_active['lite'] ) ) {
					$pro_first_active['lite'] = current_time( 'timestamp' );
					update_option( 'fk_fb_active_date', $pro_first_active, false );
				}

			}
			if ( ( defined( 'WFFN_PRO_FILE' ) && $plugin === plugin_basename( WFFN_PRO_FILE ) ) || ( defined( 'WFFN_BASIC_FILE' ) && $plugin === plugin_basename( WFFN_BASIC_FILE ) ) ) {
				$pro_first_active = get_option( 'fk_fb_active_date', [] );
				if ( empty( $pro_first_active ) || ! isset( $pro_first_active['pro'] ) ) {
					$pro_first_active['pro'] = current_time( 'timestamp' );
					update_option( 'fk_fb_active_date', $pro_first_active, false );
				}


			}

		}

	}
}
if ( ! function_exists( 'WFFN_Core' ) ) {
	/**
	 * @return WFFN_Core|null
	 */
	function WFFN_Core() {  //@codingStandardsIgnoreLine
		return WFFN_Core::get_instance();
	}
}

$GLOBALS['WFFN_Core'] = WFFN_Core();
