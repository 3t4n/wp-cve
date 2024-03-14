<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_ADMIN', VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "admin" . DIRECTORY_SEPARATOR );
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_FRONTEND', VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "frontend" . DIRECTORY_SEPARATOR );
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_LANGUAGES', VICUFFW_CHECKOUT_UPSELL_FUNNEL_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES', VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "templates" . DIRECTORY_SEPARATOR );
$plugin_url = plugins_url( 'checkout-upsell-funnel-for-woo' );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS', $plugin_url . "/assets/css/" );
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS_DIR', VICUFFW_CHECKOUT_UPSELL_FUNNEL_DIR . "assets/css" . DIRECTORY_SEPARATOR );
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_JS', $plugin_url . "/assets/js/" );
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_JS_DIR', VICUFFW_CHECKOUT_UPSELL_FUNNEL_DIR . "assets/js" . DIRECTORY_SEPARATOR );
define( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_IMAGES', $plugin_url . "/assets/images/" );

/*Include functions file*/
if ( is_file( VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "functions.php" ) ) {
	require_once VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "functions.php";
}
if ( is_file( VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "support.php" ) ) {
	require_once VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "support.php";
}
if ( is_file( VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "data.php" ) ) {
	require_once VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "data.php";
}
if ( is_file( VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "report-table.php" ) ) {
	require_once VICUFFW_CHECKOUT_UPSELL_FUNNEL_INCLUDES . "report-table.php";
}
villatheme_include_folder( VICUFFW_CHECKOUT_UPSELL_FUNNEL_ADMIN, 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Admin_' );
villatheme_include_folder( VICUFFW_CHECKOUT_UPSELL_FUNNEL_FRONTEND, 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_' );
