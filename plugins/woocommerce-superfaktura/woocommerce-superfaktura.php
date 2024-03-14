<?php
/**
 * SuperFaktúra WooCommerce
 *
 * @package   SuperFaktúra WooCommerce
 * @author    2day.sk <superfaktura@2day.sk>
 * @copyright 2022 2day.sk s.r.o., Webikon s.r.o.
 * @license   GPL-2.0+
 * @link      https://www.superfaktura.sk/integracia/
 *
 * @wordpress-plugin
 * Plugin Name: SuperFaktúra WooCommerce
 * Plugin URI:  https://www.superfaktura.sk/integracia/
 * Description: Integrácia služby <a href="http://www.superfaktura.sk/api/">SuperFaktúra.sk</a> pre WooCommerce. Máte s modulom technický problém? Napíšte nám na <a href="mailto:superfaktura@2day.sk">superfaktura@2day.sk</a>
 * Version:     1.40.6
 * Author:      2day.sk, Webikon
 * Author URI:  https://www.superfaktura.sk/integracia/
 * Text Domain: woocommerce-superfaktura
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * WC requires at least: 3.7.0
 * WC tested up to: 8.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'vendors/SFAPIclient.php';
require_once plugin_dir_path( __FILE__ ) . 'class-wc-superfaktura.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-secret-key-helper.php';

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'WC_SuperFaktura', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WC_SuperFaktura', 'deactivate' ) );

// Declare compatibility with HPOS.
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

WC_SuperFaktura::get_instance();

/**
 * Add link to plugin settings to plugin action links
 *
 * @param array $links Plugin action links.
 */
function sf_action_links( $links ) {

	return array_merge(
		array(
			'settings' => '<a href="' . get_admin_url( null, 'admin.php?page=wc-settings&tab=superfaktura' ) . '">' . __( 'Settings', 'woocommerce-superfaktura' ) . '</a>',
		),
		$links
	);

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'sf_action_links' );
