<?php
use Bpost\BpostApiClient\Bpost\ProductConfiguration;
use Bpost\BpostApiClient\Bpost\ProductConfiguration\DeliveryMethod;

/**
 * Class WC_BPost_Shipping_Delivery_Method treats the delivery methods
 */
class WC_BPost_Shipping_Delivery_Methods {

	/**
	 * @var ProductConfiguration
	 */
	private $product_configuration;

	/**
	 * WC_BPost_Shipping_Delivery_Methods constructor.
	 *
	 * @param ProductConfiguration $product_configuration
	 */
	public function __construct( ProductConfiguration $product_configuration ) {
		$this->product_configuration = $product_configuration;
	}

	/**
	 * @param bool $is_national_shipping
	 *
	 * @return string[]
	 */
	public function get_delivery_method_overrides( $is_national_shipping, $check_visible_methods = true ) {
		$methods = $is_national_shipping ? $this->get_national_methods() : $this->get_international_methods();

		$override_methods = array();

		$visible_methods = $this->get_visible_methods();

		foreach ( $methods as $method_name ) {
			$method = new WC_BPost_Shipping_Delivery_Method( $method_name );

			$method_api_name = $method->get_api_name();
			if ( ! $check_visible_methods || (
					isset( $visible_methods[ $method_api_name ] )
					&& $visible_methods[ $method_api_name ]->isVisibleAndActive()
				)
			) {
				// Do not override a invisible or inactive method
				$override_methods[] = "$method_name|VISIBLE|0";
			}
		}

		return $override_methods;
	}

	/**
	 * @return string[]
	 */
	public function get_national_methods() {
		return array(
			WC_BPost_Shipping_Delivery_Method::DELIVERY_METHOD_REGULAR,
			WC_BPost_Shipping_Delivery_Method::DELIVERY_METHOD_PUGO,
			WC_BPost_Shipping_Delivery_Method::DELIVERY_METHOD_PARCELS_DEPOT,
			WC_BPost_Shipping_Delivery_Method::DELIVERY_METHOD_SHOP_POINT,
		);
	}

	/**
	 * @return string[]
	 */
	public function get_international_methods() {
		return array(
			WC_BPost_Shipping_Delivery_Method::DELIVERY_METHOD_BPACK_BUSINESS,
			WC_BPost_Shipping_Delivery_Method::DELIVERY_METHOD_BPACK_EXPRESS,
			WC_BPost_Shipping_Delivery_Method::DELIVERY_METHOD_PUGO_INTERNATIONNAL,
		);
	}

	/**
	 * @return DeliveryMethod[]
	 */
	public function get_visible_methods() {
		$visible_methods = array();

		foreach ( $this->product_configuration->getDeliveryMethods() as $delivery_method ) {
			if ( $delivery_method->isVisibleAndActive() ) {
				$visible_methods[ $delivery_method->getName() ] = $delivery_method;
			}
		}

		return $visible_methods;
	}
}
