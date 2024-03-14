<?php
/**
 * PayPal Card fields gateway.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * .
 */
class PeachPay_PayPal_Card_Gateway extends PeachPay_PayPal_Payment_Gateway {
	/**
	 * .
	 */
	public function __construct() {
		$this->id                = 'peachpay_paypal_card';
		$this->icons             = array(
			'full'  => array(
				'clear' => PeachPay::get_asset_url( 'img/marks/paypal/cc-quad.svg' ),
			),
			'small' => array(
				'clear' => PeachPay::get_asset_url( 'img/marks/card-small.svg' ),
			),
		);
		$this->settings_priority = 0;

		$this->title        = 'Card';
		$this->description  = 'Pay securely using your credit or debit card.';
		$this->method_title = 'Card (PeachPay)';

		$this->payment_method_family = __( 'Card', 'peachpay-for-woocommerce' );

		parent::__construct();
	}

	/**
	 * Renders payment fields.
	 */
	public function payment_method_form() {
		?>
			<div>
				<?php $this->display_fallback_currency_option_message(); ?>
				<p style="text-align: left; margin: 0;">
					<?php echo esc_html( $this->description ); ?>
				<p>
				<div id="pp-paypal-card-element" data-is-mounted="false">
					<div id="pp-paypal-card-name">
					</div>
					<div id="pp-paypal-card-number">
					</div>
					<div id="pp-paypal-card-expiry">
					</div>
					<div id="pp-paypal-card-cvv">
					</div>
				</div>
				<div id="pp-paypal-card-status" class="hide" style="display:flex;align-items:center;justify-content:center;gap:4px;">
					<i class="pp-icon-info"></i>
					<span></span>
				</div>
			</div>
		<?php
	}
}
