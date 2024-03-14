<?php
/**
 * Plugin Name: NOWPayments for WooCommerce
 * Plugin URI: https://coderpress.co/products/nowpayments-for-woocommerce/
 * Author: CoderPress
 * Description: Allow WooCommerce user to checkout with 150+ crypto currencies.
 * Version: 1.1
 * Author: Syed Muhammad Usman
 * Author URI: https://coderpress.co/products/nowpayments-for-woocommerce/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * @author Syed Muhammad Usman
 * @url https://www.linkedin.com/in/syed-muhammad-usman/
 */


defined( 'ABSPATH' ) || exit;

/**
 * Freemius Integration
 *
 * @return Freemius
 * @throws Freemius_Exception
 * @since 1.0
 * @version 1.0
 */
if ( ! function_exists( 'nfw_fs' ) ) {
    // Create a helper function for easy SDK access.
    function nfw_fs() {
        global $nfw_fs;

        if ( ! isset( $nfw_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $nfw_fs = fs_dynamic_init( array(
                'id'                  => '10766',
                'slug'                => 'nowpayments-for-woocommerce',
                'type'                => 'plugin',
                'public_key'          => 'pk_d1f216cade13caf8ec98da3aa993d',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'first-path'     => 'plugins.php',
                    'account'        => false,
                    'contact'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $nfw_fs;
    }

    // Init Freemius.
    nfw_fs();
    // Signal that SDK was initiated.
    do_action( 'nfw_fs_loaded' );
}

if ( ! defined( 'NPWC_PLUGIN_FILE' ) ) {
    define( 'NPWC_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'NPWC_VERSION' ) ) {
    define( 'NPWC_VERSION', '1.1' );
}

if ( ! defined( 'NPWC_PLUGIN_URL' ) ) {
    define( 'NPWC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'NPWC_PLUGIN_DIR_PATH' ) ) {
    define( 'NPWC_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

require dirname( NPWC_PLUGIN_FILE ) . '/includes/class-npwc-init.php';

add_action( 'plugins_loaded', 'load_npwc' );


/**
 * Loads Plugin
 *
 * @since 1.0
 * @version 1.0
 */
function load_npwc() {
    NPWC_Init::get_instance();
}
