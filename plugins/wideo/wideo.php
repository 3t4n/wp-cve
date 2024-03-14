<?php
/**
 * @package  Wideo
 */
/*
Plugin Name:Wideo视频播放器
Plugin URI: https://www.wibir.cn/wideo/
Description: 整合dplayer,支持mp4,flv,hls等协议视频播放
Version: 2.0.3
Author: 熊猫大人
Author URI:  https://www.wibir.cn/
License: GPLv2 or later
Text Domain: Wideo
*/

defined( 'ABSPATH' ) or die( '你无权访问这个文件！' );
define('WIDEO_VERSION', '2.0.3');
define('WIDEO_URL', plugins_url('', __FILE__));
define('WIDEO_PATH', dirname(__FILE__));
define('WIDEO_ADMIN_URL', admin_url());


require WIDEO_PATH . '/class.wideo.php';

if ( class_exists( 'Wideo' ) ) {
	$wideo = new Wideo();
}

// 激活插件
register_activation_hook( __FILE__,array($wideo,'activate') );

// 停止插件
register_deactivation_hook( __FILE__, array($wideo,'deactivate') );


