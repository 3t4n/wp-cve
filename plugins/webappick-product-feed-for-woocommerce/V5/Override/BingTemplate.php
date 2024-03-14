<?php

/**
 * This class is responsible for all override bing template.
 *
 * @package    CTXFeed
 */

namespace CTXFeed\V5\Override;

/**
 * Class BingTemplate
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Override
 */
class BingTemplate {

	/**
	 * GoogleTemplate class constructor
	 */
	public function __construct() {

		add_filter( 'woo_feed_filter_product_availability_date', array(
			$this,
			'woo_feed_filter_product_availability_date_callback'
		), 10, 1 );
	}

	/**
	 * Modify product availability date.
	 *
	 * @param string $availability_date availability date.
	 *
	 * @return string
	 */
	public function woo_feed_filter_product_availability_date_callback( $availability_date ) {
		return gmdate( 'c', strtotime( $availability_date ) );
	}

}
