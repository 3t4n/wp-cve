<?php

/**
 * Furgonetka plugin
 *
 * @link    https://furgonetka.pl
 * @since   1.0.0
 * @package Furgonetka
 *
 * @wordpress-plugin
 * Plugin Name:       Furgonetka.pl
 * Plugin URI:        https://furgonetka.pl
 * Description:       Połącz swój sklep z modułem Furgonetka.pl! Generuj etykiety, twórz szablony przesyłek, śledź statusy paczek. Nadawaj paczki szybko i tanio korzystając z 10 firm kurierskich.
 * Version:           1.4.0
 * Author:            Furgonetka.pl
 * Author URI:        https://furgonetka.pl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Developer:         codebox
 * Developer URI:     http://codebox.pl
 * Text Domain:       furgonetka
 * Domain Path:       /languages
 *
 * WC requires at least: 3.4.7
 * WC tested up to: 7.8.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
{
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FURGONETKA_VERSION', '1.4.0' );
define( 'FURGONETKA_PLUGIN_NAME', 'furgonetka' );
define( 'FURGONETKA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FURGONETKA_DEBUG', false );


/**
 * This function runs when WordPress completes its install/upgrade process
 */
function furgonetka_upgrade_completed( $upgrader_object, $options )
{
    // If an update has taken place and the updated type is plugins and the plugins element exists
    if ( $options['action'] === 'update' && $options['type'] === 'plugin' && isset( $options['plugins'] ) ) {

        // Iterate through the plugins being updated and check if ours is there
        if ( in_array( 'furgonetka/furgonetka.php', $options['plugins'] ) ) {
            $plugin_data = get_plugin_data( __FILE__ );
            Furgonetka_Admin::perform_migrations();
            Furgonetka_Admin::update_plugin_version( $plugin_data['Version'] );
        }
    }

    // If an update has taken place via package install
    if ( $options['action'] === 'install' && $options['type'] === 'plugin' ) {
        $new_plugin_data = $upgrader_object->new_plugin_data;

        if ( $new_plugin_data['Author'] === 'Furgonetka.pl' && $new_plugin_data['Name'] === 'Furgonetka.pl' ) {
            Furgonetka_Admin::perform_migrations();
            Furgonetka_Admin::update_plugin_version( $new_plugin_data['Version'] );
        }
    }
}

/**
 * Add tasks after upgrade module
 */
function perform_migrations() {
    $previous_plugin_version = get_option('furgonetka_version', '1.0.0');

    /** If module version is already this same, skip tasks */
    if ($previous_plugin_version === FURGONETKA_VERSION) {
        return;
    }

    require_once 'includes/class-furgonetka-migrations-performer.php';
    $migrations_performer = new Furgonetka_Migrations_Performer();
    $migrations_performer->run($previous_plugin_version);

    /** Update module version, to prevent run tasks twice */
    update_option('furgonetka_version', FURGONETKA_VERSION);
}

add_action( 'upgrader_process_complete', 'furgonetka_upgrade_completed', 10, 2 );

add_action( 'woocommerce_add_to_cart', 'set_default_customer_address' );

add_action('furgonetka_perform_migrations', 'perform_migrations');

/**
 * Set default customer address to manage shipping prices problem
 *
 * @return void
 */
function set_default_customer_address() {
    $default_customer_address = get_option( 'woocommerce_default_customer_address' );
    if ( $default_customer_address === '' ) {
        $shop_country = get_customer_shop_country();

        if ( $shop_country == '' ) {
            $shop_country = get_option('woocommerce_default_country');
        }
        if ( $shop_country != '' ) {
            WC()->customer->set_billing_country( $shop_country );
            WC()->customer->set_shipping_country( $shop_country );
        }
    }
}

function get_customer_shop_country() {
    $customer = WC()->session->get( 'customer' );
    $shop_country = '';

    if ($customer !== NULL) {
        $shipping_destination = get_option( 'woocommerce_ship_to_destination' );
        $shipping = $customer['shipping_country'];
        $billing = $customer['country'];

        if ( $shipping_destination === 'shipping' && $shipping != '' ) {
            $shop_country = $shipping;
        } elseif ( $shipping_destination === 'billing' || $shipping_destination === 'billing_only' ) {
            if ( $billing != '' ) {
                $shop_country = $billing;
            }
        }
    }

    return $shop_country;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-furgonetka-activator.php
 */
function activate_furgonetka()
{
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-furgonetka-activator.php';
        Furgonetka_Activator::activate();

        Furgonetka_Admin::update_plugin_version( FURGONETKA_VERSION );
    }
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-furgonetka-deactivator.php
 */
function deactivate_furgonetka()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-furgonetka-deactivator.php';
    Furgonetka_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_furgonetka' );
register_deactivation_hook( __FILE__, 'deactivate_furgonetka' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-furgonetka.php';

add_filter(
    'woocommerce_rest_orders_prepare_object_query',
    function ( array $args, \WP_REST_Request $request )
    {
        $modified_after = $request->get_param( 'modified_after' );

        if ( ! $modified_after ) {
            return $args;
        }

        $args['date_query'][] = array(
            'column' => 'post_modified',
            'after'  => $modified_after,
        );

        return $args;
    },
    100,
    2
);

/**
 * Declare compatibility
 */
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
    }
} );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_furgonetka()
{
    if (
        in_array(
            'woocommerce/woocommerce.php',
            apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
            true
        )
    ) {
        $plugin = new Furgonetka();
        $plugin->run();
    }
}

run_furgonetka();
