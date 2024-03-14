<?php
/**
 * Support for the PW Woocommerce Gift Cards Plugin.
 * Plugin: https://www.pimwick.com/gift-cards/
 * Default Plugin Keys:
 *      pw-woocommerce-gift-cards/pw-gift-cards.php
 *      pw-gift-cards/pw-gift-cards.php
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Initializes support for PW Gift cards with PeachPay.
 */
function peachpay_pwgc_init() {
	add_filter( 'peachpay_register_feature', 'peachpay_pwgc_register_feature', 10, 1 );
	add_filter( 'peachpay_dynamic_feature_metadata', 'peachpay_pwgc_dynamic_feature_metadata', 10, 2 );

	add_filter( 'peachpay_cart_applied_gift_cards', 'peachpay_pwgc_applied_gift_cards', 10, 1 );
}
add_action( 'peachpay_init_compatibility', 'peachpay_pwgc_init' );

/**
 * Registers the PW Gift Cards feature.
 *
 * @param array $features The existing features.
 */
function peachpay_pwgc_register_feature( $features ) {
	$features['giftcard_input'] = array(
		'enabled' => true,
	);

	return $features;
}

/**
 * Adds meta data to the peachpay script data object.
 *
 * @param array $feature_metadata The existing feature data.
 * @param int   $cart_key The cart key.
 */
function peachpay_pwgc_dynamic_feature_metadata( $feature_metadata, $cart_key ) {
	if ( '0' !== $cart_key ) {
		return $feature_metadata;
	}

	$feature_metadata['giftcard_input'] = array(
		'pw_gift_cards_apply_nonce' => wp_create_nonce( 'pw-gift-cards-apply-gift-card' ),
	);
	return $feature_metadata;
}

/**
 * Gets gift cards that are currently applied to the cart.
 */
function peachpay_pwgc_applied_gift_cards() {
	$cards = array();

	if ( ! WC() || ! isset( WC()->session ) ) {
		return $cards;
	}

	$session_data = WC()->session->get( PWGC_SESSION_KEY );
	if ( ! is_array( $session_data ) || ! isset( $session_data['gift_cards'] ) ) {
		return $cards;
	}

	foreach ( $session_data['gift_cards'] as $card_number => $balance ) {
		array_push(
			$cards,
			array(
				'card_number' => $card_number,
				'balance'     => $balance,
			)
		);
	}

	return $cards;
}

/**
 * Builds a record of applied gift cards toward a given cart.
 *
 * @param array   $record Existing coupons recorded.
 * @param WC_Cart $cart A given cart to check for applied gift cards.
 */
function peachpay_pwgc_record( $record, $cart ) {
	if ( isset( $cart->pwgc_calculated_total ) ) {
		if ( ! WC() || ! isset( WC()->session ) ) {
			return $record;
		}

		$session_data = WC()->session->get( PWGC_SESSION_KEY );
		if ( ! is_array( $session_data ) || ! isset( $session_data['gift_cards'] ) ) {
			return $record;
		}

		$total = $cart->pwgc_total_gift_cards_redeemed;
		foreach ( $session_data['gift_cards']  as $card_number => $amount ) {
			$applied_amount         = peachpay_pwgc_gift_card_applied_amount( $card_number, $total );
			$total                 -= $applied_amount;
			$record[ $card_number ] = floatval( $applied_amount );
		}
	}
	return $record;
}
add_filter( 'peachpay_cart_applied_gift_cards_record', 'peachpay_pwgc_record', 10, 2 );

/**
 * Calculates the gift card applied amount.
 *
 * @param string $card_number The card to find the applied amount.
 * @param float  $total The current cart total.
 */
function peachpay_pwgc_gift_card_applied_amount( $card_number, $total ) {
	$pw_gift_card = new PW_Gift_Card( $card_number );
	$balance      = $pw_gift_card->get_balance();

	if ( $balance <= 0 ) {
		return 0;
	}

	if ( $balance > $total ) {
		return $total;
	}

	return $total - ( $total - $balance );
}
