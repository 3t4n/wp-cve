<?php

/**
 * Trigger this file on Plugin uninstall
 *
 *  @package Channelize Shopping

 */

defined('ABSPATH') || exit;

if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}


$args = array(
	'post_type'              => array('page'),
	'meta_query' => array(
		array(
			'key'       => 'created_by_channelize_live_shopping',
			'value'     => '1',
		),
	),
);
/**
 * Deleting the channelize cookies
 */
if (isset($_COOKIE['channelize_live_shop_access_token'])) {
	setcookie('channelize_live_shop_access_token', '', time() - 3600, '/');
}

if (isset($_COOKIE['channelize_public_key'])) {
	setcookie('channelize_public_key', '', time() - 3600, '/');
}

// The Query
$query = new WP_Query($args);

if ($query->have_posts()) {
	while ($query->have_posts()) {
		$query->the_post();
		$channelize_post_id = get_the_ID();
		wp_delete_post($channelize_post_id, true);

		$channelize_post_meta = get_post_meta($channelize_post_id);
		foreach ($channelize_post_meta as $key => $val) {
			delete_post_meta($channelize_post_id, $key);
		}
	}
}

//deleting the option values

delete_option('channelize_live_shopping');
delete_option('channelize_live_shopping_settings');
