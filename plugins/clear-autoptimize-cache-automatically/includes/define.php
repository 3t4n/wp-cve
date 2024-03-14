<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VICACA_LANGUAGES', VICACA_DIR . "languages" . DIRECTORY_SEPARATOR );
$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VICACA_ASSETS', $plugin_url . "/assets/" );
define( 'VICACA_ASSETS_DIR', VICACA_DIR . "assets" . DIRECTORY_SEPARATOR );
define( 'VICACA_CSS', VICACA_ASSETS . "css/" );
define( 'VICACA_CSS_DIR', VICACA_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VICACA_JS', VICACA_ASSETS . "js/" );
define( 'VICACA_JS_DIR', VICACA_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VICACA_IMAGES', VICACA_ASSETS . "images/" );
if ( is_file( VICACA_INCLUDES . "data.php" ) ) {
	require_once VICACA_INCLUDES . "data.php";
}
if ( is_file( VICACA_INCLUDES . "support.php" ) ) {
	require_once VICACA_INCLUDES . "support.php";
}