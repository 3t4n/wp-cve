<?php
/**
 * Contains code for the plugin container class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce
 */

namespace Boxtal\BoxtalConnectWoocommerce;

use Boxtal\BoxtalConnectWoocommerce\Init\Environment_Check;
use Boxtal\BoxtalConnectWoocommerce\Init\Setup_Wizard;
use Boxtal\BoxtalConnectWoocommerce\Init\Api_Action;
use Boxtal\BoxtalConnectWoocommerce\Notice\Notice_Controller;
use Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point\Checkout;
use Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point\Render;
use Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Settings_Override;
use Boxtal\BoxtalConnectWoocommerce\Subscription\Admin_Subscription_Page;
use Boxtal\BoxtalConnectWoocommerce\Subscription\Front_Subscription_Page;
use Boxtal\BoxtalConnectWoocommerce\Util\Environment_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Configuration_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Shipping_Method_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Database_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Auth_Util;
use Boxtal\BoxtalConnectWoocommerce\Order\Admin_Order_Page;
use Boxtal\BoxtalConnectWoocommerce\Order\Front_Order_Page;
use Boxtal\BoxtalConnectWoocommerce\Rest_Controller\Order;
use Boxtal\BoxtalConnectWoocommerce\Rest_Controller\Shop;
use Boxtal\BoxtalConnectWoocommerce\Settings\Page;

/**
 * Plugin container class.
 */
class Plugin implements \ArrayAccess {

	/**
	 * Plugin instance.
	 *
	 * @var Plugin
	 */
	protected static $instance = null;

	/**
	 * Store content.
	 *
	 * @var contents
	 */
	protected $contents;

	/**
	 * Construct function. Initializes contents.
	 *
	 * @param string $file Plugin main file.
	 */
	public function __construct( $file ) {
		$this['file']            = $file;
		$this['path']            = realpath( plugin_dir_path( $this['file'] ) ) . DIRECTORY_SEPARATOR;
		$this['url']             = plugin_dir_url( $this['file'] );
		$this['version']         = Branding::$plugin_version;
		$this['min-wc-version']  = Branding::$min_wc_version;
		$this['min-php-version'] = Branding::$min_php_version;
	}

	/**
	 * Create and return Plugin instance.
	 *
	 * @param string $file Plugin main file.
	 * @return Plugin
	 */
	public static function initInstance( $file ) {
		if ( null === self::$instance ) {
			self::$instance = new Plugin( $file );
		}

		return self::$instance;
	}

	/**
	 * Set value.
	 *
	 * @param string $offset key.
	 * @param mixed  $value value.
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		$this->contents[ $offset ] = $value;
	}

	/**
	 * Key exists.
	 *
	 * @param mixed $offset key.
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return isset( $this->contents[ $offset ] );
	}

	/**
	 * Unset key.
	 *
	 * @param mixed $offset key.
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		unset( $this->contents[ $offset ] );
	}

	/**
	 * Get value.
	 *
	 * @param string $offset key.
	 * @mixed
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		if ( is_callable( $this->contents[ $offset ] ) ) {
			return call_user_func( $this->contents[ $offset ], $this );
		}
		return isset( $this->contents[ $offset ] ) ? $this->contents[ $offset ] : null;
	}

	/**
	 * Check if the plugin is enabled.
	 *
	 * @return bool
	 */
	public function can_use_plugin() {
		return Auth_Util::can_use_plugin();
	}

	/**
	 * Check if the environment has errors.
	 *
	 * @return bool
	 */
	public function environment_has_no_errors() {
		return false === Environment_Util::check_errors( $this );
	}

	/**
	 * Add boxtal connect shipping method.
	 *
	 * @param array $methods woocommerce loaded shipping methods.
	 *
	 * @return array
	 */
	public function woocommerce_shipping_methods_filter( $methods ) {
		$methods[ Branding::$branding . '_connect' ] = 'Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Shipping_Method';
		return $methods;
	}

	/**
	 * Register shipping method hooks and filters.
	 */
	public function register_shipping_pethod() {
		if ( $this->environment_has_no_errors() && $this->can_use_plugin() ) {
			add_filter( 'woocommerce_shipping_methods', array( $this, 'woocommerce_shipping_methods_filter' ), 10, 1 );

			$settings = new Settings_Override( $this );
			$settings->run();

			$controller = new \Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Controller( $this );
			$controller->run();
		}
	}

	/**
	 * Register parcel points hooks and filters.
	 */
	public function register_parcel_point() {
		if ( $this->environment_has_no_errors() && $this->can_use_plugin() ) {
			$parcel_point_render = new Render( $this );
			$parcel_point_render->run();

			$controller = new \Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point\Controller( $this );
			$controller->run();

			$checkout = new Checkout( $this );
			$checkout->run();
		}
	}

	/**
	 * Register rest hooks and filters.
	 */
	public function register_rest() {
		if ( $this->environment_has_no_errors() ) {
			$shop = new Shop( $this );
			$shop->run();

			if ( $this->can_use_plugin() ) {
				$order = new Order( $this );
				$order->run();
			}
		}
	}

	/**
	 * Initialize environment notices
	 */
	public function init_check_environment_notices() {
		$environment_check = new Environment_Check( $this );
		$environment_check->run();
	}

	/**
	 * Initialize admin notices
	 */
	public function init_admin_notices() {
		$notice_controller = new Notice_Controller( $this );
		$notice_controller->run();
	}

	/**
	 * Initialize setup_wizard notices
	 */
	public function init_setup_wizard_notices() {
		if ( $this->environment_has_no_errors() ) {
			$setup_wiard = new Setup_Wizard();
			$setup_wiard->run();
		}
	}

	/**
	 * Init front order page.
	 */
	public function init_front_order_page() {
		if ( $this->environment_has_no_errors() && $this->can_use_plugin() ) {
			$front_order_page = new Front_Order_Page( $this );
			$front_order_page->run();
		}
	}

	/**
	 * Init front subscription page.
	 */
	public function init_front_subscription_page() {
		if ( $this->environment_has_no_errors() && $this->can_use_plugin() ) {
			$front_subscription_page = new Front_Subscription_Page( $this );
			$front_subscription_page->run();
		}
	}

	/**
	 * Init admin order page.
	 */
	public function init_admin_order_page() {
		if ( $this->environment_has_no_errors() && $this->can_use_plugin() ) {
			$admin_order_page = new Admin_Order_Page( $this );
			$admin_order_page->run();
		}
	}

	/**
	 * Init admin subscription page.
	 */
	public function init_admin_subscription_page() {
		if ( $this->environment_has_no_errors() && $this->can_use_plugin() ) {
			$admin_subscription_page = new Admin_Subscription_Page( $this );
			$admin_subscription_page->run();
		}
	}

	/**
	 * Init settings page.
	 */
	public function init_settings_page() {
		if ( $this->environment_has_no_errors() && $this->can_use_plugin() ) {
			$settings_page = new Page( $this );
			$settings_page->run();
		}
	}

	/**
	 * Init api actions.
	 */
	public function init_api_action() {
		if ( $this->environment_has_no_errors() && $this->can_use_plugin() ) {
			$api_action = new Api_Action( $this );
			$api_action->run();
		}
	}

	/**
	 * Register before woocommerce init hooks and filters.
	 *
	 * @void
	 */
	public function plugins_before_woocommerce_init_action() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			$plugin_name = Branding::$branding . '-connect/' . Branding::$branding . '-connect.php';
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $plugin_name, true );
		}
	}

	/**
	 * Register plugin hooks and filters.
	 *
	 * @void
	 */
	public function plugins_loaded_action() {
		$this->init_check_environment_notices();
		$this->init_admin_notices();
		$this->init_setup_wizard_notices();

		$this->init_front_order_page();
		$this->init_front_subscription_page();
		$this->init_admin_order_page();
		$this->init_admin_subscription_page();
		$this->init_settings_page();

		$this->init_api_action();

		$this->register_parcel_point();
		$this->register_shipping_pethod();
		$this->register_rest();
	}

	/**
	 * Network activation.
	 *
	 * @param boolean $network_wide whether it is a network wide activation or not.
	 * @void
	 */
	public static function activation_hook( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() && $network_wide ) {
			global $wpdb;

			$blog_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				self::$instance->activation_notices();
				restore_current_blog();
			}
		} else {
			self::$instance->activation_notices();
		}

		$setup_wizzard = new Setup_Wizard( true );
		$setup_wizzard->run();
	}

	/**
	 * Initialize activation notices.
	 *
	 * @void
	 */
	private function activation_notices() {
		Database_Util::create_tables();

		if ( ! Configuration_Util::is_first_activation() && $this->can_use_plugin() && Shipping_Method_Util::is_used_deprecated_parcel_point_field() ) {
			Notice_Controller::add_notice(
				Notice_Controller::$custom,
				array(
					'status'       => 'warning',
					/* translators: 1) Company name 2) Company name */
					'message'      => sprintf( __( '%1$s Connect - from version 1.1.0, use of parcel point map additional field on shipping methods is deprecated. Use the %2$s Connect method instead.', 'boxtal-connect' ), Branding::$company_name, Branding::$company_name ),
					'autodestruct' => false,
				)
			);
		}
	}

	/**
	 * Network uninstall hook.
	 */
	public static function uninstall_hook() {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			global $wpdb;

			$blog_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				Configuration_Util::delete_configuration();
				restore_current_blog();
			}
		} else {
			Configuration_Util::delete_configuration();
		}
	}

	/**
	 * Runs activation for a plugin on a new site if plugin is already set as network activated on multisite
	 *
	 * @param int    $blog_id blog id of the created blog.
	 * @param int    $user_id user id of the user creating the blog.
	 * @param string $domain domain used for the new blog.
	 * @param string $path path to the new blog.
	 * @param int    $site_id site id.
	 * @param array  $meta meta data.
	 *
	 * @void
	 */
	public function wpmu_new_blog_action( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( is_plugin_active_for_network( Branding::$branding . '-connect/' . Branding::$branding . '-connect.php' ) ) {
			switch_to_blog( $blog_id );
			self::$instance->activation_notices();
			restore_current_blog();
		}
	}

	/**
	 * Runs uninstall for a plugin on a multisite site if site is deleted
	 *
	 * @param array $tables the site tables to be dropped.
	 * @param int   $blog_id the id of the site to drop tables for.
	 *
	 * @return array
	 */
	public function wpmu_drop_tables_action( $tables, $blog_id ) {
		global $wpdb;
		$tables[] = $wpdb->prefix . Branding::$branding_short . '_pricing_items';
		return $tables;
	}
}
