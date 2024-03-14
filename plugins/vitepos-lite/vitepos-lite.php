<?php
/**
 * Plugin Name: Vitepos Lite
 * Plugin URI: http://appsbd.com
 * Description: It's a Point of Sale plugin for Woocommerce, so fast and easy.
 * Version: 3.0.3
 * Author: appsbd
 * Author URI: http://www.appsbd.com
 * Text Domain: vitepos
 * wc require:3.2.0
 *
 * @package Vitepos
 */

include_once ABSPATH . 'wp-admin/includes/plugin.php';

use VitePos_Lite\Core\VitePosLite;

require_once 'vitepos_lite/helper/global-helper.php';
require_once 'vitepos_lite/libs/class-vitepos-loader.php';
if ( true === \VitePos_Lite\Libs\Vitepos_Loader::is_ready_to_load( __FILE__ ) ) {
	require_once 'vitepos_lite/helper/plugin-helper.php';
	require_once 'vitepos_lite/core/class-viteposlite.php';
	


	$vtpos = new VitePosLite( __FILE__ );
	$vtpos->start_plugin();
}
