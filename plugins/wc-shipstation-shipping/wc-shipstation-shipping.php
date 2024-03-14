<?php
/**
 * Plugin Name: Multi-Carrier ShipStation Shipping for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/wc-shipstation-shipping/
 * Description: Displays live ShipStation shipping rates at cart and checkout pages
 * Version: 1.4.10
 * Tested up to: 6.4
 * Requires PHP: 5.6
 * Author: OneTeamSoftware
 * Author URI: http://oneteamsoftware.com/
 * Developer: OneTeamSoftware
 * Developer URI: http://oneteamsoftware.com/
 * Text Domain: wc-shipstation-shipping
 * Domain Path: /languages
 *
 * Copyright: © 2024 FlexRC, Canada.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace OneTeamSoftware\WooCommerce\Shipping;

defined('ABSPATH') || exit;

require_once(__DIR__ . '/includes/autoloader.php');
	
(new Plugin(
		__FILE__, 
		'ShipStation', 
		sprintf('<div class="notice notice-info inline"><p>%s<br/><li><a href="%s" target="_blank">%s</a><br/><li><a href="%s" target="_blank">%s</a></p></div>', 
			__('Real-time ShipStation live shipping rates', 'wc-shipstation-shipping'),
			'https://1teamsoftware.com/contact-us/',
			__('Do you have any questions or requests?', 'wc-shipstation-shipping'),
			'https://wordpress.org/plugins/wc-shipstation-shipping/', 
			__('Do you like our plugin and can recommend to others?', 'wc-shipstation-shipping')),
		'1.4.10'
	)
)->register();
