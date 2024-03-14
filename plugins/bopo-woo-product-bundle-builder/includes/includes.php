<?php

// no direct access allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
define( 'VI_WOO_BOPO_BUNDLE_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "bopo-woo-product-bundle-builder" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOPO_BUNDLE_INCLUDES', VI_WOO_BOPO_BUNDLE_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOPO_BUNDLE_ADMIN', VI_WOO_BOPO_BUNDLE_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOPO_BUNDLE_FRONTEND', VI_WOO_BOPO_BUNDLE_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOPO_BUNDLE_LANGUAGES', VI_WOO_BOPO_BUNDLE_DIR . "languages" . DIRECTORY_SEPARATOR );

$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );

define( 'VI_WOO_BOPO_BUNDLE_CSS', $plugin_url . "/css/" );
define( 'VI_WOO_BOPO_BUNDLE_CSS_DIR', VI_WOO_BOPO_BUNDLE_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOPO_BUNDLE_JS', $plugin_url . "/js/" );
define( 'VI_WOO_BOPO_BUNDLE_JS_DIR', VI_WOO_BOPO_BUNDLE_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOPO_BUNDLE_IMAGES', $plugin_url . "/assets/images/" );
define( 'VI_WOO_BOPO_BUNDLE_FONTS', $plugin_url . "/assets/fonts/" );
define( 'VI_WOO_BOPO_BUNDLE_TEMP_DIR', VI_WOO_BOPO_BUNDLE_DIR . "templates" . DIRECTORY_SEPARATOR );

if ( is_file( VI_WOO_BOPO_BUNDLE_INCLUDES . "functions.php" ) ) {
	require_once VI_WOO_BOPO_BUNDLE_INCLUDES . "functions.php";
}
if ( is_file( VI_WOO_BOPO_BUNDLE_INCLUDES . "data.php" ) ) {
	require_once VI_WOO_BOPO_BUNDLE_INCLUDES . "data.php";
}
if ( is_file( VI_WOO_BOPO_BUNDLE_INCLUDES . "support.php" ) ) {
	require_once VI_WOO_BOPO_BUNDLE_INCLUDES . "support.php";
}
if ( is_file( VI_WOO_BOPO_BUNDLE_INCLUDES . "helper.php" ) ) {
	require_once VI_WOO_BOPO_BUNDLE_INCLUDES . "helper.php";
}
if ( is_file( VI_WOO_BOPO_BUNDLE_INCLUDES . 'elementor/elementor.php' ) ) {
	require_once VI_WOO_BOPO_BUNDLE_INCLUDES . 'elementor/elementor.php';
}

vi_include_folder( VI_WOO_BOPO_BUNDLE_ADMIN, 'VI_WOO_BOPO_BUNDLE_' );
vi_include_folder( VI_WOO_BOPO_BUNDLE_FRONTEND, 'VI_WOO_BOPO_BUNDLE_' );