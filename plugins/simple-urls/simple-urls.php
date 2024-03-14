<?php

use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Init;

// ? ==============================================================================================
// ? WE SHOULD UPDATE THE VERSION NUMBER HERE AS WELL WHEN RELEASING A NEW VERSION
define( 'LASSO_LITE_VERSION', '125' );
// ? ==============================================================================================

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( "SIMPLE_URLS_SLUG", "surl" );
define( 'SIMPLE_URLS_DIR', plugin_dir_path( __FILE__ ) );
define( 'SIMPLE_URLS_URL', plugins_url( '', __FILE__ ) );
define( 'SIMPLE_URLS_PLUGIN_PATH', __DIR__ );

require_once SIMPLE_URLS_DIR . '/admin/constant.php';
require_once SIMPLE_URLS_DIR . DIRECTORY_SEPARATOR . 'autoload.php';
require_once SIMPLE_URLS_DIR . '/vendor-prefix/vendor/autoload.php';

// ? Sentry declaration
require_once SIMPLE_URLS_DIR . '/libs/lasso-lite/lasso-lite-sentry.php';

require_once SIMPLE_URLS_DIR . '/includes/class-simple-urls.php';
new Simple_Urls();

if ( is_admin() ) {
	require_once SIMPLE_URLS_DIR . '/includes/class-simple-urls-admin.php';
	new Simple_Urls_Admin();
}

new Init();

function activate_lasso_lite() {
	update_option( Enum::LASSO_LITE_ACTIVE, 1 );
}

function deactivate_lasso_lite() {
	Helper::update_option( Enum::IS_PRE_POPULATED_AMAZON_API, 0 );
}
register_activation_hook( __FILE__, 'activate_lasso_lite' );
register_deactivation_hook( __FILE__, 'deactivate_lasso_lite' );
