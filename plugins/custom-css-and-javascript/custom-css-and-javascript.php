<?php
/**
 * Plugin Name: Custom CSS and JavaScript
 * Description: Easily add custom CSS and JavaScript code to your WordPress site.
 * Version: 2.0.15
 * Author: WP Zone
 * Author URI: https://wpzone.co/?utm_source=custom-css-and-javascript&utm_medium=link&utm_campaign=wp-plugin-author-uri
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

/*
	Custom CSS and JavaScript plugin
    Copyright (C) 2023 WP Zone

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

define('HM_CUSTOM_CSS_JS_VERSION', '2.0.15');

add_action('wp_enqueue_scripts', 'hm_custom_css_js_scripts', 999999);
function hm_custom_css_js_scripts() {
	if (current_user_can('wpz_custom_css_js')) {
		wp_enqueue_script('hm_custom_js', get_site_url(null, '/index.php').'?hm_custom_js_draft=1', array(), time());
		wp_enqueue_style('hm_custom_css', get_site_url(null, '/index.php').'?hm_custom_css_draft=1', array(), time());
	} else {
		$uploadDir = wp_upload_dir();
		if (is_ssl()) {
			$uploadDir['baseurl'] = set_url_scheme($uploadDir['baseurl'], 'https');
		}
		if (file_exists($uploadDir['basedir'].'/hm_custom_css_js/custom.js'))
			wp_enqueue_script('hm_custom_js', $uploadDir['baseurl'].'/hm_custom_css_js/custom.js', array(), get_option('hm_custom_javascript_ver', 1));
		if (file_exists($uploadDir['basedir'].'/hm_custom_css_js/custom.css'))
			wp_enqueue_style('hm_custom_css', $uploadDir['baseurl'].'/hm_custom_css_js/custom.css', array(), get_option('hm_custom_css_ver', 1));
	}
}
add_action('admin_menu', 'hm_custom_css_admin_menu');
function hm_custom_css_admin_menu() {
	add_submenu_page('themes.php', 'Custom CSS', 'Custom CSS', 'wpz_custom_css_js', 'hm_custom_css', 'hm_custom_css_js_page');
	add_submenu_page('themes.php', 'Custom JavaScript', 'Custom JavaScript', 'wpz_custom_css_js', 'hm_custom_js', 'hm_custom_css_js_page');
}
add_action('admin_menu', 'hm_custom_js_admin_menu');
function hm_custom_js_admin_menu() {
	add_submenu_page('themes.php', 'Custom CSS', 'Custom CSS', 'wpz_custom_css', 'themes.php?page=hm_custom_css_js#JavaScript');
	add_submenu_page('themes.php', 'Custom JavaScript', 'Custom JavaScript', 'wpz_custom_js', 'themes.php?page=hm_custom_css_js#JavaScript');
}

add_action('admin_enqueue_scripts', 'hm_custom_css_js_admin_scripts');
function hm_custom_css_js_admin_scripts($hook) {
	if ($hook != 'appearance_page_hm_custom_css' && $hook != 'appearance_page_hm_custom_js') {
		return;
	}
	wp_enqueue_script('hm_custom_css_js_codemirror', plugins_url('codemirror/codemirror.js', __FILE__));
		wp_enqueue_script('hm_custom_css_js_codemirror_mode_css', plugins_url('codemirror/mode/css.js', __FILE__));
		wp_enqueue_script('hm_custom_css_js_codemirror_mode_js', plugins_url('codemirror/mode/javascript.js', __FILE__));
	wp_enqueue_script('hm_custom_css_js_codemirror_dialog', plugins_url('codemirror/addon/dialog/dialog.js', __FILE__));
	wp_enqueue_script('hm_custom_css_js_codemirror_matchbrackets', plugins_url('codemirror/addon/edit/matchbrackets.js', __FILE__));
	wp_enqueue_script('hm_custom_css_js_codemirror_search', plugins_url('codemirror/addon/search/search.js', __FILE__));
	wp_enqueue_script('hm_custom_css_js_codemirror_searchcursor', plugins_url('codemirror/addon/search/searchcursor.js', __FILE__));
	wp_enqueue_script('hm_custom_css_js_codemirror_matchhighlighter', plugins_url('codemirror/addon/search/match-highlighter.js', __FILE__));
	wp_enqueue_script('hm_custom_css_js_codemirror_annotatescrollbar', plugins_url('codemirror/addon/scroll/annotatescrollbar.js', __FILE__));
	wp_enqueue_script('hm_custom_css_js_codemirror_matchesonscrollbar', plugins_url('codemirror/addon/search/matchesonscrollbar.js', __FILE__));
	
	wp_enqueue_style('hm_custom_css_js_codemirror', plugins_url('codemirror/codemirror.css', __FILE__));
	wp_enqueue_style('hm_custom_css_js_codemirror_dialog', plugins_url('codemirror/addon/dialog/dialog.css', __FILE__));
	wp_enqueue_style('hm_custom_css_js_codemirror_matchesonscrollbar', plugins_url('codemirror/addon/search/matchesonscrollbar.css', __FILE__));
	wp_enqueue_script('hm_custom_css_js', plugins_url('js/custom-css-and-javascript.min.js', __FILE__), array('jquery'), HM_CUSTOM_CSS_JS_VERSION);
	wp_localize_script('hm_custom_css_js', 'pp_custom_css_js_config', array(
		'api_url' => esc_url( admin_url( 'admin-ajax.php?ppccj_nonce='.wp_create_nonce('ppccj_ajax') ) )
	));
	wp_enqueue_style('hm_custom_css_js', plugins_url('css/custom-css-and-javascript.min.css', __FILE__));
	wp_enqueue_style('hm_custom_css_js_admin', plugins_url('css/admin.min.css', __FILE__));

	wp_enqueue_style( 'hm_custom_css_js_addons-admin', plugins_url( 'includes/admin/addons/css/admin.min.css', __FILE__), null, HM_CUSTOM_CSS_JS_VERSION );

}

add_action('wp_ajax_hm_custom_css_js_save', 'hm_custom_css_js_save');
function hm_custom_css_js_save() {
	if (!current_user_can('wpz_custom_css_js') || !isset($_REQUEST['ppccj_nonce']) || !wp_verify_nonce($_REQUEST['ppccj_nonce'], 'ppccj_ajax') || empty($_POST['mode']) || !isset($_POST['code']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$_POST['code'] = ( function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() ? stripslashes($_POST['code']) : $_POST['code']);
	
	$rev_id = wp_insert_post(array(
		'post_content' => $_POST['code'],
		'post_status' => 'draft',
		'post_type' => 'hm_custom_'.$_POST['mode'],
	));
	if ($rev_id === false)
		wp_send_json_error();
	
	wp_send_json_success($rev_id);
}

add_action('wp_ajax_hm_custom_css_js_publish', 'hm_custom_css_js_publish');
function hm_custom_css_js_publish() {
	if (!current_user_can('wpz_custom_css_js') || !isset($_REQUEST['ppccj_nonce']) || !wp_verify_nonce($_REQUEST['ppccj_nonce'], 'ppccj_ajax') || empty($_POST['mode']) || !isset($_POST['rev']) || !is_numeric($_POST['rev']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$post = get_post($_POST['rev']);
	if ($post->post_type != 'hm_custom_'.$_POST['mode'])
		wp_send_json_error();
	
	$uploadDir = wp_upload_dir();
	if (!is_dir($uploadDir['basedir'].'/hm_custom_css_js'))
		mkdir($uploadDir['basedir'].'/hm_custom_css_js') or wp_send_json_error();
	$outputFile = $uploadDir['basedir'].'/hm_custom_css_js/custom.'.($_POST['mode'] == 'css' ? 'css' : 'js');
	if (file_put_contents($outputFile, $post->post_content) === false)
		wp_send_json_error();
	if (empty($_POST['minify'])) {
		update_option('hm_custom_'.$_POST['mode'].'_minify', false);
	} else {
		update_option('hm_custom_'.$_POST['mode'].'_minify', true);
		require_once(__DIR__.'/minify/src/Minify.php');
		require_once(__DIR__.'/minify/src/Exception.php');
		if ($_POST['mode'] == 'css') {
			require_once(__DIR__.'/minify/src/CSS.php');
			require_once(__DIR__.'/minify/src/Converter.php');
			$minifier = new MatthiasMullie\Minify\CSS;
		} else {
			require_once(__DIR__.'/minify/src/JS.php');
			$minifier = new MatthiasMullie\Minify\JS;
		}
		$minifier->add($outputFile);
		$minifier->minify($outputFile);
	}
	
	update_option('hm_custom_'.$_POST['mode'].'_ver', time());
	
	// Unpublish previous revisions
	$wp_query = new WP_Query(array(
		'post_type' => 'hm_custom_'.$_POST['mode'],
		'post_status' => 'publish',
		'fields' => 'ids',
		'nopaging' => true
	));
	$posts = $wp_query->get_posts();
	foreach ($posts as $postId) {
		if (!wp_update_post(array(
		'ID' => $postId,
		'post_status' => 'draft',
		)))
		wp_send_json_error();
	}
	
	if (!wp_update_post(array(
		'ID' => $_POST['rev'],
		'post_status' => 'publish',
		'post_date' => current_time('Y-m-d H:i:s'),
		)))
		wp_send_json_error();
	
	wp_send_json_success();
}

add_action('wp_ajax_hm_custom_css_js_delete_revision', 'hm_custom_css_js_delete_revision');
function hm_custom_css_js_delete_revision() {
	if (!current_user_can('wpz_custom_css_js') || !isset($_REQUEST['ppccj_nonce']) || !wp_verify_nonce($_REQUEST['ppccj_nonce'], 'ppccj_ajax') || empty($_POST['mode']) || !isset($_POST['rev']) || !is_numeric($_POST['rev']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$post = get_post($_POST['rev']);
	if ($post->post_type != 'hm_custom_'.$_POST['mode'] || $post->post_status == 'publish')
		wp_send_json_error();
	
	
	if (!wp_delete_post($post->ID, true))
		wp_send_json_error();
	
	wp_send_json_success();
}

add_action('wp_ajax_hm_custom_css_js_delete_revisions', 'hm_custom_css_js_delete_revisions');
function hm_custom_css_js_delete_revisions() {
	if (!current_user_can('wpz_custom_css_js') || !isset($_REQUEST['ppccj_nonce']) || !wp_verify_nonce($_REQUEST['ppccj_nonce'], 'ppccj_ajax') || empty($_POST['mode']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$wp_query = new WP_Query(array(
		'post_type' => 'hm_custom_'.$_POST['mode'],
		'post_status' => 'draft',
		'fields' => 'ids',
		'nopaging' => true
	));
	$posts = $wp_query->get_posts();
	foreach ($posts as $postId) {
		if (!wp_delete_post($postId, true))
			wp_send_json_error();
	}
	
	wp_send_json_success();
}


add_action('wp_ajax_hm_custom_css_js_get_revisions', 'hm_custom_css_js_get_revisions');
function hm_custom_css_js_get_revisions() {
	if (!current_user_can('wpz_custom_css_js') || !isset($_REQUEST['ppccj_nonce']) || !wp_verify_nonce($_REQUEST['ppccj_nonce'], 'ppccj_ajax') || empty($_POST['mode']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();

	$wp_query = new WP_Query();
	$posts = $wp_query->query(array(
		'post_type' => 'hm_custom_'.$_POST['mode'],
		'post_status' => 'any',
		'nopaging' => true
	));
	
	
	$revisions = array();
	if (empty($posts)) {
		$uploadDir = wp_upload_dir();
		$customFile = $uploadDir['basedir'].'/hm_custom_css_js/custom.'.($_POST['mode'] == 'css' ? 'css' : 'js');
		if (file_exists($customFile)) {
			$contents = file_get_contents($customFile);
			if ($contents === false)
				wp_send_json_error();
			$rev_id = wp_insert_post(array(
				'post_content' => $contents,
				'post_status' => 'publish',
				'post_type' => 'hm_custom_'.$_POST['mode'],
			));
			$revisions[] = array('id' => $rev_id, 'rev_date' => current_time('Y-m-d H:i:s'), 'published' => true);
		}
	} else {
		foreach ($posts as $post) {
			$revisions[] = array('id' => $post->ID, 'rev_date' => $post->post_date, 'published' => ($post->post_status == 'publish'));
		}
	}
	
	wp_send_json_success($revisions);
}

add_action('wp_ajax_hm_custom_css_js_get_revision', 'hm_custom_css_js_get_revision');
function hm_custom_css_js_get_revision() {
	if (!current_user_can('wpz_custom_css_js') || !isset($_REQUEST['ppccj_nonce']) || !wp_verify_nonce($_REQUEST['ppccj_nonce'], 'ppccj_ajax') || empty($_POST['mode']) || !isset($_POST['rev']) || !is_numeric($_POST['rev']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$post = get_post($_POST['rev']);
	if ($post->post_type != 'hm_custom_'.$_POST['mode'])
		wp_send_json_error();
	
	wp_send_json_success(array(
		'id' => $post->ID,
		'content' => $post->post_content
	));
}

add_action('init', 'hm_custom_css_js_init');
function hm_custom_css_js_init() {
	register_post_type('hm_custom_css');
	register_post_type('hm_custom_javascript');
	
	$adminRole = get_role('administrator');
	if ($adminRole) {
		$adminRole->add_cap('wpz_custom_css_js');
	}
	
	if (!empty($_GET['hm_custom_css_draft'])) {
		$wp_query = new WP_Query(array(
			'post_type' => 'hm_custom_css',
			'post_status' => 'any',
			'posts_per_page' => 1
		));
		$posts = $wp_query->get_posts();
		header('Content-Type: text/css');
		if (isset($posts[0]))
			echo($posts[0]->post_content);
		exit;
	}
	if (!empty($_GET['hm_custom_js_draft'])) {
		$wp_query = new WP_Query(array(
			'post_type' => 'hm_custom_javascript',
			'post_status' => 'any',
			'posts_per_page' => 1
		));
		$posts = $wp_query->get_posts();
		header('Content-Type: text/javascript');
		if (isset($posts[0]))
			echo($posts[0]->post_content);
		exit;
	}
}

function hm_custom_css_js_page() {
	$potent_slug = 'custom-css-and-javascript';
	include(__DIR__.'/includes/admin-page.php');
}

/* Review/donate notice */

register_activation_hook(__FILE__, 'hm_custom_css_js_first_activate');
function hm_custom_css_js_first_activate() {
	$pre = 'hm_custom_css_js';
	$firstActivate = get_option($pre.'_first_activate');
	if (empty($firstActivate)) {
		update_option($pre.'_first_activate', time());
	}
}
if (is_admin() && get_option('hm_custom_css_js_rd_notice_hidden') != 1 && time() - get_option('hm_custom_css_js_first_activate') >= (14*86400)) {
	add_action('admin_notices', 'hm_custom_css_js_rd_notice');
	add_action('wp_ajax_hm_custom_css_js_rd_notice_hide', 'hm_custom_css_js_rd_notice_hide');
}
function hm_custom_css_js_rd_notice() {
	$pre = 'hm_custom_css_js';
	$slug = 'custom-css-and-javascript';
	echo('
		<div id="'.$pre.'_rd_notice" class="updated notice is-dismissible"><p>Does the <strong>Custom CSS and JavaScript</strong> plugin make your life easier?
		Please support our free plugin by <a href="https://wordpress.org/support/view/plugin-reviews/'.$slug.'" target="_blank">writing a review</a>
		Thanks!</p></div>
		<script>jQuery(document).ready(function($){$(\'#'.$pre.'_rd_notice\').on(\'click\', \'.notice-dismiss\', function(){jQuery.post(ajaxurl, {action:\'hm_custom_css_js_rd_notice_hide\'})});});</script>
	');
}
function hm_custom_css_js_rd_notice_hide() {
	$pre = 'hm_custom_css_js';
	update_option($pre.'_rd_notice_hidden', 1);
}

add_action('admin_init', 'hm_custom_css_js_admin_init');

function hm_custom_css_js_admin_init() {
	if ( is_admin() ) {
		include(__DIR__.'/includes/admin/addons/addons.php');
	}
}

add_filter('user_has_cap', 'hm_custom_css_js_filter_capabilities');
function hm_custom_css_js_filter_capabilities($caps) {
	if (!empty($caps['edit_theme_options'])) {
		$caps['wpz_custom_css_js'] = true;
	}
	return $caps;
}