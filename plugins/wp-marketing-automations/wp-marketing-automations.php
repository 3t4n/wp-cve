<?php
/**
 * Plugin Name: FunnelKit Automations
 * Plugin URI: https://funnelkit.com/wordpress-marketing-automation-autonami/
 * Description: Recover lost revenue with Abandoned Cart Recovery for WooCommerce. Increase retention with Post Purchase Follow-Up Emails. Send beautiful Newsletters.
 * Version: 2.8.3
 * Author: FunnelKit
 * Author URI: https://funnelkit.com
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wp-marketing-automations
 * Requires at least: 5.0
 * Tested up to: 6.4.3
 * WooFunnels: true
 *
 * FunnelKit Automations is free software.
 * You can redistribute it and/or modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * FunnelKit Automations is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with FunnelKit Automations. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
#[AllowDynamicProperties]
final class BWFAN_Core {

	/**
	 * @var BWFAN_Core
	 */
	private static $_instance = null;
	private static $_registered_entity = array(
		'active'   => array(),
		'inactive' => array(),
	);
	/**
	 * @var BWFAN_Admin
	 */
	public $admin;

	/**
	 * @var BWFAN_Public
	 */
	public $public;

	/**
	 * @var BWFAN_Load_Integrations
	 */
	public $integration;

	/**
	 * @var BWFAN_Load_Sources
	 */
	public $sources;

	/**
	 * @var BWFAN_Rules_Loader
	 */
	public $rules;

	/**
	 * @var BWFAN_Shortcodes
	 */
	public $shortcodes;

	/**
	 * @var BWFAN_Logger
	 */
	public $logger;

	/**
	 * @var  BWFAN_Merge_Tag_Loader
	 */
	public $merge_tags;
	public $native_connectors;

	/**
	 * @var BWFAN_Automations
	 */
	public $automations;

	/**
	 * @var BWFAN_Automation_V2
	 */
	public $automations_v2;

	/**
	 * @var BWFAN_Tasks
	 */
	public $tasks;

	/**
	 * @var BWFAN_Logs
	 */
	public $logs;

	/**
	 * @var BWFAN_Abandoned_Cart
	 */
	public $abandoned;

	/**
	 * @var BWFAN_WooFunnels_Support
	 */
	public $support;

	/**
	 * @var BWFAN_Email_Conversations
	 */
	public $conversations;

	/**
	 * @var BWFAN_Conversions
	 */
	public $conversions;

	/**
	 * @var BWFAN_Connectors
	 */
	public $connectors;

	/**
	 * @var BWFAN_Load_Custom_Search
	 */
	public $custom_search;

	/**
	 * @var BWFAN_Subscribe_Link_Handler
	 */
	public $subscribe_link_handler;

	public $wfco_admin;

	public $db;
	public $bwfan_api;
	public $bwfan_recipe;
	public $automations_v2_contact;

	private function __construct() {
		add_filter( 'wfco_include_connector', function () {
			return true;
		} );
		/**
		 * Load important variables and constants
		 */
		$this->define_plugin_properties();

		/**
		 * Load dependency classes like bwfan-functions.php
		 */
		$this->load_dependencies_support();
		/**
		 * Initiates and loads WooFunnels start file
		 */
		$this->load_woofunnels_core_classes();

		/**
		 * Loads common file
		 */
		$this->load_commons();
	}

	/**
	 * Defining constants
	 */
	public function define_plugin_properties() {
		define( 'BWFAN_VERSION', '2.8.3' );
		define( 'BWFAN_MIN_PRO_VERSION', '2.8.3' );
		define( 'BWFAN_MIN_WC_VERSION', '3.0' );
		define( 'BWFAN_SLUG', 'bwfan' );
		define( 'BWFAN_FULL_NAME', 'FunnelKit Automations' );
		define( 'BWFAN_BWF_VERSION', '1.10.12.05' );
		define( 'BWFAN_PLUGIN_FILE', __FILE__ );
		define( 'BWFAN_PLUGIN_DIR', __DIR__ );
		define( 'BWFAN_TEMPLATE_DIR', plugin_dir_path( BWFAN_PLUGIN_FILE ) . 'templates' );

		$plugin_url = untrailingslashit( plugin_dir_url( BWFAN_PLUGIN_FILE ) );
		if ( is_ssl() ) {
			$plugin_url = preg_replace( "/^http:/i", "https:", $plugin_url );
		}

		define( 'BWFAN_PLUGIN_URL', $plugin_url );
		define( 'BWFAN_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'BWFAN_DB_VERSION', '1.0' );


		( ! defined( 'BWFCRM_REACT_ENVIRONMENT' ) ) ? define( 'BWFCRM_REACT_ENVIRONMENT', 1 ) : '';
		define( 'BWFAN_REACT_PROD_URL', BWFAN_PLUGIN_URL . '/admin/frontend/dist' );

		if ( ! defined( 'BWFAN_IS_DEV' ) ) {
			define( 'BWFAN_IS_DEV', false );
		}

		( defined( 'BWFAN_IS_DEV' ) && true === BWFAN_IS_DEV ) ? define( 'BWFAN_VERSION_DEV', time() ) : define( 'BWFAN_VERSION_DEV', BWFAN_VERSION );
	}

	/**
	 * Setting up event Dependency Classes
	 */
	public function load_dependencies_support() {
		require BWFAN_PLUGIN_DIR . '/includes/bwfan-functions.php';
		require BWFAN_PLUGIN_DIR . '/includes/bwfan-options.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-plugin-dependency.php';

		add_action( 'admin_notices', array( $this, 'maybe_show_old_pro_notice' ), 1 );
	}

	public function load_woofunnels_core_classes() {

		/** Setting Up WooFunnels Core */
		require_once( BWFAN_PLUGIN_DIR . '/start.php' );
	}

	public function load_commons() {
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-phone-number.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-common.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-woofunnel-support.php';
		require BWFAN_PLUGIN_DIR . '/libraries/action-scheduler/action-scheduler.php';

		BWFAN_Common::init();
		/**
		 * Loads common hooks
		 */
		$this->load_hooks();
	}

	public function load_hooks() {
		/** Initialize Localization */
		add_action( 'init', array( $this, 'localization' ) );
		add_action( 'plugins_loaded', array( $this, 'load_classes' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'define_api_basename' ) );
		/** Redirecting Plugin to the settings page after activation */
		add_action( 'activated_plugin', array( $this, 'redirect_on_activation' ) );

		/** Initializing Action Schedule WooFunnels Custom Table */
		add_action( 'action_scheduler_pre_init', array( $this, 'initiate_as_ct' ), 1 );

		/** Loading CLI */
		if ( version_compare( PHP_VERSION, '5.3', '>' ) ) {
			add_action( 'plugins_loaded', array( $this, 'load_cli' ), 20 );
		}
	}

	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	public static function register( $short_name, $class, $overrides = null ) {
		/** Ignore classes that have been marked as inactive */
		if ( in_array( $class, self::$_registered_entity['inactive'], true ) ) {
			return;
		}

		/** Mark classes as active. Override existing active classes if they are supposed to be overridden */
		$index = array_search( $overrides, self::$_registered_entity['active'], true );
		if ( false !== $index ) {
			self::$_registered_entity['active'][ $index ] = $class;
		} else {
			self::$_registered_entity['active'][ $short_name ] = $class;
		}

		/** Mark overridden classes as inactive. */
		if ( ! empty( $overrides ) ) {
			self::$_registered_entity['inactive'][] = $overrides;
		}
	}

	/**
	 * Admin notice if Pro older version active
	 */
	public function maybe_show_old_pro_notice() {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return;
		}

		if ( version_compare( BWFAN_PRO_VERSION, BWFAN_MIN_PRO_VERSION, '>=' ) ) {
			return;
		}
		?>
        <div class="notice notice-warning" style="display: block!important;">
            <p>
				<?php
				echo __( '<strong>Warning! Old version of FunnelKit Automations Pro is detected.</strong> We strongly recommend to update the latest version of FunnelKit Automations Pro.', 'wp-marketing-automations' );
				?>
            </p>
        </div>
		<?php
	}

	public static function define_api_basename() {
		$slug = 'autonami-app';
		if ( defined( 'BWFAN_PRO_VERSION' ) && version_compare( BWFAN_PRO_VERSION, '2.5.1', '<' ) ) {
			$slug = 'autonami-admin';
		}

		define( 'BWFAN_API_NAMESPACE', $slug );
	}

	public function load_classes() {
		/**
		 * Loads all the public
		 */
		$this->load_public();

		/**
		 * Loads all the admin
		 */
		if ( is_admin() ) {
			$this->load_admin();
		}

		$this->register_abstract();
		/**
		 * Loads core classes
		 */
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-rules.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-rules-loader.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-db.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-load-integrations.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-merge-tag-loader.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-load-sources.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-load-connectors.php';

		require BWFAN_PLUGIN_DIR . '/compatibilities/class-bwfan-compatibilities.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-automations.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-tasks.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-logs.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-logger.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-api-loader.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-dashboards.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-connectors.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-load-custom-search.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-recipe-loader.php';

		/** Automation builder v2 */
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-automation-v2.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-automation-v2-contact.php';

		/** Subscribe link handler */
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-subscribe-link-handler.php';

		/** Remove duplicate contacts */
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-remove-duplicate-contact.php';
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-get-broadcast-timings.php';
		$this->register_controllers();

		do_action( 'bwfan_before_register_modules' );

		$this->register_modules();

		// After including class now initialize all class or functions
		$this->register_classes();
	}

	public function load_public() {
		require BWFAN_PLUGIN_DIR . '/includes/class-bwfan-public.php';
	}

	public function load_admin() {
		require BWFAN_PLUGIN_DIR . '/admin/class-bwfan-admin.php';
		include_once( BWFAN_PLUGIN_DIR . '/admin/includes/class-bwfan-header.php' );
	}

	private function register_abstract() {
		$abstract_path = BWFAN_PLUGIN_DIR . '/includes/abstracts';
		foreach ( glob( $abstract_path . '/class-*.php' ) as $_field_filename ) {
			$file_data = pathinfo( $_field_filename );
			if ( isset( $file_data['basename'] ) && 'index.php' === $file_data['basename'] ) {
				continue;
			}
			require_once( $_field_filename );
		}
	}

	private function register_controllers() {
		$abstract_path = BWFAN_PLUGIN_DIR . '/includes/controllers';
		foreach ( glob( $abstract_path . '/class-*.php' ) as $_field_filename ) {
			$file_data = pathinfo( $_field_filename );
			if ( isset( $file_data['basename'] ) && 'index.php' === $file_data['basename'] ) {
				continue;
			}
			require_once( $_field_filename );
		}
	}

	public function register_modules() {
		$integration_dir = BWFAN_PLUGIN_DIR . '/modules';
		foreach ( glob( $integration_dir . '/*/class-*.php' ) as $_field_filename ) {
			require_once( $_field_filename );
		}
	}

	public function register_classes() {
		$load_classes = self::get_registered_class();
		if ( is_array( $load_classes ) && count( $load_classes ) > 0 ) {
			foreach ( $load_classes as $access_key => $class ) {
				if ( ! method_exists( $class, 'get_instance' ) ) {
					continue;
				}
				$this->{$access_key} = $class::get_instance();
			}
			do_action( 'bwfan_loaded' );
		}
	}

	public static function get_registered_class() {
		return self::$_registered_entity['active'];
	}

	public function localization() {
		load_plugin_textdomain( 'wp-marketing-automations', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Added redirection on plugin activation
	 *
	 * @param $plugin
	 */
	public function redirect_on_activation( $plugin ) {
		if ( ! defined( 'WP_CLI' ) && bwfan_is_woocommerce_active() && class_exists( 'WooCommerce' ) ) {
			if ( plugin_basename( __FILE__ ) === $plugin ) {

				wp_safe_redirect( add_query_arg( array(
					'page' => 'autonami',
				), admin_url( 'admin.php' ) ) );
				exit;
			}
		}
	}

	public function initiate_as_ct() {
		/** AS older data store */
		$as_ct_files = glob( BWFAN_PLUGIN_DIR . '/libraries/action-scheduler-ct/*.php' );

		foreach ( $as_ct_files as $file_name ) {
			if ( false !== strpos( $file_name, 'class-bwfan-as-ct-cli.php' ) ) {
				/** Will load when CLI to run */
				continue;
			}
			require_once $file_name;
		}

		/** AS new data store */
		$as_ct_files = glob( BWFAN_PLUGIN_DIR . '/libraries/action-scheduler-v2/*.php' );

		foreach ( $as_ct_files as $file_name ) {
			if ( false !== strpos( $file_name, 'class-bwfan-as-ct-cli.php' ) ) {
				/** Will load when CLI to run */
				continue;
			}
			require_once $file_name;
		}
	}

	public function load_cli() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			/** v1 cli command register */
			require_once BWFAN_PLUGIN_DIR . '/libraries/action-scheduler-ct/class-bwfan-as-ct-cli.php';
			WP_CLI::add_command( 'autonami-tasks', 'BWFAN_AS_CT_CLI' );

			/** v2 cli command register */
			require_once BWFAN_PLUGIN_DIR . '/libraries/action-scheduler-v2/class-bwfan-as-ct-cli.php';
			WP_CLI::add_command( 'autonami-automation-contact', 'BWFAN_AS_CT_CLI' );
		}
	}

	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'BWFAN_Core can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'BWFAN_Core can`t converted to string' );
	}

	/**
	 * To avoid cloning of current class
	 */
	protected function __clone() {
	}
}

if ( ! function_exists( 'BWFAN_Core' ) ) {

	/**
	 * Global Common function to load all the classes
	 * @return BWFAN_Core
	 */
	function BWFAN_Core() {  //@codingStandardsIgnoreLine
		return BWFAN_Core::get_instance();
	}
}

BWFAN_Core();
