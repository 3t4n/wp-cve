<?php

/**
 * Plugin Name:       Combine Social Photos | Still BE
 * Description:       Provides Instagram embedding functionality exclusively for WP Block Editor. You can embed your own feeds, other Pro accounts' feeds and posts related to hashtags.
 * Version:           0.13.5
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Daisuke Yamamoto
 * Author URI:        https://web.analogstd.com/
 * License:           GPL2
 * Text Domain:       still-be-combine-social-photos
 */


// Do not allow direct access to the file.
if( !defined( 'ABSPATH' ) ) {
	exit;
}




if( ! defined( 'SB_CSP_VERSION' ) ) {
	define( 'SB_CSP_VERSION', '0.13.5' );
}

if( ! defined( 'SB_CSP_PREFIX' ) ) {
	define( 'SB_CSP_PREFIX', 'sb-csp-' );
}

if( ! defined( 'STILLBE_CSP_BASE_DIR' ) ) {
	define( 'STILLBE_CSP_BASE_DIR', untrailingslashit( __DIR__ ) );
}

if( ! defined( 'STILLBE_CSP_BASE_URL' ) ) {
	define( 'STILLBE_CSP_BASE_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}


if( ! defined( 'STILLBE_CSP_API_VERSION' ) ) {
	define( 'STILLBE_CSP_API_VERSION', 'v1' );
}


if( ! defined( 'STILLBE_CSP_FB_GRAPH_API_VERSION' ) ) {
	define( 'STILLBE_CSP_FB_GRAPH_API_VERSION', 'v19.0' );
}



require_once( __DIR__. '/includes/function/function-stillbe-do-settings-sections-tab-style.php' );


require_once( __DIR__. '/includes/trait/trait-instagram-api-common-method.php' );


require_once( __DIR__. '/includes/class/class-main.php' );

require_once( __DIR__. '/includes/class/class-cron.php' );

require_once( __DIR__. '/includes/class/class-blocks.php' );

require_once( __DIR__. '/includes/class/class-rest-api.php' );

require_once( __DIR__. '/includes/class/class-other-products.php' );

require_once( __DIR__. '/includes/class/class-setting.php' );

require_once( __DIR__. '/includes/class/class-basic-display-api.php' );

require_once( __DIR__. '/includes/class/class-graph-api.php' );



register_deactivation_hook( __FILE__, 'StillBE\Plugin\CombineSocialPhotos\Cron::deactivate_actions' );




$GLOBALS['still-be-combine-social-photos'] = (object) array(
	'main'      => StillBE\Plugin\CombineSocialPhotos\Main::init(),
	'setting'   => StillBE\Plugin\CombineSocialPhotos\Setting::init(),

);





// END

