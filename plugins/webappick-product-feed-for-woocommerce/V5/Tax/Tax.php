<?php

namespace CTXFeed\V5\Tax;

use CTXFeed\V5\Utility\Settings;


/**
 * Class Tax
 *
 * @package    CTXFeed\V5\Tax
 * @subpackage CTXFeed\V5\Tax
 */
class Tax {
	private $tax;

	public function __construct( TaxInterface $tax ) {
		$this->tax = $tax;
	}

	public function get_tax() {
		return $this->tax->get_tax();
	}

	public function get_taxes() {
		return $this->tax->get_taxes();
	}

	public function merchant_formatted_tax($key) {
		return $this->tax->merchant_formatted_tax($key);
	}


	/**
	 * @param $taxes
	 * @param $config
	 *
	 * @return mixed
	 */
	public static function get_tax_setting($all_taxes, $config){
		$allow_all_country = Settings::get( 'allow_all_shipping' );
		$tax_country            = $config->get_tax_country();
		$feed_country            = $config->get_feed_country();
		foreach($all_taxes as $key=>$taxes){
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
		}

		return $taxes;
	}

}
