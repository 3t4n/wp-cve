<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'WOO_F_LOOKBOOK_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-lookbook" . DIRECTORY_SEPARATOR );
define( 'WOO_F_LOOKBOOK_ADMIN', WOO_F_LOOKBOOK_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'WOO_F_LOOKBOOK_FRONTEND', WOO_F_LOOKBOOK_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'WOO_F_LOOKBOOK_LANGUAGES', WOO_F_LOOKBOOK_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'WOO_F_LOOKBOOK_INCLUDES', WOO_F_LOOKBOOK_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'WOO_F_LOOKBOOK_CSS', WOO_F_LOOKBOOK_PLUGIN_URL . "css/" );
define( 'WOO_F_LOOKBOOK_CSS_DIR', WOO_F_LOOKBOOK_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'WOO_F_LOOKBOOK_JS', WOO_F_LOOKBOOK_PLUGIN_URL . "js/" );
define( 'WOO_F_LOOKBOOK_JS_DIR', WOO_F_LOOKBOOK_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'WOO_F_LOOKBOOK_IMAGES', WOO_F_LOOKBOOK_PLUGIN_URL . "/images/" );


/*Include functions file*/
if ( is_file( WOO_F_LOOKBOOK_INCLUDES . "data.php" ) ) {
	require_once WOO_F_LOOKBOOK_INCLUDES . "data.php";
}

if ( is_file( WOO_F_LOOKBOOK_INCLUDES . "functions.php" ) ) {
	require_once WOO_F_LOOKBOOK_INCLUDES . "functions.php";
}
/*Include functions file*/
if ( is_file( WOO_F_LOOKBOOK_INCLUDES . "support.php" ) ) {
	require_once WOO_F_LOOKBOOK_INCLUDES . "support.php";
}

if ( is_file( WOO_F_LOOKBOOK_INCLUDES . "facebook-sdk/autoload.php" ) ) {
	require_once WOO_F_LOOKBOOK_INCLUDES . "facebook-sdk/autoload.php";
}

if ( is_file( WOO_F_LOOKBOOK_INCLUDES . "instagram.php" ) ) {
	require_once WOO_F_LOOKBOOK_INCLUDES . "instagram.php";
}

if ( is_file( WOO_F_LOOKBOOK_INCLUDES . "elementor/elementor.php" ) ) {
	require_once WOO_F_LOOKBOOK_INCLUDES . "elementor/elementor.php";
}
vi_include_folder( WOO_F_LOOKBOOK_ADMIN, 'WOO_F_LOOKBOOK_Admin_' );
vi_include_folder( WOO_F_LOOKBOOK_FRONTEND, 'WOO_F_LOOKBOOK_Frontend_' );

if ( class_exists( 'VillaTheme_Support' ) ) {
	new VillaTheme_Support(
		array(
			'support'   => 'https://villatheme.com/supports/forum/plugins/woocommerce-lookbook/',
			'docs'      => 'http://docs.villatheme.com/?item=woocommerce-lookbook',
			'review'    => 'https://wordpress.org/support/plugin/woo-lookbook/reviews/?rate=5#rate-response',
			'pro_url'   => 'https://1.envato.market/mV0bM',
			'css'       => WOO_F_LOOKBOOK_CSS,
			'image'     => WOO_F_LOOKBOOK_IMAGES,
			'slug'      => 'woo-lookbook',
			'menu_slug' => 'edit.php?post_type=woocommerce-lookbook',
			'survey_url' => 'https://script.google.com/macros/s/AKfycbxwRAAILhwQ8-zXk8GXNmC6vP2KTIM_n4allRONk2K7B5goJ_K_R00pnZQ6sANNMkXbpg/exec',
			'version'   => WOO_F_LOOKBOOK_VERSION
		)
	);
}
