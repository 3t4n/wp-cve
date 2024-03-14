<?php
/*
Plugin Name: Xmas Decoration
Plugin URI: https://wordpress.org/plugins/xmas-decoration/
Description: This plugin adds Xmas Decoration
Version: 1.3
Author: Mr. Meo
Author URI: https://github.com/trananhmanh89/
*/

defined('WPINC') or die('error');

// site

function xmas_decoration_load_script_style()
{
	wp_enqueue_script('jquery');

	wp_register_script('xmas-decoration-yuiloader', plugins_url('site/assets/js/yuiloader-dom-event.js', __FILE__));
	wp_enqueue_script('xmas-decoration-yuiloader');

	wp_register_script('xmas-decoration-holiday-bells', plugins_url('site/assets/js/holiday_bells.js', __FILE__));
	wp_enqueue_script('xmas-decoration-holiday-bells');

	wp_register_style('xmas-decoration-style', plugins_url('site/assets/css/additional.css', __FILE__));
	wp_enqueue_style('xmas-decoration-style');
}

function xmas_decoration()
{
	$show = true;
	$range = get_option('xmas_decoration_body_date_range');
	if ($range) {
		$now = current_time('Y-m-d');
		$dates = array_map(function($d) {
			return trim($d);
		}, explode('to', $range));

		if (count($dates) === 2) {
			list($start, $end) = $dates;
			if ($start > $now || $end < $now) {
				$show = false;
			}
		}
		
		if (count($dates) === 1) {
			list($date) = $dates;
			if ($date !== $now) {
				$show = false;
			}
		}
	}

	if ($show) {
		include __DIR__ . '/site/tmpl/default.php';
	}
}

add_action('wp_footer', 'xmas_decoration');
add_action('wp_footer', 'xmas_decoration_load_script_style');

// admin

function xmas_decoration_register_settings()
{
	add_option('xmas_decoration_body_padding_top', '100px');
	add_option('xmas_decoration_bar_type', 'fixed');
	add_option('xmas_decoration_body_date_range', '');

	register_setting('xmas_decoration_settings', 'xmas_decoration_body_padding_top');
	register_setting('xmas_decoration_settings', 'xmas_decoration_bar_type');
	register_setting('xmas_decoration_settings', 'xmas_decoration_body_date_range');
}
add_action('admin_init', 'xmas_decoration_register_settings');

function xmas_decoration_register_options_page()
{
	add_options_page('Xmas Decoration', 'Xmas Decoration', 'manage_options', 'xmas_decoration', 'xmas_decoration_admin_page');
}
add_action('admin_menu', 'xmas_decoration_register_options_page');

function xmas_decoration_admin_page()
{
	include __DIR__ . '/admin/tmpl/default.php';
}