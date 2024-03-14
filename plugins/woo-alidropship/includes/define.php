<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_WOO_ALIDROPSHIP_ADMIN', VI_WOO_ALIDROPSHIP_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_FRONTEND', VI_WOO_ALIDROPSHIP_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_LANGUAGES', VI_WOO_ALIDROPSHIP_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_TEMPLATES', VI_WOO_ALIDROPSHIP_DIR . "templates" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_PLUGINS', VI_WOO_ALIDROPSHIP_DIR . "plugins" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_CACHE', VI_WOO_ALIDROPSHIP_DIR . "cache" . DIRECTORY_SEPARATOR );
$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VI_WOO_ALIDROPSHIP_ASSETS', $plugin_url . "/assets/" );
define( 'VI_WOO_ALIDROPSHIP_ASSETS_DIR', VI_WOO_ALIDROPSHIP_DIR . "assets" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_PACKAGES', VI_WOO_ALIDROPSHIP_ASSETS_DIR . "packages" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_CSS', VI_WOO_ALIDROPSHIP_ASSETS . "css/" );
define( 'VI_WOO_ALIDROPSHIP_CSS_DIR', VI_WOO_ALIDROPSHIP_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_JS', VI_WOO_ALIDROPSHIP_ASSETS . "js/" );
define( 'VI_WOO_ALIDROPSHIP_JS_DIR', VI_WOO_ALIDROPSHIP_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ALIDROPSHIP_IMAGES', VI_WOO_ALIDROPSHIP_ASSETS . "images/" );
define( 'VI_WOO_ALIDROPSHIP_EXTENSION_VERSION', '1.0' );


/*Constants for AliExpress dropshipping API*/
define( 'VI_WOOCOMMERCE_ALIDROPSHIP_APP_KEY', '34058263' );
define( 'VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_PLACE_ORDER_URL', 'https://api.villatheme.com/wp-json/aliexpress/get_signature' );
define( 'VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_PLACE_ORDER_BATCH_URL', 'https://api.villatheme.com/wp-json/aliexpress/create_orders' );
define( 'VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_GET_PRODUCT_URL', 'https://api.villatheme.com/wp-json/aliexpress/get_products' );
define( 'VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_GET_PRODUCT_URL_V2', 'https://api.villatheme.com/wp-json/aliexpress/get_products/v2' );
define( 'VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_GET_ORDER_URL', 'https://api.villatheme.com/wp-json/aliexpress/get_orders' );
define( 'VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_GET_SHIPPING_URL', 'https://api.villatheme.com/wp-json/aliexpress/get_shipping' );
define( 'VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_SEARCH_PRODUCT', 'https://api.villatheme.com/wp-json/aliexpress/search' );

/*Include functions file*/
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "functions.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "functions.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "support.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "support.php";
}

if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "class-ald-post.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "class-ald-post.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "ali-product-query.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "ali-product-query.php";
}
/*Include functions file*/
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "wp-async-request.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "wp-async-request.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "wp-background-process.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "wp-background-process.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "data.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "data.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-draft-product.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-draft-product.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_ADMIN . "class-villatheme-admin-show-message.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_ADMIN . "class-villatheme-admin-show-message.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-error-images-table.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-error-images-table.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-background-download-images.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-background-download-images.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-background-import.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-background-import.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-background-migrate-new-table.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-background-migrate-new-table.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-background-download-description.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-background-download-description.php";
}
if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "setup-wizard.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "setup-wizard.php";
}
vi_include_folder( VI_WOO_ALIDROPSHIP_ADMIN, 'VI_WOO_ALIDROPSHIP_Admin_' );
vi_include_folder( VI_WOO_ALIDROPSHIP_FRONTEND, 'VI_WOO_ALIDROPSHIP_Frontend_' );
vi_include_folder( VI_WOO_ALIDROPSHIP_PLUGINS, 'VI_WOO_ALIDROPSHIP_Plugins_' );