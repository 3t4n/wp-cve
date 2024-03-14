<?php

namespace WC_BPost_Shipping\Locale;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;

/**
 * Class WC_BPost_Shipping_Locale_Locale
 * @package WC_BPost_Shipping\Locale
 */
class WC_BPost_Shipping_Locale_Locale {

	const LANGUAGE_EN      = 'EN';
	const LANGUAGE_FR      = 'FR';
	const LANGUAGE_NL      = 'NL';
	const LANGUAGE_DEFAULT = self::LANGUAGE_EN;

	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;

	/**
	 * WC_BPost_Shipping_Locale_Locale constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 */
	public function __construct( WC_BPost_Shipping_Adapter_Woocommerce $adapter ) {

		$this->adapter = $adapter;
	}

	/**
	 * @return string
	 */
	public function get_locale() {
		return $this->adapter->get_locale();
	}

	/**
	 * @return string
	 */
	public function get_language() {
		$split_locale = explode( '_', $this->get_locale() );

		if ( count( $split_locale ) === 2 ) {
			return strtoupper( $split_locale[0] );
		}

		return self::LANGUAGE_DEFAULT;
	}
}
