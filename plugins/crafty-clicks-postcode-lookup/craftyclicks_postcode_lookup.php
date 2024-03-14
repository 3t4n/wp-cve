<?php
/**
 * Plugin Name: Crafty Clicks Postcode Lookup
 * Plugin URI: https://craftyclicks.co.uk/plugins/download-info/woo-commerce/
 * Description: Adds CraftyClicks' UK Postcode Lookup to WooCommerce checkout pages.
 * Version: 1.2.11
 * Author: Crafty Clicks
 * Author URI: https://craftyclicks.co.uk/
 * WC requires at least: 2.4
 * WC tested up to: 3.6.4
 * Copyright: © 2019 Crafty Clicks.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html

 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

if ( ! class_exists( 'WC_CraftyClicks_Postcode_Lookup' ) ) :

class WC_CraftyClicks_Postcode_Lookup {

	/**
	* Construct the plugin.
	*/
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	* Initialize the plugin.
	*/
	public function init() {
		// Checks if WooCommerce is installed.
		if ( class_exists( 'WC_Integration' ) ) {
			// Include our integration class.
			include_once 'includes/class-wc-craftyclicks-postcode-lookup-integration.php';

			// Register the integration.
			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
		} else {
			// throw an admin error if you like
		}
	}

	/**
	 * Add a new integration to WooCommerce.
	 */
	public function add_integration( $integrations ) {
		$integrations[] = 'WC_CraftyClicks_Postcode_Lookup_Integration';
		return $integrations;
	}

}

$WC_CraftyClicks_Postcode_Lookup_Integration = new WC_CraftyClicks_Postcode_Lookup( __FILE__ );

endif;
