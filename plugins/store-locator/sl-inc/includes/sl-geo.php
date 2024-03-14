<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Last saved: 7/13/15 12:57:32a
if (empty($_GET['sl_id']) || empty($_GET['lat']) || empty($_GET['lng']) || empty($_GET['_wpnonce'])) {
	die('Missing parameters');
} elseif ( !wp_verify_nonce($_GET['_wpnonce'], 'second-pass-geo_'.$_GET['sl_id']) ) {
	die('Security check');
} else {
	$query = $wpdb->prepare("UPDATE ".SL_DB_PREFIX."store_locator SET sl_latitude = %s, sl_longitude = %s WHERE sl_id = %d", esc_sql($_GET['lat']),  esc_sql($_GET['lng']), esc_sql($_GET['sl_id']) );
	$wpdb->query($query);
	print "Successful Update via second-pass geocoding";
}
?>