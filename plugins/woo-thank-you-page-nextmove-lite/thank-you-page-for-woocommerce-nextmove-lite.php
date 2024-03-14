<?php
/**
 * Plugin Name: NextMove Lite - Thank You Page for WooCommerce
 * Plugin URI: https://xlplugins.com/woocommerce-thank-you-page-nextmove/
 * Description: The only plugin in WooCommerce that empowers you to build profit-pulling Thank You Pages with plug & play components. It's for store owners who want to get repeat orders on autopilot.
 * Version: 2.18.3
 * Author: XLPlugins
 * Author URI: https://www.xlplugins.com
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: woo-thank-you-page-nextmove-lite
 * Domain Path: /languages/
 * XL: True
 * XLTOOLS: True
 * Requires at least: 5.0
 * Tested up to: 6.4.3
 * Requires PHP: 7.3
 * WC requires at least: 4.4
 * WC tested up to: 8.6.1
 *
 * NextMove Lite - Thank You Page for WooCommerce is free software.
 * You can redistribute it and/or modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * NextMove Lite - Thank You Page for WooCommerce is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NextMove Lite - Thank You Page for WooCommerce. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package NextMove
 * @Category Core
 * @author XLPlugins
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'xlwcty_dependency' ) ) {

	/**
	 * Function to check if NextMove's pro version is loaded and activated or not?
	 * @return bool True|False
	 */
	function xlwcty_dependency() {
		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( 'thank-you-page-for-woocommerce-nextmove/woocommerce-thankyou-pages.php', $active_plugins, true ) || array_key_exists( 'thank-you-page-for-woocommerce-nextmove/woocommerce-thankyou-pages.php', $active_plugins );
	}
}

/** HPOS compatibility */
add_action( 'before_woocommerce_init', 'xlwcty_hpos_compatibility_declaration' );

/**
 * Declares compatibility with WooCommerce HPOS
 *
 * @return void
 */
function xlwcty_hpos_compatibility_declaration() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}

if ( xlwcty_dependency() ) {
	return;
}

if ( ! class_exists( 'XLWCTY_Core' ) ) :

	class XLWCTY_Core {

		/**
		 * @var XLWCTY_Core
		 */
		public static $_instance = null;
		private static $_registered_entity = array(
			'active'   => array(),
			'inactive' => array(),
		);

		/**
		 * @var xlwcty
		 */
		public $public;

		/**
		 * @var XLWCTY_XL_Support
		 */
		public $xl_support;

		/**
		 * @var XLWCTY_Data
		 */
		public $data;

		/**
		 * @var bool Dependency check property
		 */
		private $is_dependency_exists = true;

		public function __construct() {

			/**
			 * Load important variables and constants
			 */
			$this->define_plugin_properties();

			/**
			 * Load dependency classes like woo-functions.php
			 */
			$this->load_dependencies_support();

			/**
			 * Run dependency check to check if dependency available
			 */
			$this->do_dependency_check();
			if ( $this->is_dependency_exists ) {

				/**
				 * Loads activation hooks
				 */
				$this->maybe_load_activation();

				/**
				 * Loads all the hooks
				 */
				$this->load_hooks();

				/**
				 * Initiates and loads XL start file
				 */
				$this->load_xl_core_classes();

				/**
				 * Include common classes
				 */
				$this->include_commons();

				/**
				 * Initialize common hooks and functions
				 */
				$this->initialize_common();

				/**
				 * Maybe load admin if admin screen
				 */
				$this->maybe_load_admin();
			}
		}

		public function define_plugin_properties() {
			/** Defining Constants */
			define( 'XLWCTY_VERSION', '2.18.3' );
			define( 'XLWCTY_MIN_WC_VERSION', '4.4' );
			define( 'XLWCTY_NAME', 'NextMove Lite' );
			define( 'XLWCTY_FULL_NAME', 'NextMove Lite - Thank You Page for WooCommerce' );
			define( 'XLWCTY_PLUGIN_FILE', __FILE__ );
			define( 'XLWCTY_PLUGIN_DIR', __DIR__ );
			define( 'XLWCTY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			define( 'XLWCTY_PURCHASE', 'xlplugin' );
			define( 'XLWCTY_SHORT_SLUG', 'xlwcty' );
			define( 'XLWCTY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		public function load_dependencies_support() {
			/** Setting up WooCommerce Dependency Classes */
			require_once( plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'woo-includes/woo-functions.php' );
		}

		public function do_dependency_check() {
			if ( ! xlwcty_is_woocommerce_active() ) {
				add_action( 'admin_notices', array( $this, 'xlwcty_wc_not_installed_notice' ) );
				$this->is_dependency_exists = false;
			}
		}

		public function maybe_load_activation() {
			/** Hooking action to the activation */
			register_activation_hook( __FILE__, array( $this, 'xlwcty_activation' ) );
		}

		public function load_hooks() {

			/** Initializing Functionality */
			add_action( 'plugins_loaded', array( $this, 'xlwcty_init' ), 0 );

			add_action( 'plugins_loaded', array( $this, 'xlwcty_register_classes' ), 1 );
			/** Initialize Localization */
			add_action( 'init', array( $this, 'xlwcty_init_localization' ) );

			/** Redirecting Plugin to the settings page after activation */
			add_action( 'activated_plugin', array( $this, 'xlwcty_settings_redirect' ) );

			add_action( 'xl_loaded', array( $this, 'xlwcty_load_xl_core_require_files' ), 10, 1 );

		}

		public function load_xl_core_classes() {

			/** Setting Up XL Core */
			require_once( plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'start.php' );
		}

		public function include_commons() {
			/** Loading Common Class */
			require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'includes/xlwcty-common.php';
			require_once plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'compatibilities/class-xlwcty-compatibilities.php';
			require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'includes/xlwcty-xl-support.php';
			require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'merge-tags/xlwcty-shortcode-merge-tags.php';
			require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'merge-tags/xlwcty-dynamic-merge-tags.php';
			require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'merge-tags/xlwcty-static-merge-tags.php';
			require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'includes/xlwcty-component.php';
			require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'includes/xlwcty-components.php';
			require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'includes/xlwcty-dynamic-component.php';
		}

		public function initialize_common() {
			/** Firing Init to init basic Functions */
			XLWCTY_Common::init();
		}

		public function maybe_load_admin() {
			/* ----------------------------------------------------------------------------*
			 * Dashboard and Administrative Functionality
			 * ---------------------------------------------------------------------------- */
			if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
				require_once( plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'admin/xlwcty-admin.php' );
			}

			/** Loading upsell class */
			if ( is_admin() ) {
				include_once plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'admin/includes/upsell/class-xlwcty-upsell.php';
			}
		}

		public function xlwcty_register_classes() {
			$load_classes = self::get_registered_class();
			if ( is_array( $load_classes ) && count( $load_classes ) > 0 ) {
				foreach ( $load_classes as $access_key => $class ) {
					$this->$access_key = $class::get_instance();
				}
			}

			do_action( 'xlwcty_loaded' );
		}

		public static function get_registered_class() {
			return self::$_registered_entity['active'];
		}

		public static function register( $shortName, $class, $overrides = null ) {

			//Ignore classes that have been marked as inactive
			if ( in_array( $class, self::$_registered_entity['inactive'], true ) ) {
				return;
			}

			//Mark classes as active. Override existing active classes if they are supposed to be overridden
			$index = array_search( $overrides, self::$_registered_entity['active'], true );
			if ( $index !== false ) {
				self::$_registered_entity['active'][ $index ] = $class;
			} else {
				self::$_registered_entity['active'][ $shortName ] = $class;
			}

			//Mark overridden classes as inactive.
			if ( ! empty( $overrides ) ) {
				self::$_registered_entity['inactive'][] = $overrides;
			}
		}

		/**
		 * @return null|XLWCTY_Core
		 */
		public static function get_instance() {
			if ( self::$_instance === null ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/** Triggering activation initialization */
		public function xlwcty_activation() {
			xlwcty_Admin::handle_activation();
		}

		public function xlwcty_init_localization() {
			load_plugin_textdomain( 'woo-thank-you-page-nextmove-lite', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Added redirection on plugin activation
		 *
		 * @param $plugin
		 */
		public function xlwcty_settings_redirect( $plugin ) {
			if ( ! defined( 'WP_CLI' ) && xlwcty_is_woocommerce_active() && class_exists( 'WooCommerce' ) ) {
				if ( $plugin === plugin_basename( __FILE__ ) ) {
					wp_safe_redirect( add_query_arg( array(
						'page'      => 'wc-settings',
						'tab'       => XLWCTY_Common::get_wc_settings_tab_slug(),
						'activated' => 'yes',
					), admin_url( 'admin.php' ) ) );
					exit;
				}
			}
		}

		/**
		 * Checking WooCommerce dependency and then loads further
		 * @return bool false on failure
		 */
		public function xlwcty_init() {
			if ( xlwcty_is_woocommerce_active() && class_exists( 'WooCommerce' ) ) {

				if ( ! version_compare( WC()->version, XLWCTY_MIN_WC_VERSION, '>=' ) ) {
					add_action( 'admin_notices', array( $this, 'xlwcty_wc_version_check_notice' ) );

					return false;
				}

				if ( isset( $_GET['xlwcty_disable'] ) && $_GET['xlwcty_disable'] === 'yes' && is_user_logged_in() && current_user_can( 'administrator' ) ) {
					return false;
				}
				require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'includes/xlwcty-data.php';
				require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'includes/xlwcty-themes-helper.php';

				if ( ! ( ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) || ! is_admin() ) {
					require plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'includes/xlwcty-public.php';
				}
			}
		}

		/** Registering Notices */
		public function xlwcty_wc_version_check_notice() {
			?>
            <div class="error">
                <p>
					<?php
					/* translators: %1$s: Min required woocommerce version */
					printf( __( 'NextMove requires WooCommerce version %1$s or greater. Kindly update the WooCommerce plugin.', XLWCTY_FULL_NAME ), XLWCTY_MIN_WC_VERSION );
					?>
                </p>
            </div>
			<?php
		}

		public function xlwcty_wc_not_installed_notice() {
			?>
            <div class="error">
                <p>
					<?php
					echo __( 'WooCommerce is not installed or activated. NextMove is a WooCommerce Extension and would only work if WooCommerce is activated. Please install the WooCommerce Plugin first.', XLWCTY_FULL_NAME );
					?>
                </p>
            </div>
			<?php
		}

		public function xlwcty_load_xl_core_require_files( $get_global_path ) {
			if ( file_exists( $get_global_path . 'includes/class-xl-cache.php' ) ) {
				require_once $get_global_path . 'includes/class-xl-cache.php';
			}
			if ( file_exists( $get_global_path . 'includes/class-xl-transients.php' ) ) {
				require_once $get_global_path . 'includes/class-xl-transients.php';
			}
			if ( file_exists( $get_global_path . 'includes/class-xl-file-api.php' ) ) {
				require_once $get_global_path . 'includes/class-xl-file-api.php';
			}
		}
	}

endif;


if ( ! function_exists( 'XLWCTY_Core' ) ) {

	/**
	 * Global Common function to load all the classes
	 *
	 * @param bool $debug
	 *
	 * @return XLWCTY_Core
	 */
	function XLWCTY_Core( $debug = false ) {
		return XLWCTY_Core::get_instance();
	}
}

require plugin_dir_path( __FILE__ ) . 'includes/xlwcty-logging.php';

/**
 * Collect PHP fatal errors and save it in the log file so that it can be later viewed
 * @see register_shutdown_function
 */
if ( ! function_exists( 'xlplugins_collect_errors' ) ) {
	function xlplugins_collect_errors() {
		$error = error_get_last();

		if ( ! isset( $error['type'] ) || empty( $error['type'] ) ) {
			return;
		}
		if ( E_ERROR === $error['type'] ) {
			xlplugins_force_log( $error['message'] . PHP_EOL, 'fatal-errors.txt' );
		}
	}

	register_shutdown_function( 'xlplugins_collect_errors' );
}

$GLOBALS['XLWCTY_Core'] = XLWCTY_Core();
