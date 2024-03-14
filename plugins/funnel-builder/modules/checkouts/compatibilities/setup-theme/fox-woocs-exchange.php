<?php
/**
 * FOX – Currency Switcher Professional for WooCommerce
 * Realmag777
 */
if ( ! class_exists( 'WFACP_With_Fox_WOOCS' ) ) {

	#[AllowDynamicProperties] 

  class WFACP_With_Fox_WOOCS {

		public function __construct() {
			add_filter( 'wfacp_product_switcher_price_data', [ $this, 'wfacp_product_switcher_price_data' ], 15, 2 );
		}

		/**
		 * @hooked into `wcct_deal_amount_fixed_amount_{$type}` | `wcct_regular_price_event_value_fixed`
		 * Modifies the amount for the fixed discount given by the admin in the currency selected.
		 *
		 * @param integer|float $price
		 *
		 * @return float
		 */
		public function alter_fixed_amount( $price, $currency = null ) {
			return $GLOBALS['WOOCS']->woocs_exchange_value( $price );
		}

		/**
		 * @param $price_data
		 * @param $pro WC_Product;
		 *
		 * @return mixed
		 */
		public function wfacp_product_switcher_price_data( $price_data, $pro ) {

			global $WOOCS;
			$currency                  = $WOOCS->current_currency;
			$regular_price             = $pro->get_meta( "_woocs_regular_price_" . $currency );
			$sale_price                = $pro->get_meta( "_woocs_sale_price_" . $currency );
			$price_data['regular_org'] = $this->alter_fixed_amount( $pro->get_regular_price( 'edit' ) );
			$price_data['price']       = $this->alter_fixed_amount( $pro->get_price( 'edit' ) );
			if ( ! empty( $regular_price ) && $regular_price >= 0 ) {//Always update variable if product level Regular price is greate or equal to 0
				$price_data['regular_org'] = $regular_price;
			}
			if ( ! empty( $sale_price ) && $sale_price >= 0 ) { //Always update variable if product level Sale price is greate or equal to 0
				$price_data['price'] = $sale_price;
			}

			return $price_data;
		}
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_With_Fox_WOOCS(), 'fox_woocs' );