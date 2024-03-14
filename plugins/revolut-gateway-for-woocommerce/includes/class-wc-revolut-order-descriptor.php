<?php
/**
 * Revolut order Descriptor.
 *
 * @package    WooCommerce
 * @category   Payment Gateways
 * @author     Revolut
 * @since      2.0.0
 */

/**
 * WC_Revolut_Order_Descriptor class.
 */
class WC_Revolut_Order_Descriptor {

	use WC_Gateway_Revolut_Helper_Trait;

	/**
	 * Order total amount.
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * Order currency.
	 *
	 * @var string
	 */
	public $currency;

	/**
	 * Revolut customer id.
	 *
	 * @var string
	 */
	public $revolut_customer_id;

	/**
	 * OrderDescriptor constructor.
	 *
	 * @param float  $amount payment amount.
	 * @param string $currency currency.
	 * @param string $revolut_customer_id Revolut customer id.
	 */
	public function __construct( $amount, $currency, $revolut_customer_id ) {
		if ( (bool) get_query_var( 'pay_for_order' ) && ! empty( get_query_var( 'key' ) ) ) {
			global $wp;
			$order  = wc_get_order( wc_clean( $wp->query_vars['order-pay'] ) );
			$amount = $order->get_total();
		}

		if ( is_add_payment_method_page() || $this->is_subs_change_payment() ) {
			$amount = 0;
		}

		$this->amount              = $this->get_revolut_order_total( $amount, $currency );
		$this->currency            = $currency;
		$this->revolut_customer_id = $revolut_customer_id;
	}
}
