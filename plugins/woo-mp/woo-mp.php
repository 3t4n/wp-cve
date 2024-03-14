<?php

/**
 * Plugin Name: WooCommerce Manual Payment
 * Description: Process payments from the WooCommerce Edit Order screen.
 * Version: 2.8.1
 * Author: bfl
 * Text Domain: woo-mp
 * WC requires at least: 3.3
 * WC tested up to: 8
 */

defined( 'ABSPATH' ) || die;

$woo_mp_should_load = ( is_admin() && ! is_network_admin() ) || ( defined( 'DOING_CRON' ) && DOING_CRON );

/**
 * Filters whether the plugin should load.
 *
 * You will need to bind your callback before this plugin loads.
 *
 * Warning: This plugin is intended to be used in the WP Admin. It may not
 *          function correctly outside of it. Loading the plugin outside of
 *          the WP Admin may also impact the security of your site. Test each
 *          release before updating your production site.
 *
 * @param bool $should_load Whether the plugin should load.
 */
$woo_mp_should_load = apply_filters( 'woo_mp_should_load', $woo_mp_should_load );

if ( ! $woo_mp_should_load ) {
    return;
}

define( 'WOO_MP_VERSION', '2.8.1' );
define( 'WOO_MP_PRO_COMPAT_VERSION', 9 );
define( 'WOO_MP_PATH', dirname( __FILE__ ) );
define( 'WOO_MP_URL', plugins_url( '', __FILE__ ) );
define( 'WOO_MP_BASENAME', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );

require WOO_MP_PATH . '/includes/woo-mp-requirement-checks.php';

if ( ! Woo_MP_Requirement_Checks::run() ) {
    return;
}

require WOO_MP_PATH . '/includes/bootstrap.php';
