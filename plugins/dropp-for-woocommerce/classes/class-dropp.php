<?php
/**
 * Dropp
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Shipping_Method\Shipping_Method;
use Dropp\Utility\Zone_Utility;
use WC_Order;
use WC_Shipping;
use WC_Shipping_Zones;

/**
 * Dropp
 */
class Dropp {

	const VERSION = '2.1.1';

	/**
	 * Setup
	 */
	public static function loaded(): void
	{
		require_once dirname( __DIR__ ) . '/traits/trait-shipping-settings.php';
		require_once dirname( __DIR__ ) . '/traits/trait-calculates-package-weight.php';

		spl_autoload_register( __CLASS__ . '::class_loader' );

		Shipping_Calculation_Shortcodes::setup();
		Shipping_Item_Meta::setup();
		// Initialise pending shipping status for orders.
		Pending_Shipping::setup();
		// Display a meta box on orders for booking with dropp.
		Shipping_Meta_Box::setup();
		Ajax::setup();
		Order_Bulk_Actions::setup();
		Social_Security_Number::setup();
		Postcode_Validation::setup();
		Tracking_Code::setup();
		Checkout::setup();
		Dropp_Oca_Admin_Warning::setup();
		Sort_Shipping_Methods::setup();
		Modify_Rate_Cost_By_Weight::setup();
		Upgrade::setup();


		add_filter( 'woocommerce_shipping_methods', __CLASS__ . '::add_shipping_method' );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts' );
		add_action( 'woocommerce_before_order_object_save', __CLASS__ . '::maybe_convert_dropp_order_ids' );
		add_action( 'woocommerce_shipping_zone_method_added', __CLASS__ . '::maybe_add_oca', 10, 3 );

		// Toggle between dropp outside and inside capital area.
		add_filter( 'woocommerce_shipping_chosen_method', __CLASS__ . '::oca_toggle', 10, 3 );

		// Add settings link on plugin page.
		$plugin_path = basename( dirname( __DIR__ ) );
		$hook        = "plugin_action_links_{$plugin_path}/dropp-for-woocommerce.php";
		add_filter( $hook, __CLASS__ . '::plugin_action_links' );

		load_plugin_textdomain( 'dropp-for-woocommerce', false, basename( dirname( __DIR__ ) ) . '/languages/' );

		Admin_Notices::setup();
	}

	/**
	 * Class loader
	 *
	 * @param string $class_name Path to class file.
	 */
	public static function class_loader( $class_name ): void
	{
		if ( ! preg_match( '/^Dropp(\\\.*)$/', $class_name, $matches ) ) {
			return;
		}
		$path      = substr( strtolower( $matches[1] ), 1 );
		$path      = preg_replace( '/_/', '-', $path );
		$parts     = explode( '\\', $path );
		$file_name = array_pop( $parts );
		$dir       = implode( '/', $parts );
		if ( $dir ) {
			$dir = "/$dir";
		}
		$file_name = __DIR__ . "{$dir}/class-{$file_name}.php";
		if ( file_exists( $file_name ) ) {
			require_once $file_name;
		}
	}

	/**
	 * Admin enqueue script
	 *
	 * @param string $hook Hook.
	 */
	public static function admin_enqueue_scripts( $hook ) {
		if ( 'woocommerce_page_wc-settings' !== $hook ) {
			return;
		}
		wp_enqueue_style('dropp-admin-css', plugin_dir_url( __DIR__ ) . '/assets/css/dropp-admin.css', [], Dropp::VERSION);
		wp_enqueue_script( 'dropp-admin-js', plugin_dir_url( __DIR__ ) . '/assets/js/dropp-admin.js', [], Dropp::VERSION, true );
		wp_localize_script( 'dropp-admin-js', '_dropp', ['ajaxurl' => admin_url( 'admin-ajax.php' ),] );
	}


	/**
	 * Maybe add OCA
	 */
	public static function maybe_add_oca( $instance_id, $type, $id ) {
		if ( 'dropp_is' !== $type ) {
			return;
		}

		$zones = Zone_Utility::get_zones();
		$zone  = false;
		foreach ( $zones as $zone_data ) {
			foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
				if ( $instance_id !== $shipping_method->instance_id ) {
					continue;
				}
				$zone = WC_Shipping_Zones::get_zone( $zone_data['zone_id'] );
				break;
			}
		}
		if ( ! $zone ) {
			return;
		}

		foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
			// Don't add duplicates.
			if ( 'dropp_is_oca' === $shipping_method->id ) {
				return;
			}
		}

		$zone->add_shipping_method( 'dropp_is_oca' );
	}


	/**
	 * @param WC_Order $order Order.
	 */
	public static function maybe_convert_dropp_order_ids( $order ): void
	{
		$adapter = new Order_Adapter( $order );
		$action  = new Actions\Convert_Dropp_Order_Ids_To_Consignments_Action( $adapter );
		$action->handle();
	}

	/**
	 * Add shipping methods
	 *
	 * @param WC_Shipping[] $shipping_methods Array of WC_Shipping mehtods.
	 *
	 * @return WC_Shipping[] $shipping_methods Array of WC_Shipping mehtods.
	 */
	public static function add_shipping_method( $shipping_methods ): array
	{
		return $shipping_methods + self::get_shipping_methods( self::is_pickup_enabled() );
	}

	/**
	 * Get shipping methods
	 *
	 * @return WC_Shipping[] $shipping_methods Array of WC_Shipping methods.
	 */
	public static function get_shipping_methods( $with_pickup = false ): array
	{
		$shipping_methods = [
			'dropp_is'        => 'Dropp\Shipping_Method\Dropp',
			'dropp_is_oca'    => 'Dropp\Shipping_Method\Dropp_Outside_Capital_Area',
			'dropp_home'      => 'Dropp\Shipping_Method\Home_Delivery',
			'dropp_home_oca'  => 'Dropp\Shipping_Method\Home_Delivery_Outside_Capital_Area',
			'dropp_daytime'   => 'Dropp\Shipping_Method\Daytime_Delivery',
			'dropp_flytjandi' => 'Dropp\Shipping_Method\Flytjandi',
		];
		if ( $with_pickup ) {
			$shipping_methods['dropp_pickup'] = 'Dropp\Shipping_Method\Pickup';
		}

		return $shipping_methods;
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @param array $links The action links displayed for each plugin in the Plugins list table.
	 *
	 * @return array
	 */
	public static function plugin_action_links( array $links ): array
	{
		$url          = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=dropp_is' );
		$action_links = [
			'settings' => '<a href="' . $url . '" title="' . esc_attr__( 'View Dropp Settings', 'dropp-for-woocommerce' ) . '">' . esc_html__( 'Settings', 'dropp-for-woocommerce' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}

	/**
	 * Is pickup enabled
	 *
	 * @param Shipping_Method|null $shipping_method (optional) Shipping method.
	 *
	 * @return boolean                          True if pickup is enabled.
	 */
	public static function is_pickup_enabled( ?Shipping_Method $shipping_method = null ): bool
	{
		$pickup_enabled = get_transient( 'dropp_pickup_enabled' );
		if ( empty( $pickup_enabled ) ) {
			try {
				$api            = new API();
				$result         = $api->get( 'orders/havepickup/' );
				$pickup_enabled = ( ! empty( $result['pickup'] ) && $result['pickup'] ? 'yes' : 'no' );
				set_transient( 'dropp_pickup_enabled', $pickup_enabled, ( 'yes' === $pickup_enabled ? DAY_IN_SECONDS : 300 ) );
			} catch ( \Exception $e ) {
				$pickup_enabled = false;
			}
		}

		return 'yes' === $pickup_enabled;
	}

	/**
	 * Dropp - Toggle between inside and outside capital area
	 *
	 * @param  string $default Default method id.
	 * @param  array  $rates Package data array.
	 * @param  string $chosen_method Chosen method id.
	 *
	 * @return string Chosen method id.
	 */
	public static function oca_toggle( $default, $rates, $chosen_method ): string
	{
		if (!str_starts_with($chosen_method, 'dropp_is')) {
			return $default;
		}

		if ( ! empty( $rates[ $chosen_method ] ) ) {
			return $default;
		}
		foreach ( $rates as $method_id => $rate ) {
			if ( !str_starts_with($method_id, 'dropp_is')) {
				continue;
			}
			return $method_id;
		}
		return $default;
	}
}
