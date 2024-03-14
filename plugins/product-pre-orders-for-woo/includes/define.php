<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
define( 'WPRO_WOO_PRE_ORDER_FILE', __FILE__ );
define( 'WPRO_WOO_PRE_ORDER_ADMIN', WPRO_WOO_PRE_ORDER_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'WPRO_WOO_PRE_ORDER_FRONTEND', WPRO_WOO_PRE_ORDER_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'WPRO_WOO_PRE_ORDER_LANGUAGES', WPRO_WOO_PRE_ORDER_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'WPRO_WOO_PRE_ORDER_INCLUDES', WPRO_WOO_PRE_ORDER_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'WPRO_WOO_PRE_ORDER_CSS', WPRO_WOO_PRE_ORDER_URL . "css/" );
define( 'WPRO_WOO_PRE_ORDER_JS', WPRO_WOO_PRE_ORDER_URL . "js/" );
define( 'WPRO_WOO_PRE_ORDER_IMAGES', WPRO_WOO_PRE_ORDER_URL . "images/" );

/*Include functions file*/
if ( is_file( WPRO_WOO_PRE_ORDER_INCLUDES . "functions.php" ) ) {
	require_once WPRO_WOO_PRE_ORDER_INCLUDES . "functions.php";
}

if ( is_file( WPRO_WOO_PRE_ORDER_INCLUDES . "support.php" ) ) {
    require_once WPRO_WOO_PRE_ORDER_INCLUDES . "support.php";
}

vi_include_folder( WPRO_WOO_PRE_ORDER_ADMIN, 'WPRO_WOO_PRE_ORDER_Admin_' );
vi_include_folder( WPRO_WOO_PRE_ORDER_FRONTEND, 'WPRO_WOO_PRE_ORDER_Frontend_' );

