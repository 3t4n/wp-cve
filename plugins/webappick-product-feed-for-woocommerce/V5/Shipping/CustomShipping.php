<?php

/**
 * Class CustomShipping
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Shipping
 */

namespace CTXFeed\V5\Shipping;

use CTXFeed\V5\Utility\Settings;

/**
 * Class representing the shipping for Custom.
 */

class CustomShipping extends Shipping {

	/**
	 * @var \CTXFeed\V5\Utility\Config $config
	 */
	private $config;

	/**
	 * Constructor for CustomShipping.
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
		$str = "";


		$allow_all_shipping         = Settings::get( 'allow_all_shipping' );
		$local_pickup_shipping      = Settings::get( 'only_local_pickup_shipping' );
		$country                    = $this->config->get_shipping_country();
		$feed_country               = $this->config->get_feed_country();


		$methods=$this->shipping;

		foreach ( $methods as $k=>$shipping ) {
			if ( 'local_pickup' == $shipping['method_id'] && $local_pickup_shipping=='yes' ) {
				unset( $methods[$k] );
			}

			if($country!=""){
				if($country=='feed'){
					$allow_all_shipping='no';
				}
				if($country=='all'){
					$allow_all_shipping='yes';
				}
			}

			if ($feed_country !== $shipping['country'] && $allow_all_shipping=='no') {
				unset($methods[$k]);
			}
		}

		$i = 1;
		if( \is_array( $methods ) ) {
			foreach ( $methods as $shipping ) {

				$currency = $this->config->get_feed_currency();
				$str      .= ( $i > 1 ) ? "<g:shipping>" . PHP_EOL : PHP_EOL;
				$str      .= "<g:country>" . $shipping['country'] . "</g:country>" . PHP_EOL;
				$str      .= ( empty( $shipping['state'] ) ) ? "" : "<g:region>" . $shipping['state'] . "</g:region>" . PHP_EOL;
				$str      .= ( empty( $shipping['service'] ) ) ? "" : "<g:service>" . $shipping['service'] . "</g:service>" . PHP_EOL;
				$str      .= "<g:price>" . $shipping['price'] . " " . $currency . "</g:price>" . PHP_EOL;
				$str      .= ( $i !== count( $methods ) ) ? "</g:shipping>" . PHP_EOL : PHP_EOL;

				$i ++;
			}
		}

		return $str;
	}
}
