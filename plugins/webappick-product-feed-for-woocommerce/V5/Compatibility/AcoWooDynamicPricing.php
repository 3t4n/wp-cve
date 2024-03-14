<?php

namespace CTXFeed\V5\Compatibility;

use AWDP_Discount;

class AcoWooDynamicPricing
{
	public function aco_dynamic_pricing( $price, $product ) {
		/**
		 * PLUGIN: Dynamic Pricing With Discount Rules for WooCommerce
		 * URL: https://wordpress.org/plugins/aco-woo-dynamic-pricing/
		 *
		 * This plugin does not apply discount on product page.
		 *
		 * Don't apply discount manually.
		 */
		if (class_exists('AWDP_Discount')) {

			$price = AWDP_Discount::instance()->wdpWCPAPrice($product->get_price(), $product);
			if( isset( $price['price'] ) ){
				if( $price['price'] == '' ) {
					$sale_price = $price['originalPrice'];
				} else {
					$sale_price = $price['price'];
				}
				$price = $sale_price;
			}
		}
		return $price;
	}

}
