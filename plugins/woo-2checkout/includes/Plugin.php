<?php
/**
 * Main Plugin Class.
 *
 * @package    StorePress\TwoCheckoutPaymentGateway
 * @since      1.0.0
 */

namespace StorePress\TwoCheckoutPaymentGateway;

defined( 'ABSPATH' ) || die( 'Keep Silent' );

use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
use Automattic\WooCommerce\Blocks\Registry\Container;
use Automattic\WooCommerce\Utilities\FeaturesUtil;
use StorePress\TwoCheckoutPaymentGateway\ConvertPlus\ConvertPlus_Block;
use StorePress\TwoCheckoutPaymentGateway\ConvertPlus\ConvertPlus_Gateway;
use WC_Payment_Gateway;
use Exception;

/**
 * Class Plugin.
 */
class Plugin {

	/**
	 * Return singleton instance of Class.
	 * The instance will be created if it does not exist yet.
	 *
	 * @return self The main instance.
	 * @since 1.0.0
	 */
	public static function instance(): self {
		static $instance = null;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Initialise the plugin.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		try {
			$this->includes();
			$this->hooks();
			$this->init();
		} catch ( Exception $e ) {
			$this->trigger_error( __METHOD__, $e->getMessage() );
		}

		/**
		 * Action to signal that Plugin has finished loading.
		 *
		 * @param Plugin $this Plugin Object.
		 *
		 * @since 1.0.0
		 */
		do_action( 'woo_2checkout_plugin_loaded', $this );
	}

	/**
	 * Plugin Absolute File.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_plugin_file(): string {
		return constant( 'STOREPRESS_TWO_CHECKOUT_PLUGIN_FILE' );
	}

	/**
	 * Get Compatible Extended Plugin Version.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_compatible_extended_version(): string {
		return constant( 'STOREPRESS_TWO_CHECKOUT_COMPATIBLE_EXTENDED_VERSION' );
	}

	/**
	 * Get Plugin Version.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function version(): string {
		static $versions;

		if ( is_null( $versions ) ) {
			$versions = get_file_data( $this->get_plugin_file(), array( 'Version' ) );
		}

		return esc_attr( $versions[0] );
	}


	/**
	 * Set constant if not defined and prevent reassign
	 *
	 * @param string $name  Constant name.
	 * @param mixed  $value Constant value.
	 *
	 * @return void.
	 * @since 1.0.0
	 */
	public function define( string $name, $value ) {
		if ( ! defined( $name ) ) {
			// phpcs:ignore
			define( $name, $value );
		}
	}

	/**
	 * Includes.
	 *
	 * @since 1.0.0
	 */
	public function includes() {

		if ( file_exists( $this->vendor_path() . '/autoload.php' ) ) {
			require_once $this->vendor_path() . '/autoload.php';
			require_once __DIR__ . '/functions.php';
		}
	}

	/**
	 * Initialize Classes.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		Extended_Plugin_Upgrade_Notice::instance();
		$this->register_dependencies();
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function hooks() {
		// Register with hook.
		add_action( 'init', array( $this, 'language' ), 1 );

		add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );

		add_action( 'woocommerce_blocks_loaded', array( $this, 'add_block_support' ) );

		add_action( 'before_woocommerce_init', array( $this, 'add_custom_order_table_support' ) );

		add_filter( 'plugin_action_links_' . $this->plugin_basename(), array( $this, 'plugin_action_links' ) );

		add_filter(
			'woocommerce_no_available_payment_methods_message',
			array(
				$this,
				'available_payment_methods_message',
			)
		);
	}

	/**
	 * Available payment methods message.
	 *
	 * @return string
	 */
	public function available_payment_methods_message(): string {
		$pro_url  = 'https://getwooplugins.com/plugins/woocommerce-2checkout/';
		$pro_text = esc_html__( 'Payment Gateway - 2Checkout for WooCommerce - Pro ', 'woo-2checkout' );
		$pro_link = sprintf( '<a target="_blank" href="%s">%s</a>', esc_url( $pro_url ), $pro_text );

		return '<strong>' . sprintf( // translators: %s: Pro version link.
			esc_html__( 'Upgrade to %s to get WooCommerce Subscriptions payments, issue refunds from wp admin, inline popup checkout and more.', 'woo-2checkout' ),
			$pro_link
		) . '</strong>';
	}

	/**
	 * Language
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function language() {
		load_plugin_textdomain( 'woo-2checkout', false, $this->plugin_path() . '/languages' );
	}

	/**
	 * Get Plugin basename directory name
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function basename(): string {
		return wp_basename( dirname( $this->get_plugin_file() ) );
	}

	/**
	 * Get Plugin basename
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_basename(): string {
		return plugin_basename( $this->get_plugin_file() );
	}

	/**
	 * Get Plugin directory name
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_dirname(): string {
		return dirname( plugin_basename( $this->get_plugin_file() ) );
	}

	/**
	 * Get Plugin directory path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_path(): string {
		return untrailingslashit( plugin_dir_path( $this->get_plugin_file() ) );
	}

	/**
	 * Get Plugin directory url
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_url(): string {
		return untrailingslashit( plugin_dir_url( $this->get_plugin_file() ) );
	}

	/**
	 * Get Plugin image url
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function images_url(): string {
		return untrailingslashit( plugin_dir_url( $this->get_plugin_file() ) . 'images' );
	}

	/**
	 * Get Assets URL
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function assets_url(): string {
		return untrailingslashit( plugin_dir_url( $this->get_plugin_file() ) . 'assets' );
	}

	/**
	 * Get Asset Absolute Path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function assets_path(): string {
		return $this->plugin_path() . '/assets';
	}

	/**
	 * Get Vendor path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function vendor_path(): string {
		return $this->plugin_path() . '/vendor';
	}

	/**
	 * Get Vendor URL
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function vendor_url(): string {
		return untrailingslashit( plugin_dir_url( $this->get_plugin_file() ) . 'vendor' );
	}

	/**
	 * Get Node Modules build URL
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function build_url(): string {
		return untrailingslashit( plugin_dir_url( $this->get_plugin_file() ) . 'build' );
	}

	/**
	 * Get Node Modules build path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function build_path(): string {
		return $this->plugin_path() . '/build';
	}

	/**
	 * Get Asset file make time for versioning.
	 *
	 * @param string $file Asset file name.
	 *
	 * @return int asset file make time.
	 * @since 1.0.0
	 */
	public function assets_version( string $file ): int {
		return filemtime( $this->assets_path() . $file );
	}

	/**
	 * Get includes directory absolute path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function include_path(): string {
		return untrailingslashit( plugin_dir_path( $this->get_plugin_file() ) . 'includes' );
	}

	/**
	 * Generates a user-level error/warning/notice/deprecation message.
	 *
	 * Generates the message when `WP_DEBUG` is true.
	 *
	 * @param string $function_name The function that triggered the error.
	 * @param string $message       The message explaining the error.
	 *                              The message can contain allowed HTML 'a' (with href), 'code',
	 *                              'br', 'em', and 'strong' tags and http or https protocols.
	 *                              If it contains other HTML tags or protocols, the message should be escaped
	 *                              before passing to this function to avoid being stripped {@see wp_kses()}.
	 *
	 * @since 1.0.0
	 */
	public function trigger_error( string $function_name, string $message ) {

		// Bail out if WP_DEBUG is not turned on.
		if ( ! WP_DEBUG ) {
			return;
		}

		if ( function_exists( 'wp_trigger_error' ) ) {
			wp_trigger_error( $function_name, $message );
		} else {

			if ( ! empty( $function_name ) ) {
				$message = sprintf( '%s(): %s', $function_name, $message );
			}

			$message = wp_kses(
				$message,
				array(
					'a' => array( 'href' ),
					'br',
					'code',
					'em',
					'strong',
				),
				array( 'http', 'https' )
			);

			// phpcs:ignore
			trigger_error( $message );
		}
	}

	// Add Features...
	// Happy Coding.

	/**
	 * Get payment gateway class name by checkout type.
	 *
	 * @return string Gateway Class Name.
	 */
	public function get_gateway_class_by_style(): string {
		return ConvertPlus_Gateway::class;
	}

	/**
	 * Get payment gateway block class name by checkout type.
	 *
	 * @return string Gateway Block Class Name.
	 */
	public function get_gateway_block_class_by_style(): string {
		return ConvertPlus_Block::class;
	}


	/**
	 * Add gateway class name.
	 *
	 * @param array $methods gateway names.
	 *
	 * @return array
	 */
	public function add_gateways( array $methods ): array {
		$methods[] = $this->get_gateway_class_by_style();

		return $methods;
	}

	/**
	 * Add gateway block support.
	 *
	 * @return void
	 */
	public function add_block_support() {

		if ( ! class_exists( '\Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			return;
		}

		add_action( 'woocommerce_blocks_payment_method_type_registration', array( $this, 'add_blocks' ) );
	}

	/**
	 * Add payment method blocks.
	 *
	 * @param PaymentMethodRegistry $payment_method_registry Payment methods registry class instance.
	 *
	 * @return void
	 * @throws Exception Throw errors.
	 */
	public function add_blocks( PaymentMethodRegistry $payment_method_registry ) {
		$container        = $this->container();
		$block_class_name = $this->get_gateway_block_class_by_style();
		$payment_method_registry->register( $container->get( $block_class_name ) );
	}

	/**
	 * Add Custom order table support.
	 *
	 * @return void
	 */
	public function add_custom_order_table_support() {

		if ( ! class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			return;
		}
		FeaturesUtil::declare_compatibility( 'custom_order_tables', $this->get_plugin_file(), true );
	}

	/**
	 * Add new action link.
	 *
	 * @param array $links available action links.
	 *
	 * @return array
	 */
	public function plugin_action_links( array $links ): array {

		$new_links = array();

		$settings_link = add_query_arg(
			array(
				'page'    => 'wc-settings',
				'tab'     => 'checkout',
				'section' => 'woo-2checkout',
			),
			admin_url( 'admin.php' )
		);

		$new_links['settings'] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', esc_url( $settings_link ), esc_attr__( 'Settings', 'woo-2checkout' ) );

		return array_merge( $links, $new_links );
	}

	/**
	 * Get Gateway class instance.
	 *
	 * @return Payment_Gateway
	 */
	public function get_gateway(): Payment_Gateway {
		$gateway_id       = 'woo-2checkout';
		$payment_gateways = WC()->payment_gateways->payment_gateways();

		return $payment_gateways[ $gateway_id ];
	}

	/**
	 * Loads the dependency injection container.
	 */
	public function register_dependencies() {

		$container = $this->container();

		// Register block service provider classes.
		$container->register(
			ConvertPlus_Block::class,
			function () {
				return new ConvertPlus_Block();
			}
		);
	}

	/**
	 * Loads the dependency injection container.
	 *
	 * @return Container;
	 */
	public function container(): Container {
		return Package::container();
	}

	/**
	 * Check is pro.
	 *
	 * @return bool
	 */
	public function is_pro(): bool {
		return false;
	}
}
