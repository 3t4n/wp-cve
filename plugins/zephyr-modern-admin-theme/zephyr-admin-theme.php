<?php

/**
 * @package ZephyrAdminTheme
 *
 * Plugin Name: Zephyr Admin Theme
 * Description: A modern theme to make your WordPress dashboard look more stylish and change things up a bit.
 * Plugin URI: 	https://zephyr-one.com
 * Version: 	1.4.1
 * Author: 		Dylan James
 * License: 	GPLv2 or later
 * Text Domain: zephyr-admin-theme
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) die;

define('ZEPHYR_THEME_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ZEPHYR_THEME_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ZEPHYR_THEME_PLUGIN', plugin_basename(__FILE__));

require('includes/zephyr-utillities.php');

function zat_enqueue_admin_scripts() {
	$version = '1.4.0';
	$settings = zat_get_settings();

	wp_enqueue_media();
	wp_enqueue_style('wp-color-picker');
	wp_register_style('zephyr-admin-theme-styles', ZEPHYR_THEME_PLUGIN_URL . '/assets/css/zephyr-admin-theme.css', [], $version);
	wp_enqueue_style('zephyr-admin-theme-styles');
	wp_add_inline_style('zephyr-admin-theme-styles', zat_get_custom_css());
	wp_enqueue_script('wp-color-picker');
	wp_register_script('zephyr-admin-theme-script', ZEPHYR_THEME_PLUGIN_URL . '/assets/js/zephyr-admin-theme.js', [], $version);
	wp_enqueue_script('zephyr-admin-theme-script');
	wp_localize_script('zephyr-admin-theme-script', 'zat_localized', [
		'settings' => zat_get_settings()
	]);

	if ($settings['theme_mode'] == "dark") {
		wp_register_style('zephyr-admin-theme-dark-styles', ZEPHYR_THEME_PLUGIN_URL . '/assets/css/zephyr-dark-theme.css', [], $version);
		wp_enqueue_style('zephyr-admin-theme-dark-styles');
		wp_add_inline_style('zephyr-admin-theme-dark-styles', zat_get_custom_dark_css());
	}
}

add_action('plugins_loaded', 'zephyr_admin_theme_init');

function zephyr_admin_theme_init() {
	zat_check_save_settings();
	add_action('admin_enqueue_scripts', 'zat_enqueue_admin_scripts');
}

function zat_theme_pages() {
	add_theme_page('Zephyr Admin Theme Settings', 'Zephyr Admin Theme', 'edit_theme_options', 'zephyr_admin_theme_settings', 'zat_settings_page');
}
add_action('admin_menu', 'zat_theme_pages');

function zat_settings_page() {
	include(ZEPHYR_THEME_PLUGIN_PATH . '/pages/settings.php');
}

function zat_get_settings() {
	$settings = get_option('zephyr_admin_theme_settings', []);
	return wp_parse_args($settings, [
		'primary_color'   => '#2d92e5',
		'secondary_color' => '#001bce',
		'background_color' => '#f4f4f4',
		'button_primary'  => '#2d92e5',
		'button_hover'    => '#001bce',
		'gradient_start'  => '#2d92e5',
		'gradient_end'    => '#001bce',
		'text_color'	  => '#fff',
		'hide_login_logo' => true,
		'login_logo'	  => '',
		'dashboard_logo'  => '',
		'login_redirect'  => '',
		'admin_bar_shadow' => false,
		'theme_mode'	   => 'light',
		'font'			   => 'Roboto'
	]);
}

function zat_save_settings($args) {
	$defaults = zat_get_settings();
	$settings = wp_parse_args($args, $defaults);
	update_option('zephyr_admin_theme_settings', $settings);
	return $settings;
}

function zat_get_custom_css() {
	$settings = zat_get_settings();
	$primary = $settings['primary_color'];
	$secondary = $settings['secondary_color'];
	$background_color = $settings['background_color'];
	$gradient_start = $settings['gradient_start'];
	$gradient_end = $settings['gradient_end'];
	$button_primary = $settings['button_primary'];
	$button_hover = $settings['button_hover'];
	$text_color = $settings['text_color'];
	$primary_white = zat_adjust_brightness($settings['primary_color'], 200);
	$font = $settings['font'];

	$html = "

	@import url('https://fonts.googleapis.com/css?family={$font}');


	html, body, #wpadminbar span:not(.ab-icon),
	#wpadminbar a:not(.ab-icon){
	  font-family: '{$font}', sans-serif !important;
	}
	body {
		background-color: {$background_color} !important;
	}
	#wpadminbar {
	  background: {$gradient_start} !important;
	  background: -moz-linear-gradient(-45deg, {$gradient_start} 0%, {$gradient_end} 100%) !important;
	  background: -webkit-linear-gradient(-60deg, {$gradient_end} 0%,{$gradient_start} 100%) !important;
	  background: linear-gradient(135deg, {$gradient_start} 0%,{$gradient_end} 100%);
	  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$gradient_start}', endColorstr='{$gradient_end}',GradientType=1 ) !important;
	}

	#adminmenuback {
	  background: {$gradient_start} !important;
	  background: -moz-linear-gradient(-45deg, {$gradient_start} 0%, {$gradient_end} 100%) !important;
	  background: -webkit-linear-gradient(-60deg, {$gradient_end} 0%,{$gradient_start} 100%) !important;
	  background: linear-gradient(135deg, {$gradient_start} 0%,{$gradient_end} 100%);
	  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$gradient_start}', endColorstr='{$gradient_end}',GradientType=1 ) !important;
	}

	#adminmenu .wp-not-current-submenu.opensub  .wp-submenu.wp-submenu-wrap  {
	  background: {$gradient_start} !important;
	  background: -moz-linear-gradient(-45deg, {$gradient_start} 0%, {$gradient_end} 100%) !important;
	  background: -webkit-linear-gradient(-60deg, {$gradient_end} 0%,{$gradient_start} 100%) !important;
	  background: linear-gradient(135deg, {$gradient_start} 0%,{$gradient_end} 100%);
	  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$gradient_start}', endColorstr='{$gradient_end}',GradientType=1 ) !important;
	  color: {$text_color} !important;
	}
	#adminmenu li:not(.wp-has-current-submenu) .wp-submenu-wrap {
	  background: {$gradient_start} !important;
	  background: -moz-linear-gradient(-45deg, {$gradient_start} 0%, {$gradient_end} 100%) !important;
	  background: -webkit-linear-gradient(-60deg, {$gradient_end} 0%,{$gradient_start} 100%) !important;
	  background: linear-gradient(135deg, {$gradient_start} 0%,{$gradient_end} 100%);
	  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$gradient_start}', endColorstr='{$gradient_end}',GradientType=1 ) !important;
	  color: {$text_color} !important;
	}

	a,
	a:hover {
	  color: {$secondary} !important;
	}

	.button:not(.wp-color-result),
	.components-button.is-default,
	.wp-core-ui .button-secondary {
	  color: {$button_primary} !important;
	  border: 1.5px solid {$button_primary} !important;
	}

	.button:not(.button-primary):not(.wp-color-result):hover,
	.components-button.is-default:hover,
	.wp-core-ui .button-secondary:hover {
	  background: {$button_hover} !important;
	  border-color: {$button_hover} !important;
	}

	.page-title-action:hover, .button.button-primary:hover {
		background-color: {$secondary} !important;
		border-color: {$secondary} !important;
	}

	input[type='text']:hover, input[type='text']:focus, input[type='text']:active, input[type='url']:hover, input[type='url']:focus, input[type='url']:active, input[type='email']:hover, input[type='email']:focus, input[type='email']:active, input[type='number']:hover, input[type='number']:focus, input[type='number']:active, input[type='search']:hover, input[type='search']:focus, input[type='search']:active, textarea:hover, textarea:focus, textarea:active {
		border-color: {$primary} !important;
	}

	#wpadminbar .ab-sub-wrapper {
		background: {$secondary} !important;
	}

	code,
	kbd {
	  background: {$primary_white} !important;
	  color: {$primary} !important;
	  padding: 4px;
	  border-radius: 3px;
	}

	code::selection,
	kbd::selection {
	  background: {$primary};
	  color: #fff !important;
	}
	code::-moz-selection,
	kbd::-moz-selection {
	  background: {$primary};
	  color: #fff !important;
	}

	::selection {
	  background: {$primary};
	  color: #fff !important;
	}

	input[type='checkbox']:checked {
	  background: {$primary} !important;
	  color: #fff !important;
	  border: 1px solid {$primary} !important;
	}
	input[type='checkbox']:hover {
	  background: {$primary} !important;
	  color: #fff !important;
	  border: 1px solid {$primary} !important;
	}

	select:hover {
	    background: {$button_hover} !important;
	    border-color: {$button_hover} !important;
	    color: #fff !important;
	}

	select {
	    box-shadow: none;
	    border: 1.5px solid {$button_primary} !important;
	    color: {$button_primary} !important;
	}

	.update-nag {
		border-color: {$primary} !important;
	}

	.wp-list-table tbody tr.active th, .wp-list-table tbody tr.active td {
		background: {$primary} !important;
	}

	.page-title-action, .button.button-primary,
	.subsubsub .count, .title-count {
		background: {$primary} !important;
		border-color: {$primary} !important;
		color: #fff !important;
	}

	.components-button.is-primary,
	.page-title-action, .button.button-primary, .subsubsub .count, .title-count {
		background: {$button_primary} !important;
		border-color: {$button_primary} !important;
		color: #fff !important;
	}
	.components-button.is-primary:hover,
	.page-title-action:hover, .button.button-primary:hover {
		background: {$button_hover} !important;
	}
	#plugin-search-input:hover, #plugin-search-input:focus, #plugin-search-input:active {
		border-color: {$primary} !important;
	}
	.tablenav-pages span,
	.screen-meta-toggle .button:after {
		color: {$primary} !important;
	}

	.wp-core-ui .button-link {
		color:  {$primary} !important;
		outline: none !important;
		box-shadow: none !important;
	}

	.view-switch a.current:before {
		color: {$primary} !important;
	}
	.update-message.notice {
		background: {$primary} !important;
		border-color: {$secondary} !important;
	}
	.theme-browser .theme.add-new-theme a:focus:after, .theme-browser .theme.add-new-theme a:hover:after {
		background: {$primary} !important;
	}
	.theme-browser .theme.add-new-theme a:focus span:after, .theme-browser .theme.add-new-theme a:hover span:after {
		color: {$primary};
	}
	.wp-core-ui .button-primary-disabled, .wp-core-ui .button-primary.disabled, .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary[disabled] {
	  background: {$primary} !important;
	  border-color: {$primary} !important;
	  color: #fff !important;
	  opacity: .4;
	}
	.plugin-card .update-now:before {
		color: {$button_primary};
	}
	.plugin-card .update-now:hover:before {
		color: #fff !important;
	}
	.filter-links .current,
	.filter-links a:hover{
	  border-bottom: 2px solid {$primary} !important;
	}

	.button.installing:before, .button.updating-message:before, .import-php .updating-message:before, .update-message p:before, .updating-message p:before {
	  color: {$button_primary};
	}
	.editor-notices .notice {
	    border: none;
	    border: 1px dashed {$primary} !important;
	    color: {$primary};
	    box-shadow: none;
	}
	#adminmenu li, #adminmenu li:hover, #adminmenu a:hover, #adminmenu li:hover a, #adminmenu li * ,
	#adminmenu div.wp-menu-image:before,
	#wpadminbar li:hover a, #wpadminbar li, #wpadminbar li:hover, #wpadminbar a:hover, #wpadminbar li:hover a, #wpadminbar li *{
	  color: {$text_color} !important;
	}
	#adminmenu .current div.wp-menu-image:before, #adminmenu .wp-has-current-submenu div.wp-menu-image:before, #adminmenu a.current:hover div.wp-menu-image:before, #adminmenu a.wp-has-current-submenu:hover div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before,
	#adminmenu li a:focus div.wp-menu-image:before, #adminmenu li.opensub div.wp-menu-image:before, #adminmenu li:hover div.wp-menu-image:before {
		color: {$text_color} !important;
	}
	#wpadminbar #adminbarsearch:before, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before {
		color: {$text_color} !important;
	}

	#wpadminbar #adminbarsearch:before, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before {
		opacity: .5
	}

	#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover>a, #wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:focus, #wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:hover, #wpadminbar li #adminbarsearch.adminbar-focused:before, #wpadminbar li .ab-item:focus .ab-icon:before, #wpadminbar li .ab-item:focus:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover #adminbarsearch:before, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover {
		color: {$text_color} !important;
	}

	#adminmenu li .update-plugins {
		border-color: {$text_color} !important;
	}
	";

	if ($settings['admin_bar_shadow']) {
		$html .= "
			#wpadminbar {
			  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.07), 0 2px 3px rgba(0, 0, 0, 0.07);
			}
		";
	}

	return $html;
}

function zat_get_custom_dark_css() {
	$settings = zat_get_settings();
	$primary = $settings['primary_color'];
	$html = "#adminmenu li:hover {
	  background: {$primary} !important;
	}";
	return $html;
}

function zat_custom_login_css() {
	$settings = zat_get_settings();
	$primary = $settings['primary_color'];
	$secondary = $settings['secondary_color'];
	$gradient_start = $settings['gradient_start'];
	$gradient_end = $settings['gradient_end'];

	$html = "
		body.login {
			background: {$gradient_start} !important;
			background: -moz-linear-gradient(-45deg, {$gradient_start} 0%, {$gradient_end} 100%) !important;
			background: -webkit-linear-gradient(-60deg, {$gradient_end} 0%,{$gradient_start} 100%) !important;
			background: linear-gradient(135deg, {$gradient_start} 0%,{$gradient_end} 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$gradient_start}', endColorstr='{$gradient_end}',GradientType=1 ) !important;
		}

		input[type='text']:active,
		input[type='password']:active,
		input[type='text']:hover,
		input[type='password']:hover,
		input[type='text']:focus,
		input[type='password']:focus {
		  border-color: {$gradient_start} !important;
		}

		input[type='text'],
		input[type='password'] {
			color: {$gradient_start};
		}

		.button:not(.wp-color-result) {
			color: {$primary} !important;
			border: 1.5px solid {$primary} !important;
			background: transparent !important;
			transition: background .15s ease-in-out;
			text-shadow: none;
			line-height: 12px !important;
		}

		.button:hover {
			background: {$secondary} !important;
			border-color: {$secondary} !important;
			color: #fff !important;
		}
		input[type='checkbox']:checked {
		  background: {$primary} !important;
		  color: #fff !important;
		  border: 1px solid {$primary} !important;
		}
		input[type='checkbox']:hover {
		  background: {$primary} !important;
		  color: #fff !important;
		  border: 1px solid {$primary} !important;
		}
		.login form {
			color: {$primary};
		}
		#login_error {
			color: #333 !important;
		}
	";

	if ($settings['hide_login_logo']) {
		$html .= '#login > h1 {
		  visibility: hidden;
		}';
	}

	if ($settings['login_logo'] !== '') {
		$html .= "
		.login h1 a {
		  background-image: url(" . $settings['login_logo'] . ") !important;
		}";
	}

	return $html;
}

function zat_login_redirect($redirect_to, $request, $user) {
	$settings = zat_get_settings();

	if ($settings['login_redirect'] !== '') {
		$redirect_to = $settings['login_redirect'];
	}

	return $redirect_to;
}

add_filter('login_redirect', 'zat_login_redirect', 999, 3);

function zat_login_styles() {
	echo '<link rel="stylesheet" type="text/css" href="' . ZEPHYR_THEME_PLUGIN_URL . '/assets/css/zephyr-login-styles.css" />';
	echo '<style>' . zat_custom_login_css() . '</style>';
}

add_action('login_head', 'zat_login_styles');

function zat_check_save_settings() {
	if (isset($_POST['zat-submit-settings']) || isset($_POST['zat-save-custom-theme-template'])) {
		$args = [];

		if (isset($_POST['zat-primary-color'])) {
			$args['primary_color'] = sanitize_text_field($_POST['zat-primary-color']);
		}
		if (isset($_POST['zat-secondary-color'])) {
			$args['secondary_color'] = sanitize_text_field($_POST['zat-secondary-color']);
		}
		if (isset($_POST['zat-background-color'])) {
			$args['background_color'] = sanitize_text_field($_POST['zat-background-color']);
		}
		if (isset($_POST['zat-gradient-start-color'])) {
			$args['gradient_start'] = sanitize_text_field($_POST['zat-gradient-start-color']);
		}
		if (isset($_POST['zat-gradient-end-color'])) {
			$args['gradient_end'] = sanitize_text_field($_POST['zat-gradient-end-color']);
		}
		if (isset($_POST['zat-primary-button-color'])) {
			$args['button_primary'] = sanitize_text_field($_POST['zat-primary-button-color']);
		}
		if (isset($_POST['zat-primary-button-hover-color'])) {
			$args['button_hover'] = sanitize_text_field($_POST['zat-primary-button-hover-color']);
		}
		if (isset($_POST['zat-hide-login-logo'])) {
			$args['hide_login_logo'] = true;
		} else {
			$args['hide_login_logo'] = false;
		}
		if (isset($_POST['zat-login-logo'])) {
			$args['login_logo'] = sanitize_text_field($_POST['zat-login-logo']);
		}
		if (isset($_POST['zat-text-color'])) {
			$args['text_color'] = sanitize_text_field($_POST['zat-text-color']);
		}

		if (isset($_POST['zat-dashboard-logo'])) {
			$args['dashboard_logo'] = sanitize_text_field($_POST['zat-dashboard-logo']);
		}

		if (isset($_POST['zat-admin-bar-shadow'])) {
			$args['admin_bar_shadow'] = true;
		} else {
			$args['admin_bar_shadow'] = false;
		}

		if (isset($_POST['zat-login-redirect'])) {
			$args['login_redirect'] = sanitize_text_field($_POST['zat-login-redirect']);
		}

		if (isset($_POST['zat-theme-mode'])) {
			$args['theme_mode'] = sanitize_text_field($_POST['zat-theme-mode']);
		}

		if (isset($_POST['zat-font'])) {
			$args['font'] = sanitize_text_field($_POST['zat-font']);
		}

		$theme = zat_save_settings($args);

		if (isset($_POST['zat-save-custom-theme-template'])) {

			if (isset($_POST['zat-theme-title'])) {
				$theme['title'] = sanitize_text_field($_POST['zat-theme-title']);
			}

			zat_add_theme($theme);
		}
	}
}

function zat_get_themes() {
	$themes = [];
	$custom_themes = maybe_unserialize(get_option('zat_custom_themes', []));
	$default_themes = [[
		'primary_color' => '#28cc74',
		'secondary_color' => '#00baa7',
		'background_color' => '#f4f4f4',
		'button_primary' => '#28cc74',
		'button_hover' => '#00baa7',
		'gradient_start' => '#28cc74',
		'text_color' => '#fff',
		'gradient_end' => '#00baa7',
		'built_in' => false,
		'title' => 'Emerald Green'
	], [
		'primary_color' => '#2d92e5',
		'secondary_color' => '#001bce',
		'background_color' => '#f4f4f4',
		'button_primary' => '#2d92e5',
		'button_hover' => '#001bce',
		'gradient_start' => '#2d92e5',
		'gradient_end' => '#001bce',
		'text_color' => '#fff',
		'built_in' => false,
		'title' => 'Zephyr Blue'
	], [
		'primary_color' => '#324353',
		'secondary_color' => '#24303d',
		'background_color' => '#f4f4f4',
		'button_primary' => '#324353',
		'button_hover' => '#24303d',
		'gradient_start' => '#324353',
		'gradient_end' => '#24303d',
		'text_color' => '#fff',
		'built_in' => false,
		'title' => 'Black'
	], [
		'primary_color' => '#db0083',
		'secondary_color' => '#bc0064',
		'background_color' => '#f4f4f4',
		'button_primary' => '#db0083',
		'button_hover' => '#bc0064',
		'gradient_start' => '#db0083',
		'gradient_end' => '#bc0064',
		'text_color' => '#fff',
		'built_in' => false,
		'title' => 'Pink Red'
	], [
		'primary_color' => '#8321d3',
		'secondary_color' => '#4e1db7',
		'background_color' => '#f4f4f4',
		'button_primary' => '#8321d3',
		'button_hover' => '#4e1db7',
		'gradient_start' => '#8321d3',
		'gradient_end' => '#4e1db7',
		'text_color' => '#fff',
		'built_in' => false,
		'title' => 'Purple'
	], [
		'primary_color' => '#eeaf89',
		'secondary_color' => '#e1688c',
		'background_color' => '#f4f4f4',
		'button_primary' => '#eeaf89',
		'button_hover' => '#e1688c',
		'gradient_start' => '#eeaf89',
		'gradient_end' => '#e1688c',
		'text_color' => '#fff',
		'built_in' => false,
		'title' => 'Warm Flame'
	], [
		'primary_color' => '#96deda',
		'secondary_color' => '#50c9c3',
		'background_color' => '#f4f4f4',
		'button_primary' => '#96deda',
		'button_hover' => '#50c9c3',
		'gradient_start' => '#96deda',
		'gradient_end' => '#50c9c3',
		'text_color' => '#fff',
		'built_in' => false,
		'title' => 'Aqua'
	]];

	foreach ($default_themes as $theme) {
		$themes[] = $theme;
	}

	foreach ($custom_themes as $theme) {
		$themes[] = $theme;
	}

	return $themes;
}

function zat_add_theme($theme) {
	$custom_themes = maybe_unserialize(get_option('zat_custom_themes', []));
	$custom_themes[] = $theme;
	update_option('zat_custom_themes', serialize($custom_themes));
	return $theme;
}

function zat_custom_dashboard_logo() {
	$settings = zat_get_settings();
	$html = '';
	if ($settings['dashboard_logo'] !== '' && !empty($settings['dashboard_logo'])) {
		$html = '
		<style type="text/css">
		#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
			background-image: url(' . $settings['dashboard_logo'] . ') !important;
			content: "";
			width: 18px;
			height: 18px;
			display: inline-block;
			background-size: cover;
			margin: 0 !important;
		}
		#wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {

		}
		</style>
		';
	}
	echo $html;
}

//hook into the administrative header output
add_action('wp_before_admin_bar_render', 'zat_custom_dashboard_logo');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');

function add_action_links($links) {
	return array_merge($links, [
		'<a href="' . admin_url('themes.php?page=zephyr_admin_theme_settings') . '">' . __('Customization Settings', 'zephyr-admin-theme') . '</a>',
	]);
}