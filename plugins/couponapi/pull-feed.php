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
global $coupons_to_be_inserted;
if (!defined('ABSPATH')) exit; // Exit if accessed directly

function couponapi_pull_incremental_feed() {

	set_time_limit(0);

	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$config = $wpdb->get_row("SELECT
																	(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'API_KEY') API_KEY,
																	(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'last_extract') last_extract
																FROM dual");

	if(empty($config->API_KEY)) {
		wp_clear_scheduled_hook('couponapi_pull_incremental_feed_event');
		return '<div class="notice notice-error is-dismissible"><p>'.__("Cannot pull feed without API Key.","couponapi").'</p></div>';
	}

	if (empty($config->last_extract)) {
		$config->last_extract = '978307200';
	}

	$feedFile = "https://couponapi.org/api/getFeed/?API_KEY=" . $config->API_KEY . "&incremental=1&last_extract=" . $config->last_extract . "&format=json";

	$sql = "INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Pulling Feed using Coupon API')";
	$wpdb->query($sql);

	$sql = "INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','$feedFile')";
	$wpdb->query($sql);

	$wpdb->query('SET autocommit = 0;');

	$result = couponapi_save_json_to_db($feedFile);

	if ($result['totalCounter'] == 0) {
		// If the account is temporarily inactive, we do not get any offers in the file.
		// Not updating the last_extract time in such situations, prevents loss of data after re-activation.
		$wpdb->query( 'SET autocommit = 1;' );
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'success','No updates found in this extract')");
		return '<div class="notice notice-info is-dismissible"><p>'.__("No updates found in this extract.","couponapi").'</p></div>';
	} elseif(!$result['error']) {
		$wpdb->query("REPLACE INTO ".$wp_prefix."couponapi_config (name,value) VALUES ('last_extract','".time()."') ");
		$wpdb->query( 'COMMIT;' );
		$wpdb->query( 'SET autocommit = 1;' );
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Starting upload process. This may take several minutes...') ");
		wp_schedule_single_event( time() , 'couponapi_process_batch_event'); // process next batch
		return '<div class="notice notice-info is-dismissible"><p>'.__("Upload process is running in background. Refresh Logs to see current status.","couponapi").'</p></div>';
	} else {
		$wpdb->query( 'ROLLBACK' );
		$wpdb->query( 'SET autocommit = 1;' );
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES
											(".microtime(true).",'debug','".esc_sql($result['error_msg'])."'),
											(".microtime(true).",'error','Error uploading feed to local database')");
		return '<div class="notice notice-error is-dismissible"><p>'.__("Error uploading feed to local database.","couponapi").'</p></div>';
	}
}

function couponapi_save_json_to_db($feedURL) {
	global $coupons_to_be_inserted;
	$coupons_to_be_inserted = array();

	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$sql = "INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Preparing to Save to DB')";
	$wpdb->query($sql);

	$result = array('error' => false);
	$totalCounter = 0;

	$response = json_decode(file_get_contents($feedURL), true);

	if (!$response['result']) {

		$result['error'] = true;
		$result['error_msg'] = $response['error'];
		return $result;
	} else {

		$coupons = $response['offers']; //gets all the coupons in array

		$coupon_keys = array('offer_id', 'title', 'description', 'label', 'code', 'featured', 'source', 'url', 'deeplink', 'affiliate_link', 'cashback_link', 'image_url', 'brand_logo', 'type', 'store', 'merchant_home_page', 'categories', 'primary_location', 'start_date', 'end_date', 'status');
		$default = array_fill_keys($coupon_keys, '');
		foreach ($coupons as $id => $coupon) {			//coupon as key=>value array
			$coupon = array_merge($default, $coupon);
			$result = couponapi_save_coupon_to_queue($coupon);
			if ($result['error']) {
				return $result;
			}
			$totalCounter++; //keeps track of total coupons
		}

		$result = couponapi_insert_coupons_to_db();
		if ($result['error']) {
			return $result;
		}
		$result['totalCounter'] = $totalCounter;
	}
	return 	$result;
}

function couponapi_save_csv_to_db($feedFile) {
	global $coupons_to_be_inserted;
	global $wpdb;

	$coupons_to_be_inserted = array(); //initialize the queue
	$wp_prefix = $wpdb->prefix;

	$sql = "INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Preparing to Save to DB')";
	$wpdb->query($sql);

	$result = array('error' => false);
	$totalCounter = 0;

	if (($handle = fopen($feedFile, 'r')) === FALSE) {

		$result['error'] = true;
		$result['error_msg'] = "cannot open" . $feedFile . "file";
		return $result;
	} else { // $feedFile is set by API or File Upload

		$topheader = fgetcsv($handle, 10000, ','); //gets the header (key)
		$topheader_db = array(
			'offer_id', 'title', 'description', 'label', 'code', 'featured', 'source', 'url', 'deeplink', 'affiliate_link',
			'cashback_link', 'image_url', 'brand_logo', 'type', 'store', 'merchant_home_page', 'categories', 'primary_location', 'start_date', 'end_date', 'status'
		);
		$topheader_diff = array_diff($topheader_db, $topheader);
		if (!empty($topheader_diff)) {

			$result['error'] = true;
			$result['error_msg'] = "header error - missing colums (" . implode(",", $topheader_diff) . ")";
			return $result;
		} else {

			while (($row = fgetcsv($handle, 10000, ',')) !== FALSE) {

				$coupon = array_combine($topheader, $row); //coupon as key=>value array

				if (empty($coupon['offer_id'])) {
					$result['error'] = true;
					$result['error_msg'] = "offer_id missing for coupon number " . ($totalCounter + 1) . " (row number " . ($totalCounter + 2) . ")";
					return $result;
				}
				if (empty($coupon['title']) and $coupon['status'] != 'suspended' and $coupon['status'] != 'updated') {
					$result['error'] = true;
					$result['error_msg'] = "title missing for coupon number " . ($totalCounter + 1) . " (row number " . ($totalCounter + 2) . ")";
					return $result;
				}
				if (!empty($coupon['start_date']) and empty(strtotime($coupon['start_date']))) {
					$result['error'] = true;
					$result['error_msg'] = "invalid start date for coupon number " . ($totalCounter + 1) . " (row number " . ($totalCounter + 2) . ")";
					return $result;
				}
				if (!empty($coupon['end_date']) and empty(strtotime($coupon['end_date']))) {
					$result['error'] = true;
					$result['error_msg'] = "invalid end date for coupon number " . ($totalCounter + 1) . " (row number " . ($totalCounter + 2) . ")";
					return $result;
				}

				$result  = couponapi_save_coupon_to_queue($coupon);
				if ($result['error']) {
					return $result;
				}
				$totalCounter++;	//keeps track of total coupons
			}
			$result = couponapi_insert_coupons_to_db();
			if ($result['error']) {
				return $result;
			}
			$result['totalCounter'] = $totalCounter;
		}
	}
	return 	$result;
}

function couponapi_save_coupon_to_queue($coupon) {

	global $coupons_to_be_inserted;
	array_push($coupons_to_be_inserted, $coupon);
	$result = array('error' => false);

	if (count($coupons_to_be_inserted) >= 500) { //Fire Query to save coupons to db if no. of coupons >500
		$result = couponapi_insert_coupons_to_db();
		if ($result['error']) {
			return $result;
		}
	}
	return $result;
}


function couponapi_insert_coupons_to_db() {
	global $coupons_to_be_inserted;
	global $wpdb;

	$wp_prefix = $wpdb->prefix;

	$result = array('error' => false);
	if (count($coupons_to_be_inserted) === 0) {
		return $result;
	}

	$sql_insert = "INSERT INTO `" . $wp_prefix . "couponapi_upload` (`offer_id`, `title`, `description`, `label`, `code`, `featured`, `source`, `url`, `deeplink`, `affiliate_link`, `cashback_link`, `image_url`, `brand_logo`, `type`, `store`, `merchant_home_page`, `categories`, `locations`, `start_date`, `end_date`, `status`) VALUES ";
	$sep = '';

	foreach ($coupons_to_be_inserted as $coupon) {
		$sql_insert .= $sep . "(" . $coupon['offer_id'] . ",
									'" . esc_sql($coupon['title']) . "',
									'" . esc_sql($coupon['description']) . "',
									'" . esc_sql($coupon['label']) . "',
									'" . esc_sql($coupon['code']) . "',
									'" . esc_sql($coupon['featured']) . "',
									'" . esc_sql($coupon['source']) . "',
									'" . esc_sql($coupon['url']) . "',
									'" . esc_sql($coupon['deeplink']) . "',
									'" . esc_sql($coupon['affiliate_link']) . "',
									'" . esc_sql($coupon['cashback_link']) . "',
									'" . esc_sql($coupon['image_url']) . "',
									" . (!empty($coupon['brand_logo']) ? "'" . esc_sql($coupon['brand_logo']) . "'" : 'NULL') . ",
									'" . esc_sql($coupon['type']) . "',
									'" . esc_sql($coupon['store']) . "',
									'" . esc_sql($coupon['merchant_home_page']) . "',
									'" . (array_key_exists("standard_categories",$coupon) ?esc_sql($coupon['standard_categories']):esc_sql($coupon['categories'])) . "',
									'" . esc_sql($coupon['primary_location'] == 'multi country' ? '' : $coupon['primary_location']) . "',
									" . (empty($coupon['start_date']) ? 'NULL' : "'" . date('Y-m-d', strtotime($coupon['start_date'])) . "'") . ",
									" . (empty($coupon['end_date']) ? 'NULL' : "'" . date('Y-m-d', strtotime($coupon['end_date'])) . "'") . ",
									'" . esc_sql($coupon['status']) . "')";
		$sep = ',';
	}

	if ($wpdb->query($sql_insert) === false) {

		$result['error'] = true;
		$result['error_msg'] = $wpdb->last_error . PHP_EOL . 'Query: ' . $sql_insert;

		$wpdb->print_error();
		$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','" . esc_sql($sql_insert) . "')");
		$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'error','" . esc_sql($result['error_msg']) . "')");
		return $result;
	} else {

		$coupons_to_be_inserted = array(); //reset coupon array
		return $result;
	}
}


function couponapi_process_batch() {
	global $wpdb;

	$wp_prefix = $wpdb->prefix;

	$theme = get_template();
	// Default config
	$config = array(
		'import_images' => 'Off',
		'import_locations' => '',
		'autopilot' => 'Off',
		'cashback' => 'Off',
		'batch_size' => 500,
		'brandlogos_key' => '',
		'use_grey_image' => 'on',
		'size'      => 'horizontal',
		'store' => 'post_tag',
		'category' => 'category',
		'code_text' => '(not required)',
		'expiry_text' => 'Currently Active',
		'generic_import_image' => 'off',
		'set_as_featured_image' => 'Off',
		'use_logos' => in_array($theme, array('clipmydeals', 'couponis')) ? 'if_empty' : 'on'
	);
	$result = $wpdb->get_results("SELECT * FROM " . $wp_prefix . "couponapi_config WHERE name IN ('import_images','cashback','batch_size','brandlogos_key', 'use_grey_image', 'use_logos', 'size', 'import_locations','ctype_code','ctype_deal','store','category','code_text','expiry_text','generic_import_image','set_as_featured_image')");
	foreach ($result as $row) {
		$config[$row->name] = $row->value;
	}

	if (empty($config['batch_size'])) {
		$config['batch_size'] = 500;
	}

	wp_defer_term_counting(true);
	$wpdb->query('SET autocommit = 0;');

	$coupons = $wpdb->get_results("SELECT * FROM " . $wp_prefix . "couponapi_upload ORDER BY upload_date LIMIT 0,{$config['batch_size']}");

	if ($theme == 'clipmydeals') {
		couponapi_clipmydeals_process_batch($config, $coupons);
	} elseif ($theme == 'clipper') {
		couponapi_clipper_process_batch($config, $coupons);
	} elseif ($theme == 'couponxl') {
		couponapi_couponxl_process_batch($config, $coupons);
	} elseif ($theme == 'couponxxl') {
		couponapi_couponxxl_process_batch($config, $coupons);
	} elseif ($theme == 'couponer') {
		couponapi_couponer_process_batch($config, $coupons);
	} elseif ($theme == 'rehub' or $theme == 'rehub-theme') {
		couponapi_rehub_process_batch($config, $coupons);
	} elseif ($theme == 'wpcoupon' or $theme == 'wp-coupon' or $theme == 'wp-coupon-pro') {
		couponapi_wpcoupon_process_batch($config, $coupons);
	} elseif ($theme == 'CP' or $theme == 'cp' or $theme == 'CPq' or substr($theme, 0, 2) === "CP" or strpos(wp_get_theme()->get('AuthorURI'), "premiumpress") !== false) {
		couponapi_couponpress_process_batch($config, $coupons);
	} elseif ($theme == 'mts_coupon') {
		couponapi_mtscoupon_process_batch($config, $coupons);
	} elseif ($theme == 'couponis') {
		couponapi_couponis_process_batch($config, $coupons);
	} elseif ($theme == 'couponhut') {
		couponapi_couponhut_process_batch($config, $coupons);
	} elseif ($theme == 'coupon-mart') {
		couponapi_couponmart_process_batch($config, $coupons);
	} else {
		generic_theme_process_batch($config,$coupons);
	}

	wp_defer_term_counting(false);
	$wpdb->query('COMMIT;');
	$wpdb->query('SET autocommit = 1;');

	$remainingCoupons = $wpdb->get_var("SELECT count(1) FROM " . $wp_prefix . "couponapi_upload");
	if ($remainingCoupons > 0) {
		wp_schedule_single_event(time(), 'couponapi_process_batch_event'); // process next batch
	} else {
		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_logs WHERE logtime < CURDATE() - INTERVAL 30 DAY");
		$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'success','All offers processed successfully.')");
	}
}

function couponapi_couponmart_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	
	
	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon (" . $coupon->offer_id . ")')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupon',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			$append = false;
			foreach ($cat_names as $cat) {
				if (!term_exists($cat, 'coupon_category')) {
					$term = wp_insert_term($cat, 'coupon_category'); 					
				}
				wp_set_object_terms($post_id, $cat, 'coupon_category', $append);
				$append = true;
			}

			$str_names = explode(',', $coupon->store);
			$append = false;
			foreach ($str_names as $str) {
				// Create New Store
				if (!term_exists($str, 'coupon_store')) {
					$term = wp_insert_term($str, 'coupon_store'); // , $args third parameter
					if (!is_wp_error($term)) { // Term did not exist. Got inserted now.
						
						// Update Meta Info
						update_term_meta($term['term_id'], '_wpc_store_url', $coupon->merchant_home_page);//store taxonomy args in wp_options
						if (!empty($coupon->brand_logo) and $config['use_logos'] != 'off') {
							update_term_meta($term['term_id'], '_wpc_store_image', wp_get_attachment_image_url(couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image']), 'full'));
						}
					}
				} 
				wp_set_object_terms($post_id, $str, 'coupon_store', $append);
				$append = true;
			}
			
			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, '_wpc_coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, '_wpc_coupon_type_code', $coupon->code);

			update_post_meta($post_id, '_wpc_destination_url', $coupon->affiliate_link);

			update_post_meta($post_id, '_wpc_start_on', (empty($coupon->start_date) ? '' : strtotime($coupon->start_date)));
			update_post_meta($post_id, '_wpc_expires', (empty($coupon->end_date) ? '' : strtotime($coupon->end_date)));
			update_post_meta($post_id, '_wpc_coupon_save', $coupon->label ?: couponapi_badge_text());
			set_post_thumbnail($post_id,couponapi_import_image($coupon->image_url));
			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'coupon_category', $append);
					$append = true;
				}
			}

			if (!empty($coupon->store)) {
				$str_names = explode(',', $coupon->store);
				$append = false;
				foreach ($str_names as $str) {
					wp_set_object_terms($post_id, $str, 'coupon_store', $append);
					$append = true;
				}
			}

			if (!empty($coupon->type)) {
				update_post_meta($post_id, '_wpc_coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			}
			if (!empty($coupon->code)) {
				update_post_meta($post_id, '_wpc_coupon_type_code', $coupon->code);
			}

			if (!empty($coupon->affiliate_link)) {
				update_post_meta($post_id, '_wpc_destination_url', $coupon->affiliate_link);
			}
	
			$start_date = (!empty($coupon->start_date)) ? strtotime($coupon->start_date) : get_post_meta($post_id, '_wpc_start_on', true);
			$end_date = (!empty($coupon->end_date)) ? strtotime($coupon->end_date) : get_post_meta($post_id, '_wpc_expires', true);

			update_post_meta($post_id, '_wpc_start_on', (empty($start_date) ? '' : $start_date));
			if (empty($end_date)) {
				update_post_meta($post_id, '_wpc_expires', '');
			} else {
				update_post_meta($post_id, '_wpc_expires', $end_date);
			}

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}
function couponapi_clipmydeals_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	if($config['use_logos'] == 'if_empty') $config['use_logos'] = 'off';

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'offer_categories',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$stores = array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'stores',
		'hide_empty' => false
	));
	foreach ($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}
	if ($config['import_locations'] == 'On' && get_theme_mod('location_taxonomy', false)) {
		$locations = array();
		$locationTerms = get_terms(array(
			'taxonomy' => 'locations',
			'hide_empty' => false
		));
		foreach ($locationTerms as $term) {
			$locations[$term->name] = $term->slug;
		}
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon (" . $coupon->offer_id . ")')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupons',
				'post_date'		 => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			$append = false;
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'offer_categories', $append);
				$append = true;
			}

			$str_names = explode(',', $coupon->store);
			$append = false;
			foreach ($str_names as $str) {
				// Create New Store
				if (!term_exists($str, 'stores')) {
					$term = wp_insert_term($str, 'stores'); // , $args third parameter
					if (!is_wp_error($term)) { // Term did not exist. Got inserted now.
						$stores[$str] = get_term($term['term_id'], "stores")->slug;

						// Update Meta Info
						$meta_args = array("store_url"	=> $coupon->merchant_home_page); //store taxonomy args in wp_options
						if (!empty($coupon->brand_logo) and $config['use_logos'] != 'off') {
							$meta_args['store_logo'] = wp_get_attachment_image_url(couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image']), 'full');
						}
						update_option("taxonomy_term_" . $term['term_id'], $meta_args);
						$wpdb->query("INSERT INTO " . $wp_prefix . "cmd_store_to_domain (store_id, domain) VALUES (" . $term['term_id'] . ",'" . str_replace("www.", "", parse_url($meta_args["store_url"], PHP_URL_HOST)) . "')");
					}
				}
				wp_set_object_terms($post_id, $str, 'stores', $append);
				$append = true;
			}

			if ($config['import_locations'] == 'On' && get_theme_mod('location_taxonomy', false)) {
				$loc_names = explode(',', $coupon->locations);
				$append = false;
				foreach ($loc_names as $loc) {
					wp_set_object_terms($post_id, $loc, 'locations', $append);
					$append = true;
				}
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'cmd_type', ($coupon->type == 'Code' ? 'code' : 'deal'));
			update_post_meta($post_id, 'cmd_code', $coupon->code);

			if ($config['cashback'] == 'On') {
				update_post_meta($post_id, 'cmd_url', str_replace("{{replace_userid_here}}", "[click_id]", $coupon->cashback_link));
			} else {
				update_post_meta($post_id, 'cmd_url', $coupon->affiliate_link);
			}

			if ($config['import_images'] == 'On') {
				update_post_meta($post_id, 'cmd_image_url', $coupon->image_url);
			}
			update_post_meta($post_id, 'cmd_start_date', (empty($coupon->start_date) ? '' : $coupon->start_date));
			if (empty($coupon->end_date)) {
				update_post_meta($post_id, 'cmd_valid_till', '');
			} else {
				update_post_meta($post_id, 'cmd_valid_till', $coupon->end_date);
			}
			update_post_meta($post_id, 'cmd_display_priority', 0);
			if (empty(get_post_meta($post_id, 'cmd_badge', true))) {
				update_post_meta($post_id, 'cmd_badge', $coupon->label ?: couponapi_badge_text());
			}
			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_categories', $append);
					$append = true;
				}
			}

			if (!empty($coupon->store)) {
				$str_names = explode(',', $coupon->store);
				$append = false;
				foreach ($str_names as $str) {
					wp_set_object_terms($post_id, $str, 'stores', $append);
					$append = true;
				}
			}

			if ($coupon->locations && $config['import_locations'] == 'On' && get_theme_mod('location_taxonomy', false)) {
				$loc_names = explode(',', $coupon->locations);
				$append = false;
				foreach ($loc_names as $loc) {
					wp_set_object_terms($post_id, $loc, 'locations', $append);
					$append = true;
				}
			}
			if (!empty($coupon->type)) {
				update_post_meta($post_id, 'cmd_type', ($coupon->type == 'Code' ? 'code' : 'deal'));
			}
			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'cmd_code', $coupon->code);
			}
			if ($config['cashback'] == 'On') {
				if (!empty($coupon->cashback_link)) {
					update_post_meta($post_id, 'cmd_url', str_replace("{{replace_userid_here}}", "[click_id]", $coupon->cashback_link));
				}
			} else {
				if (!empty($coupon->affiliate_link)) {
					update_post_meta($post_id, 'cmd_url', $coupon->affiliate_link);
				}
			}
			if ($config['import_images'] == 'On' and !empty($coupon->image_url)) {
				update_post_meta($post_id, 'cmd_image_url', $coupon->image_url);
			}


			$start_date = (!empty($coupon->start_date)) ? $coupon->start_date : get_post_meta($post_id, 'cmd_start_date', true);
			$end_date = (!empty($coupon->end_date)) ? $coupon->end_date : get_post_meta($post_id, 'cmd_valid_till', true);

			update_post_meta($post_id, 'cmd_start_date', (empty($start_date) ? '' : $start_date));
			if (empty($end_date)) {
				update_post_meta($post_id, 'cmd_valid_till', '');
			} else {
				update_post_meta($post_id, 'cmd_valid_till', $end_date);
			}
			update_post_meta($post_id, 'cmd_display_priority', 0);
			update_post_meta($post_id, 'cmd_badge', $coupon->label ?: couponapi_badge_text());

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_clipper_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'coupon_category',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$stores = array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'stores',
		'hide_empty' => false
	));
	foreach ($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon (" . $coupon->offer_id . ")')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupon',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'coupon_category', true);
				wp_set_object_terms($post_id, $cat, 'coupon_tag', true);
			}

			$store_names = explode(',', $coupon->store);
			foreach ($store_names as $str) {
				if (!term_exists($str, 'stores')) {
					$term = wp_insert_term($str, 'stores');
					if (!is_wp_error($term)) {
						$stores[$str] = get_term($term['term_id'], "stores")->slug;
						if (!empty($coupon->brand_logo) and $config['use_logos'] == 'on') {
							$store_logo_id = couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image']);
							$wpdb->query("INSERT INTO `{$wp_prefix}clpr_storesmeta` (`stores_id`, `meta_key`, `meta_value`) VALUES ({$term['term_id']}, 'clpr_store_image_id', '{$store_logo_id}')");
						}
						$wpdb->query("INSERT INTO `{$wp_prefix}clpr_storesmeta` (`stores_id`, `meta_key`, `meta_value`) VALUES ({$term['term_id']}, 'clpr_store_url', '{$coupon->merchant_home_page}')");
					}
				}
				wp_set_object_terms($post_id, $str, 'stores', true);
			}

			wp_set_object_terms($post_id, ($coupon->type == 'Code' ? 'coupon-code' : 'deal'), 'coupon_type', true);

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'clpr_coupon_aff_url', $coupon->affiliate_link);
			update_post_meta($post_id, 'clpr_coupon_code', $coupon->code);
			if (!empty($coupon->end_date)) {
				update_post_meta($post_id, 'clpr_expire_date', $coupon->end_date);
			}
			update_post_meta($post_id, 'clpr_featured', $coupon->featured);
			update_post_meta($post_id, 'clpr_votes_percent', '100');
			update_post_meta($post_id, 'clpr_coupon_aff_clicks', '0');

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->category)) {
				$cat_names = explode(',', $coupon->category);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'coupon_category', $append);
					wp_set_object_terms($post_id, $cat, 'coupon_tag', $append);
					$append = true;
				}
			}

			if (!empty($coupon->store)) {
				$store_names = explode(',', $coupon->store);
				$append = false;
				foreach ($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'stores', $append);
					$append = true;
				}
			}

			if (!empty($coupon->type)) {
				wp_set_object_terms($post_id, ($coupon->type == 'Code' ? 'coupon-code' : 'deal'), 'coupon_type', false);
			}
			if (!empty($coupon->affiliate_link)) {
				update_post_meta($post_id, 'clpr_coupon_aff_url', $coupon->affiliate_link);
			}
			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'clpr_coupon_code', $coupon->code);
			}
			if (!empty($coupon->end_date)) {
				update_post_meta($post_id, 'clpr_expire_date', $coupon->end_date);
			}
			if (!empty($coupon->featured)) {
				update_post_meta($post_id, 'clpr_featured', $coupon->featured);
			}

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_couponxl_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'offer_cat',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$thumbnails = $stores = array();
	$sql_stores = "SELECT `ID`, `post_title`, (SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `meta_key`='_thumbnail_id' AND `ID` =`post_id`) AS `logo_id` FROM `{$wp_prefix}posts` WHERE `post_type` = 'store'";
	$result_stores = $wpdb->get_results($sql_stores);
	foreach ($result_stores as $str) {
		$stores[$str->ID] = $str->post_title;
		$thumbnails[$str->post_title] = $str->logo_id;
	}

	$locations = array();
	$locationTerms = get_terms(array(
		'taxonomy' => 'location',
		'hide_empty' => false
	));
	foreach ($locationTerms as $term) {
		$locations[$term->name] = $term->slug;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon (" . $coupon->offer_id . ")')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'offer',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'offer_cat', true);
			}

			$store_id = array_search($coupon->store, $stores);
			if ($store_id) {
				update_post_meta($post_id, 'offer_store', $store_id);
			} else {
				$store_data = array(
					'ID'             => '',
					'post_title'     => $coupon->store,
					'post_status'    => 'publish',
					'post_type'      => 'store',
					'post_author'    => get_current_user_id()
				);
				$store_id = wp_insert_post($store_data);
				if (!empty($coupon->brand_logo) and $config['use_logos'] == 'on' and $store_id != 0) {
					$thumbnails[$coupon->store] = couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image'], $store_id);
					set_post_thumbnail($store_id, $thumbnails[$coupon->store]);
				}
				update_post_meta($store_id, 'store_link', $coupon->merchant_home_page);
				$stores[$store_id] = $coupon->store;
				update_post_meta($post_id, 'offer_store', $store_id);
			}

			if ($config['import_locations'] == 'On') {
				$loc_names = explode(',', $coupon->locations);
				foreach ($loc_names as $loc) {
					wp_set_object_terms($post_id, $loc, 'location', true);
				}
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'coupon_code', $coupon->code);
			update_post_meta($post_id, 'coupon_url', $coupon->url);
			update_post_meta($post_id, 'coupon_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_sale', $coupon->affiliate_link);
			update_post_meta($post_id, 'offer_start', strtotime($coupon->start_date));
			if (empty($coupon->end_date)) {
				update_post_meta($post_id, 'offer_expire', '99999999999');
			} else {
				update_post_meta($post_id, 'offer_expire', strtotime($coupon->end_date . ' + 1 day'));
			}
			update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, 'offer_clicks', '0');
			update_post_meta($post_id, 'offer_views', '1');
			update_post_meta($post_id, 'offer_in_slider', 'yes');
			update_post_meta($post_id, 'offer_initial_payment', 'paid');
			update_post_meta($post_id, 'deal_type', 'shared');
			update_post_meta($post_id, 'deal_status', 'has_items');
			update_post_meta($post_id, 'offer_type', 'coupon');
			update_post_meta($post_id, 'offer_views', '1');
			if ($config['import_images'] == 'On' and $config['use_logos'] == 'on') {
				set_post_thumbnail($post_id, $thumbnails[$coupon->store]);
			}

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;

			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_cat', $append);
					$append = true;
				}
			}

			if ($coupon->locations && $config['import_locations'] == 'On') {
				$loc_names = explode(',', $coupon->locations);
				$append = false;
				foreach ($loc_names as $loc) {
					wp_set_object_terms($post_id, $loc, 'location', $append);
					$append = true;
				}
			}

			if (!empty($coupon->store)) {
				$store_id = array_search($coupon->store, $stores);
				if ($store_id) {
					update_post_meta($post_id, 'offer_store', $store_id);
				} else {
					$store_data = array(
						'ID'             => '',
						'post_title'     => $coupon->store,
						'post_status'    => 'publish',
						'post_type'      => 'store',
						'post_author'    => get_current_user_id()
					);
					$store_id = wp_insert_post($store_data);
					update_post_meta($store_id, 'store_link', $coupon->merchant_home_page);
					$stores[$store_id] = $coupon->store;
					update_post_meta($post_id, 'offer_store', $store_id);
				}
			}

			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'coupon_code', $coupon->code);
			}
			if (!empty($coupon->url)) {
				update_post_meta($post_id, 'coupon_url', $coupon->url);
			}
			if (!empty($coupon->affiliate_link)) {
				update_post_meta($post_id, 'coupon_link', $coupon->affiliate_link);
				update_post_meta($post_id, 'coupon_sale', $coupon->affiliate_link);
			}
			if (!empty($coupon->start_date)) {
				update_post_meta($post_id, 'offer_start', strtotime($coupon->start_date));
			}
			$end_date = (!empty($coupon->end_date)) ? $coupon->end_date : get_post_meta($post_id, 'offer_expire', true);

			if (empty($end_date)) {
				update_post_meta($post_id, 'offer_expire', '99999999999');
			} else {
				update_post_meta($post_id, 'offer_expire', strtotime($end_date . ' + 1 day'));
			}
			if (!empty($coupon->type)) {
				update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			}
			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_couponxxl_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'offer_cat',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$locations = array();
	$locationTerms = get_terms(array(
		'taxonomy' => 'location',
		'hide_empty' => false
	));
	foreach ($locationTerms as $term) {
		$locations[$term->name] = $term->slug;
	}

	$thumbnails = $stores = array();
	$sql_stores = "SELECT `ID`, `post_title`, (SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `meta_key`='_thumbnail_id' AND `ID` =`post_id`) AS `logo_id` FROM `{$wp_prefix}posts` WHERE `post_type` = 'store'";
	$result_stores = $wpdb->get_results($sql_stores);
	foreach ($result_stores as $str) {
		$stores[$str->ID] = $str->post_title;
		$thumbnails[$str->post_title] = $str->logo_id;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon (" . $coupon->offer_id . ")')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'offer',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$wpdb->query($wpdb->prepare("INSERT INTO " . $wp_prefix . "offers (post_id,offer_type,offer_start,offer_expire,offer_in_slider,offer_has_items,offer_thumbs_recommend,offer_clicks) VALUES (%d,'coupon',%s,%s,'yes','1','1','1')", $post_id, strtotime($coupon->start_date), strtotime($coupon->end_date)));

			$cat_names = explode(',', $coupon->categories);
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'offer_cat', true);
			}

			$store_id = array_search($coupon->store, $stores);
			if ($store_id) {
				update_post_meta($post_id, 'offer_store', $store_id);
			} else {
				$store_data = array(
					'ID'             => '',
					'post_title'     => $coupon->store,
					'post_status'    => 'publish',
					'post_type'      => 'store',
					'post_author'    => get_current_user_id()
				);
				$store_id = wp_insert_post($store_data);
				if (!empty($coupon->brand_logo) and $config['use_logos'] == 'on' and $store_id != 0) {
					$thumbnails[$coupon->store] = couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image'], $store_id);
					set_post_thumbnail($store_id, $thumbnails[$coupon->store]);
				}
				$stores[$store_id] = $coupon->store;
				update_post_meta($store_id, 'store_link', $coupon->merchant_home_page);
				update_post_meta($post_id, 'offer_store', $store_id);
			}

			if ($config['import_locations'] == 'On') {
				$loc_names = explode(',', $coupon->locations);
				foreach ($loc_names as $loc) {
					wp_set_object_terms($post_id, $loc, 'location', true);
				}
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'coupon_code', $coupon->code);
			update_post_meta($post_id, 'coupon_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_sale', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_url', $coupon->url);
			update_post_meta($post_id, 'coupon_type', $coupon->type);
			update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, 'offer_start', strtotime($coupon->start_date));
			if (empty($coupon->end_date)) {
				update_post_meta($post_id, 'offer_expire', '99999999999');
			} else {
				update_post_meta($post_id, 'offer_expire', strtotime($coupon->end_date . ' + 1 day'));
			}
			update_post_meta($post_id, 'deal_type', 'shared');
			update_post_meta($post_id, 'offer_thumbs_up', '1');
			update_post_meta($post_id, 'offer_thumbs_down', '0');
			update_post_meta($post_id, 'offer_views', '1');
			if ($config['import_images'] == 'On' and $config['use_logos'] == 'on') {
				set_post_thumbnail($post_id, $thumbnails[$coupon->store]);
			}

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish'
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_cat', $append);
					$append = true;
				}
			}

			if (!empty($coupon->store)) {
				$store_id = array_search($coupon->store, $stores);
				if ($store_id) {
					update_post_meta($post_id, 'offer_store', $store_id);
				} else {
					$store_data = array(
						'ID'             => '',
						'post_title'     => $coupon->store,
						'post_status'    => 'publish',
						'post_type'      => 'store',
						'post_author'    => get_current_user_id()
					);
					$store_id = wp_insert_post($store_data);
					update_post_meta($store_id, 'store_link', $coupon->merchant_home_page);
					$stores[$store_id] = $coupon->store;
					update_post_meta($post_id, 'offer_store', $store_id);
				}
			}

			if ($coupon->locations && $config['import_locations'] == 'On') {
				$loc_names = explode(',', $coupon->locations);
				$append = false;
				foreach ($loc_names as $loc) {
					wp_set_object_terms($post_id, $loc, 'location', $append);
					$append = true;
				}
			}

			$query = "SELECT offer_start, offer_expire FROM " . $wp_prefix . "offers WHERE post_id = %d";

			$dates = $wpdb->get_row($wpdb->prepare($query, $post_id));
			$start_date = (!empty($coupon->start_date)) ? $coupon->start_date : $dates->offer_start;
			$end_date = (!empty($coupon->end_date)) ? $coupon->end_date : $dates->offer_expire;

			$wpdb->query($wpdb->prepare("UPDATE " . $wp_prefix . "offers SET
												offer_start=%s,
												offer_expire=%s
											WHERE post_id = %d", strtotime($start_date), strtotime($end_date), $post_id));

			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'coupon_code', $coupon->code);
			}
			if (!empty($coupon->affiliate_link)) {
				update_post_meta($post_id, 'coupon_link', $coupon->affiliate_link);
				update_post_meta($post_id, 'coupon_sale', $coupon->affiliate_link);
			}
			if (!empty($coupon->url)) {
				update_post_meta($post_id, 'coupon_url', $coupon->url);
			}
			if (!empty($coupon->type)) {
				update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			}
			if (!empty($coupon->start_date)) {
				update_post_meta($post_id, 'offer_start', strtotime($coupon->start_date));
			}

			$expire_date = (!empty($coupon->end_date)) ? $coupon->end_date : get_post_meta($post_id, 'offer_expire', true);

			if (empty($expire_date)) {
				update_post_meta($post_id, 'offer_expire', '99999999999');
			} else {
				update_post_meta($post_id, 'offer_expire', strtotime($expire_date . ' + 1 day'));
			}

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_couponer_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'code_category',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$stores = array();
	$storeTerms = $wpdb->get_results("SELECT ID,post_title FROM {$wp_prefix}posts WHERE post_type = 'shop' AND post_status = 'publish'");
	foreach ($storeTerms as $str) {
		$stores[$str->ID] = $str->post_title;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = is_array($coupons) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon ({$coupon->offer_id})')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'code',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'code_category', true);
			}

			$store_id = array_search($coupon->store, $stores);
			if ($store_id) {
				update_post_meta($post_id, 'code_shop_id', $store_id);
			} else {
				$store_data = array(
					'ID'             => '',
					'post_title'     => $coupon->store,
					'post_status'    => 'publish',
					'post_type'      => 'shop',
					'post_author'    => get_current_user_id()
				);
				$store_id = wp_insert_post($store_data);
				update_post_meta($store_id, 'shop_link', $coupon->merchant_home_page);
				if (!empty($coupon->brand_logo) and $config['use_logos'] == 'on' and $store_id != 0) {
					couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image'], $store_id);
				}
				$stores[$store_id] = $coupon->store;
				update_post_meta($post_id, 'code_shop_id', $store_id);
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'pending_shop_url', $coupon->url);
			update_post_meta($post_id, 'code_couponcode', $coupon->code);
			update_post_meta($post_id, 'code_type', ($coupon->featured == 'Yes' ? 1 : 0));
			update_post_meta($post_id, 'code_expire', empty($coupon->end_date) ? '99999999999' : strtotime("{$coupon->end_date} + 1 day"));
			update_post_meta($post_id, 'code_api', $coupon->url);
			update_post_meta($post_id, 'coupon_label', $coupon->type == 'Code' ? 'couponer' : 'discount');
			update_post_meta($post_id, 'code_discount', $coupon->label ?: couponapi_badge_text());
			update_post_meta($post_id, 'code_for', 'all_users');
			update_post_meta($post_id, 'code_clicks', 0);
			update_post_meta($post_id, 'code_type', 1);

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon ({$coupon->offer_id})')");

			$post_id = $wpdb->get_var("SELECT post_id FROM {$wp_prefix}postmeta WHERE meta_key = 'capi_id' AND meta_value = '{$coupon->offer_id}' LIMIT 0,1");
			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;

			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'code_category', $append);
					$append = true;
				}
			}

			if (!empty($coupon->store)) {
				$store_id = array_search($coupon->store, $stores);
				if ($store_id) {
					update_post_meta($post_id, 'code_shop_id', $store_id);
				} else {
					$store_data = array(
						'ID'             => '',
						'post_title'     => $coupon->store,
						'post_status'    => 'publish',
						'post_type'      => 'shop',
						'post_author'    => get_current_user_id()
					);
					$store_id = wp_insert_post($store_data);
					if (!empty($coupon->merchant_home_page)) {
						update_post_meta($store_id, 'shop_link', $coupon->merchant_home_page);
					}
					$stores[$store_id] = $coupon->store;
					update_post_meta($post_id, 'code_shop_id', $store_id);
				}
			}

			if (!empty($coupon->offer_id)) {
				update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			}
			if (!empty($coupon->url)) {
				update_post_meta($post_id, 'pending_shop_url', $coupon->url);
				update_post_meta($post_id, 'code_api', $coupon->url);
			}
			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'code_couponcode', $coupon->code);
			}
			if (!empty($coupon->featured)) {
				update_post_meta($post_id, 'code_type', ($coupon->featured == 'Yes' ? 1 : 0));
			}

			$end_date = (!empty($coupon->end_date)) ? $coupon->end_date : get_post_meta($post_id, 'code_expire', true);
			update_post_meta($post_id, 'code_expire', empty($end_date) ? '99999999999' : strtotime("{$end_date} + 1 day"));
			if (!empty($coupon->type)) {
				update_post_meta($post_id, 'coupon_label', $coupon->type == 'Code' ? 'couponer' : 'discount');
			}
			if (empty(get_post_meta($post_id, 'code_discount', true))) {
				update_post_meta($post_id, 'code_discount', $coupon->label ?: couponapi_badge_text());
			}
			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon ({$coupon->offer_id})')");

			$post_id = $wpdb->get_var("SELECT post_id FROM {$wp_prefix}postmeta WHERE meta_key = 'capi_id' AND meta_value = '{$coupon->offer_id}' LIMIT 0,1");

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM {$wp_prefix}couponapi_upload WHERE offer_id = {$coupon->offer_id}");
	}

	$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}

function couponapi_couponpress_process_batch($config, $coupons) {

	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$stores = array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'store',
		'hide_empty' => false
	));
	foreach ($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'listing',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	$ol_code = get_term($config['ctype_code'], 'ctype');
	$ol_deal = get_term($config['ctype_deal'], 'ctype');

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon (" . $coupon->offer_id . ")')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'listing_type',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'listing', true);
			}

			$core_values = get_option('core_admin_values', array());
			$store_names = explode(',', $coupon->store);
			$append = false;
			foreach ($store_names as $str) {
				// Create New Store
				if (!term_exists($str, 'store')) {
					$term = wp_insert_term($str, 'store'); // , $args third parameter
					if (!is_wp_error($term)) { // Term did not exist. Got inserted now.
						$stores[$str] = get_term($term['term_id'], "store")->slug;
						// Update Meta Info
						if (!empty($coupon->brand_logo) and $config['use_logos'] == 'on') {
							$core_values["storeimage_{$term['term_id']}"] = wp_get_attachment_image_url(couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image']), 'full');
						}
						$core_values["category_website_{$term['term_id']}"] = $coupon->merchant_home_page;
						$core_values['storelink_'.$term['term_id']] = $coupon->merchant_home_page;
					}
				}
				wp_set_object_terms($post_id, $str, 'store', $append);
				$append = true;
			}
			update_option('core_admin_values', $core_values);

			if ($coupon->type == "Code") {
				update_post_meta($post_id, 'coupon_type', '1');
				if (!empty($config['ctype_code'])) {
					wp_set_post_terms($post_id, array($ol_code->term_id), 'ctype');
				}
			} else {
				update_post_meta($post_id, 'coupon_type', '3');
				if (!empty($config['ctype_deal'])) {
					wp_set_post_terms($post_id, array($ol_deal->term_id), 'ctype');
				}
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'url', $coupon->url);
			update_post_meta($post_id, 'link', $coupon->affiliate_link);
			update_post_meta($post_id, 'buy_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'lookinggen', '2');
			update_post_meta($post_id, 'code', $coupon->code);
			update_post_meta($post_id, 'type', ($coupon->type == 'Code' ? '1' : '3'));
			update_post_meta($post_id, 'coupon_txt', couponapi_badge_text());
			update_post_meta($post_id, 'start_date', $coupon->start_date . ' 00:00:00');
			if (empty($coupon->end_date)) {
				update_post_meta($post_id, 'expiry_date', '');
				update_post_meta($post_id, 'listing_expiry_date', '');
			} else {
				update_post_meta($post_id, 'expiry_date',  date("Y-m-d H:i:s", strtotime($coupon->end_date . ' + 1 day')));
				update_post_meta($post_id, 'listing_expiry_date',  date("Y-m-d H:i:s", strtotime($coupon->end_date . ' + 1 day')));
			}
			update_post_meta($post_id, 'featured', $coupon->featured);
			update_post_meta($post_id, 'listing_sticker', 0);

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;

			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);

			wp_update_post($post_data);

			if (!empty($coupon->store)) {
				$store_names = explode(',', $coupon->store);
				$append = false;
				foreach ($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'store', $append);
					$append = true;
				}
			}

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'listing', $append);
					$append = true;
				}
			}

			if (!empty($coupon->type)) {
				if ($coupon->type != 'Code') {
					$ol = get_term_by('name', 'offer', 'ctype');
					if (isset($ol->term_id)) {
						wp_set_post_terms($post_id, array($ol->term_id), 'ctype');
					}
				}
				update_post_meta($post_id, 'type', ($coupon->type == 'Code' ? '1' : '3'));
				update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? '1' : '3'));
			}

			if (!empty($coupon->url)) {
				update_post_meta($post_id, 'url', $coupon->url);
			}
			if (!empty($coupon->affiliate_link)) {
				update_post_meta($post_id, 'link', $coupon->affiliate_link);
			}

			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'code', $coupon->code);
			}

			if (empty(get_post_meta($post_id, 'coupon_txt', true))) {
				update_post_meta($post_id, 'coupon_txt', couponapi_badge_text());
			}

			if (!empty($coupon->start_date)) {
				update_post_meta($post_id, 'start_date', $coupon->start_date . " 00:00:00");
			}

			$end_date = (!empty($coupon->end_date)) ? $coupon->end_date : get_post_meta($post_id, 'expiry_date', true);
			if (empty($end_date)) {
				update_post_meta($post_id, 'expiry_date', '');
				update_post_meta($post_id, 'listing_expiry_date', '');
			} else {
				update_post_meta($post_id, 'expiry_date',  date("Y-m-d H:i:s", strtotime($end_date . ' + 1 day')));
				update_post_meta($post_id, 'listing_expiry_date',  date("Y-m-d H:i:s", strtotime($end_date . ' + 1 day')));
			}
			if (!empty($coupon->featured)) {
				update_post_meta($post_id, 'featured', $coupon->featured);
			}
			update_post_meta($post_id, 'listing_sticker', 0);

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_rehub_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$thumbnails = array();
	$sql_thumbnail = "SELECT (SELECT `name` FROM `{$wpdb->prefix}terms` WHERE `{$wpdb->prefix}termmeta`.`term_id` = `term_id`) as `name`,
						  	 (SELECT `ID` FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'attachment' AND `post_title` = RIGHT(`meta_value`, 41)) AS `thumbnail_id`
					  FROM `{$wpdb->prefix}termmeta`
					  WHERE `meta_key` = 'brandimage'";
	$result_thumbnail = $wpdb->get_results($sql_thumbnail);
	foreach ($result_thumbnail as $thm) {
		$thumbnails[$thm->name] = $thm->thumbnail_id;
	}

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'category',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$stores = array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'dealstore',
		'hide_empty' => false
	));
	foreach ($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon (" . $coupon->offer_id . ")')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'post',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			$append = false;
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'category', $append);
				$append = true;
			}

			$str_names = explode(',', $coupon->store);
			foreach ($str_names as $str) {
				// Create New Store
				if (!term_exists($str, 'dealstore')) {
					$term = wp_insert_term($str, 'dealstore'); // , $args third parameter
					if (!is_wp_error($term)) { // Term did not exist. Got inserted now.
						$stores[$str] = get_term($term['term_id'], "dealstore")->slug;
						if (!empty($coupon->brand_logo) and $config['use_logos'] == 'on') {
							$thumbnails[$str] = couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image']);
							update_term_meta($term['term_id'], 'brandimage', wp_get_attachment_image_url($thumbnails[$str], 'full'));
						}
						update_term_meta($term['term_id'], 'brand_url', $coupon->merchant_home_page);
					}
				}
				wp_set_object_terms($post_id, $str, 'dealstore', $append);
				$append = true;
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'post_size', 'normal_post');
			update_post_meta($post_id, 'rehub_framework_post_type', 'regular');
			update_post_meta($post_id, 'rehub_offer_clicks_count', '0');
			update_post_meta($post_id, 'rehub_views', '0');
			if (!empty($coupon->end_date)) {
				update_post_meta($post_id, 'rehub_offer_coupon_date', $coupon->end_date);
			}
			update_post_meta($post_id, 'rehub_offer_product_url', $coupon->affiliate_link);
			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'rehub_offer_coupon_mask', '1');
				update_post_meta($post_id, 'rehub_offer_product_coupon', $coupon->code);
			}
			if ($config['import_images'] == 'On' and $config['use_logos'] == 'on') {
				set_post_thumbnail($post_id, $thumbnails[$coupon->store]);
			}

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;

			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish'
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'category', $append);
					$append = true;
				}
			}
			if (!empty($coupon->store)) {
				$str_names = explode(',', $coupon->store);
				$append = false;
				foreach ($str_names as $str) {
					wp_set_object_terms($post_id, $str, 'dealstore', $append);
					$append = true;
				}
			}

			if (!empty($coupon->end_date)) {
				update_post_meta($post_id, 'rehub_offer_coupon_date', $coupon->end_date);
			}
			if (!empty($coupon->affiliate_link)) {
				update_post_meta($post_id, 'rehub_offer_product_url', $coupon->affiliate_link);
			}
			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'rehub_offer_coupon_mask', '1');
				update_post_meta($post_id, 'rehub_offer_product_coupon', $coupon->code);
			}

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_wpcoupon_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$stores = array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'coupon_store',
		'hide_empty' => false
	));
	foreach ($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'coupon_category',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}


	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon (" . $coupon->offer_id . ")')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupon',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'coupon_category', true);
			}

			$store_names = explode(',', $coupon->store);
			$append = false;
			foreach ($store_names as $str) {
				// Create New Store
				if (!term_exists($str, 'coupon_store')) {
					$term = wp_insert_term($str, 'coupon_store'); // , $args third parameter
					if (!is_wp_error($term)) { // Term did not exist. Got inserted now.
						$stores[$str] = get_term($term['term_id'], "coupon_store")->slug;
						if (!empty($coupon->brand_logo) and $config['use_logos'] == 'on') {
							$thumbnails[$str] = couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image']);
							update_term_meta($term['term_id'], '_wpc_store_image_id', $thumbnails[$str]);
							update_term_meta($term['term_id'], '_wpc_store_image', wp_get_attachment_image_url($thumbnails[$str], 'full'));
						}
						update_term_meta($term['term_id'], '_wpc_store_url', $coupon->merchant_home_page);
						update_term_meta($term['term_id'], '_wpc_store_name', $str);
					}
				}
				wp_set_object_terms($post_id, $str, 'coupon_store', $append);
				$append = true;
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, '_wpc_percent_success', '100');
			update_post_meta($post_id, '_wpc_used', '0');
			update_post_meta($post_id, '_wpc_today', '');
			update_post_meta($post_id, '_wpc_vote_up', '0');
			update_post_meta($post_id, '_wpc_vote_down', '0');
			if (!empty($coupon->end_date)) {
				update_post_meta($post_id, '_wpc_expires', strtotime($coupon->end_date));
			}
			update_post_meta($post_id, '_wpc_store', '');
			update_post_meta($post_id, '_wpc_coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, '_wpc_coupon_type_code', $coupon->code);
			update_post_meta($post_id, '_wpc_destination_url', $coupon->affiliate_link);
			update_post_meta($post_id, '_wpc_exclusive', '');
			update_post_meta($post_id, '_wpc_views', '0');

			if ($config['import_images'] == 'On' and $config['use_logos'] == 'on') {
				set_post_thumbnail($post_id, $thumbnails[$coupon->store]);
			}

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;

			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->store)) {
				$store_names = explode(',', $coupon->store);
				$append = false;
				foreach ($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'coupon_store', $append);
					$append = true;
				}
			}

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'coupon_category', $append);
					$append = true;
				}
			}

			if (!empty($coupon->end_date)) {
				update_post_meta($post_id, '_wpc_expires', strtotime($coupon->end_date));
			}
			if (!empty($coupon->type)) {
				update_post_meta($post_id, '_wpc_coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			}
			if (!empty($coupon->code)) {
				update_post_meta($post_id, '_wpc_coupon_type_code', $coupon->code);
			}
			if (!empty($coupon->affiliate_link)) {
				update_post_meta($post_id, '_wpc_destination_url', $coupon->affiliate_link);
			}
			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");

			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_mtscoupon_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$stores = array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'mts_coupon_tag',
		'hide_empty' => false
	));
	foreach ($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'mts_coupon_categories',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = is_array($coupons) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon ({$coupon->offer_id})')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupons',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$cat_names = explode(',', $coupon->categories);
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'mts_coupon_categories', true);
			}

			$store_names = explode(',', $coupon->store);
			foreach ($store_names as $str) {
				wp_set_object_terms($post_id, $str, 'mts_coupon_tag', true);
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'mts_coupon_expire', $coupon->end_date);
			update_post_meta($post_id, 'mts_coupon_button_type', $coupon->type == 'Code' ? 'coupon' : 'deal');
			update_post_meta($post_id, 'mts_coupon_deal_URL', $coupon->url);
			update_post_meta($post_id, 'mts_coupon_code', $coupon->code);
			// update_post_meta($post_id, '_thumbnail_id', $config['import_images'] == 'On' ? couponapi_import_image($coupon->image_url, $config['use_grey_image'], $post_id) : 0);

			update_post_meta($post_id, '_mts_custom_sidebar', '');
			update_post_meta($post_id, '_mts_sidebar_location', '');
			update_post_meta($post_id, 'mts_coupon_extra_rewards', '');
			update_post_meta($post_id, 'mts_coupon_people_used', '1');
			update_post_meta($post_id, 'mts_coupon_expire_time', '11:59 PM');
			update_post_meta($post_id, 'mts_coupon_featured_text', $coupon->label ?: couponapi_badge_text());

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon ({$coupon->offer_id})')");

			$post_id = $wpdb->get_var("SELECT post_id FROM {$wp_prefix}postmeta WHERE meta_key = 'capi_id' AND meta_value = '{$coupon->offer_id}' LIMIT 0,1");

			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;

			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'mts_coupon_categories', $append);
					$append = true;
				}
			}
			if (!empty($coupon->store)) {
				$store_names = explode(',', $coupon->store);
				$append = false;
				foreach ($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'mts_coupon_tag', $append);
					$append = true;
				}
			}
			if (!empty($coupon->end_date)) {
				update_post_meta($post_id, 'mts_coupon_expire', $coupon->end_date);
			}
			if (!empty($coupon->type)) {
				update_post_meta($post_id, 'mts_coupon_button_type', $coupon->type == 'Code' ? 'coupon' : 'deal');
			}
			if (!empty($coupon->url)) {
				update_post_meta($post_id, 'mts_coupon_deal_URL', $coupon->url);
			}
			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'mts_coupon_code', $coupon->code);
			}
			if (empty(get_post_meta($post_id, 'mts_coupon_featured_text', true))) {
				update_post_meta($post_id, 'mts_coupon_featured_text', $coupon->label ?: couponapi_badge_text());
			}
			if (!empty($coupon->image_url)) {
				// update_post_meta($post_id, '_thumbnail_id', $config['import_images'] == 'On' ? couponapi_import_image($coupon->image_url, $config['use_grey_image'], $post_id) : 0);
			}

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon ({$coupon->offer_id})')");

			$post_id = $wpdb->get_var("SELECT post_id FROM {$wp_prefix}postmeta WHERE meta_key = 'capi_id' AND meta_value = '{$coupon->offer_id}' LIMIT 0,1");

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM {$wp_prefix}couponapi_upload WHERE offer_id = {$coupon->offer_id}");
	}

	$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_couponis_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'coupon-category',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$stores = array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'coupon-store',
		'hide_empty' => false
	));
	foreach ($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = is_array($coupons) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon ({$coupon->offer_id})')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupon',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			$wpdb->query($wpdb->prepare("INSERT INTO {$wp_prefix}couponis_coupon_data (post_id,expire,ctype,exclusive,used,positive,negative,success) VALUES ($post_id,'%s','%s',%s,'0','0','0','0')", empty($coupon->end_date) ? '99999999999' : strtotime("{$coupon->end_date} + 1 day"), $coupon->type == 'Code' ? '1' : '3', $coupon->featured == 'Yes' ? '1' : '0'));

			$cat_names = explode(',', $coupon->categories);
			foreach ($cat_names as $cat) {
				wp_set_object_terms($post_id, $cat, 'coupon-category', true);
			}

			$store_names = explode(',', $coupon->store);
			foreach ($store_names as $str) {
				if (!term_exists($str, 'coupon-store')) {
					$term = wp_insert_term($str, 'coupon-store'); 							// , $args third parameter
					if (!is_wp_error($term)) { 											// Term did not exist. Got inserted now.
						$stores[$str] = get_term($term['term_id'], "coupon-store")->slug;
						update_term_meta($term['term_id'], 'store_url', $coupon->merchant_home_page); 		//store taxonomy args in wp_options
						if (!empty($coupon->brand_logo) and $config['use_logos'] != 'off') {
							update_term_meta($term['term_id'], 'store_image', couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image']));
						}
					}
				}
				wp_set_object_terms($post_id, $str, 'coupon-store', true);
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'coupon_code', $coupon->code);
			update_post_meta($post_id, 'coupon_affiliate', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_url', $coupon->url);
			if (empty($coupon->brand_logo) or $config['use_logos'] != 'on') {
				update_post_meta($post_id, '_thumbnail_id', couponapi_import_image($coupon->image_url, $config['use_grey_image'], $post_id));
			}

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon ({$coupon->offer_id})')");

			$post_id = $wpdb->get_var("SELECT post_id FROM {$wp_prefix}postmeta WHERE meta_key = 'capi_id' AND meta_value = '{$coupon->offer_id}' LIMIT 0,1");

			$data = get_post($post_id);
			$title = (!empty($coupon->title)) ? $coupon->title : $data->post_title;
			$description = (!empty($coupon->description)) ? $coupon->description : $data->post_content;

			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $title,
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$cat_names = explode(',', $coupon->categories);
				$append = false;
				foreach ($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'coupon-category', $append);
					$append = true;
				}
			}

			if (!empty($coupon->store)) {
				$store_names = explode(',', $coupon->store);
				$append = false;
				foreach ($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'coupon-store', $append);
					$append = true;
				}
			}
			$query = "SELECT expire, ctype, exclusive FROM " . $wp_prefix . "couponis_coupon_data WHERE post_id = %d";
			$dates = $wpdb->get_row($wpdb->prepare($query, $post_id));
			$end_date = (!empty($coupon->end_date)) ? $coupon->end_date : $dates->expire;
			$type = (!empty($coupon->type)) ? $coupon->type : $dates->ctype;
			$featured = (!empty($coupon->featured)) ? $coupon->featured : $dates->exclusive;

			$wpdb->query($wpdb->prepare("UPDATE {$wp_prefix}couponis_coupon_data SET expire=%s, ctype=%s, exclusive=%s WHERE post_id = $post_id", empty($end_date) ? '99999999999' : strtotime("{$end_date} + 1 day"), $type == 'Code' ? '1' : '3', $featured == 'Yes' ? '1' : '0'));

			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'coupon_code', $coupon->code);
			}
			if (!empty($coupon->affiliate_link)) {
				update_post_meta($post_id, 'coupon_affiliate', $coupon->affiliate_link);
			}
			if (!empty($coupon->url)) {
				update_post_meta($post_id, 'coupon_url', $coupon->url);
			}

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon ({$coupon->offer_id})')");

			$post_id = $wpdb->get_var("SELECT post_id FROM {$wp_prefix}postmeta WHERE meta_key = 'capi_id' AND meta_value = '{$coupon->offer_id}' LIMIT 0,1");

			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM {$wp_prefix}couponapi_upload WHERE offer_id = {$coupon->offer_id}");
	}

	$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}


function couponapi_couponhut_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$thumbnails = array();
	$sql_thumbnail = "SELECT (SELECT `name` FROM `{$wpdb->prefix}terms` WHERE `{$wpdb->prefix}termmeta`.`term_id` = `term_id`) as `name`,
						  	`meta_value` AS `thumbnail_id`
					  FROM `{$wpdb->prefix}termmeta`
					  WHERE `meta_key` = 'company_logo'";
	$result_thumbnail = $wpdb->get_results($sql_thumbnail);
	foreach ($result_thumbnail as $thm) {
		$thumbnails[$thm->name] = $thm->thumbnail_id;
	}

	$categories = array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'deal_category',
		'hide_empty' => false
	));
	foreach ($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}

	$stores = array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'deal_company',
		'hide_empty' => false
	));
	foreach ($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");

	foreach ($coupons as $coupon) {

		if ($coupon->status == 'new' or $coupon->status == '') {

			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon ($coupon->offer_id)')");
			$post_date = (!empty($coupon->start_date) and strtotime(current_time( 'mysql' )) > strtotime($coupon->start_date)) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'deal',
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);

			$post_id = wp_insert_post($post_data);

			$append = false;
			foreach (explode(',', $coupon->categories) as $cat) {
				wp_set_object_terms($post_id, $cat, 'deal_category', $append);
				$append = true;
			}

			$append = false;
			foreach (explode(',', $coupon->store) as $str) {
				// Create New Store
				if (!term_exists($str, 'deal_company')) {
					$term = wp_insert_term($str, 'deal_company'); // , $args third parameter
					if (!is_wp_error($term)) { // Term did not exist. Got inserted now.
						$stores[$str] = get_term($term['term_id'], "deal_company")->slug;
						$thumbnails[$str] = couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), $config['use_grey_image']);

						update_term_meta($term['term_id'], 'company_logo', $thumbnails[$str]);
						update_term_meta($term['term_id'], '_company_logo', 'field_55073707dee91');
						update_term_meta($term['term_id'], 'company_website', $coupon->merchant_home_page);
						update_term_meta($term['term_id'], '_company_website', 'field_55225d80cd66e');
					}
				}
				wp_set_object_terms($post_id, $str, 'deal_company', $append);
				$append = true;
			}

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);

			update_post_meta($post_id, 'deal_type', $coupon->code ? 'coupon' : 'discount');
			update_post_meta($post_id, '_deal_type', 'field_5519756e0f4e2');
			update_post_meta($post_id, 'coupon_code', $coupon->code);
			update_post_meta($post_id, '_coupon_code', 'field_551976780f4e4');
			update_post_meta($post_id, 'url', $coupon->url);
			update_post_meta($post_id, '_url', 'field_55016e3011ba3');
			update_post_meta($post_id, 'deal_summary', $coupon->label ?: couponapi_badge_text());
			update_post_meta($post_id, '_deal_summary', 'field_554f8e55b6dd8');
			update_post_meta($post_id, 'discount_value', $coupon->label ?: couponapi_badge_text());
			update_post_meta($post_id, '_discount_value', 'field_55016e0911ba1');
			update_post_meta($post_id, 'coupon_code_description', $coupon->title);
			update_post_meta($post_id, '_coupon_code_description', 'field_5b3dcdadd300c');
			update_post_meta($post_id, 'expiring_date', empty($coupon->end_date) ? '' : date("Ymd", strtotime($coupon->end_date)));
			update_post_meta($post_id, '_expiring_date', 'field_55016e3d11ba4');

			update_post_meta($post_id, 'deal_layout', 'big');
			update_post_meta($post_id, '_deal_layout', 'field_59db655dfb0cb');
			update_post_meta($post_id, 'virtual_deal', '1');
			update_post_meta($post_id, '_virtual_deal', 'field_56f12e67f1d15');
			update_post_meta($post_id, 'printable_coupon', '0');
			update_post_meta($post_id, '_printable_coupon', 'field_5683c0ebd307f');
			update_post_meta($post_id, 'registered_members_only', '0');
			update_post_meta($post_id, '_registered_members_only', 'field_5673f4f869107');
			update_post_meta($post_id, 'show_location', 'hide');
			update_post_meta($post_id, '_show_location', 'field_55d30607a99a0');
			update_post_meta($post_id, 'redirect_to_offer', '');
			update_post_meta($post_id, '_redirect_to_offer', 'field_551976ac0f4e5');
			update_post_meta($post_id, 'show_pricing_fields', '0');
			update_post_meta($post_id, '_show_pricing_fields', 'field_568a54a25476a');
			update_post_meta($post_id, 'image_type', 'image');
			update_post_meta($post_id, '_image_type', 'field_55532006b5e0c');
			update_post_meta($post_id, 'ssd_post_button_clicks_count', '0');
			update_post_meta($post_id, 'ssd_post_views_count', '0');
			update_post_meta($post_id, 'ssd_couponhut_published_deal_email_pending', 'waiting_to_send');
			update_post_meta($post_id, 'geo_city', '');
			update_post_meta($post_id, 'geo_city_slug', '');
			update_post_meta($post_id, 'geo_country', '');
			update_post_meta($post_id, 'geo_country_slug', '');

			if ($config['import_images'] == 'On' and $thumbnails[$str]) {
				update_post_meta($post_id, 'image', $thumbnails[$str]);
				update_post_meta($post_id, '_image', 'field_55016dd111b9f');
			}

			$count_new = $count_new + 1;
		} elseif ($coupon->status == 'updated') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon ($coupon->offer_id)')");
			$post_id = $wpdb->get_var("SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$coupon->offer_id' LIMIT 0,1");

			$data = get_post($post_id);
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title ?: $data->post_title,
				'post_content'   => $coupon->description ?: $data->post_content,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
			wp_update_post($post_data);

			if (!empty($coupon->categories)) {
				$append = false;
				foreach (explode(',', $coupon->categories) as $cat) {
					wp_set_object_terms($post_id, $cat, 'deal_category', $append);
					$append = true;
				}
			}

			if (!empty($coupon->store)) {
				$append = false;
				foreach (explode(',', $coupon->store) as $str) {
					wp_set_object_terms($post_id, $str, 'deal_company', $append);
					$append = true;
				}
			}

			if (!empty($coupon->code)) {
				update_post_meta($post_id, 'deal_type', $coupon->code ? 'coupon' : 'discount');
				update_post_meta($post_id, '_deal_type', 'field_5519756e0f4e2');
				update_post_meta($post_id, 'coupon_code', $coupon->code);
				update_post_meta($post_id, '_coupon_code', 'field_551976780f4e4');
			}

			if (!empty($coupon->url)) {
				update_post_meta($post_id, 'url', $coupon->url);
				update_post_meta($post_id, '_url', 'field_55016e3011ba3');
			}

			update_post_meta($post_id, 'deal_summary', $coupon->label ?: couponapi_badge_text());
			update_post_meta($post_id, '_deal_summary', 'field_554f8e55b6dd8');
			update_post_meta($post_id, 'discount_value', $coupon->label ?: couponapi_badge_text());
			update_post_meta($post_id, '_discount_value', 'field_55016e0911ba1');

			if (!empty($coupon->title)) {
				update_post_meta($post_id, 'coupon_code_description', $coupon->title);
				update_post_meta($post_id, '_coupon_code_description', 'field_5b3dcdadd300c');
			}

			$end_date = (!empty($coupon->end_date)) ? date("Ymd", strtotime($coupon->end_date)) : get_post_meta($post_id, 'expiring_date', true);
			update_post_meta($post_id, 'expiring_date', empty($end_date) ? '' : $end_date);
			update_post_meta($post_id, '_expiring_date', 'field_55016e3d11ba4');

			update_post_meta($post_id, 'deal_layout', 'big');
			update_post_meta($post_id, '_deal_layout', 'field_59db655dfb0cb');
			update_post_meta($post_id, 'virtual_deal', '1');
			update_post_meta($post_id, '_virtual_deal', 'field_56f12e67f1d15');
			update_post_meta($post_id, 'printable_coupon', '0');
			update_post_meta($post_id, '_printable_coupon', 'field_5683c0ebd307f');
			update_post_meta($post_id, 'registered_members_only', '0');
			update_post_meta($post_id, '_registered_members_only', 'field_5673f4f869107');
			update_post_meta($post_id, 'show_location', 'hide');
			update_post_meta($post_id, '_show_location', 'field_55d30607a99a0');
			update_post_meta($post_id, 'redirect_to_offer', '');
			update_post_meta($post_id, '_redirect_to_offer', 'field_551976ac0f4e5');
			update_post_meta($post_id, 'show_pricing_fields', '0');
			update_post_meta($post_id, '_show_pricing_fields', 'field_568a54a25476a');
			update_post_meta($post_id, 'image_type', 'image');
			update_post_meta($post_id, '_image_type', 'field_55532006b5e0c');
			update_post_meta($post_id, 'ssd_post_button_clicks_count', '0');
			update_post_meta($post_id, 'ssd_post_views_count', '0');
			update_post_meta($post_id, 'ssd_couponhut_published_deal_email_pending', 'waiting_to_send');
			update_post_meta($post_id, 'geo_city', '');
			update_post_meta($post_id, 'geo_city_slug', '');
			update_post_meta($post_id, 'geo_country', '');
			update_post_meta($post_id, 'geo_country_slug', '');

			$count_updated = $count_updated + 1;
		} elseif ($coupon->status == 'suspended') {

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");
			$post_id = $wpdb->get_var("SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$coupon->offer_id' LIMIT 0,1");
			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;
		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
	}

	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}

function generic_theme_process_batch($config,$coupons){

	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	if(!taxonomy_exists($config['store']) and $config['store'] != 'none') {
		$config['store'] = 'post_tag';
	}

	if(!taxonomy_exists($config['category']) and $config['category'] != 'none') {
		$config['category'] = 'category';
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Found $found_count coupons to process')");
	
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
	{{description}}';

	$description_template = get_theme_mod('custom_coupon_template' ,$default_template);


	foreach($coupons as $coupon){
		$image_attach_id = 0;
		$image_attach_url = '';
		
		if($coupon->status == 'new' or $coupon->status == ''){
			$wpdb->query("INSERT INTO {$wp_prefix}couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Adding New Coupon ($coupon->offer_id)')");
			$post_date = empty($coupon->start_date) ? current_time( 'mysql' ) : $coupon->start_date;

			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => '',
				'post_status'    => 'publish',
				'post_excerpt'   => $coupon->description,
				'post_date'      => $post_date,
				'post_author'    => get_current_user_id()
			);
			$post_id = wp_insert_post($post_data);

			if ($config['store'] != 'none'){ 

				$store_term = get_term_by('name', $coupon->store, $config['store']);
	
				if(!$store_term){
					$result = wp_insert_term($coupon->store, $config['store']);
					update_term_meta( $result['term_id']  ,'couponapi_store', 1 );
					update_term_meta( $result['term_id']  ,'capi_store_url', $coupon->merchant_home_page );
					if(!empty($config['brandlogos_key']) and $config['generic_import_image'] == 'brandlogos_image') {
						$image_attach_id = couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), 'off');
					}
					update_term_meta( $result['term_id']  ,'capi_store_logo', $image_attach_id );
					$store_id = $result['term_id'];
				} else {
					$result = get_term_meta(  $store_term->term_id );
					$image_attach_id = $result['capi_store_logo'][0];
					$store_id = $store_term->term_id;
				}
				
				wp_set_object_terms( $post_id, intval($store_id), $config['store'] ); 
			}
			
			if ($config['category'] != 'none'){ 
				$category_list = [];
				if(!empty($coupon->categories)){
					foreach(explode(',',$coupon->categories) as $category){
						$categoryTerms = get_term_by('name', $category, $config['category']);
						if($categoryTerms){
							$category_list[] = $categoryTerms->term_id;
						} else {
							$result = wp_insert_term($category, $config['category']);
							$category_list[] = $result['term_id'];
						}
					}
				}
	
				($config['category'] != $config['store'])?:$category_list[] = intval($store_id);
	
				wp_set_object_terms($post_id,$category_list,$config['category']);
			}
			if(($config['generic_import_image'] == 'coupon_image' or (empty($config['brandlogos_key']) and $config['generic_import_image'] == 'brandlogos_image')) and !empty($coupon->image_url)) {
				
				$image_attach_id = couponapi_import_image($coupon->image_url, 'off');
			}
			if($config['generic_import_image'] != 'off') {
				$image_attach_url = wp_get_attachment_image_url($image_attach_id);
				if($config['set_as_featured_image'] == 'On'){
					set_post_thumbnail($post_id, $image_attach_id);
				}
			}
			$start_date = '';
			if(!empty($coupon->start_date)){
				$dt = get_date_from_gmt($coupon->start_date, 'Y-m-d');// convert from GMT to local date/time based on WordPress time zone setting.
				$start_date = date_i18n(get_option('date_format') , strtotime($dt));// get format from WordPress settings.
			}
			
			$end_date = '';
			if(!empty($coupon->end_date)){
				$dt = get_date_from_gmt($coupon->end_date, 'Y-m-d');// convert from GMT to local date/time based on WordPress time zone setting.
				$end_date =  date_i18n(get_option('date_format') , strtotime($dt));// get format from WordPress settings.
			}
			
			$coupon->label = empty($coupon->label) ? couponapi_badge_text() : $coupon->label;

			$replace_variable_list_keys = ['{{description}}','{{link}}','{{label}}','{{store}}','{{code}}','{{start_date}}','{{expiry}}','{{image}}','{{image_url}}'];
			$replace_variable_list_values = 
			[
				$coupon->description ,
				$coupon->affiliate_link ,
				$coupon->label,
				$coupon->store ,
				($coupon->code ?: $config['code_text']),
				$start_date ,
				($end_date ?: $config['expiry_text']), 
				($image_attach_url ?"<img class='wp-image-351' src='".$image_attach_url."' />": ""),
				($image_attach_url ?: "")
			];

			$description = $description_template;
			
			$description = str_replace($replace_variable_list_keys,$replace_variable_list_values,$description);

			$post_data = array(
				'ID' => $post_id,
				'post_content' => $description,
			);

			wp_update_post($post_data);

			update_post_meta($post_id, 'capi_id', $coupon->offer_id);
			update_post_meta($post_id, 'capi_start_date', ($coupon->start_date ?: ''));
			update_post_meta($post_id, 'capi_valid_till', ($coupon->end_date ?: ''));
			update_post_meta($post_id, 'capi_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'capi_label', $coupon->label);
			update_post_meta($post_id, 'capi_image_attach_id', $image_attach_id);
			update_post_meta($post_id, 'capi_store', $coupon->store);
			update_post_meta($post_id, 'capi_code', ($coupon->code ?: $config['code_text']));


			$count_new = $count_new + 1;

		} else if($coupon->status == 'updated'){

			$post_id = $wpdb->get_var("SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$coupon->offer_id' LIMIT 0,1");
			
			if (!$post_id) {
				$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);
				continue;
			}  
			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Updating Coupon ($coupon->offer_id)')");
			
			$data = get_post($post_id);

			$old_post_meta = get_post_meta($post_id);

			$coupon->start_date = ($coupon->start_date?:$old_post_meta['capi_start_date'][0]);
			$coupon->end_date = ($coupon->end_date?:$old_post_meta['capi_valid_till'][0]);
			$coupon->affiliate_link = ($coupon->affiliate_link?:$old_post_meta['capi_link'][0]);
			$coupon->label = ($coupon->label?:$old_post_meta['capi_label'][0]);
			$coupon->store = ($coupon->store?:$old_post_meta['capi_store'][0]);
			$coupon->code = ($coupon->code?:$old_post_meta['capi_code'][0]);
			$coupon->description = ($coupon->description?:$data->post_excerpt);
			$image_attach_id = $old_post_meta['capi_image_attach_id'][0];

			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title ?: $data->post_title,
				'post_content'   => '',
				'post_status'    => 'publish',
				'post_excerpt' => $coupon->description,
				'post_author'    => get_current_user_id()
			);

			wp_update_post($post_data);
			if ($config['store'] != 'none'){ 
				$store_term = get_term_by('name', $coupon->store, $config['store']);
	
				if(!$store_term){
					$result = wp_insert_term($coupon->store, $config['store']);
					update_term_meta( $result['term_id']  ,'couponapi_store', 1 );
					update_term_meta( $result['term_id']  ,'capi_store_url', $coupon->merchant_home_page );
					if(!empty($config['brandlogos_key']) and $config['generic_import_image'] == 'brandlogos_image' and !empty($coupon->brand_logo)) {
						$image_attach_id = couponapi_import_image(couponapi_brandlogo_url($config, $coupon->brand_logo), 'off');
						if($config['set_as_featured_image'] == 'On'){
							set_post_thumbnail($post_id, $image_attach_id);
						}
					}
					update_term_meta( $result['term_id']  ,'capi_store_logo', $image_attach_id );
					$store_id = $result['term_id'];
				} else {
					$store_id = $store_term->term_id;
				}
	
				wp_set_object_terms( $post_id, $store_id, $config['store'] ); // Replace existing terms with new ones
			}
			if ($config['category'] != 'none'){ 
				$category_list = [];
				if(!empty($coupon->categories)){
					foreach(explode(',',$coupon->categories) as $category){
						$categoryTerms = get_term_by('name', $category, $config['category']);
						if($categoryTerms){
							$category_list[] = $categoryTerms->term_id;
						} else {
							$result = wp_insert_term($category, $config['category']);
							$category_list[] = $result['term_id'];
						}
					}
				}
	
				($config['category'] != $config['store'])?:$category_list[] = intval($store_id);
	
				wp_set_object_terms($post_id,$category_list,$config['category']);
			}
			
			$start_date = '';
			if(!empty($coupon->start_date)){
				$dt = get_date_from_gmt($coupon->start_date, 'Y-m-d');// convert from GMT to local date/time based on WordPress time zone setting.
				$start_date = date_i18n(get_option('date_format') , strtotime($dt));// get format from WordPress settings.
			}
			
			$end_date = '';
			if(!empty($coupon->end_date)){
				$dt = get_date_from_gmt($coupon->end_date, 'Y-m-d');// convert from GMT to local date/time based on WordPress time zone setting.
				$end_date =  date_i18n(get_option('date_format') , strtotime($dt));// get format from WordPress settings.
			}
			
			$image_attach_url = wp_get_attachment_url($image_attach_id);

			$replace_variable_list_keys = ['{{description}}','{{link}}','{{label}}','{{store}}','{{code}}','{{start_date}}','{{expiry}}','{{image}}','{{image_url}}'];
			$replace_variable_list_values = 
			[
				$coupon->description ,
				$coupon->affiliate_link ,
				$coupon->label,
				$coupon->store ,
				($coupon->code ?: $config['code_text']),
				$start_date ,
				($end_date ?: $config['expiry_text']), 
				($image_attach_url ?"<img class='wp-image-351' src='".$image_attach_url."' />": ""),
				($image_attach_url ?: "")
			];

			$description = $description_template;
			
			$description = str_replace($replace_variable_list_keys,$replace_variable_list_values,$description);

			$post_data = array(
				'ID' => $post_id,
				'post_content' => $description,
			);

			wp_update_post($post_data);			
			
			update_post_meta($post_id, 'capi_start_date', ($coupon->start_date ?: ''));
			update_post_meta($post_id, 'capi_valid_till', ($coupon->end_date ?: ''));
			update_post_meta($post_id, 'capi_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'capi_label', $coupon->label);
			update_post_meta($post_id, 'capi_store', $coupon->store);
			update_post_meta($post_id, 'capi_code', ($coupon->code ?: $config['code_text']));
			update_post_meta($post_id, 'capi_image_attach_id', $image_attach_id);

			$count_updated = $count_updated + 1;

		}else if($coupon->status == 'suspended'){

			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','Suspending Coupon (" . $coupon->offer_id . ")')");
			$post_id = $wpdb->get_var("SELECT post_id FROM " . $wp_prefix . "postmeta WHERE meta_key = 'capi_id' AND meta_value = '$coupon->offer_id' LIMIT 0,1");
			wp_delete_post($post_id, true);

			$count_suspended = $count_suspended + 1;

		}

		$wpdb->query("DELETE FROM " . $wp_prefix . "couponapi_upload WHERE offer_id = " . $coupon->offer_id);

	}
	$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");

}


function couponapi_brandlogo_url($config, $url, $brand = '') {
	// TODO :  add [token] in couponapi/api/getFeed
	// TODO :  add [domain] in couponapi/api/getFeed
	if(empty($config['brandlogos_key'])) {
		return '';
	}
	$token = hash('sha256', $config['brandlogos_key'] . date('Y-m-d'));
	$search = array('placeholder', '[format]', '[size]', '[token]', '[domain]');
	$replace = array('api/get/images', 'png', $config['size'], $token, parse_url(home_url(), PHP_URL_HOST));

	return str_replace($search, $replace, $url ?: "https://brandlogos.org/placeholder/?brand=$brand&size=[size]&format=[format]&token=[token]&domain=[domain]");
}

function couponapi_import_image($image_url, $use_grey_image = 'on', $post_id = 0, $return_error_msg = false) {

	if (empty($image_url) or strpos($image_url, "svg") !== false) return 0;

	$image = file_get_contents($image_url, false, stream_context_create(array('http' => array('timeout' => 5))));
	if ($image === false) return 0;

	$image_info = getimagesizefromstring($image);

	if (!in_array($image_info['mime'], array('image/bmp', 'image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp')) or !$image_info[0] or !$image_info[1]) return 0;

	$wp_upload_dir = wp_upload_dir();
	$upload_dir    = wp_mkdir_p($wp_upload_dir['path']) ? $wp_upload_dir['path'] : $wp_upload_dir['basedir'];
	$uniquename    = "capi_" . md5($image);
	$filename  	   = "$uniquename." . preg_split("/[\\/]+/", $image_info['mime'])[1]; // Create image file name
	$filepath  	   = "$upload_dir/$filename";

	if (in_array($uniquename, array('capi_03b66b38578f7b52cba1fe8ec70718eb', 'capi_cd2e107cf85ee0fadb50ddddd8535da1')) and $use_grey_image != 'on') return $return_error_msg ? 'grey_image_fail' : 0;

	global $wpdb;
	$attach_id = intval($wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE guid LIKE '%{$uniquename}%' LIMIT 1"));
	if ($attach_id === 0) {
		if (file_put_contents($filepath, $image) === false) return 0;
		$attach_id = wp_insert_attachment(array(
			'post_mime_type' => $image_info['mime'],
			'post_title'     => $filename,
			'post_content'   => '',
			'post_status'    => 'inherit'
		), $filepath, $post_id);
	}

	require_once(ABSPATH . 'wp-admin/includes/image.php');
	wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $filepath));
	set_post_thumbnail($post_id, $attach_id);

	return $attach_id;
}

function couponapi_badge_text() {
	$random_badges = array(__('Hot Offer', 'couponapi'), __('Super Offer', 'couponapi'), __('Best Offer', 'couponapi'), __('Best Deal', 'couponapi'), __('Hot Deal', 'couponapi'), __('Super Deal', 'couponapi'), __('Value for Money', 'couponapi'), __('Best Value', 'couponapi'));
	return $random_badges[array_rand($random_badges)];
}
