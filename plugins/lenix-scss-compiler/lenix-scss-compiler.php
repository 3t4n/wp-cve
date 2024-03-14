<?php
/**
 * Plugin Name: Lenix scss compiler
 * Plugin URI: https://lenix.co.il/plugin/lenix-scss-compiler/
 * Author URI: https://lenix.co.il/
 * Description: Compiles scss files live on WordPress.
 * Version: 1.2
 * Author: Lenix
 * License: GPLv3
 */
 
define('LENIX_SCSS_FILE', plugin_basename(__FILE__));
define('LENIX_SCSS_COMPILER', trim(dirname(LENIX_SCSS_FILE), '/'));
define('LENIX_SCSS_COMPILER_DIR', WP_PLUGIN_DIR . '/' . LENIX_SCSS_COMPILER);
define('LENIX_SCSS_COMPILER_URL', WP_PLUGIN_URL . '/' . LENIX_SCSS_COMPILER);
define('LENIX_SCSS_COMPILER_TEMP', LENIX_SCSS_COMPILER_DIR . '/temp/');

add_action('init','lenix_scss_load');
function lenix_scss_load(){
	if( is_user_logged_in() ){
		include_once LENIX_SCSS_COMPILER_DIR . '/scssphp/scss.inc.php';
		include_once LENIX_SCSS_COMPILER_DIR . '/class/lenix-scss-dir-compiler.php';
		include_once LENIX_SCSS_COMPILER_DIR . '/options.php';
		include_once LENIX_SCSS_COMPILER_DIR . '/class/lenix-scss-compiler.php';
	}
}