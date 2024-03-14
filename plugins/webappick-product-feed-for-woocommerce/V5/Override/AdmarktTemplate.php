<?php

/**
 * This class is responsible for all override admarkt template.
 *
 * @package    CTXFeed
 */

namespace CTXFeed\V5\Override;

use CTXFeed\V5\Utility\Config;

/**
 * Class AdmarktTemplate
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Override
 */
class AdmarktTemplate {

	/**
	 * AdmarktTemplate class constructor
	 */
	public function __construct() {
		add_filter( 'woo_feed_filter_product_title', array( $this, 'woo_feed_filter_product_title_callback' ), 10, 3 );
	}

	/**
	 * Modify Product Title
	 *
	 * @param string $title Product title.
	 * @param object $product Product object.
	 * @param Config $config Feed config.
	 *
	 * @return string
	 */
	public function woo_feed_filter_product_title_callback( $title, $product, $config ) { // phpcs:ignore
		if ( $config->get_feed_file_type() == 'xml' ) {
			$title = str_replace( '&', '&amp;', $title );
		}

		return $title;
	}

}
