<?php

/**
 *
 */
class WC_QuickPay_Subscriptions_Change_Payment_Method extends WC_QuickPay_Module {

	public function hooks() {
		add_filter( 'woocommerce_gateway_description', [ $this, 'maybe_apply_description_notice' ], 10, 2 );
	}

	/**
	 * Applies a text description in the payment fields area on checkout when changing payment gateway.
	 *
	 * @param string|null $description
	 * @param string $gateway_id
	 *
	 * @return string|null
	 */
	public function maybe_apply_description_notice( ?string $description, string $gateway_id ): ?string {
		if ( $gateway_id === WC_QP()->id && is_checkout() && wc_string_to_bool( WC_QP()->s( 'subscription_update_card_on_manual_renewal_payment' ) ) && WC_QuickPay_Subscription::cart_contains_renewal() ) {
			$description .= __( '<p><strong>NB:</strong> This will pay your order and update the credit card on your subscription for future payments.</p>', 'woo-quickpay' );
		}

		return $description;
	}
}
