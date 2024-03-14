<?php
/**
 * Plugin Name: WooCommerce Honey Pot Anti Spam
 * Plugin URI: https://wordpress.org/plugins/woo-honey-pot-anti-spam
 * Description: Add honeypot anti-spam functionality to the popular woocommerce plugin.
 * Version: 1.1.0
 * Author: Kudosta
 * Author URI: https://kudosta.com
  * Text Domain: kudos-wchpas
 */


if ( ! defined( 'ABSPATH' ) )
{
  exit;
}

if ( ! function_exists('kudos_wc_hpas') )
{
  define( 'KUDOS_WC_HPAS', '0.1.0' );
  define( 'KUDOS_WC_HPAS_PATH', __DIR__ );
  require_once( KUDOS_WC_HPAS_PATH . '/classes/class.honeypot.plugin.php' );

  // Global function to retrieve the plugin instance
  function kudos_wc_honeypot_anti_spam ()
  {
    global $kudos_wc_hpas;

    // Initialise if not already available
    if ( empty( $kudos_wc_hpas ) )
    {
      $kudos_wc_hpas = new \KUDOS\WCHPAS\HomenypotPlugin();
    }

    return $kudos_wc_hpas;
  }

  // Add the action
  add_action( 'plugins_loaded', 'kudos_wc_honeypot_anti_spam' );
}
