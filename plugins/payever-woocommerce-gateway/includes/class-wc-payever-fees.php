<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Fees' ) ) {
	return;
}

class WC_Payever_Fees {

	use WC_Payever_WP_Wrapper_Trait;

	/** @var object */
	public $current_gateway;

	/** @var float */
	public $current_extra_charge_amount;

	/**
	 * WC_Payever_Fees constructor.
	 */
	public function __construct() {
		$this->current_gateway             = null;
		$this->current_extra_charge_amount = 0;

		//Hooks & Filters
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'calculate_order_totals' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_frontend' ) );
	}

	public function enqueue_scripts_frontend() {
		$min = ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ? '.min' : '';

		if ( ! $this->get_wp_wrapper()->is_checkout() ) {
			return false;
		}

		wp_enqueue_script(
			'wc-pf-checkout',
			WC_PAYEVER_PLUGIN_URL . '/assets/js/checkout' . $min . '.js',
			array( 'jquery' ),
			WC_PAYEVER_PLUGIN_VERSION,
			true
		);

		return true;
	}

	/**
	 * Add extra charge to cart totals
	 *
	 * @param double $totals
	 * @return double
	 */
	public function calculate_order_totals( $cart ) {
		if ( ! defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
			return false;
		}

		$current_gateway = WC()->session->chosen_payment_method;
		if ( empty( $current_gateway ) ) {
			return false;
		}

		$subtotal       = $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '3.2.0', '>=' ) ? $cart->get_subtotal() : $cart->subtotal;
		$shipping_total = $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '3.2.0', '>=' ) ? $cart->get_shipping_total() : $cart->shipping_total;
		$shipping_tax   = $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '3.2.0', '>=' ) ? $cart->get_shipping_tax() : $cart->shipping_taxes[1];
		$shipping       = $shipping_total + $shipping_tax;
		$total          = $subtotal + $shipping - $cart->discount_cart - $cart->discount_cart_tax;

		$this->current_gateway = $current_gateway; //Note: this is an object

		//Add charges to cart totals
		$extra_charge_name = __( 'payever Fee', 'payever-woocommerce-gateway' );
		$payment_data       = $this->get_wp_wrapper()->get_option( 'woocommerce_' . $current_gateway . '_settings' );
		$extra_charge_amount = 0;
		if ( WC_Payever_Helper::instance()->is_payever_method( $current_gateway ) && 'no' === $payment_data['accept_fee'] ) {
			$payment_data         = $this->get_wp_wrapper()->get_option( 'woocommerce_' . $current_gateway . '_settings' );
			$fee                 = $payment_data['fee'];
			$variable_fee        = $payment_data['variable_fee'];
			$extra_charge_amount = $total * $variable_fee / 100 + $fee;
		}

		$taxable = false;

		$extra_charge_amount = $this->get_wp_wrapper()->apply_filters(
			'woocommerce_wc_pf_' . $current_gateway . '_amount',
			$extra_charge_amount,
			$subtotal,
			$current_gateway
		);
		$do_apply            = 0 !== $extra_charge_amount;
		$do_apply            = $this->get_wp_wrapper()->apply_filters(
			'woocommerce_wc_pf_apply',
			$do_apply,
			$extra_charge_amount,
			$subtotal,
			$current_gateway
		);
		$do_apply            = $this->get_wp_wrapper()->apply_filters(
			'woocommerce_wc_pf_apply_for_' . $current_gateway,
			$do_apply,
			$extra_charge_amount,
			$subtotal,
			$current_gateway
		);

		if ( $do_apply ) {
			$extra_charge_amount = $this->apply_extra_charge(
				$cart,
				$extra_charge_amount,
				$extra_charge_name,
				$taxable
			);
		}

		$this->current_extra_charge_amount = $extra_charge_amount;

		return true;
	}

	private function apply_extra_charge( $cart, $extra_charge_amount, $extra_charge_name, $taxable ) {
		$already_exists = false;
		$fees           = $cart->get_fees();
		$fee_count      = count( $fees );
		for ( $i = 0; $i < $fee_count; $i++ ) {
			if ( 'payment-method-fee' === $fees[ $i ]->id ) {
				$already_exists = true;
				$fee_id         = $i;
			}
		}
		//rounding down eg 4.165 to 4.16
		$extra_charge_amount = round( $extra_charge_amount, 2, PHP_ROUND_HALF_DOWN );
		if ( ! $already_exists ) {
			$cart->add_fee( $extra_charge_name, $extra_charge_amount, $taxable );

			return $extra_charge_amount;
		}
		$fees[ $fee_id ]->amount = $extra_charge_amount;

		return $extra_charge_amount;
	}
}
