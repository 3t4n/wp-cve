<?php

namespace WC_BPost_Shipping\Api;

use Bpost\BpostApiClient\Bpost;
use Bpost\BpostApiClient\Bpost\ProductConfiguration;
use Bpost\BpostApiClient\Bpost\ProductConfiguration\Product;
use Bpost\BpostApiClient\BpostException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostCurlException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidResponseException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidSelectionException;
use Bpost\BpostApiClient\Exception\BpostLogicException\BpostInvalidWeightException;
use WC_BPost_Shipping\Api\Exception\WC_BPost_Shipping_Api_Exception_No_Price_Found;
use WC_BPost_Shipping_Logger;

/**
 * Class WC_BPost_Shipping_Product_Configuration
 */
class WC_BPost_Shipping_Api_Product_Configuration {

	/** @var WC_BPost_Shipping_Api_Connector */
	private $connector;
	/** @var WC_BPost_Shipping_Logger */
	private $logger;

	/**
	 * WC_BPost_Shipping_Api_Product_Configuration constructor.
	 *
	 * @param WC_BPost_Shipping_Api_Connector $connector
	 * @param WC_BPost_Shipping_Logger $logger
	 */
	public function __construct( WC_BPost_Shipping_Api_Connector $connector, WC_BPost_Shipping_Logger $logger ) {
		$this->connector = $connector;
		$this->logger    = $logger;
	}

	/**
	 * @return array
	 */
	public function get_bpost_countries() {
		$countries = array();
		try {
			foreach ( $this->connector->fetchProductConfig()->getDeliveryMethods() as $delivery_method ) {
				foreach ( $delivery_method->getProducts() as $product ) {
					foreach ( $product->getPrices() as $price ) {
						$countries[ $price->getCountryIso2() ] = $price->getCountryIso2();
					}
				}
			}
		} catch ( BpostException $bpost_exception ) {
			$this->logger->log_exception( $bpost_exception );
		}

		return $countries;
	}

	/**
	 * @param $country_iso_2
	 *
	 * @return bool
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 */
	public function is_bpost_country( $country_iso_2 ) {
		foreach ( $this->connector->fetchProductConfig()->getDeliveryMethods() as $delivery_method ) {
			foreach ( $delivery_method->getProducts() as $product ) {
				foreach ( $product->getPrices() as $price ) {
					if ( $price->getCountryIso2() === $country_iso_2 ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * @param string $country_iso_2
	 * @param int $cart_weight_grams
	 *
	 * @return int Euro-cents
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @throws BpostInvalidWeightException
	 * @throws WC_BPost_Shipping_Api_Exception_No_Price_Found
	 */
	public function get_minimum_shipping_cost_from_weight( $country_iso_2, $cart_weight_grams ) {
		$prices = array();

		$this->logger->debug( 'call API (' . __METHOD__ . ')' );
		// @todo: make unit tests on that, from given XMLs
		foreach ( $this->connector->fetchProductConfig()->getDeliveryMethods() as $delivery_method ) {
			if ( $delivery_method->isVisibleAndActive() ) {
				foreach ( $delivery_method->getProducts() as $product ) {
					if ( $this->is_good_product( $product, $country_iso_2 === 'BE' ) ) {
						foreach ( $product->getPrices() as $price ) {
							if ( $price->getCountryIso2() === $country_iso_2 ) {
								$prices[] = $price->getPriceByWeight( $cart_weight_grams );
							}
						}
					}
				}
			}
		}

		if ( empty( $prices ) ) {
			throw new WC_BPost_Shipping_Api_Exception_No_Price_Found();
		}

		return min( $prices );
	}

	/**
	 * @return bool true if product found
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @throws BpostCurlException
	 */
	public function has_configured_products() {
		return count( $this->connector->fetchProductConfig()->getDeliveryMethods() ) >= 1;
	}

	/**
	 * @param ProductConfiguration\Product $product
	 * @param bool $is_national_shipping
	 *
	 * @return bool
	 */
	private function is_good_product( Product $product, $is_national_shipping ) {
		// Disable ? -> false
		if ( ! $product->isDefault() ) {
			return false;
		}

		// International product for an national shipping ? -> false
		if ( ! $product->isForNationalShipping() && $is_national_shipping ) {
			return false;
		}

		// National product for an international shipping ? -> false
		if ( $product->isForNationalShipping() && ! $is_national_shipping ) {
			return false;
		}

		// Else true
		return true;
	}

	/**
	 * //TODO not related to product config? go away
	 *
	 * @param int $weight in grams
	 *
	 * @return bool
	 */
	public function is_valid_weight( $weight ) {
		return $this->connector->isValidWeight( $weight );
	}

	/**
	 * @return int
	 */
	public function get_maximal_allowed_weight() {
		return Bpost::MAX_WEIGHT;
	}

	/**
	 * @return int
	 */
	public function get_minimal_allowed_weight() {
		return Bpost::MIN_WEIGHT;
	}

}
