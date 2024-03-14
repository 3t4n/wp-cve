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

// Add Custom Fields to Stores
function couponapi_cmd_stores_taxonomy_brandlogo_field()
{
?>
	<script>
		const couponapi_cmd_create_brandlogos_resync_button = () => {
			const url = document.createElement('a');
			url.setAttribute('href', document.getElementById('store_url').value);
			const brand = url.hostname;

			btn = document.getElementById('brand_logo_btn');
			if (btn) btn.remove();

			document.getElementById('store_logo').parentElement.insertAdjacentHTML('beforeend',
				`<div id="brand_logo_btn"style="display: flex;justify-content: right;align-items: start;">
 <div class="label label-danger" style="color:#b32d2e;padding: 5px 5px;" id="brand_logo_fail_msg" role="alert"></div>
 <div class="button button-primary" onclick="couponapi_cmd_update_brandlogo('${brand}', '<?= $_GET['tag_ID'] ?>')">Resync With brandLogos</div>
 </div>`
			);
		}

		window.onload = couponapi_cmd_create_brandlogos_resync_button;
		document.getElementById('store_url').addEventListener('keyup', couponapi_cmd_create_brandlogos_resync_button);

		function couponapi_cmd_update_brandlogo(brand, term_id) {
			event.preventDefault();

			document.getElementById('store_logo').readOnly = true;

			var form = new FormData();
			form.append('action', 'couponapi_cmd_save_brandlogo');
			form.append('brand', brand);
			form.append('term_id', term_id);

			fetch('<?= admin_url('admin-ajax.php') ?>', {
					method: 'POST',
					body: form
				})
				.then((res) => res.json())
				.then((response) => {
					if (response.error) {
						document.getElementById('brand_logo_fail_msg').innerHTML = response.message;
					} else {
						document.getElementById('store_logo').value = response.attachment_url;
					}
				})
				.finally(() => {
					document.getElementById('store_logo').readOnly = false;
				});
			return;
		}
	</script>
<?php
}

function couponapi_cmd_save_brandlogo()
{
	$brand = str_replace("www.", "", $_POST['brand']);
	$term_id = intval($_POST['term_id']);

	if ($brand) {

		// Default config
		global $wpdb;
		$config = array('import_images' => 'Off', 'cashback' => 'Off', 'batch_size' => 500, 'brandlogos_key' => '', 'use_grey_image' => 'on', 'size' => 'horizontal', 'use_logos' => 'on');
		$result = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}couponapi_config`");
		foreach ($result as $row) $config[$row->name] = $row->value;

		$image_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image'], 0, true);
		if ($image_id == 'grey_image_fail') {
			$response = array('error' => true, 'message' => "No Logo found for selected store and since your setting 'For Stores without logo' is set to 'Do not use grey images as default placeholder' Logo URL field isn't updated");
		} else {
			$attachment_url = wp_get_attachment_url($image_id);
			if (!$attachment_url) {
				$response = array('error' => true, 'message' => "Failed Saving Logo URL. Please try again later");
			} else {
				$termmeta = get_option("taxonomy_term_$term_id", array('store_logo' => ''));
				$termmeta['store_logo'] = $attachment_url;
				update_option("taxonomy_term_$term_id", $termmeta);
				$response = array('error' => false, 'attachment_url' => $attachment_url);
			}
		}
	} else {
		$response = array('error' => true, 'message' => "Empty / Invalid Store URL");
	}
	$response['image_id'] = $image_id;
	echo json_encode($response);
	wp_die();
}

if (get_template() === 'clipmydeals') {
	global $wpdb;

	if ($wpdb->get_var("SELECT value FROM `{$wpdb->prefix}couponapi_config` WHERE name = 'use_logos'") !== 'off' and $wpdb->get_var("SELECT value FROM `{$wpdb->prefix}couponapi_config` WHERE name = 'brandlogos_key'") != '') {
		add_action('stores_edit_form_fields', 'couponapi_cmd_stores_taxonomy_brandlogo_field', 11);
		add_action('wp_ajax_nopriv_couponapi_cmd_save_brandlogo', 'couponapi_cmd_save_brandlogo');
		add_action('wp_ajax_couponapi_cmd_save_brandlogo', 'couponapi_cmd_save_brandlogo');
	}
}

function couponapi_brandlogos_resync()
{
	if (wp_verify_nonce($_POST['brandlogos_resync_nonce'], 'couponapi')) {
		global $wpdb;

		wp_clear_scheduled_hook('couponapi_process_brandlogos_resync_event');

		$resync = array('empty_logos' => isset($_POST['empty_logos']), 'grey_logos' => isset($_POST['grey_logos']), 'custom_logos' => isset($_POST['custom_logos']));
		$resync['store_slugs'] = $_POST['store_slugs'] ? explode(',', sanitize_text_field($_POST['store_slugs'])) : false;
		$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'info', 'Starting logos resync process. This may take several minutes...') ");
		wp_schedule_single_event(time(), 'couponapi_process_brandlogos_resync_event', array($resync));

		$message = '<div class="notice notice-success is-dismissible">' . __("Sync process has been initiated. Refresh Logs to see current status.", "couponapi") . '</p></div>';
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>' . __("Access Denied. Nonce could not be verified.", "couponapi") . '</p></div>';
	}

	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi-brandlogos-settings');
	exit;
}


function couponapi_process_brandlogos_resync($resync)
{
	global $wpdb;

	// Default config
	$config = array('import_images' => 'Off', 'cashback' => 'Off', 'batch_size' => 500, 'brandlogos_key' => '', 'use_grey_image' => 'on', 'size' => 'horizontal', 'use_logos' => 'on', 'generic_import_image' => 'off', 'set_as_featured_image' => 'Off');
	$result = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}couponapi_config`");
	foreach ($result as $row) $config[$row->name] = $row->value;

	$theme = get_template();

	wp_defer_term_counting(true);
	$wpdb->query('SET autocommit = 0;');

	if ($theme == 'clipmydeals') {
		couponapi_clipmydeals_resync_logos($config, $resync);
	} elseif ($theme == 'clipper') {
		couponapi_clipper_resync_logos($config, $resync);
	} elseif ($theme == 'couponer') {
		couponapi_couponer_resync_logos($config, $resync);
	} elseif ($theme == 'couponhut') {
		couponapi_couponhut_resync_logos($config, $resync);
	} elseif ($theme == 'couponis') {
		couponapi_couponis_resync_logos($config, $resync);
	} elseif ($theme == 'couponxl') {
		couponapi_couponxl_resync_logos($config, $resync);
	} elseif ($theme == 'couponxxl') {
		couponapi_couponxxl_resync_logos($config, $resync);
	} elseif ($theme == 'cp' or substr($theme, 0, 2) === "CP") {
		couponapi_couponpress_resync_logos($config, $resync);
	} elseif ($theme == 'rehub' or $theme == 'rehub-theme') {
		couponapi_rehub_resync_logos($config, $resync);
	} elseif ($theme == 'wpcoupon' or $theme == 'wp-coupon' or $theme == 'wp-coupon-pro') {
		couponapi_wpcoupon_resync_logos($config, $resync);
	} elseif ($theme == 'coupon-mart') {
		couponapi_couponmart_resync_logos($config, $resync);
	} elseif ($config['generic_import_image'] == 'brandlogos_image') {
		couponapi_generic_theme_resync_logos($config, $resync);
	}

	wp_defer_term_counting(false);
	$wpdb->query('COMMIT;');
	$wpdb->query('SET autocommit = 1;');
}
add_action('couponapi_process_brandlogos_resync_event', 'couponapi_process_brandlogos_resync');



function couponapi_couponmart_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = get_terms(array('taxonomy' => 'coupon_store', 'hide_empty' => false));

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;
		$current_term_meta = array();
		foreach (get_term_meta($store->term_id) as $key => $value) {
			$current_term_meta[$key] = $value[0];
		}
		$term_meta = array_merge(array('_wpc_store_image' => '', '_wpc_store_url' => ''), $current_term_meta);
		$store_image_url = explode('/', $term_meta['_wpc_store_image']);
		$store_image = array_pop($store_image_url);
		$store_url = $term_meta['_wpc_store_url'];
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");

			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image']);
			$term_meta['_wpc_store_image'] = wp_get_attachment_image_url($store_logo_id, 'full');
			update_term_meta($store->term_id, "_wpc_store_image", $term_meta['_wpc_store_image']);
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Logos.')");
}


function couponapi_clipmydeals_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = get_terms(array('taxonomy' => 'stores', 'hide_empty' => false));

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;
		if($config['use_logos'] == 'if_empty') $config['use_logos'] = 'off';
		$term_meta = array_merge(array('store_logo' => '', 'store_url' => ''), get_option("taxonomy_term_{$store->term_id}"));
		$store_image_url = explode('/', $term_meta['store_logo']);
		$store_image = array_pop($store_image_url);
		$store_url = $term_meta['store_url'];
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");

			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image']);
			$term_meta['store_logo'] = wp_get_attachment_image_url($store_logo_id, 'full');
			update_option("taxonomy_term_{$store->term_id}", $term_meta);
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_clipper_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = get_terms(array('taxonomy' => 'stores', 'hide_empty' => false));

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;

		$store_logo_id = intval($wpdb->get_var("SELECT `meta_value` FROM `{$wpdb->prefix}clpr_storesmeta` WHERE `meta_key` = 'clpr_store_image_id' AND `stores_id` = {$store->term_id}"));
		$store_image = get_post($store_logo_id)->post_title ?? false;
		$store_url = $wpdb->get_var("SELECT `meta_value` FROM `{$wpdb->prefix}clpr_storesmeta` WHERE `meta_key` = 'clpr_store_url' AND `stores_id` = {$store->term_id}");
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");

			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image']);
			$isImageSaved = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}clpr_storesmeta` WHERE `meta_key` = 'clpr_store_image_id' AND `stores_id` = {$store->term_id}");
			$update_query = "UPDATE `{$wpdb->prefix}clpr_storesmeta` SET `meta_value` = '{$store_logo_id}' WHERE `meta_key` = 'clpr_store_image_id' AND `stores_id` = {$store->term_id}";
			$insert_query = "INSERT INTO `{$wpdb->prefix}clpr_storesmeta` (`stores_id`, `meta_key`, `meta_value`) VALUES ({$store->term_id}, 'clpr_store_image_id', '$store_logo_id')";
			$wpdb->query($isImageSaved ? $update_query : $insert_query);
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_couponxl_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = $wpdb->get_results("SELECT `ID`, `post_name` FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'store' AND `post_status` = 'publish'");

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->post_name, $resync['store_slugs'])) continue;

		$store_logo_id = get_post_meta($store->ID, '_thumbnail_id', true);
		$store_image = get_post($store_logo_id)->post_title ?? false;
		$store_url = get_post_meta($store->ID, 'store_link', true);
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->post_name')");

			couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image'], $store->ID);
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_couponxxl_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = $wpdb->get_results("SELECT `ID`, `post_name` FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'store' AND `post_status` = 'publish'");

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->post_name, $resync['store_slugs'])) continue;

		$store_logo_id = get_post_meta($store->ID, '_thumbnail_id', true);
		$store_image = get_post($store_logo_id)->post_title ?? false;
		$store_url = get_post_meta($store->ID, 'store_link', true);
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->post_name')");

			couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image'], $store->ID);
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_couponer_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = $wpdb->get_results("SELECT `ID`, `post_name` FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'shop' AND `post_status` = 'publish'");

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->post_name, $resync['store_slugs'])) continue;

		$store_logo_id = get_post_meta($store->ID, '_thumbnail_id', true);
		$store_image = get_post($store_logo_id)->post_title ?? false;
		$store_url = get_post_meta($store->ID, 'shop_link', true);
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->post_name')");

			couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image'], $store->ID);
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_couponpress_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = get_terms(array('taxonomy' => 'store', 'hide_empty' => false));
	$core_values = get_option('core_admin_values', array());

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;

		$store_image_url = explode('/', $core_values["storeimage_{$store->term_id}"]);
		$store_image = array_pop($store_image_url);
		$store_url = $core_values["category_website_{$store->term_id}"];
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");

			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image']);
			$core_values["storeimage_{$store->term_id}"] = wp_get_attachment_image_url($store_logo_id, 'full');
		}
	}

	update_option('core_admin_values', $core_values);
	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_rehub_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = get_terms(array('taxonomy' => 'dealstore', 'hide_empty' => false));

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;

		$store_image_url = explode('/', get_term_meta($store->term_id, 'brandimage', true));
		$store_image = array_pop($store_image_url);
		$store_url = get_term_meta($store->term_id, 'brand_url', true);
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");

			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image']);
			update_term_meta($store->term_id, 'brandimage', wp_get_attachment_image_url($store_logo_id, 'full'));
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_couponis_resync_logos($config, $resync)
{

	global $wpdb;
	$storeTerms = get_terms(array('taxonomy' => 'coupon-store', 'hide_empty' => false));

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;

		$store_logo_id = get_term_meta($store->term_id, 'store_image', true);
		$store_image = get_post($store_logo_id)->post_title ?? false;
		$store_url = get_term_meta($store->term_id, 'store_url', true);
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");

			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image']);
			update_term_meta($store->term_id, 'store_image', $store_logo_id);
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_couponhut_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = get_terms(array('taxonomy' => 'deal_company', 'hide_empty' => false));

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;

		$store_logo_id = get_term_meta($store->term_id, 'company_logo', true);
		$store_image = get_post($store_logo_id)->post_title ?? false;
		$store_url = get_term_meta($store->term_id, 'company_website', true);
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");

			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image']);
			update_term_meta($store->term_id, 'company_logo', $store_logo_id);
			update_term_meta($store->term_id, '_company_logo', 'field_55073707dee91');
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}


function couponapi_wpcoupon_resync_logos($config, $resync)
{
	global $wpdb;
	$storeTerms = get_terms(array('taxonomy' => 'coupon_store', 'hide_empty' => false));

	$count = 0;
	foreach ($storeTerms as $store) {

		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;

		$store_image_url = explode('/', get_term_meta($store->term_id, '_wpc_store_image', true));
		$store_image = array_pop($store_image_url);
		$store_url = get_term_meta($store->term_id, '_wpc_store_url', true);
		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));

		if ($brand and couponapi_replace_brandlogo_image($store_image, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");

			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), $config['use_grey_image']);
			update_term_meta($store->term_id, '_wpc_store_image_id', $store_logo_id);
			update_term_meta($store->term_id, '_wpc_store_image', wp_get_attachment_image_url($store_logo_id, 'full'));
		}
	}

	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}

function couponapi_generic_theme_resync_logos($config, $resync)
{
	global $wpdb;

	$args = array(
		'taxonomy' => $config['store'], 
		'meta_query' => array(
			array(
				'key' => 'couponapi_store', 
				'value' => 1, 
				'compare' => '=', 
			),
		),
	);

	$storeTerms = get_terms($args);
	$count = 0;
	foreach ($storeTerms as $store) {
		if ($resync['store_slugs'] and !in_array($store->slug, $resync['store_slugs'])) continue;
		$store_meta =  get_term_meta($store->term_id);
		$store_url = $store_meta['capi_store_url'][0];
		$old_attach_id = $store_meta['capi_store_logo'][0];
		$old_image_url = wp_get_attachment_url($old_attach_id);
		if($resync['empty_logos'] and !empty($old_image_url)) continue;

		$brand = str_replace("www.", "", parse_url($store_url, PHP_URL_HOST) ?: parse_url($store_url, PHP_URL_PATH));
		
		if ($brand and couponapi_replace_brandlogo_image(false, $resync)) {
			$count++;
			$store->name = esc_sql($store->name);
			$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'debug', 'Resyncing Logo for $store->name')");
			wp_delete_attachment($old_attach_id, true);
			$old_image_url = $old_image_url ?: '';
			$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, false, $brand), 'off');
			$new_image_url = wp_get_attachment_url($store_logo_id);

			update_term_meta($store->term_id, 'capi_store_logo', $store_logo_id);

			$posts = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}term_relationships` WHERE term_taxonomy_id = {$store->term_id}");
			foreach ($posts as $post) {
				$post_id = $post->object_id;

				$post_content = get_post_field('post_content', $post_id);
				if ($config['set_as_featured_image'] == 'On') {
					set_post_thumbnail($post_id, $store_logo_id);
				}
				if (empty($old_image_url)) {
					$default_template = '<p class="has-medium-font-size">{{label}}</p>
	
												<hr/>
												
												<table style="border: none;border-collapse: collapse;">
												<tr>
												
													<td style="width: 64%;border: none;">
														<strong>Store</strong>: {{store}}<br>
														<strong>Coupon Code</strong>: {{code}}<br>
														<strong>Expiry</strong>: {{expiry}}
													</td>
													<td style="width: 36%;border: none;"> 
														
															<figure>{{image}}</figure><br>
															<div class="wp-block-buttons">
															<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-fill" style="text-align: center;"><a class="wp-block-button__link wp-element-button" href="{{link}}">Visit Website</a></div>
															</div>
														
													</td>
												
												</tr>
												
												</table>
												{{description}}
												';
					$content = get_theme_mod('custom_coupon_template', $default_template);
					$post_meta = get_post_meta($post_id);
					$replace_variable_list_keys = array('{{description}}', '{{link}}', '{{label}}', '{{store}}', '{{code}}', '{{start_date}}', '{{expiry}}', '{{image}}', '{{image_url}}');
					$replace_variable_list_values =
						array(
							get_post_field('post_excerpt', $post_id),
							$post_meta['capi_link'][0],
							$post_meta['capi_label'][0],
							$store->name,
							$post_meta['capi_code'][0],
							$post_meta['capi_start_date'][0],
							$post_meta['capi_valid_till'][0],
							($new_image_url ? "<img class='wp-image-351' src='" . $new_image_url . "' />" : ""),
							($new_image_url ?: "")
						);
					$content = str_replace($replace_variable_list_keys, $replace_variable_list_values, $content);
				} else {
					$content = str_replace($old_image_url, $new_image_url, $post_content);
				}

				$post_data = array(
					'ID' => $post_id,
					'post_content' => $content
				);
				wp_update_post($post_data);
				update_post_meta($post_id, 'capi_image_attach_id', $store_logo_id);
			}
		}
	}
	$wpdb->query("INSERT INTO `{$wpdb->prefix}couponapi_logs` (`microtime`, `msg_type`, `message`) VALUES (" . microtime(true) . ", 'success', 'Resynced - $count Store Logos.')");
}

function couponapi_replace_brandlogo_image($store_image, $resync)
{
	return ( // IF STORE and store present in slug list
		$resync['store_slugs']
	) or ( // IF EMPTY IMAGE and resync EMPTY IMAGE is checked
		$resync['empty_logos'] and !$store_image
	) or ( // IF GREY IMAGE and resync GREY IMAGE is checked
		$resync['grey_logos'] and $store_image and in_array(substr($store_image, 0, 37), array('capi_03b66b38578f7b52cba1fe8ec70718eb', 'capi_cd2e107cf85ee0fadb50ddddd8535da1'))
	) or ( // IF CUSTOM IMAGE and resync CUSTOM IMAGE is checked
		$resync['custom_logos'] and $store_image and !(in_array(strlen($store_image), array(37, 41)) and substr($store_image, 0, 5) === "capi_")
	);
}
