<?php

if (!function_exists('add_action')) {
	echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
	exit;
}

add_action('wp_ajax_scode_add_shortcode', 'scode_ajax_add_shortcode');
function scode_ajax_add_shortcode() {
	$nonce = $_POST['scode_new_nonce'];

	if (!wp_verify_nonce($nonce, 'addNewShortcode')) wp_die(json_encode(array('error' => __('Nonce verify fail...', 'scode'))));
	if (!current_user_can('manage_options')) wp_die(json_encode(array('error' => __('No rights...', 'scode'))));
	
	global $wpdb;
	
	$shortcode = trim($_POST['scode_new_shortcode']);
	$value = trim($_POST['scode_new_value']);
	$description = trim($_POST['scode_new_description']);
	$newgroup = trim($_POST['scode_new_group']);
	if (isset($_POST['scode_group'])) $group = (int)$_POST['scode_group']; else $group = -1;
	
	if (empty($shortcode)) wp_die(json_encode(array('error' => __('Enter shortcode!', 'scode'))));
	if (empty($value)) wp_die(json_encode(array('error' => __('Value can not be empty!', 'scode'))));
	
	preg_match('#^[a-zA-Z0-9_.-]*$#i', $shortcode, $match);
	if (empty($match)) wp_die(json_encode(array('error' => __('Shortcode must consist only of digits 0-9, the letters a-z A-Z, dots and symbols "_" and "-"', 'scode'))));
	if (strlen($shortcode) < 2) wp_die(json_encode(array('error' => __('Shortcode must consist of more than one character!', 'scode'))));
	if (strlen($shortcode) > 40) wp_die(json_encode(array('error' => __('Shortcode must consist of a maximum of 40 characters!', 'scode'))));
	
	$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".$wpdb->prefix."shortcodes` WHERE `code` = %s", $shortcode));
	if ($count != 0) wp_die(json_encode(array('error' => __('This shortcode already exists!', 'scode'))));
	
	$description = esc_html($description);
	
	if (empty($newgroup)) {
		if ($group == -1 || $group == 0) {
			$newgroup = 'Без группы';
			$group = $wpdb->get_row($wpdb->prepare("SELECT `group_id` FROM `".$wpdb->prefix."shortcodes_groups` WHERE `group_name` = %s", $newgroup), ARRAY_A);
			if ($group) {
				$group = $group['group_id'];
				$newgroup = '';
			}
		} else {
			$count_g = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".$wpdb->prefix."shortcodes_groups` WHERE `group_id` = %d", $group));
			if ($count_g == 0) wp_die(json_encode(array('error' => __('Select group does not exist!', 'scode'))));
		}
	} else {
		preg_match('#^[а-яА-Яa-zA-Z0-9_.\-\s]*$#iu', $newgroup, $matchg);
		if (empty($matchg)) wp_die(json_encode(array('error' => __('The name of the group must contain only the numbers 0-9, the letters a-z A-Z, dots, spaces and symbols "_" and "-"', 'scode'))));
		
		$group = $wpdb->get_row($wpdb->prepare("SELECT `group_id` FROM `".$wpdb->prefix."shortcodes_groups` WHERE `group_name` = %s", $newgroup), ARRAY_A);
		if ($group) {
			$group = $group['group_id'];
			$newgroup = '';
		}
	}
	
	if (empty($newgroup)) {
		$inserted = $wpdb->insert(
					$wpdb->prefix."shortcodes",
					array('group_id' => $group, 'code' => $shortcode, 'description' => $description, 'value' => $value),
					array('%d', '%s', '%s', '%s')
				);
	} else {
		$insertGroup = $wpdb->insert(
					$wpdb->prefix."shortcodes_groups",
					array('group_name' => $newgroup),
					array('%s')
				);
		if ($insertGroup !== false) $insertGroupId = $wpdb->insert_id;
		else wp_die(json_encode(array('error' => __('Error adding group. Refer to the developers!', 'scode'))));
		
		$inserted = $wpdb->insert(
					$wpdb->prefix."shortcodes",
					array('group_id' => $insertGroupId, 'code' => $shortcode, 'description' => $description, 'value' => $value),
					array('%d', '%s', '%s', '%s')
				);
	}

	if ($inserted !== false)
		wp_die(json_encode(array('success' => __('Shortcode added! Within seconds the page will refresh...', 'scode'))));
	else
		wp_die(json_encode(array('error' => __('Failure to insert the shortcode, contact the developers!', 'scode'))));
}

add_action('wp_ajax_scode_del_shortcode', 'scode_ajax_del_shortcode');
function scode_ajax_del_shortcode() {
	$nonce = $_POST['scode_del_nonce'];
	if (!wp_verify_nonce($nonce, 'nonce_del')) wp_die(json_encode(array('error' => __('Nonce verify fail...', 'scode'))));
	if (!current_user_can('manage_options')) wp_die(json_encode(array('error' => __('No rights...', 'scode'))));
	
	$shortcodeID = intval($_POST['scode_shortcode_id']);
	
	global $wpdb;
	$deleted = $wpdb->delete($wpdb->prefix."shortcodes", array('shortcode_id' => $shortcodeID), array('%d'));
	
	if ($deleted !== false)
		wp_die(json_encode(array('success' => __('Shortcode successfully deleted!', 'scode'))));
	else
		wp_die(json_encode(array('error' => __('Failure to remove the shortcode, contact the developers!', 'scode'))));
}

add_action('wp_ajax_scode_view_shortcode', 'scode_ajax_view_shortcode');
function scode_ajax_view_shortcode() {
	$nonce = $_POST['scode_view_nonce'];
	if (!wp_verify_nonce($nonce, 'nonce_view')) wp_die(json_encode(array('error' => __('Nonce verify fail...', 'scode'))));
	if (!current_user_can('manage_options')) wp_die(json_encode(array('error' => __('No rights...', 'scode'))));
	
	$shortcodeID = intval($_POST['scode_shortcode_id']);
	
	global $wpdb;
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."shortcodes` WHERE `shortcode_id` = %d", $shortcodeID), ARRAY_A);
	
	// print_r($row);
	
	if ($row !== false) {
		$row['description'] = stripslashes($row['description']);
		$row['value'] = stripslashes($row['value']);
		$row['nonce'] = wp_create_nonce('editShortcode_'.$row['shortcode_id']);
		wp_die(json_encode(array('success' => $row)));
	} else
		wp_die(json_encode(array('error' => __('Failed to get the shortcode, contact the developers!', 'scode'))));
}

add_action('wp_ajax_scode_edit_shortcode', 'scode_ajax_edit_shortcode');
function scode_ajax_edit_shortcode() {
	$nonce = $_POST['scode_editable_nonce'];
	$shortcodeID = intval($_POST['scode_editable_shortcode_id']);
	
	if (!wp_verify_nonce($nonce, 'editShortcode_'.$shortcodeID)) wp_die(json_encode(array('error' => 'Nonce verify fail...')));
	if (!current_user_can('manage_options')) wp_die(json_encode(array('error' => 'No rights...')));
	
	$shortcode = trim($_POST['scode_editable_shortcode']);
	$value = trim($_POST['scode_editable_value']);
	$description = trim($_POST['scode_editable_description']);
	$newgroup = trim($_POST['scode_new_group']);
	$group = (int)$_POST['scode_group'];
	
	if (empty($shortcode)) wp_die(json_encode(array('error' => __('Enter shortcode!', 'scode'))));
	if (empty($value)) wp_die(json_encode(array('error' => __('Value can not be empty!', 'scode'))));
	
	preg_match('#^[a-zA-Z0-9_.-]*$#i', $shortcode, $match);
	if (empty($match)) wp_die(json_encode(array('error' => __('Shortcode must consist only of digits 0-9, the letters a-z A-Z, dots and symbols "_" and "-"', 'scode'))));
	if (strlen($shortcode) < 2) wp_die(json_encode(array('error' => __('Shortcode must consist of more than one character!', 'scode'))));
	if (strlen($shortcode) > 40) wp_die(json_encode(array('error' => __('Shortcode must consist of a maximum of 40 characters!', 'scode'))));
	
	$description = esc_html($description);
	
	global $wpdb;
	
	if (!empty($newgroup)) {
		preg_match('#^[а-яА-Яa-zA-Z0-9_.-\s]*$#iu', $newgroup, $matchg);
		if (empty($matchg)) wp_die(json_encode(array('error' => __('The name of the group must contain only the numbers 0-9, the letters a-z A-Z, dots, spaces and symbols "_" and "-"', 'scode'))));
		
		$group = $wpdb->get_row($wpdb->prepare("SELECT `group_id` FROM `".$wpdb->prefix."shortcodes_groups` WHERE `group_name` = %s", $newgroup), ARRAY_A);
		if ($group) {
			$group = $group['group_id'];
			$newgroup = '';
		}
		
	} else {
		$count_g = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".$wpdb->prefix."shortcodes_groups` WHERE `group_id` = %d", $group));
		if ($count_g == 0) wp_die(json_encode(array('error' => __('Select group does not exist!', 'scode'))));
	}
	
	if (empty($newgroup)) {
		$updated = $wpdb->update(
			$wpdb->prefix.'shortcodes',
			array('group_id' => $group, 'code' => $shortcode, 'description' => $description, 'value' => $value),
			array('shortcode_id' => $shortcodeID),
			array('%d', '%s', '%s', '%s'),
			array('%d')
		);
	} else {
		$insertGroup = $wpdb->insert(
					$wpdb->prefix."shortcodes_groups",
					array('group_name' => $newgroup),
					array('%s')
				);
		if ($insertGroup !== false) $insertGroupId = $wpdb->insert_id;
		else wp_die(json_encode(array('error' => __('Error adding group. Refer to the developers!', 'scode'))));
		
		$updated = $wpdb->update(
			$wpdb->prefix.'shortcodes',
			array('group_id' => $insertGroupId, 'code' => $shortcode, 'description' => $description, 'value' => $value),
			array('shortcode_id' => $shortcodeID),
			array('%d', '%s', '%s', '%s'),
			array('%d')
		);
	}
	
	if ($updated !== false) {
		wp_die(json_encode(array('success' => __('Shortcode updated!', 'scode'))));
	} else
		wp_die(json_encode(array('error' => __('Failure to update the shortcode, contact the characters!', 'scode'))));
}

?>