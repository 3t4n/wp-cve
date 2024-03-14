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

function couponapi_activate() {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;	
	// DROP TABLES IF ALREADY PRESENT
	$sql = "DROP TABLE IF EXISTS ".$wp_prefix."couponapi_logs, ".$wp_prefix."couponapi_config, ".$wp_prefix."couponapi_upload";
	$wpdb->query($sql);
	// CREATE LOG TABLE
	$sql = "CREATE TABLE IF NOT EXISTS ".$wp_prefix."couponapi_logs (
						logtime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
						microtime DECIMAL(20,6) NOT NULL DEFAULT '0',
						msg_type VARCHAR( 10 ) NOT NULL,
						message text NOT NULL) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_520_ci";
	$wpdb->query($sql);
	// CREATE CONFIG TABLE
	$sql = "CREATE TABLE IF NOT EXISTS ".$wp_prefix."couponapi_config (
						name varchar(50) NOT NULL,
						value text NOT NULL,
						UNIQUE  (name)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_520_ci";
	$wpdb->query($sql);
	// CREATE UPLOAD TABLE
	$sql = "CREATE TABLE IF NOT EXISTS ".$wp_prefix."couponapi_upload (
						offer_id int(11) NOT NULL,
						title text NOT NULL,
						description text NOT NULL,
						label varchar(255) NOT NULL,
						code varchar(50) NOT NULL,
						featured varchar(10) NOT NULL,
						source varchar(20) NOT NULL,
						url text NOT NULL,
						deeplink text NOT NULL,
						affiliate_link text NOT NULL,
						cashback_link text NOT NULL,
						image_url text NOT NULL,
						type VARCHAR(5) NOT NULL,
						store varchar(50) NOT NULL,
						merchant_home_page text NOT NULL,
						categories text NOT NULL,
						locations text NOT NULL,
						start_date DATE NULL DEFAULT NULL,
						end_date DATE NULL DEFAULT NULL,
						status VARCHAR(20) NOT NULL,
						upload_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_520_ci";
	$wpdb->query($sql);
}

// TODO: Remove this in later versions
function couponapi_update_to_3_point_2_point_1() {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	$res = $wpdb->get_results("SHOW COLUMNS FROM ".$wp_prefix."couponapi_upload WHERE Field='merchant_home_page'");
	if(count($res)==0) { // merchant_home_page column does not exist
		$sql = "ALTER TABLE ".$wp_prefix."couponapi_upload ADD COLUMN merchant_home_page TEXT NOT NULL AFTER store";
		$wpdb->query($sql);
	}
}

// TODO: Remove this in later versions
function couponapi_update_to_3_point_4_point_3() {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	$res = $wpdb->get_results("SHOW COLUMNS FROM ".$wp_prefix."couponapi_upload WHERE Field='brand_logo'");
	if(count($res)==0) { // brand_logo column does not exist
		$sql = "ALTER TABLE ".$wp_prefix."couponapi_upload ADD COLUMN brand_logo TEXT NULL AFTER image_url";
		$wpdb->query($sql);
	}
}

// TODO: Remove this in later versions
function couponapi_update_to_4_point_0_point_2() {
	global $wpdb;
	$wpdb->get_results("UPDATE {$wpdb->prefix}couponapi_config 
						SET value = CASE WHEN value = 'always' THEN 'on'
										 WHEN value = 'never' THEN 'off'
									END
						WHERE name ='use_logos' AND value in ('always','never')");
}

// TODO: Remove this in later versions
function couponapi_update_to_4_point_1() {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	$res = $wpdb->get_results("SHOW COLUMNS FROM ".$wp_prefix."couponapi_upload WHERE Field='label'");
	if(count($res)==0) { // label column does not exist
		$sql = "ALTER TABLE ".$wp_prefix."couponapi_upload ADD COLUMN label varchar(255) NOT NULL AFTER description";
		$wpdb->query($sql);
	}
}

// TODO: Remove this in later versions
function couponapi_update_to_4_point_2() {
	global $wpdb;
	if(count($wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}couponapi_upload WHERE Field='locations'"))==0) { // locations column does not exist
		$wpdb->query("ALTER TABLE {$wpdb->prefix}couponapi_upload ADD COLUMN locations TEXT NULL AFTER categories");
	}
}

// TODO: Remove this in later versions
// delete_option( 'couponapi_updated_to_4_point_2_point_1' )
function couponapi_update_to_4_point_2_point_1()
{
	if (get_option('couponapi_updated_to_4_point_2_point_1') != 'completed') {
		global $wpdb;
		$wpdb->query("UPDATE {$wpdb->prefix}postmeta SET meta_key = 'capi_id' WHERE meta_key = 'offer_id'");
		update_option('couponapi_updated_to_4_point_2_point_1', 'completed');
	}
}

// TODO: Remove this in later versions
function couponapi_update_to_6_point_0_point_7() {
	global $wpdb;
	$wpdb->query("DELETE FROM {$wpdb->prefix}couponapi_config WHERE name = 'is_premium'");
}