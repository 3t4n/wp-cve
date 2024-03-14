<?php
/**
 * Express checkout page template.
 *
 * @package PeachPay
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$checkout    = WC_Checkout::instance();
$brand_color = peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> style="--peachpay-theme-color: <?php echo esc_attr( $brand_color ); ?>;--peachpay-theme-color-opaque: <?php echo esc_attr( $brand_color . '80' ); ?>;--peachpay-theme-color-light: <?php echo esc_attr( $brand_color . '20' ); ?>;">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
	<meta name="robots" content="noindex">
	<meta name="googlebot" content="noindex">
	<?php
	add_action(
		'wp_enqueue_scripts',
		function () {
			// Dequeue all scripts and styles.
			wp_scripts()->dequeue( wp_scripts()->queue );
			wp_styles()->dequeue( wp_styles()->queue );

			// Enqueue checkout styles
			PeachPay::enqueue_style( 'pp-icons', 'public/icon.css' );
			PeachPay::enqueue_style( 'pp-checkout', 'public/dist/express-checkout-css.bundle.css' );

			PeachPay::enqueue_script( 'pp-checkout-core', 'public/dist/express-checkout-js.bundle.js' );
			PeachPay::register_script_data( 'pp-checkout-core', 'checkout_data', pp_checkout_page_data() );

			PeachPay::enqueue_script( 'pp-sentry', 'https://browser.sentry-cdn.com/7.59.2/bundle.min.js', array(), false, true );

			/**
			 * Allows enqueuing of custom Express Checkout scripts and styles.
			 */
			do_action( 'pp_checkout_enqueue_scripts' );
		},
		PHP_INT_MAX
	);

	wp_head();
	?>
</head>

<body class="pp-container pp-container-new <?php echo esc_attr( peachpay_is_test_mode() ? 'pp-test-mode' : '' ); ?>">
	<div id="pp-modal-content" class="pp-content pp-disabled-processing pp-disabled-loading">
		<a href="#" class="pp-close pp-close-x pp-icon-times"></a>
		<div class="pp-loading-line-bar-container"></div>
		<?php if ( peachpay_is_test_mode() ) : ?>
		<div class="test-mode-banner">
			<span><?php esc_html_e( 'Test mode: customers cannot see PeachPay', 'peachpay-for-woocommerce' ); ?></span>
		</div>
		<?php endif; ?>
		<div id="pp-checkout" class="w-100 flex-container">
			<div class="flex-left pp-modal-col">
				<div class="pp-top-section mobile">
					<div class="pp-top-corner">
						<div class="pp-merchant-logo-container"></div>
					</div>
					<div class="pp-top-corner mobile" style="margin-left: auto">
						<div class="flex">
							<div class="pp-apply-code-sm coupon-code-option hide" tabindex="0">
								<span><?php esc_html_e( '+ COUPON', 'peachpay-for-woocommerce' ); ?></span>
							</div>
							<form class="wc-coupon-code coupon-code hide">
								<div class="flex w-100 h-100">
									<input type="text" id="coupon-code-new" name="coupon_code" placeholder=" " class="wc-coupon-code-input text-input" autocomplete="off" required>
									<label for="coupon-code-new" class="pp-form-label mobile"><?php esc_html_e( 'Coupon code', 'peachpay-for-woocommerce' ); ?></label>
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="wc-coupon-spinner coupon-spinner hide">
									<input type="submit" value="<?php esc_html_e( 'apply', 'peachpay-for-woocommerce' ); ?>" class="coupon-code-apply-text">
								</div>
							</form>
							<span class="wc-invalid-coupon invalid-coupon hide block"><?php esc_html_e( 'You entered an invalid coupon code', 'peachpay-for-woocommerce' ); ?></span>
						</div>
						<div class="flex">
							<div class="pp-apply-code-sm gift-card-option hide">
								<span><?php esc_html_e( '+ REDEEM GIFT CARD/STORE CREDIT', 'peachpay-for-woocommerce' ); ?></span>
							</div>
							<form class="pw-wc-gift-card coupon-code hide">
								<div class="flex w-100 h-100">
									<input type="text" id="card-number-new" name="card_number" placeholder=" " class="wc-gift-card-input text-input" autocomplete="off" required>
									<label for="card-number-new" class="pp-form-label"><?php esc_html_e( 'Gift card number', 'peachpay-for-woocommerce' ); ?></label>
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="wc-coupon-spinner coupon-spinner hide">
									<input type="submit" value="<?php esc_html_e( 'apply', 'peachpay-for-woocommerce' ); ?>" class="gift-card-apply-text">
								</div>
							</form>
						</div>
						<div class="pp-order-summary-mobile">
							<div id="pp-dropdown-new" role="button" class="pp-dropdown" aria-expanded="false" tabindex="0">
								<i class="pp-icon-cart"></i>
								<span id="pp-total-cost" class="flex pp-summary-total pp-ai-center pp-gap-6 pp-recalculate-blur">
									<!-- New Customer Cart summary Total-->
								</span>
								<img src="<?php peachpay_version_url( 'public/img/chevron-down-solid.svg' ); ?>" id="dropdown-down-new" class="dropdown-btn-new" />
							</div>
						</div>
					</div>
				</div>
				<div class="pp-merchant-logo-container pp-hide-for-mobile hide"></div>
				<div class="center">
					<div id="checkout-status">
						<button type="button "data-goto-page="billing" id="pp-billing-tab" class="pp-checkout-status-col current">
							<div class="pp-checkout-status-text"><span id="pp-billing-tab-text" class="hide">✓ </span><span><?php esc_html_e( 'BILLING', 'peachpay-for-woocommerce' ); ?></span></div>
							<div class="pp-checkout-status-bar">
								<div class="pp-checkout-status-bar-fill"></div>
							</div>
						</button>
						<button type="button" data-goto-page="shipping" id="pp-shipping-tab" class="pp-checkout-status-col no-fill">
							<div class="pp-checkout-status-text"><span id="pp-shipping-tab-text" class="hide">✓ </span><span class="hide-for-virtual-carts"><?php esc_html_e( 'SHIPPING', 'peachpay-for-woocommerce' ); ?></span><span class="show-for-virtual-carts"><?php esc_html_e( 'additional', 'peachpay-for-woocommerce' ); ?></span></div>
							<div class="pp-checkout-status-bar">
								<div class="pp-checkout-status-bar-fill"></div>
							</div>
						</button>
						<button type="button" data-goto-page="payment" id="pp-payment-tab" class="pp-checkout-status-col no-fill">
							<div class="pp-checkout-status-text"><span><?php esc_html_e( 'PAYMENT', 'peachpay-for-woocommerce' ); ?></span></div>
							<div class="pp-checkout-status-bar">
								<div class="pp-checkout-status-bar-fill"></div>
							</div>
						</button>
					</div>
				</div>
				<div style="min-height: 24px; margin: none; padding: none;" id="pp-notice-container-mobile" class="flex-col pp-notice-container hide">
					<!-- New customer notice mobile-->
				</div>
				<section id="pp-billing-page" class="flex col pp-section-mt h-100">
					<form id="pp-billing-form" class="pp-form">
						<?php

						$billing_fields = $checkout->get_checkout_fields( 'billing' );
						foreach ( $billing_fields as $key => $field ) {
							peachpay_form_field( $key, $field, $checkout->get_value( $key ) );
						}
						?>
					</form>
					<div class="pp-spacer pp-rp-spacer"></div>
					<div id="pp-related-products-section" class="hide">
						<div class="related-products-title hide pp-title bold"></div>
						<div id="pp-related-products-container">
							<div id="pp-products-list-related">
								<div id="pp-products-list-related-main">
								</div>
							</div>
							<div class="pp-rp-fade-left pp-rp-fade-left-hide"></div>
							<div class="pp-rp-fade-right"></div>
						</div>
					</div>
					<div class="center pp-btn-desktop">
						<div class="pp-continue-order-error"></div>
						<div id="pp-stripe-payment-request-checkout-wrapper" style="width: 100%; display: flex; justify-content: center;">
							<div id="pp-stripe-payment-request-checkout-btn" class="hide" style="width: 15rem;"></div>
						</div>
						<button type="submit" form="pp-billing-form" id="pp-continue-to-shipping" class="btn">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" id="continue-spinner-shipping" class="spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"><span><?php esc_html_e( 'Continue', 'peachpay-for-woocommerce' ); ?></span></span>
						</button>
						<div class="flex-center">
							<div style="width: fit-content;">
								<button type="button" id="pp-exit" class="pp-exit pp-back-to pp-close"><span class="exit-back-btn"><?php esc_html_e( 'Exit checkout', 'peachpay-for-woocommerce' ); ?></span></button>
							</div>
						</div>
					</div>
				</section>
				<section id="pp-shipping-page" class="flex col hide pp-section-mt h-100">
					<form id="pp-shipping-form" class="pp-form hide-for-virtual-carts">

						<label for="pp-shipping-edit" class="flex row" style="flex-grow: 1;">
							<span class="hide-for-virtual-carts pp-address-header bold" style="padding-top: 0.5rem;"><?php esc_html_e( 'Ship to', 'peachpay-for-woocommerce' ); ?></span>
							<span class="show-for-virtual-carts pp-address-header bold"  style="padding-top: 0.5rem;"><?php esc_html_e( 'Bill to', 'peachpay-for-woocommerce' ); ?></span>
						</label>

						<div style="flex-basis: 0;">
							<input id="pp-shipping-edit" type="checkbox" name="ship_to_different_address" value="1" style="opacity: 0;position: absolute;">
							<label data-testid="edit-shipping" for="pp-shipping-edit" class="pp-edit-checkbox color-change-text" style="float:right;" >
								<i class="pp-icon-edit"></i>
								<?php esc_html_e( 'Edit', 'peachpay-for-woocommerce' ); ?>
							</label>
						</div>

						<span id="long-address" class="flex w-100" style="justify-content: center; background-color: #f4f4f4; padding: 16px;text-align:center;"></span>

						<fieldset id="pp-shipping-fieldset" class="pp-form w-100 hide" disabled>
							<?php
							$shipping_fields = $checkout->get_checkout_fields( 'shipping' );
							foreach ( $shipping_fields as $key => $field ) {
								peachpay_form_field( $key, $field, $checkout->get_value( $key ) );
							}
							?>
						</fieldset>
					</form>
					<div class="hide-for-virtual-carts">
						<div id="pp-shipping-options" ></div>
						<div id="pp-shipping-address-error" class="hide pp-error"></div>
					</div>
					<?php
					$additional_fields = $checkout->get_checkout_fields( 'order' );
					if ( count( $additional_fields ) > 0 ) {
						?>
						<div class="pp-title"><?php esc_html_e( 'Additional information', 'peachpay-for-woocommerce' ); ?></div>
						<form id="pp-additional-form" class="flex col pp-form" style="margin-bottom: 1rem;">
							<?php
							foreach ( $additional_fields as $key => $field ) {
								peachpay_form_field( $key, $field, $checkout->get_value( $key ) );
							}
							?>
						</form>
					<?php } ?>
					<div id="pp-shipping-page-message" class="hide pp-error">
						<?php esc_html_e( 'No shipping or additional information to collect. Please press continue.', 'peachpay-for-woocommerce' ); ?>
					</div>

					<div class="center pp-btn-desktop">
						<div class="pp-continue-order-error"></div>
						<button id="pp-continue-to-payment" form="pp-shipping-form" class="btn">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" id="continue-spinner-payment" class="spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"><span><?php esc_html_e( 'Continue', 'peachpay-for-woocommerce' ); ?></span></span>
						</button>
						<div class="flex-center">
							<div style="width: fit-content;">
								<button type="button" data-goto-page="billing" class="pp-back-to pp-back-to-info"><span class="exit-back-btn"><?php esc_html_e( 'Back', 'peachpay-for-woocommerce' ); ?></span></button>
							</div>
						</div>
					</div>
				</section>
				<section id="pp-payment-page" class="flex col hide pp-section-mt h-100">
					<div id="invalid-order-message" class="hide center mb-1 p-2 lightgrey rounded"></div>
					<div id="pp-pms-new-container" class="flex col pp-section-mb pp-hide-on-free-order">
						<div class="pp-title"><?php esc_html_e( 'Payment', 'peachpay-for-woocommerce' ); ?></div>
						<div id="pp-pms-new" class="pp-pms" data-payment-selector="new">
							<div class="header">

							</div>
							<div class="body">

							</div>
						</div>
					</div>
					<div id="pp-store-account-info"></div>
					<div class="pp-spacer"></div>
					<div class="center pp-btn-desktop">
						<div id="pay-button" class="pay-button-container w-100">
							<!-- Order Support message (desktop) -->
							<div id="pp-custom-order-message-hover" class="w-100 center hide">
								<div class="pp-custom-order-message-label inline-block rounded pp-hover">
									<div class="pp-order-message-button" tabindex="0">&#9432; Order notice</div>
									<div class="pp-hover-content">
										<div class="pp-custom-order-message">
											<p class="muted inline-block">
											</p>
										</div>
										<div class="pp-order-message-arrow-down center"></div>
									</div>
								</div>
							</div>
							<!-- New Free order button-->
							<div class="free-btn-container hide">
								<div class="pp-btn-spinner-container hide">
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
								</div>
								<button class="btn free-btn">
									<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="free-btn-spinner spinner hide">
									<i class="pp-icon-lock"></i>
									<span class="button-text"><?php esc_html_e( 'Place order', 'peachpay-for-woocommerce' ); ?></span>
								</button>
							</div>
							<!-- New Customer Peachpay Integrated button -->
							<div class="peachpay-integrated-btn-container hide">
								<div class="peachpay-integrated-spinner-container hide">
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
								</div>
								<button class="hide pay-btn btn peachpay-integrated-btn">
									<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="peachpay-integrated-btn-spinner spinner hide">
									<i class="pp-icon-lock"></i>
									<span class="button-text"></span>
								</button>
							</div>
							<!-- New Customer Stripe Pay button-->
							<div class="stripe-btn-container hide">
								<div class="stripe-spinner-container hide">
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
								</div>
								<button class="hide pay-btn btn stripe-btn">
									<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="stripe-btn-spinner spinner hide">
									<i class="pp-icon-lock"></i>
									<span class="button-text"></span>
								</button>
							</div>
							<!-- New Customer Square Pay button-->
							<div class="square-btn-container hide">
								<div class="square-spinner-container hide">
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
								</div>
								<button class="hide pay-btn btn square-btn">
									<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="square-btn-spinner spinner hide">
									<i class="pp-icon-lock"></i>
									<span class="button-text"></span>
								</button>
								<div class="square-custom-btn-container hide "></div>
							</div>
							<!-- New Customer Authorize.net Pay button -->
							<div class="authnet-btn-container hide">
								<div class="authnet-spinner-container hide">
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
								</div>
								<button class="hide pay-btn btn authnet-btn">
									<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="authnet-btn-spinner spinner hide">
									<i class="pp-icon-lock"></i>
									<span class="button-text"></span>
								</button>
							</div>
							<!-- New Customer Paypal non hosted Pay button -->
							<div class="paypal-btn-container hide">
								<div class="paypal-spinner-container hide">
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
								</div>
								<button class="hide pay-btn btn paypal-btn">
									<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="paypal-btn-spinner spinner hide">
									<i class="pp-icon-lock"></i>
									<span class="button-text"></span>
								</button>
							</div>
							<!-- New Customer Paypal Hosted Pay buttons -->
							<div>
								<div class="paypal-pay-btn-container hide">
								</div>
								<div class="paypal-pay-spinner-container hide">
									<img class="paypal-pay-spinner spinner" src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>">
								</div>
							</div>
							<!-- New Customer GoDaddy Poynt Pay button-->
							<div class="poynt-btn-container hide">
								<div class="poynt-spinner-container hide">
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
								</div>
								<button class="hide pay-btn btn poynt-btn">
									<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="poynt-btn-spinner spinner hide">
									<i class="pp-icon-lock"></i>
									<span class="button-text"></span>
								</button>
							</div>
						</div>
						<div class="pp-tc-section"></div>
						<div class="flex-center">
							<div style="width: fit-content;">
								<button type="button" class="pp-back-to pp-back-to-shipping" data-goto-page="shipping">
									<span class="exit-back-btn"><?php esc_html_e( 'Back', 'peachpay-for-woocommerce' ); ?></span>
								</button>
							</div>
						</div>
					</div>
				</section>
			</div>
			<div class="flex-right pp-modal-col">
				<h2 class="pp-title pp-hide-for-mobile"><?php esc_html_e( 'Order summary', 'peachpay-for-woocommerce' ); ?></h2>
				<div class="pp-order-summary">
					<div style="min-height: 24px; margin: none; padding: none;" id="pp-notice-container-new" class="flex-col pp-notice-container hide">
						<!-- New customer notices-->
					</div>
					<div class="pp-summary order-summary-table">
						<div id="pp-summary-body" class="order-summary-list">
							<!-- New Customer Cart Lines -->
						</div>
					</div>
					<div id="pp-summary-lines-body" class="pp-summary">
						<!--New Customer Summary Lines-->
					</div>
					<table class="pp-summary pp-subscription-summary hide">
						<tbody id="pp-subscription-summary-body">
							<tr class="bold">
								<td colspan="2" class="text-left"><span><?php esc_html_e( 'Recurring total', 'peachpay-for-woocommerce' ); ?></span></td>
							</tr>
							<tr id="subscription-subtotal-row" class="summary-text-format">
								<td><span><?php esc_html_e( 'Subtotal', 'peachpay-for-woocommerce' ); ?></span></td>
								<td><span class="currency-symbol"></span><span id="pp-subscription-subtotal-cost">0.00</span><span class="currency-symbol-after"></span> / <span class="subscription-period"></span>
								</td>
							</tr>
							<tr id="subscription-shipping-cost-row" class="hide summary-text-format">
								<td><span id="subscription-shipping-cost-row-shipping-label"><?php esc_html_e( 'Shipping', 'peachpay-for-woocommerce' ); ?></td>
								<td><span class="currency-symbol"></span><span id="pp-subscription-shipping-cost">0.00</span><span class="currency-symbol-after"></span> / <span class="subscription-period"></span>
								</td>
							</tr>
							<tr id="subscription-tax-cost-row" class="hide summary-text-format">
								<td><span><?php esc_html_e( 'Tax', 'peachpay-for-woocommerce' ); ?></td>
								<td><span class="currency-symbol"></span><span id="pp-subscription-tax-cost">0.00</span><span class="currency-symbol-after"></span>
									/ <span class="subscription-period"></span></td>
							</tr>
							<tr id="subscription-total-cost-row" class="bold total-row mt-half border-total">
								<td><span><?php esc_html_e( 'Total', 'peachpay-for-woocommerce' ); ?></td>
								<td><span class="currency-symbol"></span><span id="pp-subscription-total-cost">0.00</span><span class="currency-symbol-after"></span> / <span class="subscription-period"></span>
								</td>
							</tr>
							<tr id="subscription-first-renewal-row" class="muted">
								<td></td>
								<td><span><?php esc_html_e( 'First renewal', 'peachpay-for-woocommerce' ); ?></span>: <span id="pp-subscription-first-renewal-date"></span></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="extra-fields-section" class="pp-section-mt pp-section-mb">
					<div class="hide-when-invalid">
						<div id="coupon-code-section" class="option-mobile hide">
							<div class="options-mbv">
								<div class="pp-apply-code coupon-code-option options-mobile-new hide" tabindex="0">
									<span><?php esc_html_e( '+ ADD A COUPON CODE', 'peachpay-for-woocommerce' ); ?></span>
								</div>
							</div>
							<form class="wc-coupon-code coupon-code hide">
								<div class="flex w-100">
									<input type="text" id="coupon-code-new" name="coupon_code" placeholder=" " class="wc-coupon-code-input text-input" autocomplete="off" required>
									<label for="coupon-code-new" class="pp-form-label"><?php esc_html_e( 'Coupon code', 'peachpay-for-woocommerce' ); ?></label>
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="wc-coupon-spinner coupon-spinner hide">
									<input type="submit" value="<?php esc_html_e( 'apply', 'peachpay-for-woocommerce' ); ?>" class="coupon-code-apply-text">
								</div>
							</form>
							<span class="wc-invalid-coupon invalid-coupon hide mb-half block"><?php esc_html_e( 'You entered an invalid coupon code', 'peachpay-for-woocommerce' ); ?></span>
						</div>
						<div id="gift-card-section" class="option-mobile hide">
							<div class="options-mbv">
								<div class="pp-apply-code gift-card-option options-mobile-new hide" tabindex="0">
									<span><?php esc_html_e( '+ REDEEM GIFT CARD/STORE CREDIT', 'peachpay-for-woocommerce' ); ?></span>
								</div>
							</div>
							<form class="pw-wc-gift-card coupon-code hide">
								<div class="flex w-100">
									<input type="text" id="card-number-new" name="card_number" placeholder=" " class="wc-gift-card-input text-input" autocomplete="off" required>
									<label for="card-number-new" class="pp-form-label"><?php esc_html_e( 'Gift card number', 'peachpay-for-woocommerce' ); ?></label>
									<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="wc-coupon-spinner coupon-spinner hide">
									<input type="submit" value="<?php esc_html_e( 'apply', 'peachpay-for-woocommerce' ); ?>" class="gift-card-apply-text">
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="pp-custom-order-message-inline pp-custom-order-message hide">
					<p class="muted"></p>
				</div>
			</div>
			<div class="pp-spacer-mobile"></div>
			<div class="center pp-btn-mobile">
				<div class="pp-continue-order-error"></div>
				<div style="display: flex; justify-content: center; align-items: center;">
					<div id="pp-stripe-payment-request-checkout-wrapper-mobile" style="width: 100%; display: flex; justify-content: center;">
						<div id="pp-stripe-payment-request-checkout-btn-mobile" class="hide" style="width: 15rem;"></div>
					</div>
				</div>
				<button type="submit" form="pp-billing-form" id="pp-continue-to-shipping-mobile" class="btn">
					<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" id="continue-spinner-shipping-mobile" class="spinner hide">
					<i class="pp-icon-lock"></i>
					<span class="button-text"><span><?php esc_html_e( 'Continue', 'peachpay-for-woocommerce' ); ?></span></span>
				</button>
				<button id="pp-continue-to-payment-mobile" form="pp-shipping-form" class="btn">
					<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" id="continue-spinner-payment-mobile" class="spinner hide">
					<i class="pp-icon-lock"></i>
					<span class="button-text"><span><?php esc_html_e( 'Continue', 'peachpay-for-woocommerce' ); ?></span></span>
				</button>
				<div id="mobile-customer-pay-button" class="pay-button-container pay-button-container-mobile w-100">
					<!-- Order Support message (mobile) -->
					<div id="pp-custom-order-message-hover" class="w-100 center hide">
						<div class="pp-custom-order-message-label inline-block rounded pp-hover">
							<div class="pp-order-message-button" tabindex="0">&#9432; Order notice</div>
							<div class="pp-hover-content">
								<div class="pp-custom-order-message inline-block">
									<p class="muted inline-block">
									</p>
								</div>
								<div class="pp-order-message-arrow-down center"></div>
							</div>
						</div>
					</div>
					<!-- Mobile Free order button-->
					<div class="free-btn-container hide">
						<div class="pp-btn-spinner-container hide">
							<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
						</div>
						<button class="btn free-btn free-btn-mobile">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="free-btn-spinner spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"><?php esc_html_e( 'Place order', 'peachpay-for-woocommerce' ); ?></span>
						</button>
					</div>
					<!-- Mobile Peachpay Integrated button -->
					<div class="peachpay-integrated-btn-container hide">
						<div class="peachpay-integrated-spinner-container hide">
							<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
						</div>
						<button class="hide pay-btn btn peachpay-integrated-btn">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="peachpay-integrated-btn-spinner spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"></span>
						</button>
					</div>
					<!-- Mobile Stripe pay button -->
					<div class="stripe-btn-container hide">
						<div class="stripe-spinner-container hide">
							<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
						</div>
						<button class="hide pay-btn btn stripe-btn">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="stripe-btn-spinner spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"></span>
						</button>
					</div>
					<!-- Mobile Square pay button -->
					<div class="square-btn-container hide">
						<div class="square-spinner-container hide">
							<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
						</div>
						<button class="hide pay-btn btn square-btn">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="square-btn-spinner spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"></span>
						</button>
						<div class="square-custom-btn-container hide"></div>
					</div>
					<!-- Mobile Authorize.net Pay button -->
					<div class="authnet-btn-container hide">
						<div class="authnet-spinner-container hide">
							<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
						</div>
						<button class="hide pay-btn btn authnet-btn">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="authnet-btn-spinner spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"></span>
						</button>
					</div>
					<!-- Mobile PayPal non hosted pay button -->
					<div class="paypal-btn-container hide">
						<div class="paypal-spinner-container hide">
							<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
						</div>
						<button class="hide pay-btn btn paypal-btn">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="paypal-btn-spinner spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"></span>
						</button>
					</div>
					<!-- Mobile PayPal hosted pay button -->
					<div class="pay-button-container">
						<div class="paypal-pay-btn-container hide">
						</div>
						<div class="paypal-pay-spinner-container hide">
							<img class="paypal-pay-spinner spinner" src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>">
						</div>
					</div>
					<!-- Mobile Poynt pay button -->
					<div class="poynt-btn-container hide">
						<div class="poynt-spinner-container hide">
							<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" class="pp-btn-shipping-spinner">
						</div>
						<button class="hide pay-btn btn poynt-btn">
							<img src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" class="poynt-btn-spinner spinner hide">
							<i class="pp-icon-lock"></i>
							<span class="button-text"></span>
						</button>
					</div>
				</div>
				<div class="pp-tc-section"></div>
				<div class="mt">
					<button type="button" id="pp-exit-mobile" class="hide pp-exit pp-back-to pp-close"><span class="exit-back-btn"><?php esc_html_e( 'Exit checkout', 'peachpay-for-woocommerce' ); ?></span></button>
					<button type="button" id="pp-back-to-info-mobile" data-goto-page="billing" class="hide pp-back-to pp-back-to-info"></img><span class="exit-back-btn"><?php esc_html_e( 'Back', 'peachpay-for-woocommerce' ); ?></span></button>
					<button type="button" id="pp-back-to-shipping-mobile" data-goto-page="shipping" class="hide pp-back-to pp-back-to-shipping"></img><span class="exit-back-btn"><?php esc_html_e( 'Back', 'peachpay-for-woocommerce' ); ?></span></button>
				</div>
			</div>
			<div class="logo-container-mobile-view">
				<div class="pp-tos-privacy-container">
					<a href="https://peachpay.app/privacy" target="_blank">
						<span class='pp-privacy'><?php esc_html_e( 'Privacy Policy', 'peachpay-for-woocommerce' ); ?></span>
					</a>
					<a href="https://peachpay.app/terms" target="_blank">
						<span class='pp-tos'><?php esc_html_e( 'Terms of Service', 'peachpay-for-woocommerce' ); ?></span>
					</a>
				</div>
			</div>
		</div>
		<div class="pp-slide-up-view" id="pp-slide-up-cart-mobile">
			<div class="pp-slide-up-view-bg"></div>
			<div class="pp-slide-up-inner">
				<div class="pp-slide-up-header"><span><?php esc_html_e( 'My cart', 'peachpay-for-woocommerce' ); ?></span></div>
				<div class="pp-summary-list-container">
					<div class="pp-order-list">
						<table class="pp-summary-mobile" id="pp-summary-mobile">
							<tbody id="pp-summary-body-mobile">
							</tbody>
						</table>
					</div>
					<div id="pp-summary-lines-body-mobile" class="pp-summary-mobile">
						<!-- Mobile Customer Summary Lines-->
					</div>
				</div>
			</div>
		</div>
		<div id="loading" class="loading-screen hide flex-row">
			<img src="<?php peachpay_version_url( 'public/img/spinner-dark.svg' ); ?>" width="50">
		</div>
	</div>
	<div class="logo-container">
		<div class="pp-tos-privacy-container">
			<a href="https://peachpay.app/privacy" target="_blank">
				<span class='pp-privacy'><?php esc_html_e( 'Privacy Policy', 'peachpay-for-woocommerce' ); ?></span>
			</a>
			<a href="https://peachpay.app/terms" target="_blank">
				<span class='pp-tos'><?php esc_html_e( 'Terms of Service', 'peachpay-for-woocommerce' ); ?></span>
			</a>
		</div>
	</div>
	<?php
	$custom_js_scripts = peachpay_get_settings_option( 'peachpay_express_checkout_advanced', 'custom_checkout_js', '' );
	if ( ! empty( $custom_js_scripts ) ) {
		echo wp_kses(
			$custom_js_scripts,
			array(
				'script' => array(
					'id'          => array(),
					'type'        => array(),
					'src'         => array(),
					'crossorigin' => array(),
					'defer'       => array(),
					'async'       => array(),
					'module'      => array(),
				),
			)
		);
	}
	?>
</body>

</html>
