<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_WOO_COUPON_BOX_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-coupon-box" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_COUPON_BOX_ADMIN', VI_WOO_COUPON_BOX_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_COUPON_BOX_FRONTEND', VI_WOO_COUPON_BOX_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_COUPON_BOX_LANGUAGES', VI_WOO_COUPON_BOX_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_COUPON_BOX_INCLUDES', VI_WOO_COUPON_BOX_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_COUPON_BOX_TEMPLATES', VI_WOO_COUPON_BOX_DIR . "templates" . DIRECTORY_SEPARATOR );
//$plugin_url = plugins_url( 'woo-coupon-box' );
$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VI_WOO_COUPON_BOX_CSS', $plugin_url . "/css/" );
define( 'VI_WOO_COUPON_BOX_CSS_DIR', VI_WOO_COUPON_BOX_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_COUPON_BOX_JS', $plugin_url . "/js/" );
define( 'VI_WOO_COUPON_BOX_JS_DIR', VI_WOO_COUPON_BOX_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_COUPON_BOX_IMAGES', $plugin_url . "/images/" );

/*Include functions file*/
if ( is_file( VI_WOO_COUPON_BOX_INCLUDES . "functions.php" ) ) {
	require_once VI_WOO_COUPON_BOX_INCLUDES . "functions.php";
}
if ( is_file( VI_WOO_COUPON_BOX_INCLUDES . "data.php" ) ) {
	require_once VI_WOO_COUPON_BOX_INCLUDES . "data.php";
}
if ( is_file( VI_WOO_COUPON_BOX_INCLUDES . "support.php" ) ) {
	require_once VI_WOO_COUPON_BOX_INCLUDES . "support.php";
}

if ( is_file( VI_WOO_COUPON_BOX_INCLUDES . "custom-controls.php" ) ) {
	require_once VI_WOO_COUPON_BOX_INCLUDES . "custom-controls.php";
}
if ( is_file( VI_WOO_COUPON_BOX_INCLUDES . "3rd/elementor/elementor.php" ) ) {
	require_once VI_WOO_COUPON_BOX_INCLUDES . "3rd/elementor/elementor.php";
}
vi_include_folder( VI_WOO_COUPON_BOX_ADMIN, 'VI_WOO_COUPON_BOX_Admin_' );
vi_include_folder( VI_WOO_COUPON_BOX_FRONTEND, 'VI_WOO_COUPON_BOX_Frontend_' );
