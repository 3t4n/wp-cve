<?php

namespace CTXFeed\V5\Compatibility;

use WAD_Discount;

class WooAdvancedDiscountWad
{

	public function wad_discount_price( $price, $product ) {

		$wad_discounts = wad_get_active_discounts( true );
		$discount_amount = 0;

		if (isset($wad_discounts["product"])) {
			//$price = $product->get_price();
			foreach ($wad_discounts["product"] as $discount_id ) {

				$wad_obj = new WAD_Discount( $discount_id );
				$is_disable = $wad_obj->settings['disable-on-product-pages'];
				if( $is_disable === "no") {

					$discount_products_list = $wad_obj->products_list->get_products(true);
					/*if ( is_array( $discount_products_list ) && count( $discount_products_list ) > 0 ) {
						if (in_array($product->get_id(), $discount_products_list)) {

							if ( isset($wad_obj->settings ) ) {
								$settings = $wad_obj->settings;
								$discount_type = $wad_obj->settings['action'];

								if ( false !== strpos( $discount_type, 'fixed' ) ) {
									$discount_amount = (float)$wad_obj->get_discount_amount( $price );
								} elseif (false !== strpos($discount_type, 'percentage')) {
									$percentage = $settings['percentage-or-fixed-amount'];
									$discount_amount = ($price * ($percentage / 100));
								}
							}

						}
					}
					else {
						if ( $wad_obj->is_applicable( $product->get_id() ) ) {
							if (isset($wad_obj->settings)) {
								$settings = $wad_obj->settings;
								$discount_type = $wad_obj->settings['action'];

								if (false !== strpos($discount_type, 'fixed')) {
									$discount_amount = (float)$wad_obj->get_discount_amount($price);
								} elseif (false !== strpos($discount_type, 'percentage')) {
									$percentage = $settings['percentage-or-fixed-amount'];
									$discount_amount = ($price * ($percentage / 100));
								}
							}
						}
					}*/
					$price = (float)$price - (float)$discount_amount;
				}

			}
//				$price = (float) $product->get_price() - (float) $discount_amount;
		}
		return $price;
	}

}
