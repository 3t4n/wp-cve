<?php
/**
 * Handle WP Emerge version checking to make sure a compatible version is loaded.
 *
 * @package WcGetnet
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'Extra_Checkout_Fields_For_Brazil' ) ) {
    add_action(
        'admin_notices',
        'admin_notices_message'
    );

    function admin_notices_message() {
        require_once plugin_dir_path( WC_GETNET_PLUGIN_FILE ) . 'views' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'dependency' . DIRECTORY_SEPARATOR . 'missing-ecfb.php';
    }
}