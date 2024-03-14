<?php
/**
 * Admin Transfers Page.
 *
 * @package     EverAccounting
 * @subpackage  Admin/Banking/Transfers
 * @since       1.0.2
 */

defined( 'ABSPATH' ) || exit();

/**
 * Render the transfers page.
 */
function eaccounting_render_transfers_tab() {
	$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
	$transfer_id    = filter_input( INPUT_GET, 'transfer_id', FILTER_VALIDATE_INT );
	if ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
		include dirname( __FILE__ ) . '/edit-transfer.php';
	} else {
		include dirname( __FILE__ ) . '/list-transfer.php';
	}
}

add_action( 'eaccounting_banking_tab_transfers', 'eaccounting_render_transfers_tab' );
