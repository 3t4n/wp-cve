<?php

namespace CTXFeed\V5\Tax;


/**
 * Class TaxFactory
 *
 * @package    CTXFeed\V5\Tax
 * @subpackage CTXFeed\V5\Tax
 */
class TaxFactory {
	public static function get( $product, $config ) {
		$template = $config->get_feed_template();
		
		$class = "\CTXFeed\V5\Tax\\" . ucfirst( $template ) . "Tax";
		
		if ( class_exists( $class ) ) {
			return new Tax( new $class( $product, $config ) );
		}
		
		return new Tax( new CustomTax( $product, $config ) );
	}
}