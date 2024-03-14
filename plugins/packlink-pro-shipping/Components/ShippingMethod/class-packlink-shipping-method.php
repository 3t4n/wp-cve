<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Components\ShippingMethod;

use Logeecom\Infrastructure\ORM\Interfaces\RepositoryInterface;
use Logeecom\Infrastructure\ORM\QueryFilter\QueryFilter;
use Logeecom\Infrastructure\ORM\RepositoryRegistry;
use Logeecom\Infrastructure\ServiceRegister;
use Packlink\BusinessLogic\Http\DTO\Package;
use Packlink\BusinessLogic\Http\DTO\ParcelInfo;
use Packlink\BusinessLogic\ShippingMethod\Models\ShippingMethod;
use Packlink\BusinessLogic\ShippingMethod\ShippingCostCalculator;
use Packlink\BusinessLogic\ShippingMethod\ShippingMethodService;
use Packlink\WooCommerce\Components\Services\Config_Service;
use Packlink\WooCommerce\Components\Services\System_Info_Service;
use WC_Eval_Math;
use WC_Product;

/**
 * Class Packlink_Shipping_Method
 *
 * @package Packlink\WooCommerce\Components\ShippingMethod
 */
class Packlink_Shipping_Method extends \WC_Shipping_Method {
	/**
	 * Fully qualified name of this interface.
	 */
	const CLASS_NAME               = __CLASS__;
	const PACKLINK_SHIPPING_METHOD = 'packlink_shipping_method';

	/**
	 * Available shipping services
	 *
	 * @var array
	 */
	private static $shipping_services = array();
	/**
	 * Available shipping services loaded.
	 *
	 * @var bool
	 */
	private static $loaded = false;
	/**
	 * Pricing policy.
	 *
	 * @var string
	 */
	public $price_policy;
	/**
	 * Type of class cost calculation.
	 *
	 * @var string
	 */
	public $class_cost_calculation_type;
	/**
	 * Configuration service.
	 *
	 * @var Config_Service
	 */
	private $configuration;
	/**
	 * Shipping method service.
	 *
	 * @var ShippingMethodService
	 */
	private $shipping_method_service;
	/**
	 * Base repository.
	 *
	 * @var RepositoryInterface
	 */
	private $repository;

	/**
	 * Constructor.
	 *
	 * @noinspection PhpDocMissingThrowsInspection
	 *
	 * @param int $instance_id Instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		parent::__construct( $instance_id );

		$this->id                 = static::PACKLINK_SHIPPING_METHOD;
		$this->method_title       = __( 'Packlink Shipping', 'packlink_pro_shipping' );
		$this->method_description = __( 'Custom Shipping Method for Packlink', 'packlink_pro_shipping' );

		$this->init();

		$this->enabled  = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
		$this->supports = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);

		/** @noinspection PhpUnhandledExceptionInspection */
		$this->repository              = RepositoryRegistry::getRepository( Shipping_Method_Map::CLASS_NAME );
		$this->shipping_method_service = ServiceRegister::getService( ShippingMethodService::CLASS_NAME );
		$this->configuration           = ServiceRegister::getService( Config_Service::CLASS_NAME );
	}

	/**
	 * Initialize settings.
	 */
	public function init() {
		// Load the settings API.
		$this->init_form_fields();
		$this->init_settings();

		$this->title                       = $this->get_option( 'title', __( 'Packlink Shipping', 'packlink_pro_shipping' ) );
		$this->price_policy                = $this->get_option( 'price_policy', __( 'Packlink prices', 'packlink_pro_shipping' ) );
		$this->class_cost_calculation_type = $this->get_option( 'class_cost_calculation_type', 'class' );

		// Save settings in admin if you have any defined.
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Initialise settings form fields.
	 *
	 * Add an array of fields to be displayed on the gateway's settings screen.
	 */
	public function init_form_fields() {
		$this->instance_form_fields = $this->instance_form_fields = include 'includes/settings-packlink-shipping.php';
	}

	/**
	 * Called to calculate shipping rates for this method. Rates can be added using the add_rate() method.
	 *
	 * @param array $package Package array.
	 */
	public function calculate_shipping( $package = array() ) {
		$shipping_method = $this->get_packlink_shipping_method();
		if ( ! $shipping_method || ! $this->load_shipping_costs( $package, $shipping_method ) ) {
			return;
		}

		$id   = $shipping_method->getId();
		$rate = array(
			'id'      => $this->get_rate_id(),
			'label'   => $this->title,
			'cost'    => - 1 === $id ? min( static::$shipping_services ) : static::$shipping_services[ $id ],
			'package' => $package,
		);

		$this->add_shipping_class_cost( $rate, $package );

		$this->add_rate( $rate );
	}

	/**
	 * Is this method available?
	 *
	 * @param array $package Package.
	 *
	 * @return bool
	 */
	public function is_available( $package ) {
		$shipping_method = $this->get_packlink_shipping_method();

		return $shipping_method && $this->load_shipping_costs( $package, $shipping_method );
	}

	/**
	 * Finds and returns shipping classes and the products with that class.
	 *
	 * @param mixed $package
	 *
	 * @return array
	 */
	public function find_shipping_classes( $package ) {
		$found_shipping_classes = array();

		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();

				$found_shipping_classes[ $found_class ][ $item_id ] = $values;
			}
		}

		return $found_shipping_classes;
	}

	/**
	 * Adds specific cost for shipping class, if set.
	 *
	 * @param array $rate
	 * @param       $package
	 */
	private function add_shipping_class_cost( array &$rate, $package ) {
		$shipping_classes = WC()->shipping->get_shipping_classes();

		if ( ! empty( $shipping_classes ) ) {
			$found_shipping_classes = $this->find_shipping_classes( $package );
			$cost                   = 0;

			foreach ( $found_shipping_classes as $shipping_class => $products ) {
				// Also handles BW compatibility when slugs were used instead of ids
				$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
				$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );

				if ( '' === $class_cost_string ) {
					continue;
				}

				$class_cost = $this->evaluate_cost( $class_cost_string, array(
					'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
					'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
				) );

				if ( 'class' === $this->class_cost_calculation_type ) {
					$cost += $class_cost;
				} else {
					$cost = max( $class_cost, $cost );
				}
			}

			$rate['cost'] += $cost;
		}
	}

	/**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param string $sum
	 * @param array  $args
	 *
	 * @return mixed
	 */
	private function evaluate_cost( $sum, $args = array() ) {
		include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
		$locale         = localeconv();
		$decimals       = array(
			wc_get_price_decimal_separator(),
			$locale['decimal_point'],
			$locale['mon_decimal_point'],
			','
		);
		$this->fee_cost = $args['cost'];

		add_shortcode( 'fee', array( $this, 'fee' ) );

		$sum = do_shortcode( str_replace(
			array(
				'[qty]',
				'[cost]',
			),
			array(
				$args['qty'],
				$args['cost'],
			),
			$sum
		) );

		remove_shortcode( 'fee', array( $this, 'fee' ) );

		// Remove whitespace from string
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Do the math
		return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
	}

	/**
	 * Returns Packlink shipping method that is assigned to this WooCommerce shipping method.
	 *
	 * @noinspection PhpDocMissingThrowsInspection
	 *
	 * @return ShippingMethod Shipping method.
	 */
	private function get_packlink_shipping_method() {
		$filter = new QueryFilter();
		/** @noinspection PhpUnhandledExceptionInspection */
		$filter->where( 'woocommerceShippingMethodId', '=', $this->instance_id );

		/**
		 * Shipping method map.
		 *
		 * @var Shipping_Method_Map $map_entry
		 */
		$map_entry = $this->repository->selectOne( $filter );
		if ( null === $map_entry ) {
			return null;
		}

		$id = $map_entry->getPacklinkShippingMethodId();
		if ( - 1 === $id ) {
			return $this->configuration->get_default_shipping_method();
		}

		return $this->shipping_method_service->getShippingMethod( $id );
	}

	/**
	 * Builds parcels out of shipping packages.
	 *
	 * @param array      $package Packages.
	 * @param ParcelInfo $default Default parcel.
	 *
	 * @return Package[] Array of parcels.
	 */
	private function build_parcels( array $package, ParcelInfo $default ) {
		$parcels  = array();
		$contents = isset( $package['contents'] ) ? $package['contents'] : array();
		foreach ( $contents as $item ) {
			/**
			 * WooCommerce product.
			 *
			 * @var WC_Product $product
			 */
			$product = $item['data'];
			for ( $i = 0; $i < $item['quantity']; $i ++ ) {
				$parcel = new Package();

				$parcel->weight = is_numeric( $product->get_weight() )
					? wc_get_weight( (float) $product->get_weight(), 'kg' )
					: $default->weight;
				$parcel->height = is_numeric( $product->get_height() )
					? wc_get_dimension( (float) $product->get_height(), 'cm' )
					: $default->height;
				$parcel->width  = is_numeric( $product->get_width() )
					? wc_get_dimension( (float) $product->get_width(), 'cm' )
					: $default->width;
				$parcel->length = is_numeric( $product->get_length() )
					? wc_get_dimension( (float) $product->get_length(), 'cm' )
					: $default->length;

				$parcels[] = $parcel;
			}
		}

		return $parcels;
	}

	/**
	 * Loads shipping costs.
	 *
	 * @param array          $package Package.
	 * @param ShippingMethod $shipping_method Shipping method.
	 *
	 * @return bool Success indicator.
	 */
	private function load_shipping_costs( array $package, ShippingMethod $shipping_method ) {
		$warehouse      = $this->configuration->getDefaultWarehouse();
		$default_parcel = $this->configuration->getDefaultParcel();

		if ( null === $warehouse || null === $default_parcel ) {
			return null;
		}

		$id         = $shipping_method->getId();
		$to_country = ! empty( $package['destination']['country'] ) ? $package['destination']['country'] : $warehouse->country;
		$to_zip     = ! empty( $package['destination']['postcode'] ) ? $package['destination']['postcode'] : $warehouse->postalCode;
		if ( ! static::$loaded ) {
			static::$shipping_services = ShippingCostCalculator::getShippingCosts(
				$this->shipping_method_service->getAllMethods(),
				$warehouse->country,
				$warehouse->postalCode,
				$to_country,
				$to_zip,
				$this->build_parcels( $package, $default_parcel ),
				$package['cart_subtotal'],
				System_Info_Service::SYSTEM_ID
			);

			static::$loaded = true;
		}

		return array_key_exists( $id, static::$shipping_services ) || ( - 1 === $id && ! empty( static::$shipping_services ) );
	}
}
