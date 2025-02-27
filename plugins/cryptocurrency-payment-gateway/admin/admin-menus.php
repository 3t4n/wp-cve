<?php
/**
 * Create the admin backend menus
 *
 * @package CryptoWoo
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Show row meta on the plugin screen.
 *
 * @param  mixed $links Plugin Row Meta.
 * @param  mixed $file  Plugin Base file.
 * @return array
 */
function cryptowoo_plugin_row_meta( $links, $file ) {
	if ( CWOO_FILE === $file ) {
		$row_meta = array(
			'docs'    => '<a href="' . esc_url( 'https://www.cryptowoo.com/installation/' ) . '" title="' . esc_attr( esc_html__( 'View CryptoWoo Installation Tutorial', 'cryptowoo' ) ) . '" target="_blank">' . esc_html__( 'Getting Started', 'cryptowoo' ) . '</a>',
			'support' => '<a href="' . esc_url( 'https://cryptowoo.zendesk.com/' ) . '" title="' . esc_attr( esc_html__( 'Visit Customer Help Desk', 'cryptowoo' ) ) . '" target="_blank">' . esc_html__( 'Knowledgebase & Support', 'cryptowoo' ) . '</a>',
		);
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
add_filter( 'plugin_row_meta', 'cryptowoo_plugin_row_meta', 10, 2 );


/**
 * Add database actions submenu page
 */
function cryptowoo_add_admin_menu_db() {
	$admin_main = new CW_AdminMain();
	add_submenu_page( 'cryptowoo', esc_html__( 'Database Actions', 'cryptowoo' ), esc_html__( 'Database Actions', 'cryptowoo' ), 'manage_woocommerce', 'cryptowoo_database_maintenance', array( $admin_main, 'database_maintenance' ) );
}
add_action( 'admin_menu', 'cryptowoo_add_admin_menu_db', 400 );

/**
 * Display cryptocurrency info on the order edit page
 *
 * @param WC_Order $order WooCommerce order object.
 */
function cryptowoo_display_admin_order_meta( $order ) {
	// Do we have a payment currency custom field?
	$payment_currency = $order->get_meta( 'payment_currency' );

	// Return if we have no CryptoWoo payment currency field.
	if ( empty( $payment_currency ) ) {
		return;
	}

	$decimals = 8;

	// Block chain link.
	$payment_address = $order->get_meta( 'payment_address' );
	if ( ! empty( $payment_address ) ) {
		$block_chain_link = CW_Formatting::link_to_address( $payment_currency, $payment_address, false, true );
	} else {
		$block_chain_link = '#';
	}

	// Which metadata do we want?
	$cryptowoo_meta = array( 'crypto_amount', 'amount_difference', 'received_confirmed', 'received_unconfirmed', 'payment_address' );

	// Prepare data.
	$output = '';
	foreach ( $cryptowoo_meta as $key ) {
		$value = $order->get_meta( $key );

		if ( empty( $value ) ) {
			continue;
		}

		// Prepare display data.
		$prepared = is_numeric( $value ) ? CW_Formatting::fbits( (int) $value, true, $decimals ) . ' ' . $payment_currency : $value;

		// Link address to blockchain.
		if ( 'payment_address' === $key ) {
			$prepared = $block_chain_link ? str_replace( $value, $block_chain_link, $prepared ) : $prepared;
		}

		// Data key nice name.
		$nice_name = ucwords( strtolower( str_replace( '_', ' ', $key ) ) );

		// Maybe use wide field.
		$wide = 20 <= strlen( $prepared ) ? ' form-field-wide' : '';

		// Output.
		$output .= sprintf( '<p class="form-field%s"><label for="%s"><strong>%s:</strong></label> %s</p>', $wide, $key, $nice_name, $prepared );
	}
	echo wp_kses_post( $output );
}
add_action( 'woocommerce_admin_order_data_after_order_details', 'cryptowoo_display_admin_order_meta', 10, 1 );

/**
 * Add cryptocurrency address and txid to WooCommerce order search fields
 *
 * @param  array $search_fields Array of search fields.
 * @return array
 */
function cryptowoo_shop_order_search_order_total( $search_fields ) {

	$search_fields[] = 'payment_address';
	$search_fields[] = 'txids';
	$search_fields[] = '_payment_method';

	return $search_fields;
}
add_filter( 'woocommerce_shop_order_search_fields', 'cryptowoo_shop_order_search_order_total' );
add_filter( 'woocommerce_order_table_search_query_meta_keys', 'cryptowoo_shop_order_search_order_total' ); // HPOS.
