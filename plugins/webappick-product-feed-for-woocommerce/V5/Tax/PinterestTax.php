<?php

namespace CTXFeed\V5\Tax;

use CTXFeed\V5\Utility\Cache;
use WC_Tax;
use CTXFeed\V5\Utility\Settings;



class PinterestTax  implements TaxInterface  {
	/**
	 * @var \WC_Product $product
	 */
	private $product;
	/**
	 * @var \CTXFeed\V5\Utility\Config $config
	 */
	private $config;

	public function __construct( $product, $config ) {
		$this->product = $product;
		$this->config  = $config;
	}

	public function get_tax() {
		$taxes = $this->get_taxes();

		return count( $taxes ) ? $taxes[0] : $taxes;
	}

	public function get_taxes() {

		$taxes = Cache::get( 'ctx_feed_tax_info' );
		if ( ! $taxes ) {
			$all_tax_rates = [];

			// Retrieve all tax classes.
			$tax_classes = WC_Tax::get_tax_classes();
			// Make sure "Standard rate" (empty class name) is present.
			if ( ! in_array( '', $tax_classes, true ) ) {
				array_unshift( $tax_classes, '' );
			}

			// For each tax class, get all rates.
			if ( ! empty( $tax_classes ) ) {
				foreach ( $tax_classes as $tax_class ) {
					$taxes = WC_Tax::get_rates_for_tax_class( $tax_class );
					if ( ! empty( $taxes ) ) {
						foreach ( $taxes as $key => $tax ) {
							$tax_class_name                                              = ( '' === $tax_class ) ? 'standard-rate' : $tax->tax_rate_class;
							$all_tax_rates [ $tax_class_name ][ $key ]['id']             = $tax->tax_rate_id;
							$all_tax_rates [ $tax_class_name ][ $key ]['country']        = $tax->tax_rate_country;
							$all_tax_rates [ $tax_class_name ][ $key ]['state']          = $tax->tax_rate_state;
							$all_tax_rates [ $tax_class_name ][ $key ]['postcode']       = isset( $tax->postcode ) ? is_array( $tax->postcode ) ? implode( ',', $tax->postcode ) : $tax->postcode : '';
							$all_tax_rates [ $tax_class_name ][ $key ]['postcode_count'] = $tax->postcode_count;
							$all_tax_rates [ $tax_class_name ][ $key ]['city']           = isset( $tax->city ) ? is_array( $tax->city ) ? implode( ',', $tax->city ) : $tax->city : '';
							$all_tax_rates [ $tax_class_name ][ $key ]['city_count ']    = $tax->city_count;
							$all_tax_rates [ $tax_class_name ][ $key ]['rate']           = number_format( $tax->tax_rate, 2 );
							$all_tax_rates [ $tax_class_name ][ $key ]['name']           = $tax->tax_rate_name;
							$all_tax_rates [ $tax_class_name ][ $key ]['shipping']       = $tax->tax_rate_shipping;
							$all_tax_rates [ $tax_class_name ][ $key ]['priority']       = $tax->tax_rate_priority;
						}
					}
				}
			}

			$taxes = ! empty( $all_tax_rates ) ? $all_tax_rates : [];
			Cache::set( 'ctx_feed_tax_info', $taxes );
		}
		return $taxes;
	}

	public function merchant_formatted_tax( $key = '' ) {
		$all_taxes = $this->get_taxes();
		$taxClass  = empty( $this->product->get_tax_class() ) ? 'standard-rate' : $this->product->get_tax_class();
		$feedType  = $this->config->get_feed_file_type();
		$str       = "";

		$allow_all_country = Settings::get( 'allow_all_shipping' );
		$tax_country            = $this->config->get_tax_country();
		$feed_country            = $this->config->get_feed_country();

		if ( $all_taxes && isset( $all_taxes[ $taxClass ] ) && ! empty( $all_taxes[ $taxClass ] ) ) {
			$taxes = array_values( $all_taxes[ $taxClass ] );

			foreach ( $taxes as $k=>$tax ) {
				if ( $tax_country != "" ) {
					if ( $tax_country == 'feed' ) {
						$allow_all_country = 'no';
					}
					if ( $tax_country == 'all' ) {
						$allow_all_country = 'yes';
					}
				}

				if ( $feed_country !== $tax['country'] && $allow_all_country == 'no') {
					unset( $taxes[ $k ] );
				}
			}

			if ( "xml" === $feedType ) {
				$i = 1;
				foreach ( $taxes as $tax ) {
					$country  = htmlentities( $tax['country'], ENT_XML1 | ENT_QUOTES, 'UTF-8' );
					$state    = htmlentities( $tax['state'], ENT_XML1 | ENT_QUOTES, 'UTF-8' );
					$rate     = htmlentities( $tax['rate'], ENT_XML1 | ENT_QUOTES, 'UTF-8' );
					$shipping = ( $tax['shipping'] ) ? "yes" : "no";

					$str .= ( $i > 1 ) ? PHP_EOL . "<g:tax>" : PHP_EOL;
					$str .= PHP_EOL . "<g:country>$country</g:country>";
					$str .= PHP_EOL . "<g:region>$state</g:region>";
					$str .= PHP_EOL . "<g:rate>$rate</g:rate>";
					$str .= PHP_EOL . "<g:tax_ship>$shipping</g:tax_ship>";
					$str .= ( $i !== count( $taxes ) ) ? PHP_EOL . "</g:tax>" : PHP_EOL;
					$i ++;
				}
			} else if ( $key !== '' && isset( $taxes[ $key ] ) ) {
				$shipping = ( $taxes[ $key ]['shipping'] ) ? "yes" : "no";
				$str      = $taxes[ $key ]['country'] . ":" . $taxes[ $key ]['state'] . ":" . $taxes[ $key ]['rate'] . ":" . $shipping;
			}
		}

		return $str;
	}
}
