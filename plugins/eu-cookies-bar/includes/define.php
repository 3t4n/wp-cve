<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'EU_COOKIES_BAR_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "eu-cookies-bar" . DIRECTORY_SEPARATOR );
define( 'EU_COOKIES_BAR_ADMIN', EU_COOKIES_BAR_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'EU_COOKIES_BAR_FRONTEND', EU_COOKIES_BAR_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'EU_COOKIES_BAR_LANGUAGES', EU_COOKIES_BAR_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'EU_COOKIES_BAR_INCLUDES', EU_COOKIES_BAR_DIR . "includes" . DIRECTORY_SEPARATOR );

define( 'EU_COOKIES_BAR_CSS', WP_PLUGIN_URL . "/eu-cookies-bar/css/" );
define( 'EU_COOKIES_BAR_CSS_DIR', EU_COOKIES_BAR_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'EU_COOKIES_BAR_JS', WP_PLUGIN_URL . "/eu-cookies-bar/js/" );
define( 'EU_COOKIES_BAR_JS_DIR', EU_COOKIES_BAR_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'EU_COOKIES_BAR_IMAGES', WP_PLUGIN_URL . "/eu-cookies-bar/images/" );


/*Include functions file*/
if ( is_file( EU_COOKIES_BAR_INCLUDES . "functions.php" ) ) {
	require_once EU_COOKIES_BAR_INCLUDES . "functions.php";
}

if ( is_file( EU_COOKIES_BAR_INCLUDES . "data.php" ) ) {
	require_once EU_COOKIES_BAR_INCLUDES . "data.php";
}
if ( is_file( EU_COOKIES_BAR_INCLUDES . "data.php" ) ) {
	require_once EU_COOKIES_BAR_INCLUDES . "support.php";
}

vi_include_folder( EU_COOKIES_BAR_ADMIN, 'EU_COOKIES_BAR_Admin_' );
vi_include_folder( EU_COOKIES_BAR_FRONTEND, 'EU_COOKIES_BAR_Frontend_' );