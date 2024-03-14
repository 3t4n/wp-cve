<?php

/*******************************************************************************
 *
 *  Copyrights 2017 to Present - Sellergize Web Technology Services Pvt. Ltd. - ALL RIGHTS RESERVED
 *
 * All information contained herein is, and remains the
 * property of Sellergize Web Technology Services Pvt. Ltd.
 *
 * The intellectual and technical concepts & code contained herein are proprietary
 * to Sellergize Web Technology Services Pvt. Ltd. (India), and are covered and protected
 * by copyright law. Reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Sellergize Web Technology Services Pvt. Ltd.
 * 
 * ******************************************************************************/
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function couponapi_delete_offers() {
	
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	
	$count_suspended = 0;
	
	wp_defer_term_counting( true );
	$wpdb->query( 'SET autocommit = 0;' );
	
    $count_suspended = $wpdb->query("DELETE FROM `{$wp_prefix}posts` WHERE `ID` IN (SELECT `post_id` FROM  `{$wp_prefix}postmeta` WHERE `meta_key` = 'capi_id' AND meta_value != '' AND meta_value IS NOT NULL)");
	
    $wpdb->query("DELETE FROM `{$wp_prefix}postmeta` WHERE `post_id` NOT IN (SELECT `ID` FROM `{$wp_prefix}posts`)");
    $wpdb->query("DELETE FROM `{$wp_prefix}term_relationships` WHERE `object_id` NOT IN (SELECT `ID` FROM `{$wp_prefix}posts`)");
    $wpdb->query("UPDATE `{$wp_prefix}term_taxonomy` `tt` SET `count` = ( SELECT count(p.ID)  FROM  `{$wp_prefix}term_relationships` `tr` LEFT JOIN `{$wp_prefix}posts` `p` ON (`p`.`ID` = `tr`.`object_id` AND `p`.`post_status` = 'publish') WHERE `tr`.`term_taxonomy_id` = `tt`.`term_taxonomy_id` )");
	
	wp_defer_term_counting( false );
	$wpdb->query( 'COMMIT;' );
	$wpdb->query( 'SET autocommit = 1;' );
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'success','All Offers imported from CouponAPI have been dropped.')");
	
	$message = '<div class="notice notice-success is-dismissible"><p>'.sprintf(__("Dropped %s offers.","couponapi"),$count_suspended).'</p></div>';

	return $message;
	
}
