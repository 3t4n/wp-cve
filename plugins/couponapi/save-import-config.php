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

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function couponapi_save_import_config() {

	if (wp_verify_nonce($_POST['feed_config_nonce'], 'couponapi')) {

		global $wpdb;
		$wp_prefix = $wpdb->prefix;
		$cashback = ((isset($_POST['cashback']) and sanitize_text_field($_POST['cashback']) == 'on') ? 'On' : 'Off');
		$import_images = ((isset($_POST['import_images']) and sanitize_text_field($_POST['import_images']) == 'on') ? 'On' : 'Off');
		$import_locations = ((isset($_POST['import_locations']) and sanitize_text_field($_POST['import_locations']) == 'on') ? 'On' : 'Off');
		$batch_size = intval($_POST['batch_size']);
		$generic_import_image = sanitize_text_field($_POST['generic_import_image']??'off');
		$set_as_featured_image = (isset($_POST['set_as_featured_image']) and $generic_import_image != 'off') ? 'On' : 'Off';
		$ctype_code = $_POST['ctype_code'] ?? '';
		$ctype_deal = $_POST['ctype_deal'] ?? '';
		$store = isset($_POST['store'])?$_POST['store']:'store';
		$category = isset($_POST['category'])?$_POST['category']:'category';
		$code_text = $_POST['code_text'] ?? '';
		$expiry_text = $_POST['expiry_text'] ?? '';

		if (empty($batch_size)) $batch_size = 500;

		$sql = "REPLACE INTO " . $wp_prefix . "couponapi_config (name,value) VALUES
							('cashback','$cashback'),
							('import_images','$import_images'),
							('import_locations','$import_locations'),
							('batch_size','$batch_size'),
							('generic_import_image','$generic_import_image'),
							('set_as_featured_image','$set_as_featured_image'),
							('ctype_code','$ctype_code'),
							('ctype_deal','$ctype_deal'),
							('code_text','$code_text'), 
							('expiry_text','$expiry_text'),
							('store','$store'),
							('category','$category')";

		if ($wpdb->query($sql) === false) {
			$message = '<div class="notice notice-error is-dismissible"><p>' . $wpdb->last_error . '</p></div>';
		} else {
			$message = '<div class="notice notice-success is-dismissible"><p>'.__("Import Settings saved successfully.","couponapi").'</p></div>';
		}

		if (empty($ctype_code)) {
			$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_config WHERE name = 'ctype_code';");
		}
		if (empty($ctype_deal)) {
			$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_config WHERE name = 'ctype_deal';");
		}
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>'.__("Access Denied. Nonce could not be verified.","couponapi").'</p></div>';
	}

	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi&tab=import-settings');
	exit;
}
