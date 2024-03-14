<?php
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;

/**
 * Class WC_BPost_Shipping_Meta_Handler:
 *  - sets  meta data into WP_Order
 *  - gets 'translated' meta data TODO too much responsibilities remove translated get system
 */
class WC_BPost_Shipping_Meta_Handler {

	const INDEX_TYPE  = 0;
	const INDEX_VALUE = 1;

	/**
	 * Keys used to store the meta
	 */
	const KEY_DELIVERY_POINT           = 'delivery_point';
	const KEY_DELIVERY_POINT_TYPE      = 'delivery_point_type';
	const KEY_DELIVERY_METHOD          = 'delivery_method';
	const KEY_DELIVERY_DATE            = 'delivery_date';
	const KEY_ORDER_REFERENCE          = 'order_reference';
	const KEY_PHONE_NUMBER             = 'phone_number';
	const KEY_EMAIL                    = 'email';
	const KEY_STATUS                   = 'status';
	const KEY_DELIVERY_METHOD_POINT_ID = 'delivery_method_point_id';

	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;

	/** @var int */
	private $order_id;
	/**
	 * @var WC_BPost_Shipping_Meta_Type
	 */
	private $meta_type;

	/**
	 * WC_BPost_Shipping_Meta_Handler constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Meta_Type $meta_type
	 * @param int $order_id
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Meta_Type $meta_type,
		$order_id
	) {
		$this->adapter   = $adapter;
		$this->meta_type = $meta_type;
		$this->order_id  = (int) $order_id;
	}

	/**
	 * @return string
	 */
	public function get_delivery_point() {
		return $this->get_value( self::KEY_DELIVERY_POINT );
	}

	/**
	 * @param int $delivery_point_type
	 */
	public function set_delivery_point_type( $delivery_point_type ) {
		$this->set_meta( self::KEY_DELIVERY_POINT_TYPE, $delivery_point_type );
	}

	/**
	 * @return int
	 */
	public function get_delivery_point_type() {
		return (int) $this->get_value( self::KEY_DELIVERY_POINT_TYPE );
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function set_meta( $key, $value ) {
		update_post_meta( $this->order_id, WC_BPost_Shipping_Meta_Type::BPOST_KEY_PREFIX . $key, $value );
	}

	/**
	 * @return string
	 */
	public function get_delivery_date() {
		return $this->get_value( self::KEY_DELIVERY_DATE, WC_BPost_Shipping_Meta_Type::VALUE_TYPE_DATE );
	}

	/**
	 * @return string
	 */
	public function get_delivery_method() {
		return $this->get_value( self::KEY_DELIVERY_METHOD, WC_BPost_Shipping_Meta_Type::VALUE_TYPE_TO_TRANSLATE );
	}

	/**
	 * @return array
	 */
	public function get_translated_bpost_meta() {
		return array_merge(
			$this->get_optional_translated_bpost_meta(),
			$this->get_mandatory_translated_bpost_meta()
		);
	}

	/**
	 * @return array
	 */
	private function get_optional_translated_bpost_meta() {
		$bpost_meta = array();

		$delivery_point = $this->get_delivery_point();
		if ( ! empty( $delivery_point ) ) {
			$bpost_meta[ self::KEY_DELIVERY_POINT ] = $this->get_translated_meta_item(
				self::KEY_DELIVERY_POINT,
				$delivery_point
			);
		}

		$delivery_date = $this->get_delivery_date();
		if ( ! empty( $delivery_date ) ) {
			$bpost_meta[ self::KEY_DELIVERY_DATE ] = $this->get_translated_meta_item(
				self::KEY_DELIVERY_DATE,
				$delivery_date
			);
		}

		return $bpost_meta;
	}

	/**
	 * @return array
	 */
	private function get_mandatory_translated_bpost_meta() {
		return array(
			self::KEY_DELIVERY_METHOD => $this->get_translated_meta_item(
				self::KEY_DELIVERY_METHOD,
				$this->get_delivery_method()
			),
			self::KEY_ORDER_REFERENCE => $this->get_translated_meta_item(
				self::KEY_ORDER_REFERENCE,
				$this->get_order_reference()
			),
			self::KEY_PHONE_NUMBER    => $this->get_translated_meta_item(
				self::KEY_PHONE_NUMBER,
				$this->get_phone()
			),
			self::KEY_EMAIL           => $this->get_translated_meta_item(
				self::KEY_EMAIL,
				$this->get_email()
			),
			self::KEY_STATUS          => $this->get_translated_meta_item(
				self::KEY_STATUS,
				$this->get_status()
			),
		);
	}

	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @return string[]
	 */
	private function get_translated_meta_item( $key, $value ) {
		return array(
			'translation' => $this->meta_type->get_translated_label_for( $key ),
			'value'       => $value,
		);
	}

	/**
	 * @return string
	 */
	public function get_order_reference() {
		return $this->get_value( self::KEY_ORDER_REFERENCE );
	}

	/**
	 * @return string
	 */
	public function get_phone() {
		return $this->get_value( self::KEY_PHONE_NUMBER );
	}

	/**
	 * @return string
	 */
	public function get_email() {
		return $this->get_value( self::KEY_EMAIL );
	}

	/**
	 * @param string $status
	 */
	public function set_status( $status ) {
		$this->set_meta( self::KEY_STATUS, $status );
	}

	/**
	 * @return string
	 */
	public function get_status() {
		return $this->get_value( self::KEY_STATUS ) ?: 'UNKNOWN';
	}

	/**
	 * @param string $key Without bpost key prefix
	 * @param string $type
	 *
	 * @return string
	 */
	private function get_value( $key, $type = WC_BPost_Shipping_Meta_Type::VALUE_TYPE_RAW ) {
		$meta = $this->adapter->get_post_meta(
			$this->order_id,
			WC_BPost_Shipping_Meta_Type::BPOST_KEY_PREFIX . $key
		);

		if ( ! is_array( $meta ) || count( $meta ) === 0 || count( $meta ) > 2 ) {
			return '';
		}

		/**
		 * Before, we stored type, then value
		 * Now, we store only the value
		 * To be compatible, we get the last item (so, the value)
		 */
		$value = array_pop( $meta );

		return $this->meta_type->get_bpost_meta_typed_value( $value, $type );
	}

	/**
	 * @return string
	 */
	public function get_delivery_point_id_value() {
		return $this->get_value( self::KEY_DELIVERY_METHOD_POINT_ID );
	}
}
