<?php

/** @noinspection PhpUnusedParameterInspection */

namespace Packlink\WooCommerce;

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use Logeecom\Infrastructure\Logger\Logger;
use Logeecom\Infrastructure\ORM\Exceptions\RepositoryNotRegisteredException;
use Logeecom\Infrastructure\ServiceRegister;
use Logeecom\Infrastructure\TaskExecution\Exceptions\TaskRunnerStatusStorageUnavailableException;
use Packlink\BusinessLogic\ShippingMethod\Interfaces\ShopShippingMethodService;
use Packlink\BusinessLogic\ShippingMethod\Utility\ShipmentStatus;
use Packlink\WooCommerce\Components\Bootstrap_Component;
use Packlink\WooCommerce\Components\Checkout\Block_Checkout_Handler;
use Packlink\WooCommerce\Components\Checkout\Checkout_Handler;
use Packlink\WooCommerce\Components\Order\Paid_Order_Handler;
use Packlink\WooCommerce\Components\Services\Config_Service;
use Packlink\WooCommerce\Components\Services\Logger_Service;
use Packlink\WooCommerce\Components\ShippingMethod\Packlink_Shipping_Method;
use Packlink\WooCommerce\Components\ShippingMethod\Shipping_Method_Helper;
use Packlink\WooCommerce\Components\ShippingMethod\Shop_Shipping_Method_Service;
use Packlink\WooCommerce\Components\Utility\Database;
use Packlink\WooCommerce\Components\Utility\Shop_Helper;
use Packlink\WooCommerce\Components\Utility\Task_Queue;
use Packlink\WooCommerce\Components\Utility\Version_File_Reader;
use Packlink\WooCommerce\Controllers\Packlink_Auto_Test_Controller;
use Packlink\WooCommerce\Controllers\Packlink_Frontend_Controller;
use Packlink\WooCommerce\Controllers\Packlink_Index;
use Packlink\WooCommerce\Controllers\Packlink_Order_Details_Controller;
use Packlink\WooCommerce\Controllers\Packlink_Order_Overview_Controller;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use WC_Order;
use WP_Post;
use wpdb;

/**
 * Class Plugin
 *
 * @package Packlink\WooCommerce
 */
class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @var Plugin
	 */
	protected static $instance;
	/**
	 * WordPress database session.
	 *
	 * @var wpdb
	 */
	public $db;
	/**
	 * Configuration service instance.
	 *
	 * @var Config_Service
	 */
	private $config_service;
	/**
	 * Plugin file.
	 *
	 * @var string
	 */
	private $packlink_plugin_file;

	/**
	 * Plugin constructor.
	 *
	 * @param wpdb   $wpdb WordPress database session.
	 * @param string $packlink_plugin_file Plugin file.
	 */
	public function __construct( $wpdb, $packlink_plugin_file ) {
		$this->db                   = $wpdb;
		$this->packlink_plugin_file = $packlink_plugin_file;
	}

	/**
	 * Returns singleton instance of the plugin.
	 *
	 * @param wpdb   $wpdb WordPress database session.
	 * @param string $packlink_plugin_file Plugin file.
	 *
	 * @return Plugin Plugin instance.
	 * @throws RepositoryNotRegisteredException
	 */
	public static function instance( $wpdb, $packlink_plugin_file ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $wpdb, $packlink_plugin_file );
		}

		self::$instance->initialize();

		return self::$instance;
	}

	/**
	 * Plugin activation function.
	 *
	 * @param bool $is_network_wide Is plugin network wide.
	 */
	public function activate( $is_network_wide ) {
		if ( ! Shop_Helper::is_curl_enabled() ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die(
				esc_html(
					__(
						'cURL is not installed or enabled in your PHP installation. This is required for background task to work. Please install it and then refresh this page.',
						'packlink-pro-shipping'
					)
				),
				'Plugin dependency check',
				array( 'back_link' => true )
			);
		}

		if ( ! Shop_Helper::is_woocommerce_active() ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die(
				esc_html( __( 'Please install and activate WooCommerce.', 'packlink-pro-shipping' ) ),
				'Plugin dependency check',
				array( 'back_link' => true )
			);
		}

		if ( $this->plugin_already_initialized() ) {
			Task_Queue::wakeup();
			Shipping_Method_Helper::enable_packlink_shipping_methods();
		} elseif ( $is_network_wide && is_multisite() ) {
			foreach ( get_sites() as $site ) {
				switch_to_blog( $site->blog_id );
				/** @noinspection DisconnectedForeachInstructionInspection */ // phpcs:ignore
				$this->init_database();
				/** @noinspection DisconnectedForeachInstructionInspection */ // phpcs:ignore
				$this->init_config();
				restore_current_blog();
			}
		} else {
			$this->init_database();
			$this->init_config();
		}
	}

	/**
	 * Plugin deactivation function.
	 *
	 * @param bool $is_network_wide Is plugin network wide.
	 */
	public function deactivate( $is_network_wide ) {
		if ( ! Shop_Helper::is_woocommerce_active() ) {
			return;
		}

		if ( $is_network_wide && is_multisite() ) {
			foreach ( get_sites() as $site ) {
				switch_to_blog( $site->blog_id );
				Shipping_Method_Helper::disable_packlink_shipping_methods();
				restore_current_blog();
			}
		} else {
			Shipping_Method_Helper::disable_packlink_shipping_methods();
		}
	}

	/**
	 * Injects plugin update options to force update.
	 *
	 * @param \stdClass $update_info
	 *
	 * @return \stdClass Altered $update_info object.
	 */
	public function packlink_plugin_update_info_debug( $update_info ) {
		if ( ! isset( $update_info->response ) ) {
			return $update_info;
		}

		$response = $update_info->no_update['packlink-pro-shipping/packlink-pro-shipping.php'];
		unset( $update_info->no_update['packlink-pro-shipping/packlink-pro-shipping.php'] );

		// set to the version you want to update
		$response->new_version = '2.1.2';
		// set the URI to the update package
		$response->package = '';
		$response->tested  = get_bloginfo( 'version' );

		$update_info->response['packlink-pro-shipping/packlink-pro-shipping.php'] = $response;

		return $update_info;
	}

	/**
	 * Plugin uninstall method.
	 */
	public function uninstall() {
		if ( is_multisite() ) {
			$sites = get_sites();
			foreach ( $sites as $site ) {
				Shipping_Method_Helper::remove_packlink_shipping_methods();
				$this->switch_to_site_and_uninstall_plugin( $site->blog_id );
			}
		} else {
			Shipping_Method_Helper::remove_packlink_shipping_methods();
			$this->uninstall_plugin_from_site();
		}

		$this->delete_logs();
		delete_option( 'PACKLINK_DATABASE_VERSION' );
		delete_transient( 'packlink-pro-messages' );
		delete_transient( 'packlink-pro-error-messages' );
		delete_transient( 'packlink-pro-success-messages' );
	}

	/**
	 * Initializes base Packlink PRO Shipping tables and values if plugin is accessed from a new site.
	 */
	public function initialize_new_site() {
		$db = new Database( $this->db );
		if ( ! $db->plugin_already_initialized() ) {
			$this->init_database();
			$this->init_config();
		}
	}

	/**
	 * Loads plugin translations.
	 */
	public function load_plugin_text_domain() {
		unload_textdomain( 'packlink-pro-shipping' );
		load_plugin_textdomain(
			'packlink-pro-shipping',
			false,
			plugin_basename( dirname( $this->packlink_plugin_file ) ) . '/languages'
		);

		$this->dismiss_notices();
		$this->dismiss_success_notices();
		$this->dismiss_error_notices();
	}

	/**
	 * Adds Packlink PRO query variable.
	 *
	 * @param array $vars Filter variables.
	 *
	 * @return array Filter variables.
	 */
	public function plugin_add_trigger( $vars ) {
		$vars[] = 'packlink_pro_controller';

		return $vars;
	}

	/**
	 * Trigger action on calling plugin controller.
	 */
	public function plugin_trigger_check() {
		$controller_name = get_query_var( 'packlink_pro_controller' );
		if ( ! empty( $controller_name ) ) {
			$controller = new Packlink_Index();
			$controller->index();
		}
	}

	/**
	 * Hook that triggers when network site is deleted and removes plugin data related to that site from the network.
	 *
	 * @param int $site_id Site identifier.
	 */
	public function uninstall_plugin_from_deleted_site( $site_id ) {
		$this->switch_to_site_and_uninstall_plugin( $site_id );
	}

	/**
	 * Creates Packlink PRO Shipping item in administrator menu.
	 */
	public function create_admin_submenu() {
		add_submenu_page(
			'woocommerce',
			'Packlink PRO',
			'Packlink PRO',
			'manage_options',
			'packlink-pro-shipping',
			array( new Packlink_Frontend_Controller(), 'render' )
		);
		add_submenu_page(
			'',
			'Packlink PRO Auto-Test',
			'',
			'manage_options',
			'packlink-pro-auto-test',
			array( new Packlink_Auto_Test_Controller(), 'render' )
		);
	}

	/**
	 * Displays messages if needed, but only once.
	 */
	public function admin_messages() {
		$messages = get_transient( 'packlink-pro-messages' );
		if ( $messages ) {
			/** @noinspection DirectoryConstantCanBeUsedInspection */ // phpcs:ignore
			include dirname( __FILE__ ) . '/resources/views/notice-message.php';
		}
	}

	/**
	 * Displays success message.
	 */
	public function admin_notice_messages_no_dismiss() {
		$messages = get_transient( 'packlink-pro-success-messages' );
		if ( $messages ) {
			/** @noinspection DirectoryConstantCanBeUsedInspection */ // phpcs:ignore
			include dirname( __FILE__ ) . '/resources/views/success-message.php';
		}
	}

	/**
	 * Displays error message.
	 */
	public function admin_error_messages() {
		$messages = get_transient( 'packlink-pro-error-messages' );
		if ( $messages ) {
			/** @noinspection DirectoryConstantCanBeUsedInspection */ // phpcs:ignore
			include dirname( __FILE__ ) . '/resources/views/error-message.php';
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param array $links Plugin Action links.
	 *
	 * @return array
	 */
	public function create_configuration_link( array $links ) {
		$action_links = array(
			'configuration' => '<a href="' . Shop_Helper::get_plugin_page_url() . '" aria-label="' . esc_attr__( 'View Packlink configuration', 'packlink-pro-shipping' ) . '">' . esc_html__( 'Configuration', 'packlink-pro-shipping' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Adds Packlink PRO Shipping method to the list of all shipping methods.
	 *
	 * @param array $methods List of all shipping methods.
	 *
	 * @return array List of all shipping methods.
	 */
	public function add_shipping_method( array $methods ) {
		$methods['packlink_shipping_method'] = Packlink_Shipping_Method::CLASS_NAME;

		return $methods;
	}

	/**
	 * Adds active shipping methods to newly created shipping zone.
	 *
	 * @param \WC_Shipping_Zone $zone Shipping zone.
	 * @param \WC_Data_Store    $data_store Shipping zone data store.
	 */
	public function on_zone_create( $zone, $data_store ) {
		if ( null !== $zone->get_id() ) {
			return;
		}

		$data_store->create( $zone );

		if ( $zone->get_id() ) {
			/** @var Shop_Shipping_Method_Service $service */ // phpcs:ignore
			$service = ServiceRegister::getService( ShopShippingMethodService::CLASS_NAME );
			$service->add_active_methods_to_zone( $zone );
		}
	}

	/**
	 * Adds Packlink PRO Shipping meta post box.
	 *
	 * @param string             $page Current page type.
	 * @param WP_Post | WC_Order $data WordPress Post or WooCommerce Order.
	 */
	public function add_packlink_shipping_box( $page, $data ) {
		if ( ( 'shop_order' === $page && $data && 'auto-draft' !== $data->post_status ) ||
		     ( 'woocommerce_page_wc-orders' === $page && $data && $data instanceof WC_Order ) ) {
			$controller = new Packlink_Order_Details_Controller();
			$screen     = wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
				? wc_get_page_screen_id( 'shop-order' )
				: 'shop_order';
			add_meta_box(
				'packlink-shipping-modal',
				__( 'Packlink PRO Shipping', 'packlink-pro-shipping' ),
				function ( $data ) use ( $controller ) {
					$data_id = ($data instanceof \WP_Post) ? $data->ID : $data->get_id();
					$controller->render( $data_id );
				},
				$screen,
				'side',
				'core'
			);
		}
	}

	/**
	 * Initializes the plugin.
	 *
	 * @throws RepositoryNotRegisteredException
	 */
	private function initialize() {
		Bootstrap_Component::init();
		$this->load_plugin_init_hooks();
		$this->declare_compatibility_with_hpos();
		if ( $this->should_update() ) {
			$this->update();
		}

		if ( Shop_Helper::is_plugin_enabled() ) {
			$this->add_settings_link();
			$this->load_admin_menu();
			$this->shipping_method_hooks_and_actions();
			$this->load_plugin_text_domain();
			$this->order_hooks_and_actions();
			$this->checkout_hooks_and_actions();
		}
	}

	/**
	 * Registers install and uninstall hook.
	 */
	private function load_plugin_init_hooks() {
		register_activation_hook( $this->packlink_plugin_file, array( $this, 'activate' ) );
		register_deactivation_hook( $this->packlink_plugin_file, array( $this, 'deactivate' ) );
		add_action( 'admin_init', array( $this, 'initialize_new_site' ) );
		add_filter( 'query_vars', array( $this, 'plugin_add_trigger' ) );
		add_action( 'template_redirect', array( $this, 'plugin_trigger_check' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_text_domain' ) );
		add_action( 'admin_notices', array( $this, 'admin_messages' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice_messages_no_dismiss' ) );
		add_action( 'admin_notices', array( $this, 'admin_error_messages' ) );
		if ( is_multisite() ) {
			add_action( 'delete_blog', array( $this, 'uninstall_plugin_from_deleted_site' ) );
		}

		if ( defined( '_PL_DEBUG_' ) ) {
			add_filter( 'site_transient_update_plugins', array( $this, 'packlink_plugin_update_info_debug' ), 20, 3 );
			add_filter( 'set_site_transient_update_plugins', array(
				$this,
				'packlink_plugin_update_info_debug'
			), 20, 3 );
		}
	}

	/**
	 * Declares extension compatibility.
	 *
	 * @return void
	 */
	private function declare_compatibility_with_hpos() {
		add_action(
			'before_woocommerce_init',
			function () {
				if ( class_exists( FeaturesUtil::class ) ) {
					FeaturesUtil::declare_compatibility( 'custom_order_tables', $this->packlink_plugin_file, true );
				}
			}
		);
	}

	/**
	 * Initializes plugin database.
	 */
	private function init_database() {
		$installer = new Database( $this->db );
		$installer->install();
	}

	/**
	 * Initializes default configuration values.
	 */
	private function init_config() {
		Shop_Helper::create_log_directory();
		$config_service = $this->get_config_service();

		try {
			$config_service->setTaskRunnerStatus( '', null );
			$config_service->setOrderStatusMappings(
				array(
					ShipmentStatus::STATUS_ACCEPTED  => 'wc-processing',
					ShipmentStatus::STATUS_DELIVERED => 'wc-completed',
					ShipmentStatus::STATUS_CANCELLED => 'wc-cancelled',
					ShipmentStatus::INCIDENT         => 'wc-failed'
				)
			);
		} catch ( TaskRunnerStatusStorageUnavailableException $e ) {
			Logger::logError( $e->getMessage(), 'Integration' );
		}
	}

	/**
	 * Checks if plugin was already installed and initialized.
	 *
	 * @return bool Plugin initialized flag.
	 */
	private function plugin_already_initialized() {
		$handler = new Database( $this->db );

		return $handler->plugin_already_initialized();
	}

	/**
	 * Plugin update method.
	 *
	 * @throws RepositoryNotRegisteredException
	 */
	private function update() {
		if ( is_multisite() ) {
			$site_ids = get_sites();
			foreach ( $site_ids as $site_id ) {
				switch_to_blog( $site_id->blog_id );
				/** @noinspection DisconnectedForeachInstructionInspection */ // phpcs:ignore
				$this->update_plugin_on_single_site();
				restore_current_blog();
			}
		} else {
			$this->update_plugin_on_single_site();
		}
	}

	/**
	 * Validates if update is for our plugin.
	 *
	 * @return bool Plugin valid for update.
	 */
	private function should_update() {
		return $this->get_config_service()->get_database_version() !== Shop_Helper::get_plugin_version();
	}

	/**
	 * Updates plugin on single WordPress site.
	 * `     */
	private function update_plugin_on_single_site() {
		$previous_version = $this->get_config_service()->get_database_version();

		$installer = new Database( $this->db );
		$installer->update( new Version_File_Reader( __DIR__ . '/upgrade/', $previous_version ) );

		$this->get_config_service()->set_database_version( Shop_Helper::get_plugin_version() );
	}

	/**
	 * Switches to site with provided ID and removes plugin from that site.
	 *
	 * @param int $site_id Site identifier.
	 */
	private function switch_to_site_and_uninstall_plugin( $site_id ) {
		switch_to_blog( $site_id );

		$this->uninstall_plugin_from_site();

		restore_current_blog();
	}

	/**
	 * Removes plugin tables and configuration from the current site.
	 */
	private function uninstall_plugin_from_site() {
		$installer = new Database( $this->db );
		$installer->uninstall();
	}

	/**
	 * Retrieves config service.
	 *
	 * @return Config_Service Configuration service.
	 */
	private function get_config_service() {
		if ( null === $this->config_service ) {
			$this->config_service = ServiceRegister::getService( Config_Service::CLASS_NAME );
		}

		return $this->config_service;
	}

	/**
	 * Adds Packlink PRO Shipping item to backend administrator menu.
	 */
	private function load_admin_menu() {
		if ( is_admin() && ! is_network_admin() ) {
			add_action( 'admin_menu', array( $this, 'create_admin_submenu' ) );
		}
	}

	/**
	 * Adds Packlink PRO Shipping shipping method.
	 */
	private function shipping_method_hooks_and_actions() {
		add_filter( 'woocommerce_shipping_methods', array( $this, 'add_shipping_method' ) );
		add_action( 'woocommerce_before_shipping_zone_object_save', array( $this, 'on_zone_create' ), 10, 2 );
	}

	/**
	 * Registers actions and filters for extending orders overview and details page.
	 */
	private function order_hooks_and_actions() {
		$handler = new Packlink_Order_Overview_Controller();

		add_action( 'add_meta_boxes', array( $this, 'add_packlink_shipping_box' ), 10, 2 );
		add_action( 'admin_head', array( $handler, 'add_packlink_hidden_fields' ) );
		add_filter( 'bulk_actions-edit-shop_order', array( $handler, 'add_packlink_bulk_action' ) );
		add_filter( 'handle_bulk_actions-edit-shop_order', array( $handler, 'bulk_print_labels' ), 10, 3 );
		add_action( 'admin_enqueue_scripts', array( $handler, 'load_scripts' ) );

		add_action(
			'woocommerce_init',
			static function () {
				foreach ( \wc_get_is_paid_statuses() as $paid_status ) {
					add_action(
						'woocommerce_order_status_' . $paid_status,
						array(
							Paid_Order_Handler::CLASS_NAME,
							'handle',
						),
						10,
						2
					);
				}
			}
		);

		add_filter( 'manage_woocommerce_page_wc-orders_columns', array( $handler, 'add_packlink_order_columns' ) );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', array(
			$handler,
			'populate_packlink_column'
		), 10, 2 );

		add_filter( 'manage_edit-shop_order_columns', array( $handler, 'add_packlink_order_columns' ) );
		add_action( 'manage_shop_order_posts_custom_column', array( $handler, 'populate_packlink_column' ), 10, 2 );
	}

	/**
	 * Registers actions for extending checkout process.
	 */
	private function checkout_hooks_and_actions() {
		$handler = new Checkout_Handler();
		$block_handler = new Block_Checkout_Handler();

		add_filter( 'woocommerce_package_rates', array( $handler, 'check_additional_packlink_rate' ) );
		add_action( 'woocommerce_after_shipping_rate', array( $handler, 'after_shipping_rate' ), 10, 2 );
		add_action( 'woocommerce_after_shipping_calculator', array( $handler, 'after_shipping_calculator' ) );
		add_action( 'woocommerce_review_order_after_shipping', array( $handler, 'after_shipping' ) );
		add_action( 'woocommerce_checkout_process', array( $handler, 'checkout_process' ) );
		add_action( 'woocommerce_checkout_create_order', array( $handler, 'checkout_update_shipping_address' ), 10, 2 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $handler, 'checkout_update_drop_off' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $handler, 'load_scripts' ) );

		add_action('woocommerce_blocks_checkout_enqueue_data', array ($block_handler, 'load_data'));
		add_action('woocommerce_store_api_checkout_update_order_meta', array ($block_handler, 'checkout_update_drop_off'));
	}

	/**
	 * Register filter for links on the plugin screen.
	 */
	private function add_settings_link() {
		add_filter(
			'plugin_action_links_' . plugin_basename( Shop_Helper::get_plugin_name() ),
			array(
				$this,
				'create_configuration_link',
			)
		);
	}

	/**
	 * Deletes packlink log files.
	 */
	private function delete_logs() {
		$dir = dirname( Logger_Service::get_log_file() );

		if ( is_dir( $dir ) ) {
			$files = scandir( $dir, SCANDIR_SORT_NONE );

			$dir = rtrim( $dir, '/' ) . '/';
			foreach ( $files as $file ) {
				if ( '.' !== $file && '..' !== $file ) {
					unlink( $dir . $file );
				}
			}

			rmdir( $dir );
		}
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	private function dismiss_notices() {
		if ( function_exists( 'wp_verify_nonce' ) && isset( $_GET['packlink-hide-notice'], $_GET['_packlink_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_packlink_notice_nonce'] ) ), 'packlink_hide_notices_nonce' ) ) {
				wp_die( esc_html_e( 'Action failed. Please refresh the page and retry.' ) );
			}

			delete_transient( 'packlink-pro-messages' );
		}
	}

	/**
	 * Hide a success notice if the GET variable is set.
	 */
	private function dismiss_success_notices() {
		if ( function_exists( 'wp_verify_nonce' ) && isset( $_GET['packlink-hide-success-notice'], $_GET['_packlink_success_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_packlink_success_notice_nonce'] ) ), 'packlink_hide_success_notices_nonce' ) ) {
				wp_die( esc_html_e( 'Action failed. Please refresh the page and retry.' ) );
			}

			delete_transient( 'packlink-pro-success-messages' );
		}
	}

	/**
	 * Hide an error notice if the GET variable is set.
	 */
	private function dismiss_error_notices() {
		if ( function_exists( 'wp_verify_nonce' ) && isset( $_GET['packlink-hide-error-notice'], $_GET['_packlink_error_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_packlink_error_notice_nonce'] ) ), 'packlink_hide_error_notices_nonce' ) ) {
				wp_die( esc_html_e( 'Action failed. Please refresh the page and retry.' ) );
			}

			delete_transient( 'packlink-pro-error-messages' );
		}
	}
}

