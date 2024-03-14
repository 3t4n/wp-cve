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

function couponapi_save_image_config()
{

	$message = "";
	if (wp_verify_nonce($_POST['image_config_nonce'], 'couponapi')) {

		global $wpdb;
		$import_images = (sanitize_text_field($_POST['import_images']) == 'on' ? 'On' : 'Off');
		$use_logos = (sanitize_text_field($_POST['use_logos'] ?? 'always'));
		$format = (sanitize_text_field($_POST['format'] ?? 'png'));
		$size = (sanitize_text_field($_POST['size'] ?? 'horizontal'));

		$sql = "REPLACE INTO {$wpdb->prefix}couponapi_config (name,value) VALUES
							('use_logos','$use_logos'),
							('format','$format'),
							('size','$size'),
							('import_images','$import_images')";
		if ($wpdb->query($sql) === false) {
			$message .= "<div class='notice notice-error is-dismissible'><p>{$wpdb->last_error}</p></div>";
		} else {
			$message .= '<div class="notice notice-success is-dismissible"><p>Image Settings saved successfully.</p></div>';
		}
	} else {
		$message .= '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}

	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi-image-settings');
	exit;
}
