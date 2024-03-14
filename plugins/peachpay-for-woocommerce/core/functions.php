<?php
/**
 * PeachPay utility functions
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * Gets updated script fragments.
 *
 * @param array $fragments .
 */
function peachpay_native_checkout_data_fragment( $fragments ) {
	$fragments['script#peachpay-native-checkout-js-extra'] = '<script id="peachpay-native-checkout-js-extra">var peachpay_checkout_data = ' . wp_json_encode( peachpay()->native_checkout_data() ) . ';</script>';
	return $fragments;
}

/**
 * Stores deactivation feedback results.
 */
function peachpay_handle_deactivation_feedback() {
	if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'peachpay-deactivation-feedback' ) ) {
		return wp_send_json(
			array(
				'success' => false,
				'message' => 'Invalid nonce. Please refresh the page and try again.',
			)
		);
	}

	$deactivation_reason      = isset( $_POST['deactivation_reason'] ) ? sanitize_text_field( wp_unslash( $_POST['deactivation_reason'] ) ) : null;
	$deactivation_explanation = isset( $_POST['deactivation_explanation'] ) ? sanitize_text_field( wp_unslash( $_POST['deactivation_explanation'] ) ) : null;

	$feedback = array(
		'deactivation_reason' => $deactivation_reason,
	);

	if ( $deactivation_explanation ) {
		$feedback['deactivation_explanation'] = $deactivation_explanation;
	}

	update_option( 'peachpay_deactivation_feedback', $feedback );

	wp_send_json_success();
}

/**
 * Gets if the gateway has a service fee.
 *
 * @param string $gateway_id The gateway Id.
 */
function peachpay_gateway_has_service_fee( $gateway_id ) {
	if ( peachpay_starts_with( $gateway_id, 'peachpay_stripe_' ) ) {
		return true;
	}

	if ( peachpay_starts_with( $gateway_id, 'peachpay_square_' ) ) {
		return true;
	}

	if ( peachpay_starts_with( $gateway_id, 'peachpay_paypal_' ) ) {
		return true;
	}

	return false;
}

/**
 * Gets the PeachPay service fee label.
 *
 * Note: we append a zero-width joiner (&zwj;) to the label to allow for unique identification of the fee line item.
 * This is currently used to append a tooltip after the fee name.
 *
 * @return string
 */
function peachpay_get_service_fee_label() {
	return __( 'Service fee', 'peachpay-for-woocommerce' ) . '‍';
}

/**
 * Adds the PeachPay service fee to the cart.
 *
 * @param WC_Cart $cart The cart object.
 */
function peachpay_add_service_fee( $cart ) {
	if ( ! PeachPay::service_fee_enabled() ) {
		return;
	}

	if ( ! WC() || ! isset( WC()->session ) || ! WC()->session->get( 'chosen_payment_method' ) ) {
		return;
	}

	$current_payment_gateway = WC()->session->get( 'chosen_payment_method' );
	if ( ! peachpay_gateway_has_service_fee( $current_payment_gateway ) ) {
		return;
	}

	// Calculate the fee amount as percentage of the cart (subtotal + shipping total) - discounts
	$fee_amount = ( ( $cart->get_subtotal() + $cart->get_shipping_total() ) - $cart->get_discount_total() ) * PeachPay::service_fee_percentage();

	if ( $fee_amount < 0.01 ) {
		return;
	}

	$cart->add_fee( peachpay_get_service_fee_label(), round( $fee_amount, 2 ), false );
}

/**
 * Adds the PeachPay service fee to the order dashboard view.
 *
 * @param WC_Order $order The order object.
 */
function peachpay_display_service_fee_tooltip( $order ) {
	if ( did_action( 'woocommerce_admin_order_data_after_billing_address' ) > 1 ) {
		return;
	}

	// translators: %s is the service fee percentage for the given order.
	$tooltip_message = sprintf( __( 'PeachPay charges a %s%% service fee to the customer. As a merchant, you don’t pay anything extra.', 'peachpay-for-woocommerce' ), PeachPay_Order_Data::get_peachpay( $order, 'service_fee_percentage' ) * 100 );
	?>
	<script>
		document.addEventListener("DOMContentLoaded", function(event) {
			for (const feeLabel of Array.from(document.querySelectorAll('tr.fee td.name div.view'))) {
				if (feeLabel.innerHTML.trim() !== '<?php echo peachpay_get_service_fee_label();//PHPCS:ignore ?>') {
					continue;
				}

				feeLabel.innerHTML += '<?php echo wc_help_tip( $tooltip_message ); //PHPCS:ignore ?>';
			}
		})
	</script>
	<?php
}


/**
 * Synchronizes the service fee configuration with the PeachPay API.
 */
function peachpay_sync_service_fee_configuration() {

	if ( ! PeachPay_Capabilities::connected( 'service_fee' ) ) {
		update_option( 'peachpay_service_fee_enabled', 'no' );
		return;
	}

	$service_fee_percentage = 0.015;
	if ( PeachPay_Capabilities::has( 'service_fee', 'account' ) ) {
		$service_fee_percentage = floatval( PeachPay_Capabilities::get( 'service_fee', 'account' )['fee_percentage'] );
	}

	update_option( 'peachpay_service_fee_enabled', 'yes' );
	update_option( 'peachpay_service_fee_percentage', $service_fee_percentage );
}

/**
 * Gets the PeachPay affiliate Id.
 */
function peachpay_affiliate_id() {
	return get_option( 'peachpay_affiliate_id', null );
}

/**
 * Gets Dynamic Feature Metadata.
 *
 * @param array  $feature_metadata The feature metadata.
 * @param string $cart_key The cart key.
 */
function peachpay_dynamic_feature_metadata( $feature_metadata, $cart_key ) {
	if ( '0' !== $cart_key ) {
		return $feature_metadata;
	}

	$feature_metadata['express_checkout'] = array(
		'checkout_nonce' => wp_create_nonce( 'woocommerce-process_checkout' ),
		'login_nonce'    => wp_create_nonce( 'peachpay-ajax-login' ),
		'logged_in'      => is_user_logged_in(),
	);

	$feature_metadata['coupon_input'] = array(
		'apply_coupon_nonce'  => wp_create_nonce( 'apply-coupon' ),
		'remove_coupon_nonce' => wp_create_nonce( 'remove-coupon' ),
	);

	return $feature_metadata;
}

/**
 * Locates a PeachPay template.
 *
 * @param string $template_name .
 * @param string $template_path .
 */
function peachpay_locate_template( $template_name, $template_path = '' ) {
	if ( ! $template_path ) {
		$template_path = PEACHPAY_ABSPATH . '/templates/';
	}

	$template = wp_normalize_path( $template_path . $template_name );

	return $template;
}

/**
 * Declare our support for Woocommerce High performance order storage.
 *
 * @since 1.99.3
 */
function pp_declare_wc_hpos_support() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', PEACHPAY_BASENAME, true );
	}
}

/**
 * Returns whether a feature should be displayed to public users
 */
function pp_should_display_public() {
	if ( ! peachpay_is_test_mode() ) {
		return true;
	}

	if ( isset( $_COOKIE['pp-override'] ) && filter_var( sanitize_text_field( wp_unslash( $_COOKIE['pp-override'] ) ), FILTER_VALIDATE_BOOLEAN ) ) {
		return true;
	}

	return current_user_can( 'editor' ) || current_user_can( 'administrator' ); //phpcs:ignore
}

/**
 * Returns whether a feature should be displayed to admin users
 */
function pp_should_display_admin() {
	if ( isset( $_COOKIE['pp-override'] ) && filter_var( sanitize_text_field( wp_unslash( $_COOKIE['pp-override'] ) ), FILTER_VALIDATE_BOOLEAN ) ) {
		return true;
	}

	return current_user_can( 'editor' ) || current_user_can( 'administrator' ); //phpcs:ignore
}

/**
 * Redirects to the PeachPay settings page on plugin activation.
 *
 * @param string $plugin The plugin that was activated.
 */
function pp_activation_redirect( $plugin ) {
	if ( PEACHPAY_BASENAME !== $plugin ) {
		return;
	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return;
	}

	if ( wp_safe_redirect( admin_url( 'admin.php?page=peachpay&tab=home' ) ) ) {
		exit();
	}
}

/**
 * Performs a wp_remote_post expecting a JSON response. If the response is invalid, it will retry up to 5 times by default with an exponential backoff and random jitter delay.
 *
 * WARNING #1: Only safe for use with idempotent requests.
 * WARNING #2: This function could cause long delays if the remote server is down or slow. Be sure to only use this where critical requests.
 *
 * @param string $url The URL to post to.
 * @param array  $args The arguments to pass to wp_remote_post.
 * @param array  $attempt_options Options for configuring retries and backoff.
 */
function peachpay_json_remote_post( $url, $args = array(), $attempt_options = array(
	'count' => 5,
	'base'  => 500,
	'cap'   => 10000,
) ) {
	$attempt = 0;
	$errors  = array();

	while ( $attempt++ < $attempt_options['count'] ) {
		if ( $attempt > 1 ) {
			$delay_ms = wp_rand( 0, min( $attempt_options['cap'], $attempt_options['base'] * 2 ** $attempt ) );
			if ( $delay_ms < 1000 ) {
				usleep( $delay_ms );
			} else {
				sleep( round( $delay_ms / 1000 ) );
			}
		}

		$response = wp_remote_post( $url, $args );
		if ( $response instanceof WP_Error ) {
			array_push( $errors, 'Request attempt #' . $attempt . ': ' . $response->get_error_message() );
			continue;
		}

		$body = wp_remote_retrieve_body( $response );
		if ( $body instanceof WP_Error ) {
			array_push( $errors, 'Request attempt #' . $attempt . ': ' . $body->get_error_message() );
			continue;
		}

		$json = json_decode( $body, true );
		if ( null === $json ) {
			array_push( $errors, 'Request attempt #' . $attempt . ': The JSON body cannot be decoded.' );
			continue;
		}

		return array(
			'result' => $json,
			'error'  => null,
		);
	}

	return array(
		'result' => null,
		'error'  => $errors,
	);
}

/**
 * Registers the core scripts and styles for PeachPay checkout blocks.
 */
function pp_register_core_checkout_blocks_scripts() {
	PeachPay::register_webpack_style( 'peachpay-checkout-blocks', 'wordpress/checkout-blocks' );
	// WC does not seem to provide a way to dynamically enqueue styles so we need
	// to unconditionally enqueue it here.
	wp_enqueue_style( 'peachpay-checkout-blocks' );

	PeachPay::register_webpack_script( 'peachpay-checkout-blocks', 'wordpress/checkout-blocks' );
	PeachPay::register_script_data(
		'peachpay-checkout-blocks',
		'peachpay_checkout_blocks',
		array(
			'create_transaction_url' => WC_AJAX::get_endpoint( 'pp-create-transaction' ),
			'update_transaction_url' => WC_AJAX::get_endpoint( 'pp-update-transaction' ),
		)
	);
}

/**
 * Registers fee to the cart based on selected payment method.
 *
 * @param WC_Cart $cart Current selected cart.
 */
function peachpay_custom_payment_method_fee( $cart ) {
	if ( ! WC() || ! isset( WC()->session ) ) {
		return;
	}

	$gateway_id = WC()->session->get( 'chosen_payment_method' );
	if ( empty( $gateway_id ) || ! isset( WC()->payment_gateways->payment_gateways()[ $gateway_id ] ) ) {
		return;
	}

	$gateway_instance = WC()->payment_gateways->payment_gateways()[ $gateway_id ];
	if ( ! ( $gateway_instance instanceof PeachPay_Payment_Gateway ) ) {
		return;
	}

	$gateway_instance->calculate_payment_method_fee( $cart );
}
