<?php
/*
 * Plugin Name: Deposits & Partial Payments for WooCommerce
 * Version: 1.1.18
 * Description: WooCommerce deposits allows customers to pay for products using a fixed or percentage amount in WooCommerce store
 * Author: Acowebs
 * Author URI: http://acowebs.com
 * Requires at least: 4.0
 * Tested up to: 6.4
 * Text Domain: deposits-partial-payments-for-woocommerce
 * WC requires at least: 4.0.0
 * WC tested up to: 8.6
 */

define('AWCDP_TOKEN', 'awcdp');
define('AWCDP_VERSION', '1.1.18');
define('AWCDP_FILE', __FILE__);
define('AWCDP_PLUGIN_NAME', 'Deposits & Partial Payments for WooCommerce');
define('AWCDP_TEXT_DOMAIN', 'deposits-partial-payments-for-woocommerce');
define('AWCDP_STORE_URL', 'https://api.acowebs.com');
define('AWCDP_POST_TYPE', 'awcdp_payment');
define('AWCDP_DEPOSITS_META_KEY', '_awcdp_deposit_enabled');
define('AWCDP_DEPOSITS_TYPE', '_awcdp_deposit_type');
define('AWCDP_DEPOSITS_AMOUNT', '_awcdp_deposits_deposit_amount');
define('AWCDP_DEPOSITS_FORCE', '_awcdp_deposit_force_deposit');
define('AWCDP_PLUGIN_PATH',  plugin_dir_path( __FILE__ ) );

require_once(realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes/helpers.php');

if (!function_exists('awcdp_init')) {

    function awcdp_init()
    {
        $plugin_rel_path = basename(dirname(__FILE__)) . '/languages'; /* Relative to WP_PLUGIN_DIR */
        load_plugin_textdomain('deposits-partial-payments-for-woocommerce', false, $plugin_rel_path);
    }

}

if (!function_exists('awcdp_autoloader')) {

    function awcdp_autoloader($class_name)
    {
      if (0 === strpos($class_name, 'AWCDP_Email')) {
          $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR. 'emails'. DIRECTORY_SEPARATOR ;
          $class_file = 'class-' . str_replace('_', '-', strtolower($class_name)) . '.php';
          require_once $classes_dir . $class_file;
      } else if (0 === strpos($class_name, 'AWCDP')) {
          $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
          $class_file = 'class-' . str_replace('_', '-', strtolower($class_name)) . '.php';
          require_once $classes_dir . $class_file;
      }
    }

}

if (!function_exists('AWCDP')) {

    function AWCDP()
    {
        $instance = AWCDP_Backend::instance(__FILE__, AWCDP_VERSION);
        return $instance;
    }

}
add_action('plugins_loaded', 'awcdp_init');
spl_autoload_register('awcdp_autoloader');
if (is_admin()) {
    AWCDP();
}
new AWCDP_Api();

new AWCDP_Front_End(__FILE__, AWCDP_VERSION);

add_action('current_screen', 'awcpd_setup_screen');

if (!function_exists('awcpd_setup_screen')) {
function awcpd_setup_screen() {

    if ( function_exists( 'get_current_screen' ) ) {
        $screen    = get_current_screen();
        $screen_id = isset( $screen, $screen->id ) ? $screen->id : '';
    }
    switch ( $screen_id ) {
        case 'edit-awcdp_payment':
            include_once  __DIR__ .'/includes/class-awcdp-list.php';
            $wc_list_table = new AWCDP_Admin_List_Table_Orders();
            break;
    }

    // Ensure the table handler is only loaded once. Prevents multiple loads if a plugin calls check_ajax_referer many times.
    remove_action( 'current_screen', 'awcpd_setup_screen' );
    remove_action( 'check_ajax_referer', 'awcpd_setup_screen' );
}
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );