<?php
use Bpost\BpostApiClient\Bpost\ProductConfiguration\DeliveryMethod;

/**
 * Class WC_BPost_Shipping_Delivery_Method treats the delivery methods
 */
class WC_BPost_Shipping_Delivery_Method {

	const DELIVERY_METHOD_REGULAR             = 'Regular';
	const DELIVERY_METHOD_PUGO                = 'Pugo';
	const DELIVERY_METHOD_PUGO_INTERNATIONNAL = 'Pugo international';
	const DELIVERY_METHOD_PARCELS_DEPOT       = 'Parcels depot';
	const DELIVERY_METHOD_SHOP_POINT          = 'Shop point';
	const DELIVERY_METHOD_BPACK_EXPRESS       = 'bpack EXPRESS';
	const DELIVERY_METHOD_BPACK_BUSINESS      = 'bpack BUSINESS';

	const DELIVERY_METHOD_TRANSLATED_HOME_OR_OFFICE = 'home or office';
	const DELIVERY_METHOD_TRANSLATED_PICKUP_POINT   = 'pick-up point';
	const DELIVERY_METHOD_TRANSLATED_PARCELS_LOCKER = 'parcel locker';
	const DELIVERY_METHOD_TRANSLATED_SHOP_POINT     = 'shop point';
	const DELIVERY_METHOD_TRANSLATED_INTERNATIONAL  = 'international';

	const DELIVERY_METHOD_API_HOME_OR_OFFICE    = 'home or office';
	const DELIVERY_METHOD_API_PICKUP_POINT      = 'pick-up point';
	const DELIVERY_METHOD_API_PARCELS_LOCKER    = 'parcel locker';
	const DELIVERY_METHOD_API_CLICK_AND_COLLECT = 'Click & Collect';

	private $delivery_method;

	/**
	 * WC_BPost_Shipping_Delivery_Method constructor.
	 *
	 * @param string $delivery_method
	 */
	public function __construct( $delivery_method ) {
		$this->delivery_method = $delivery_method;
	}

	/**
	 * @param string $postal_location
	 * @param string $company
	 *
	 * @return string
	 */
	public function get_company_name( $postal_location, $company ) {
		switch ( $this->delivery_method ) {
			case self::DELIVERY_METHOD_PUGO:
			case self::DELIVERY_METHOD_PARCELS_DEPOT:
			case self::DELIVERY_METHOD_SHOP_POINT:
				return $postal_location;

			case self::DELIVERY_METHOD_REGULAR:
			case self::DELIVERY_METHOD_BPACK_EXPRESS:
			case self::DELIVERY_METHOD_BPACK_BUSINESS:
			default:
				return $company;
		}
	}

	/**
	 * @return string return a string not translated
	 */
	public function get_delivery_method_as_string() {
		/**
		 *   Here to help the generation of the pot file:
		 * bpost('home or office');
		 * bpost('pick-up point');
		 * bpost('parcel locker');
		 * bpost('shop point');
		 * bpost('international');
		 * bpost('bpost shipping');
		 */

		switch ( $this->delivery_method ) {
			case self::DELIVERY_METHOD_REGULAR:
				return self::DELIVERY_METHOD_TRANSLATED_HOME_OR_OFFICE;
			case self::DELIVERY_METHOD_PUGO:
				return self::DELIVERY_METHOD_TRANSLATED_PICKUP_POINT;
			case self::DELIVERY_METHOD_PARCELS_DEPOT:
				return self::DELIVERY_METHOD_TRANSLATED_PARCELS_LOCKER;
			case self::DELIVERY_METHOD_SHOP_POINT:
				return self::DELIVERY_METHOD_TRANSLATED_SHOP_POINT;
			case self::DELIVERY_METHOD_BPACK_EXPRESS:
			case self::DELIVERY_METHOD_BPACK_BUSINESS:
				return self::DELIVERY_METHOD_TRANSLATED_INTERNATIONAL;
			default:
				return 'bpost shipping';
		}
	}

	/**
	 * @return string return the name used by API
	 */
	public function get_api_name() {
		switch ( $this->delivery_method ) {
			case self::DELIVERY_METHOD_REGULAR:
			case self::DELIVERY_METHOD_BPACK_EXPRESS:
			case self::DELIVERY_METHOD_BPACK_BUSINESS:
				return DeliveryMethod::DELIVERY_METHOD_NAME_HOME_OR_OFFICE;
			case self::DELIVERY_METHOD_PUGO:
			case self::DELIVERY_METHOD_PUGO_INTERNATIONNAL:
				return DeliveryMethod::DELIVERY_METHOD_NAME_PICKUP_POINT;
			case self::DELIVERY_METHOD_PARCELS_DEPOT:
				return DeliveryMethod::DELIVERY_METHOD_NAME_PARCEL_LOCKER;
			case self::DELIVERY_METHOD_SHOP_POINT:
				return DeliveryMethod::DELIVERY_METHOD_NAME_CLICK_AND_COLLECT;
			default:
				return 'bpost shipping';
		}
	}

	/**
	 * when a pugo or parcel depot is used it's important to get the name of this one
	 *
	 * @param string $postal_location
	 *
	 * @return string
	 */
	public function get_delivery_point( $postal_location ) {
		switch ( $this->delivery_method ) {
			case self::DELIVERY_METHOD_PUGO:
			case self::DELIVERY_METHOD_PARCELS_DEPOT:
				return $postal_location;

			default:
				return '';
		}
	}

}
