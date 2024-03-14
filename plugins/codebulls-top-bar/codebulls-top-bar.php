<?php
/*
Plugin Name: Codebulls Top Bar
Author: CodeBulls S.A.S
Author URI: http://codebullsteam.com/
Description: A simple Top Bar that can be fully customized.
Version: 1.5.0
Requires at least: 5.2
License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once(__DIR__ . '/includes/tab-manager.php');
require_once(__DIR__ . '/includes/general-content.php');
require_once(__DIR__ . '/includes/top-bar-content.php');
require_once(__DIR__ . '/includes/validation-manager.php');
require_once(__DIR__ . '/includes/generate-top-bar.php');

add_action( 'admin_menu', 'cb_top_bar_menu' );

/**
 *  Add the plugin option in the settings options list
 */
function cb_top_bar_menu() {
	add_menu_page( 'Top Bar  CodeBulls', 'Top Bar - CodeBulls', 'administrator', 'cb_top_bar', 'cb_top_bar_add_content_top_bar_options_page', 'https://codebullsteam.com/wp-content/uploads/2020/07/cb-top-bar-settings-icon.svg');
    add_action( 'admin_init', 'cb_top_bar_init' );
}

/**
 *  Add configuration tabs in the menu option created above
 */
function cb_top_bar_init() {
	$args_general = array(
		'sanitize_callback' => 'cb_top_bar_validate_top_bar_options',
		'default' => NULL,
	);
	$args_content = array(
		'sanitize_callback' => 'cb_top_bar_validate_top_bar_options_content',
		'default' => NULL,
	);
	register_setting( 'cb_top_bar_settings', 'options_cb_top_bar', $args_general );
	register_setting( 'cb_top_bar_content_settings', 'options_cb_top_bar_content', $args_content );
}

/**
 *  Paint current selected tab interface
 */
function cb_top_bar_add_content_top_bar_options_page() {
	global $cb_top_bar_active_tab,$wp;
	$cb_top_bar_active_tab = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_URL);
	if($cb_top_bar_active_tab == null){
		$cb_top_bar_active_tab='general';
	}
	?>

	<h2 class="nav-tab-wrapper">
	<?php
		do_action( 'manage_top_bar_settings_tabs' );
	?>
	</h2>
	<?php
	if($cb_top_bar_active_tab == 'general'){
		do_action( 'generate_top_bar_settings_general_content' );
	}else if($cb_top_bar_active_tab == 'content'){
		do_action( 'generate_top_bar_settings_content' );
	}
}

add_action( 'wp_enqueue_scripts', 'cb_top_bar_add_top_bar_styles_js' );
add_action( 'admin_enqueue_scripts', 'cb_top_bar_add_top_bar_admin_styles' );

/**
 *  Add plugin styles and js in the admin page
 */
function cb_top_bar_add_top_bar_admin_styles() {
	$is_cb_top_bar_settings = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_URL) == 'cb_top_bar' ? true:false;
	if($is_cb_top_bar_settings){
		$cb_top_bar_active_tab = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_URL);
		$options=get_option('options_cb_top_bar');
		$number_columns=$options['number-columns-top-bar-plugin'];
		wp_enqueue_style( 'cb-top-bar-styles', plugins_url('css/cb_top_bar_admin_styles.css',__DIR__.'/codebulls-top-bar.php'));
		wp_enqueue_script('wp-theme-plugin-editor');
		wp_enqueue_style('wp-codemirror');
		wp_enqueue_script(
			'general-cb-top-bar',
			plugins_url('js/cb_top_bar_js.js',__DIR__.'/codebulls-top-bar.php'),
			array('jquery','wp-color-picker')
		);
		wp_localize_script( 'general-cb-top-bar', 'tab', json_decode(json_encode($cb_top_bar_active_tab), true));
		wp_localize_script( 'general-cb-top-bar', 'number_columns', json_decode(json_encode($number_columns), true));
	}
}

/**
 *  Add plugin styles and js in the front end
 */
function cb_top_bar_add_top_bar_styles_js(){
	$options=get_option('options_cb_top_bar');
	if($options['available-plugin'] == '1'){
	    $sticky_top_bar = $options['sticky-top-bar'];
        $user_can_close_top_bar=$options['user-can-close-top-bar'];
		wp_enqueue_style( 'cb-top-bar-styles', plugins_url('css/cb_top_bar_styles.css',__DIR__.'/codebulls-top-bar.php'));
		wp_enqueue_script(
			'general-cb-top-bar',
			plugins_url('js/cb_top_bar_general_js.js',__DIR__.'/codebulls-top-bar.php'),
			array('jquery')
		);
		wp_localize_script( 'general-cb-top-bar', 'user_can_close_top_bar', json_decode(json_encode($user_can_close_top_bar), true));
        wp_localize_script( 'general-cb-top-bar', 'sticky_top_bar', json_decode(json_encode($sticky_top_bar), true));
	}
}

add_action('admin_enqueue_scripts', 'cb_top_bar_add_code_editor_css_and_js');

/**
 *  Add codemirror js and styles for the textarea editor
 */
function cb_top_bar_add_code_editor_css_and_js($hook) {
	$is_cb_top_bar_settings = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_URL) == 'cb_top_bar' ? true:false;
	if($is_cb_top_bar_settings){
		$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
		$cm_html_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
		wp_localize_script('jquery', 'cm_settings', $cm_settings);
		wp_localize_script('jquery', 'cm_html_settings', $cm_html_settings);
	}
}

add_action( 'admin_enqueue_scripts', 'cb_top_bar_add_color_picker' );

function cb_top_bar_add_color_picker(){
	$is_cb_top_bar_settings = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_URL) == 'cb_top_bar' ? true:false;
	if($is_cb_top_bar_settings){
		wp_enqueue_style( 'wp-color-picker' );
	}
}

// Add settings link for plugin configuration
function cb_top_bar_add_plugin_link_in_settings($links) {
	$settings_link = '<a href="options-general.php?page=cb_top_bar&tab=general">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}

$file_path=__DIR__.'/codebulls-top-bar.php';
$plugin = plugin_basename($file_path);
add_filter("plugin_action_links_$plugin", 'cb_top_bar_add_plugin_link_in_settings' );

// Enable menu shortcodes
function print_menu_shortcode($atts, $content = null) {
	extract(shortcode_atts(array( 'name' => null, 'class' => null ), $atts));
	return wp_nav_menu( array( 'menu' => $name, 'menu_class' => 'myclass', 'echo' => false ) );
}
add_shortcode('menu', 'print_menu_shortcode');

?>