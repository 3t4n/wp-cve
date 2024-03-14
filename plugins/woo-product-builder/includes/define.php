<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
define( 'VI_WPRODUCTBUILDER_F_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-product-builder" . DIRECTORY_SEPARATOR );
define( 'VI_WPRODUCTBUILDER_F_ADMIN', VI_WPRODUCTBUILDER_F_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_WPRODUCTBUILDER_F_FRONTEND', VI_WPRODUCTBUILDER_F_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_WPRODUCTBUILDER_F_LANGUAGES', VI_WPRODUCTBUILDER_F_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VI_WPRODUCTBUILDER_F_INCLUDES', VI_WPRODUCTBUILDER_F_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'VI_WPRODUCTBUILDER_F_TEMPLATES', VI_WPRODUCTBUILDER_F_DIR . "templates" . DIRECTORY_SEPARATOR );
$plugin_url = plugins_url( 'woo-product-builder' );
//$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VI_WPRODUCTBUILDER_F_CSS', $plugin_url . "/css/" );
define( 'VI_WPRODUCTBUILDER_F_CSS_DIR', VI_WPRODUCTBUILDER_F_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_WPRODUCTBUILDER_F_JS', $plugin_url . "/js/" );
define( 'VI_WPRODUCTBUILDER_F_JS_DIR', VI_WPRODUCTBUILDER_F_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_WPRODUCTBUILDER_F_IMAGES', $plugin_url . "/images/" );


/*Include functions file*/
if ( is_file( VI_WPRODUCTBUILDER_F_INCLUDES . "functions.php" ) ) {
	require_once VI_WPRODUCTBUILDER_F_INCLUDES . "functions.php";
}


/*Include functions file*/
if ( is_file( VI_WPRODUCTBUILDER_F_INCLUDES . "mobile_detect.php" ) ) {
	require_once VI_WPRODUCTBUILDER_F_INCLUDES . "mobile_detect.php";
}
/*Include functions file*/
if ( is_file( VI_WPRODUCTBUILDER_F_INCLUDES . "data.php" ) ) {
	require_once VI_WPRODUCTBUILDER_F_INCLUDES . "data.php";
}
/*Include functions file*/
if ( is_file( VI_WPRODUCTBUILDER_F_INCLUDES . "support.php" ) ) {
	require_once VI_WPRODUCTBUILDER_F_INCLUDES . "support.php";
}

vi_include_folder( VI_WPRODUCTBUILDER_F_ADMIN, 'VI_WPRODUCTBUILDER_F_Admin_' );
vi_include_folder( VI_WPRODUCTBUILDER_F_FRONTEND, 'VI_WPRODUCTBUILDER_F_FrontEnd_' );

if ( class_exists( 'VillaTheme_Support' ) ) {
	new VillaTheme_Support(
		array(
			'support'   => 'https://wordpress.org/support/plugin/woo-product-builder/',
			'docs'      => 'http://docs.villatheme.com/?item=woocommerce-product-builder',
			'review'    => 'https://wordpress.org/support/plugin/woo-product-builder/reviews/?rate=5#rate-response',
			'pro_url'   => 'https://1.envato.market/M3Wjq',
			'css'       => VI_WPRODUCTBUILDER_F_CSS,
			'image'     => VI_WPRODUCTBUILDER_F_IMAGES,
			'slug'      => 'woo-product-builder',
			'menu_slug' => 'edit.php?post_type=woo_product_builder',
			'version'   => VI_WPRODUCTBUILDER_F_VERSION,
			'survey_url' => 'https://script.google.com/macros/s/AKfycbx47eNFcc6x4TFQt7YvyAk37q52Hm2L9lOAroU-12Bd_VMuFjkLKG2qTK70XH5Em1Jqug/exec'
		)
	);
}