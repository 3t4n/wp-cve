<?php

namespace CTXFeed\V5\Compatibility;

class PricingDealsForWoocommerceVT
{

	public function vt_pricing_deals_discount_price( $price, $product ) {
		if($product->get_type() == 'variation' ){
			$price = $product->get_regular_price();
		}
		if ( class_exists( 'VTPRD_Controller' ) ) {
			global $vtprd_rules_set;
			$vtprd_rules_set = maybe_unserialize(get_option( 'vtprd_rules_set' ));
			if ( ! empty( $vtprd_rules_set ) && is_array( $vtprd_rules_set ) ) {
				foreach ( $vtprd_rules_set as $key =>$vtprd_rule_set ) {
					$status = $vtprd_rule_set->rule_on_off_sw_select;
					if ( 'on' === $status || 'onForever' === $status ) {
						$discount_type = $vtprd_rule_set->rule_deal_info[0]['discount_amt_type'];
						$discount      = (float)$vtprd_rule_set->rule_deal_info[0]['discount_amt_count'];
						if ( 'currency' === $discount_type || 'fixedPrice' === $discount_type ) {
							$price = (float)$price - $discount;
						} elseif ( 'percent' === $discount_type ) {
							$price = (float)$price - ( ( (float)$price * $discount ) / 100 );
						}

					}

				}
			}
		}

		return $price;

	}

}
