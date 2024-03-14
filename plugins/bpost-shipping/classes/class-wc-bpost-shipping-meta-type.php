<?php
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;

/**
 * Class WC_BPost_Shipping_Meta_Type allows to manage different meta data's types & 'translate' it
 */
class WC_BPost_Shipping_Meta_Type {

	/** don't need translation, show 'as it' */
	const VALUE_TYPE_RAW = 'raw';
	/** use the translation system to provide a i18n value */
	const VALUE_TYPE_TO_TRANSLATE = 'translate';
	/** format the date according WordPress requirement */
	const VALUE_TYPE_DATE = 'date';
	/** prefix for all bpost keys used */
	const BPOST_KEY_PREFIX = '_bpost_';

	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;

	/**
	 * WC_BPost_Shipping_Meta_Type constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 */
	public function __construct( WC_BPost_Shipping_Adapter_Woocommerce $adapter ) {
		$this->adapter = $adapter;
	}

	/**
	 * @param string $bpost_meta_value
	 * @param string $bpost_meta_type
	 *
	 * @return string
	 */
	public function get_bpost_meta_typed_value( $bpost_meta_value, $bpost_meta_type ) {
		switch ( $bpost_meta_type ) {
			case self::VALUE_TYPE_DATE:
				$delivery_date = DateTime::createFromFormat( 'Y-m-d', $bpost_meta_value );
				if ( ! $delivery_date ) {
					return null;
				}

				return $this->adapter->date_i18n( get_option( 'date_format' ), $delivery_date->getTimestamp() );
			case self::VALUE_TYPE_TO_TRANSLATE:
				return bpost__( $bpost_meta_value );

			case self::VALUE_TYPE_RAW:
			default:
				return $bpost_meta_value;
		}
	}

	/**
	 * @param string $bpost_meta_key
	 *
	 * @return string
	 */
	public function get_translated_label_for( $bpost_meta_key ) {
		switch ( $bpost_meta_key ) {
			case WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_DATE:
				return bpost__( 'Delivery Date' );

			case WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_METHOD:
				return bpost__( 'Delivery Method' );

			case WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_POINT:
				return bpost__( 'Delivery point' );

			case WC_BPost_Shipping_Meta_Handler::KEY_ORDER_REFERENCE:
				return bpost__( 'Order reference' );

			case WC_BPost_Shipping_Meta_Handler::KEY_EMAIL:
				return bpost__( 'Shipping email' );

			case WC_BPost_Shipping_Meta_Handler::KEY_PHONE_NUMBER:
				return bpost__( 'Shipping phone' );

			case WC_BPost_Shipping_Meta_Handler::KEY_STATUS:
				return bpost__( 'Shipping status' );

			default:
				return '';

		}
	}

}
