<?php

/**
 * Lnk.Bio
 *
 * @package     LnkBio
 * @author      Lnk.Bio
 * @copyright   2022 Gimucco PTE LTD
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Lnk.Bio
 * Plugin URI:  https://lnk.bio/manage/integrations/wordpress
 * Description: Auto-publish a new Lnk on your Lnk.Bio page everytime you publish a new WordPress blog post.
 * Version:     0.2.2
 * Author:      Lnk.Bio
 * Author URI:  https://lnk.bio
 * Text Domain: lnkbio
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

define('LB_BASE_API', 'https://lnk.bio/api');
define('LB_SESSION_ID', 'LB_SESSION_ID');
function lnkbio_admin() {
	include plugin_dir_path(__FILE__).'admin.php';
}
function lnkbio_load_menu() {
	$menu = add_submenu_page(
		'tools.php',
		'Lnk.Bio integration',
		'Lnk.Bio',
		'manage_options',
		'lnkbio',
		'lnkbio_admin'
	);
	add_action('load-'.$menu, 'lnkbio_load_admin_js');
}

add_filter('https_ssl_verify', '__return_false');
add_action('wp_insert_post', 'lnkbio_api_sync', 10, 3);
add_action('added_post_meta', 'lnkbio_api_syncMeta', 10, 4);
// add_action('updated_post_meta', 'lnkbio_api_syncMeta', 10, 4);


if (is_admin()) { // admin actions
	add_action('admin_menu', 'lnkbio_load_menu');
	add_action('admin_init', 'lnkbio_register_settings');
	add_action('wp_ajax_nopriv_lnkbio_api_test', 'lnkbio_api_test');
	add_action('wp_ajax_lnkbio_api_test', 'lnkbio_api_test');
	add_action('wp_ajax_nopriv_lnkbio_api_mass', 'lnkbio_api_mass');
	add_action('wp_ajax_lnkbio_api_mass', 'lnkbio_api_mass');
	add_action('wp_ajax_nopriv_lnkbio_api_getgroups', 'lnkbio_api_getgroups');
	add_action('wp_ajax_lnkbio_api_getgroups', 'lnkbio_api_getgroups');
} else {
	// non-admin enqueues, actions, and filters
}

function lnkbio_register_settings() {
	register_setting('lnkbio_options', 'lnkbio_id');
	register_setting('lnkbio_options', 'lnkbio_secret');
	register_setting('lnkbio_options', 'lnkbio_group');
}

function lnkbio_load_admin_js() {
	add_action('admin_enqueue_scripts', 'lnkbio_enqueue_admin_js');
}

function lnkbio_enqueue_admin_js() {
	wp_enqueue_script('lnkbio', plugin_dir_url(__FILE__).'lnkbio.js', array('jquery'), null, true);
}

function lnkbio_api_test() {
	$outcome = lnkbio_api_post(LB_BASE_API, ['ACTION' => 'WP_test']);
	if ($outcome->status) {
		echo 1;
	}
	die();
}

function lnkbio_get_groups_options(array $groups) {
	$options = [];
	if (!sizeof($groups)) {
		return '';
	}
	$options[] = '<option value="0">No group (uncategorized)</option>';
	$selected = get_option('lnkbio_group');
	foreach ($groups as $g) {
		if ($g->group_id && $g->group_name) {
			$sel = '';
			if ($selected == $g->group_id) {
				$sel = 'selected';
			}
			$options[] = '<option value="'.$g->group_id.'" '.$sel.'>'.$g->group_name.'</option>';
		}
	}
	return implode(PHP_EOL, $options);
}

function lnkbio_api_getgroups() {
	$outcome = lnkbio_api_post(LB_BASE_API, ['ACTION' => 'WP_getGroups']);
	if ($outcome->status) {
		$options = lnkbio_get_groups_options($outcome->info->groups);
		if ($options) {
			echo json_encode(['status' => true, 'options' => $options]);
		} else {
			echo json_encode(['status' => false, 'error' => 'invalid']);
		}
	} else {
		echo json_encode(['status' => false, 'error' => 'invalid']);
	}
	wp_die();
}

function lnkbio_api_post(string $url, array $data) {
	$data['id'] = get_option('lnkbio_id');
	$data['secret'] = get_option('lnkbio_secret');
	$params = array(
		'method' => 'POST',
		'body' => http_build_query($data)
	);
	$curl = new WP_Http_Curl();
	$outcome = $curl->request($url, $params);
	$outcome = $outcome['body'];
	return json_decode($outcome);
}

function lnkbio_api_sync($post_id, $post, $update) {
	$local_post = clone($post);
	unset($local_post->post_content);
	if ($post->post_status === 'publish') {
		if (!get_option('lnkbio_id') || !get_option('lnkbio_secret')) {
			return;
		}
		wp_remote_post(
			LB_BASE_API,
			[
				'blocking' => false,
				'body' => [
					'ACTION' 	=> 'WP_sync',
					'post_id' 	=> $post_id,
					'post'    	=> json_encode($local_post),
					'id'		=> get_option('lnkbio_id'),
					'secret' 	=> get_option('lnkbio_secret'),
					'permalink' => get_permalink($post),
					'is_pub'	=> true,
					'image'		=> get_the_post_thumbnail_url($post_id, 'full'),
					'group_id' 	=> get_option('lnkbio_group'),
				]
			]
		);
	}
}

function lnkbio_api_syncMeta(int $meta_id, int $object_id, string $meta_key, $meta_value) {
	if (!$meta_value || $meta_key != "_thumbnail_id") {
		return;
	}
	$post = get_post($object_id);
	unset($post->post_content);
	if ($post->post_status === 'publish') {
		if (!get_option('lnkbio_id') || !get_option('lnkbio_secret')) {
			return;
		}
		wp_remote_post(
			LB_BASE_API,
			[
				'blocking' => false,
				'body' => [
					'ACTION' 	=> 'WP_sync',
					'post_id' 	=> $object_id,
					'post'    	=> json_encode($post),
					'id'		=> get_option('lnkbio_id'),
					'secret' 	=> get_option('lnkbio_secret'),
					'permalink' => get_permalink($post),
					'image' 	=> wp_get_attachment_url($meta_value),
					'is_img'	=> true,
					'group_id' 	=> get_option('lnkbio_group'),
				]
			]
		);
	}
}

function lnkbio_api_mass() {
	@session_start();
	if (!empty($_POST['force_restart']) && $_POST['force_restart'] == "true") {
		unset($_SESSION[LB_SESSION_ID]);
	}
	if (!get_option('lnkbio_id') || !get_option('lnkbio_secret')) {
		echo json_encode(['status' => false, 'error' => 'invalid status']);
		wp_die();
	}
	if (empty($_SESSION[LB_SESSION_ID])) {
		$number = wp_count_posts('post');
		$_SESSION[LB_SESSION_ID] = ['todo' => $number->publish, 'done' => 0];
		echo json_encode(['status' => true, 'num_posts' => $number->publish, 'num_done' => 0, 'last_post' => 0 ]);
		wp_die();
	}


	$myposts = get_posts(array(
		'orderby' => 'date',
		'order' => 'asc',
		'posts_per_page' => 1,
		'offset'         => $_SESSION[LB_SESSION_ID]['done']
	));

	if ($myposts) {
		foreach ($myposts as $post) {
			$_SESSION[LB_SESSION_ID]['done']++;
			setup_postdata($post);
			$test = wp_remote_post(
				LB_BASE_API,
				[
					'blocking' => true,
					'body' => [
						'ACTION' 	=> 'WP_sync',
						'post_id' 	=> $post->ID,
						'post'    	=> json_encode($post),
						'id'		=> get_option('lnkbio_id'),
						'secret' 	=> get_option('lnkbio_secret'),
						'permalink' => get_permalink($post),
						'image' 	=> get_the_post_thumbnail_url($post),
						'group_id' 	=> get_option('lnkbio_group'),
					]
				]
			);
			echo json_encode(['status' => true, 'num_posts' => $_SESSION[LB_SESSION_ID]['todo'], 'num_done' => $_SESSION[LB_SESSION_ID]['done'], 'last_post' => 0 ]);
			sleep(1);
		}
		wp_reset_postdata();
	} else {
		echo json_encode(['status' => true, 'num_posts' => $_SESSION[LB_SESSION_ID]['todo'], 'num_done' => $_SESSION[LB_SESSION_ID]['done'], 'last_post' => 0, 'completed' => true ]);
	}
	wp_die();
}
