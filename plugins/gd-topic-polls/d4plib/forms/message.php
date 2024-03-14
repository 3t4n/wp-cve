<?php

use Dev4Press\v43\Core\Quick\Sanitize;
use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_message = '';
$_color   = 'success';

if ( ! empty( $_GET['message'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
	$msg_code = Sanitize::slug( $_GET['message'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification

	switch ( $msg_code ) {
		case 'saved':
			$_message = __( 'Settings are saved.', 'd4plib' );
			break;
		case 'feature-reset':
			$_message = __( 'Feature Settings reset completed.', 'd4plib' );
			break;
		case 'imported':
			$_message = __( 'Import operation completed.', 'd4plib' );
			break;
		case 'removed':
			$_message = __( 'Removal operation completed.', 'd4plib' );
			break;
		case 'import-failed':
			$_message = __( 'Import operation failed.', 'd4plib' );
			$_color   = 'error';
			break;
		case 'nothing':
			$_color   = 'error';
			$_message = __( 'Nothing is done.', 'd4plib' );
			break;
		case 'nothing-removed':
			$_color   = 'error';
			$_message = __( 'Nothing removed.', 'd4plib' );
			break;
		case 'invalid':
			$_message = __( 'Requested operation is invalid.', 'd4plib' );
			$_color   = 'error';
			break;
	}

	$msg = panel()->a()->message_process(
		$msg_code,
		array(
			'message' => $_message,
			'color'   => $_color,
		)
	);
	$msg = apply_filters( panel()->a()->h( 'admin_panel_message' ), $msg );

	$_message = $msg['message'];
	$_color   = $msg['color'];
}

if ( $_message != '' ) {
	echo '<div class="d4p-message"><div class="notice notice-' . esc_attr( $_color ) . ' is-dismissible">' . wp_kses_post( $_message ) . '</div></div>';
}
