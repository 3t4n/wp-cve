<?php

if (!function_exists('add_action')) {
	echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
	exit;
}

function scode_get_shortcodes($group_id = 0) {
	global $wpdb;
	
	$group_id = (int)$group_id;
	
	if ($group_id != 0)
		$shortcodes = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."shortcodes` WHERE `group_id` = %d", $group_id), ARRAY_A);
	else
		$shortcodes = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."shortcodes`", ARRAY_A);
		
	return $shortcodes;
}

function scode_get_groups() {
	global $wpdb;
	
	$groups = $wpdb->get_results("SELECT *, (SELECT COUNT(`shortcode_id`) FROM `".$wpdb->prefix."shortcodes` sh WHERE sh.`group_id` = shg.`group_id`) AS count FROM `".$wpdb->prefix."shortcodes_groups` shg ORDER BY `group_name`", ARRAY_A);
		
	return $groups;
}

function scode_get_group_name($group_id, $groups) {
	foreach ($groups as $group)
		if ($group['group_id'] == $group_id) return $group['group_name'];
	
	return '';
}

function scode_get_plural_form($number) {
	$after = array(__('shortcode', 'scode'), __('shortcodes ', 'scode'), __('shortcodes', 'scode'));
	$cases = array (2, 0, 1, 1, 1, 2);
	return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

?>