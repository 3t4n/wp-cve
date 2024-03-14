<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Active_InfuseWooPro {
	public function __construct() {
		/* checkout page */
		add_action( 'wfacp_product_switcher_price_data', [ $this, 'price_data' ], 1, 2 );
		$this->replace_hooks();

	}

	/**
	 * @param $price
	 * @param $pro WC_Product
	 *
	 * @return mixed
	 */
	public function price_data( $price, $pro ) {
		$infusionsoft_sub = $pro->get_meta( 'infusionsoft_sub' );
		if ( ! empty( $infusionsoft_sub ) && ( $infusionsoft_sub = absint( $infusionsoft_sub ) ) > 0 ) {

			$infusionsoft_trial       = $pro->get_meta( 'infusionsoft_trial' );
			$infusionsoft_sign_up_fee = $pro->get_meta( 'infusionsoft_sign_up_fee' );

			if ( $infusionsoft_trial > 0 ) {
				$price['regular_org'] = $pro->get_regular_price();
				if ( $infusionsoft_sign_up_fee > 0 ) {
					$price['price'] = $infusionsoft_sign_up_fee;
				} else {
					$price['price'] = 0;
				}
			}
		}

		return $price;
	}

	public function replace_hooks() {
		if ( function_exists( 'ia_woocommerce_before_order_total' ) ) {
			remove_action( 'woocommerce_review_order_before_payment', 'ia_woocommerce_before_order_total', 10 );
			add_action( 'wfacp_after_order_summary', 'ia_woocommerce_before_order_total', 10, 2 );

		}
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Active_InfuseWooPro(), 'infusewoopro' );
