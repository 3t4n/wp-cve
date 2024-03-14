<?php
/**
* Plugin name: Foxtool
* Plugin URL: https://foxtheme.net
* Description: Summarize the essential functions for managing a WordPress website
* Version: 1.4.1
* Author: Fox Theme
* Author URL: https://foxtheme.net
* License: GPLv2 or later
**/
// kiem tra thuc thi
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// Them ver
define( 'FOXTOOL_VERSION', '1.4.1' );
// khai bao duong dan
define('FOXTOOL_URL', plugin_dir_url( __FILE__ ));
define('FOXTOOL_DIR', plugin_dir_path( __FILE__ ));
define('FOXTOOL_BASE', plugin_basename( __FILE__ ));
// them Global
global $foxtool_options;
$foxtool_options = get_option('foxtool_settings');
$foxtool_code_options = get_option('foxtool_code_settings');
// main
function foxtool_load_admin_files() {
    global $foxtool_options;
    $files_to_include = array(
        'main/admin.php',
        'main/code.php',
        'main/backup.php'
    );
    if (isset($foxtool_options['foxtool2']) && !empty($foxtool_options['foxtool21'])) {
        $admin_id = get_current_user_id();
        $allowed_id = $foxtool_options['foxtool21'];
        if (is_admin() && current_user_can('manage_options') && $admin_id == $allowed_id) {
            foreach ($files_to_include as $file) {
                include(FOXTOOL_DIR . $file);
            }
        }
    } else {
        foreach ($files_to_include as $file) {
            include(FOXTOOL_DIR . $file);
        }
    }
}
add_action('init', 'foxtool_load_admin_files');
// code
include( FOXTOOL_DIR . 'inc/foxtool.php');
include( FOXTOOL_DIR . 'inc/code.php');
// chuc nang
if (isset($foxtool_options['speed'])){
	include( FOXTOOL_DIR . 'inc/speed.php');
}
if (isset($foxtool_options['scuri'])){
	include( FOXTOOL_DIR . 'inc/scuri.php');
}
if (isset($foxtool_options['tool'])){
	include( FOXTOOL_DIR . 'inc/tool.php');
}
if (isset($foxtool_options['main'])){
	include( FOXTOOL_DIR . 'inc/main.php');
}
if (isset($foxtool_options['media'])){
	include( FOXTOOL_DIR . 'inc/media.php');
}
if (isset($foxtool_options['post'])){
	include( FOXTOOL_DIR . 'inc/post.php');
}
if (isset($foxtool_options['mail'])){
	include( FOXTOOL_DIR . 'inc/mail.php');
}
if (isset($foxtool_options['woo'])){
	include( FOXTOOL_DIR . 'inc/woo.php');
}
if (isset($foxtool_options['user'])){
	include( FOXTOOL_DIR . 'inc/user.php');
}
if (isset($foxtool_options['custom'])){
	include( FOXTOOL_DIR . 'inc/custom.php');
}
if (isset($foxtool_options['goo'])){
	include( FOXTOOL_DIR . 'inc/goo.php');
}
if (isset($foxtool_options['chat'])){
	include( FOXTOOL_DIR . 'inc/chat.php');
}
if (isset($foxtool_options['shortcode'])){
	include( FOXTOOL_DIR . 'inc/shortcode.php');
}
if (isset($foxtool_options['notify'])){
	include( FOXTOOL_DIR . 'inc/notify.php');
}
if (isset($foxtool_options['ads'])){
	include( FOXTOOL_DIR . 'inc/ads.php');
}
// them ngon ngu
function foxtool_load_textdomain() {
  load_plugin_textdomain( 'foxtool', false, dirname( FOXTOOL_BASE ) . '/lang'); 
}
add_action( 'plugins_loaded', 'foxtool_load_textdomain' );
// load css js
function foxtool_customize_enqueue() {
	wp_enqueue_style('foxtool-icon', FOXTOOL_URL . 'font/css/all.css', array(), FOXTOOL_VERSION);
	wp_enqueue_style('foxtool-css', FOXTOOL_URL . 'link/style.css', array(), FOXTOOL_VERSION);
	wp_enqueue_script('foxtool-js', FOXTOOL_URL . 'link/script.js', array(), FOXTOOL_VERSION);
	// color
	wp_enqueue_style('coloris-css', FOXTOOL_URL . 'link/color/coloris.css', array(), FOXTOOL_VERSION);
	wp_enqueue_script('coloris-js', FOXTOOL_URL . 'link/color/coloris.js', array(), FOXTOOL_VERSION);
}
add_action( 'admin_head', 'foxtool_customize_enqueue' );
// tai js media vao plugin
function foxtool_enqueue_media_uploader() {
    if (function_exists('wp_enqueue_media') && isset($_GET['page']) && $_GET['page'] === 'foxtool-options') {
        wp_enqueue_media();
		wp_enqueue_editor();
    }
	if (isset($_GET['page']) && $_GET['page'] === 'foxtool-code-options') {
	$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
	wp_localize_script('jquery', 'cm_settings', $cm_settings);
	wp_enqueue_style('wp-codemirror');
	}
}
add_action('admin_enqueue_scripts', 'foxtool_enqueue_media_uploader');
// ad js home
function foxtool_enqueue_home(){
	// khoi chay jquery
	wp_enqueue_script('jquery');
	// js foxtool
	wp_enqueue_script('index-ft', FOXTOOL_URL . 'link/index.js', array(), FOXTOOL_VERSION);
}
add_action('wp_enqueue_scripts', 'foxtool_enqueue_home');
// them lien ket gioi thieu
function foxtool_settings_about($links, $file) {
	if (false !== strpos($file, 'foxtool/foxtool.php')) {
		$settings_link = '<a href="' . admin_url('admin.php?page=foxtool-options') . '">'. __('Settings', 'foxtool'). '</a>';
		array_unshift($links, $settings_link);
		$settings_link = '<a href="https://foxplugin.com" target="_blank">'. __('Home', 'foxtool'). '</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'foxtool_settings_about', 10, 2);
