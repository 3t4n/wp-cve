<?php

namespace CTXFeed\V5\Compatibility;

use WCCS_Pricing;

class EasyWoocommerceDiscounts
{

	public function easy_woocommerce_discounts_price( $price, $product ) {

		$pricing = new WCCS_Pricing(
			WCCS()->WCCS_Conditions_Provider->get_pricings( array( 'status' => 1 ) )
		);
		$pricing_rules = $pricing->get_all_pricing_rules();

		if( count( $pricing_rules ) > 0){
			foreach ( $pricing_rules as $key => $value ) {
				$discount_type = $pricing_rules[$key]->discount_type;
				if( isset( $pricing_rules[$key]->discount ) ){
					$discount = (float)$pricing_rules[$key]->discount;
				}else {
					$discount = "";
				}
				if( $price == "") {
					$price = (float)$product->get_price();
				}

				$product_discounts_type = $pricing_rules[$key]->items[0]['item'];
				$with_products = $pricing_rules[$key]->items[0]['products'];
				if( is_numeric( $discount ) && $discount > 0 ) {
					if( $product_discounts_type === "all_products") {
						if ( 'percentage_discount' === $discount_type ) {
							$price = $price - ( ( $price * $discount ) / 100 );
						} elseif ( 'price_discount' === $discount_type ) {
							$price = $price - $discount;
						}
					}else if( $product_discounts_type === "products_in_list" ) {

						if( is_array( $with_products ) && count($with_products) > 0){

							if( in_array( $product->get_id(), $with_products )) {
								if ( 'percentage_discount' === $discount_type ) {
									$price = $price - ( ( $price * $discount ) / 100 );
								} elseif ( 'price_discount' === $discount_type ) {
									$price = $price - $discount;
								}
							}

						}
					} else if( $product_discounts_type ==="products_not_in_list" ) {
						if( !in_array( $product->get_id(), $with_products )) {
							if ( 'percentage_discount' === $discount_type ) {
								$price = $price - ( ( $price * $discount ) / 100 );
							} elseif ( 'price_discount' === $discount_type ) {
								$price = $price - $discount;
							}
						}
					}
				}
			}
		}

		return $price;

	}

}
