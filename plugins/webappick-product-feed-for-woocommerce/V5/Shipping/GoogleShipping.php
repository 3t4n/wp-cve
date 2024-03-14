<?php

/**
 * Class GoogleShipping
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Shipping
 */

namespace CTXFeed\V5\Shipping;

use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Utility\Settings;

/**
 * Class representing the shipping for Google.
 */

class GoogleShipping extends Shipping {

	/**
	 * @var \CTXFeed\V5\Utility\Config $config
	 */
	private $config;

	/**
	 * @var string[] Shipping attributes
	 */
	public static $shipping_attrs = [
		'location_id',
		'location_group_name',
		'min_handling_time',
		'max_handling_time',
		'min_transit_time',
		'max_transit_time'
	];

	/**
	 * Constructor for GoogleShipping.
	 *
	 * @param mixed $config Configuration settings.
	 */
	public function __construct( $product, $config ) {
		parent::__construct( $product, $config );
		$this->config = $config;
	}

	/**
	 * @throws \Exception
	 */
	public function get_shipping_info() {
		$this->get_shipping_zones( $this->config->get_feed_file_type() );
		return $this->shipping;
	}

	/**
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function get_shipping( $key = '' ) {

		$this->get_shipping_zones( $this->config->get_feed_file_type() );

		if ( 'xml' === $this->config->get_feed_file_type() ) {
			return $this->get_xml();
		}

		return $this->get_csv( $key );
	}

	/**
	 * @return string
	 */
	private function get_xml() {
		$str = "";

		$allow_all_shipping         = Settings::get( 'allow_all_shipping' );
		$local_pickup_shipping      = Settings::get( 'only_local_pickup_shipping' );
		$country                    = $this->config->get_shipping_country();
		$feed_country               = $this->config->get_feed_country();
		$currency                   = $this->config->get_feed_currency();

		$methods = $this->shipping;

		if( \is_array( $methods ) || \is_object( $methods ) ){
			foreach ( $methods as $key=>$shipping ) {
				if ( 'local_pickup' == $shipping['method_id'] && $local_pickup_shipping=='yes' ) {
					unset( $methods[$key] );
				}

				if( $country!="" ){
					if($country=='feed'){
						$allow_all_shipping='no';
					}
					if( $country=='all' ){
						$allow_all_shipping='yes';
					}
				}

				if ( $feed_country !== $shipping['country'] && $allow_all_shipping=='no' ) {
					unset( $methods[$key] );
				}
			}
		}

		$i = 1;
		if( \is_array( $methods ) ){
			foreach ( $methods as $shipping ) {
				$str .= ( $i > 1 ) ? "<g:shipping>" . PHP_EOL : PHP_EOL;
				$str .= "<g:country>" . $shipping['country'] . "</g:country>" . PHP_EOL;
				$str .= ( empty( $shipping['state'] ) ) ? "" : "<g:region>" . $shipping['state'] . "</g:region>" . PHP_EOL;
				$str .= ( empty( $shipping['service'] ) ) ? "" : "<g:service>" . $shipping['service'] . "</g:service>" . PHP_EOL;
				$str .= "<g:price>" . $shipping['price'] . " " . $currency . "</g:price>" . PHP_EOL;

				foreach ( self::$shipping_attrs as $shipping_attr ) {
					$key = \array_search( $shipping_attr, $this->config->mattributes, true );
					if ( $key ) {
						$attributeValue = ( $this->config->type[ $key ] === 'pattern' ) ? $this->config->default[ $key ] : $this->config->attributes[ $key ];
						$value          = ProductHelper::get_attribute_value_by_type( $attributeValue, $this->product, $this->config, $shipping_attr );
						$str            .= "<g:$shipping_attr>$value</g:$shipping_attr>" . PHP_EOL;
					}
				}

				$str .= ( $i !== \count( $methods ) ) ? "</g:shipping>" . PHP_EOL : PHP_EOL;
				$i ++;
			}
		}

		return $str;
	}

	/**
	 * @param $key
	 *
	 * @return string
	 */
	private function get_csv( $key ) {
		$allow_all_shipping         = Settings::get( 'allow_all_shipping' );
		$local_pickup_shipping      = Settings::get( 'only_local_pickup_shipping' );
		$country                    = $this->config->get_shipping_country();
		$feed_country               = $this->config->get_feed_country();
		$currency                   = $this->config->get_feed_currency();

		$methods = $this->shipping;

		foreach ( $methods as $k=>$shipping ) {
			if ( 'local_pickup' == $shipping['method_id'] && $local_pickup_shipping == 'yes' ) {
				unset( $methods[ $k ] );
			}

			if ( $country != "" ) {
				if ( $country == 'feed' ) {
					$allow_all_shipping = 'no';
				}
				if ( $country == 'all' ) {
					$allow_all_shipping = 'yes';
				}
			}

			if ( $feed_country !== $shipping['country'] && $allow_all_shipping == 'no' ) {
				unset( $methods[ $k ] );
			}
		}
		$shipping_info = array();
		foreach ( $methods as $k=>$shipping ) {
			$shipping_info = [
				isset( $methods[ $key ]['country'] ) ? $methods[ $key ]['country'] : "",
				isset( $methods[ $key ]['state'] ) ? $methods[ $key ]['state'] : "",
				isset( $methods[ $key ]['service'] ) ? $methods[ $key ]['service'] : "",
				isset( $methods[ $key ]['price'] ) ? $methods[ $key ]['price'] . " " . $currency : "",
			];
		}
		return \implode( ":", $shipping_info );
	}

	/**
	 * @param $shipping_attr
	 *
	 * @return mixed|string|null
	 */
	public function get_value( $shipping_attr ) {
		$m_key = \array_search( $shipping_attr, $this->config->mattributes, true );
		if ( $m_key ) {
			$attribute_value = ( $this->config->type[ $m_key ] === 'pattern' ) ? $this->config->default[ $m_key ] : $this->config->attributes[ $m_key ];

			return ProductHelper::get_attribute_value_by_type( $attribute_value, $this->product, $this->config, $shipping_attr );
		}

		return "";
	}
}
