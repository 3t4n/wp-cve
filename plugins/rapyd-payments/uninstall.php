<?php
/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once dirname(__FILE__) . '/includes/consts/rapyd-consts.php';

$categories_ids = array(RAPYD_EWALLET_ID,RAPYD_CASH_ID,RAPYD_CARD_ID,RAPYD_BANK_ID,RAPYD_COMMON_ID);
$arrlength = count($categories_ids);
for ( $i = 0; $i < $arrlength; $i++ ) {
	delete_option( 'woocommerce_' . $categories_ids[$i] . '_settings' );
}




