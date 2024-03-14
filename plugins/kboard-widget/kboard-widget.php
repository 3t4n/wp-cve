<?php
/*
Plugin Name: KBoard 위젯
Plugin URI: https://www.cosmosfarm.com/
Description: 최다 사용자 무료 워드프레스 게시판 KBoard 위젯 입니다.
Version: 1.1
Author: 코스모스팜 - Cosmosfarm
Author URI: https://www.cosmosfarm.com/
*/

if(!defined('ABSPATH')) exit;

define('KBOARD_WIDGET_VERSION', '1.1');
define('KBOARD_WIDGET_DIR_PATH', dirname(__FILE__));
define('KBOARD_WIDGET_URL', plugins_url('', __FILE__));

include_once 'class/KBoardWidget.class.php';
include_once 'class/KBoardWidgetList.class.php';

add_action('init', 'kboard_widget_languages');
function kboard_widget_languages(){
	load_plugin_textdomain('kboard-widget', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

add_action('widgets_init', 'kboard_widget_init');
function kboard_widget_init(){
	if(defined('KBOARD_VERSION')){
		register_widget('KBoardWidget');
	}
}

add_action('admin_notices', 'kboard_widget_admin_notices');
function kboard_widget_admin_notices(){
	if(!defined('KBOARD_VERSION')){
		$class = 'notice notice-error';
		$message = 'KBoard 위젯 사용을 위해서는 먼저 <a href="http://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;">KBoard</a> 플러그인을 설치해주세요.';
		printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
	}
}

function get_kboard_widget_title($tab_name){
	$title = '';
	switch($tab_name){
		case 'latest':
			$title =  __('Latest Topics', 'kboard-widget');
			break;
		case 'comment':
			$title = __('Latest Comments', 'kboard-widget');
			break;
		case 'vote':
			$title = __('Top Voted', 'kboard-widget');
			break;
		case 'view':
			$title = __('Most Viewed', 'kboard-widget');
			break;
		case 'notice':
			$title = __('Notice', 'kboard-widget');
			break;
		case 'my_post':
			$title = __('My Topics', 'kboard-widget');
			break;
		case 'my_comment':
			$title = __('My Comments', 'kboard-widget');
			break;
		default:
			$title = $tab_name;
			break;
	}
	return apply_filters('get_kboard_widget_title', $title);
}