<?php
/*
Plugin Name: KBoard 위젯 default 스킨
Plugin URI:
Description: KBoard 위젯 default 스킨입니다.
Version: 1.1
Author: 코스모스팜 - Cosmosfarm
Author URI: http://www.cosmosfarm.com/
*/

if(!defined('ABSPATH')) exit;

add_filter('kboard_widget_skin_list', 'kboard_widget_default_skin', 10, 1);
function kboard_widget_default_skin($list){
	
	$skin = new stdClass();
	$skin->dir = dirname(__FILE__);
	$skin->url = plugins_url('', __FILE__);
	$skin->name = basename($skin->dir);
	
	$list[$skin->name] = $skin;
	
	return $list;
}
?>