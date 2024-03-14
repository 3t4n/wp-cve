<?php
/**
 * XT Ajax Add To Cart for WooCommerce
 *
 * @package     XT_WOOATC
 * @author      XplodedThemes
 * @copyright   2018 XplodedThemes
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: XT Ajax Add To Cart for WooCommerce
 * Plugin URI:  https://xplodedthemes.com/products/woo-ajax-add-to-cart/
 * Description: Shop / Single Ajax Add To Cart for WooCommerce will allow users to add single products or variable products to the cart without the need to reload the entire site each time.
 * Version:     1.1.1
 * WC requires at least: 4.0
 * WC tested up to: 8.5
 * Author:      XplodedThemes
 * Author URI:  https://xplodedthemes.com
 * Text Domain: xt-woo-ajax-add-to-cart
 * Domain Path: /languages/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
  */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $xt_wooatc_plugin;

$market = '##XT_MARKET##';
$market = ( strpos( $market, 'XT_MARKET' ) !== false ) ? 'freemius' : $market;

$xt_wooatc_plugin = array(
    'version'       => '1.1.1',
    'name'          => esc_html__('Ajax Add To Cart for WooCommerce', 'xt-woo-ajax-add-to-cart'),
    'menu_name'     => esc_html__('Ajax Add To Cart', 'xt-woo-ajax-add-to-cart'),
    'url'           => 'https://xplodedthemes.com/products/woo-ajax-add-to-cart/',
    'icon'          => 'dashicons-image-filter',
    'slug'          => 'xt-woo-ajax-add-to-cart',
	'prefix'        => 'xt_wooatc',
	'short_prefix'  => 'xt_wooatc',
    'market'        => $market,
    'markets'      => array(
        'freemius' => array(
            'id'            => 8641,
            'key'           => 'pk_1905ddbe3a63322ab5b416656d21d',
            'url'           => 'https://xplodedthemes.com/products/xt-woo-ajax-add-to-cart/',
            'freemium_slug' => 'xt-woo-ajax-add-to-cart'
        )
    ),
    'dependencies'  => array(
		array(
			'name'  => 'WooCommerce',
            'class' => 'WooCommerce',
            'slug'  => 'woocommerce'
		)
	),
    'conflicts' => array(),
    'file'          => __FILE__
);

if ( function_exists( 'xt_wooatc' ) ) {

    xt_wooatc()->access_manager()->set_basename(true, __FILE__);

}else{

    /**
     * Require XT Framework
     *
     * @since    1.0.0
     */
    require_once plugin_dir_path(__FILE__) . 'xt-framework/start.php';

    /**
     * Require main plugin file
     *
     * @since    1.0.0
     */
    require_once plugin_dir_path(__FILE__) . 'class-core.php';

    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function xt_wooatc()
    {

        global $xt_wooatc_plugin;

        return XT_WOOATC::instance($xt_wooatc_plugin);
    }

    // Run Plugin.
    xt_wooatc();

}
