<?php

/*******************************************************************************
 *
 * Copyrights 2017 to Present - Sellergize Web Technology Services Pvt. Ltd. - ALL RIGHTS RESERVED
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

function couponapi_save_brandlogos_config()
{
	$message = "";
	if (wp_verify_nonce($_POST['brandlogos_config_nonce'], 'couponapi')) {

		global $wpdb;
		$use_logos = (sanitize_text_field($_POST['use_logos'] ?? 'if_empty'));
		$use_grey_image = $_POST['use_grey_image'] != 'on' ? 'off' : 'on';
		couponapi_process_use_logos($use_logos);
		$size = (sanitize_text_field($_POST['size'] ?? 'horizontal'));

		$sql = "REPLACE INTO {$wpdb->prefix}couponapi_config (name,value) VALUES ('use_logos','$use_logos'), ('use_grey_image','$use_grey_image'), ('size','$size')";
		if ($wpdb->query($sql) === false) {
			$message .= "<div class='notice notice-error is-dismissible'><p>{$wpdb->last_error}</p></div>";
		} else {
			$message .= '<div class="notice notice-success is-dismissible"><p>'.__("Image Settings saved successfully.","couponapi").'</p></div>';
		}
	} else {
		$message .= '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}

	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi-brandlogos-settings');
	exit;
}

function couponapi_process_use_logos($use_logos)
{
	global $wpdb;

	$theme = get_template();
	if ($use_logos == 'on' and $theme == 'clipmydeals') {
		// Get coupons created from CouponAPI and DELETE their images
		$wpdb->query("DELETE FROM `{$wpdb->prefix}postmeta` WHERE `post_id` IN (SELECT `post_id` FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` = 'capi_id') AND `meta_key` = 'cmd_image_url'");
	} else if ($theme == 'couponis') {
		$couponis_options = get_option('couponis_options');
		$couponis_options['coupon_listing_image'] = $use_logos != 'off' ? 'store' : 'featured';
		update_option('couponis_options', $couponis_options);
	}
}
