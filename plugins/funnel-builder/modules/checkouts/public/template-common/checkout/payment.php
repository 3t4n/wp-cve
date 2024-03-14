<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$wc_version = wc()->version;


if ( version_compare( $wc_version, '3.4.0', '>=' ) ) {
	include __DIR__ . '/payment-3.4.0.php';
} elseif ( version_compare( $wc_version, '3.4.0', '<' ) && version_compare( $wc_version, '3.3.0', '>=' ) ) {
	include __DIR__ . '/payment-3.3.0.php';
} else {
	include __DIR__ . '/payment-2.5.0.php';
}

return;

