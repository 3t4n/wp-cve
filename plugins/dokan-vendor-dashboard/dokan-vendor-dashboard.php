<?php
/**
 * Plugin Name: Dokan Vendor Dashboard
 * Plugin URI: https://wordpress.org/plugins/dokan-vendor-dashboard/
 * Description: An extension for vendor dashboard of Dokan
 * Version: 1.0.1
 * Author: weDevs
 * Author URI: https://wedevs.com/
 * Requires PHP: 7.2
 * WC requires at least: 5.0.0
 * WC tested up to: 7.7.2
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dokan-vendor-dashboard
 * Domain Path: /languages
 */

/*
 * Copyright (c) 2019 weDevs (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
defined( 'ABSPATH' ) || exit;

use WeDevs\DokanVendorDashboard\Hooks;
use WeDevs\DokanVendorDashboard\Settings;

/**
 * Vendor_Dashboard class
 *
 * @class Dokan_Vendor_Dashboard The class that holds the entire Vendor_Dashboard plugin
 *
 * @since 1.0.0
 */
final class Dokan_Vendor_Dashboard {

	/**
	 * Plugin version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.0.1';

	/**
	 * Holds the dependency data.
	 *
	 * @since 1.0.0
	 *
	 * @var \WeDevs\DokanVendorDashboard\Dependency
	 */
	public $dependency;

	/**
	 * Instance of self.
	 *
	 * @since 1.0.0
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Holds various class instances
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $container = array();

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once __DIR__ . '/vendor/autoload.php';

		$this->define_constants();

		// Instanciate the dependency resolver.
		$this->dependency = new \WeDevs\DokanVendorDashboard\Dependency();

		// Load this plugin after dokan is loaded successfully.
		add_action( 'dokan_loaded', [ $this, 'init_plugin' ] );

		$this->init_appsero_tracker();

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
	}

	/**
	 * Magic getter to bypass referencing plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param $prop
	 *
	 * @return mixed
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return $this->{$prop};
	}

	/**
	 * Magic isset to bypass referencing plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param $prop
	 *
	 * @return mixed
	 */
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}

	/**
	 * Initializes the Dokan_Vendor_Dashboard() class.
	 *
	 * Checks for an existing Dokan_Vendor_Dashboard() instance
	 * and if it doesn't find one, creates it.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Define all vendor-dashboard plugin constant.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION', $this->version );
		define( 'DOKAN_VENDOR_DASHBOARD_FILE', __FILE__ );
		define( 'DOKAN_VENDOR_DASHBOARD_DIR', dirname( DOKAN_VENDOR_DASHBOARD_FILE ) );
		define( 'DOKAN_VENDOR_DASHBOARD_URL', plugins_url( '', DOKAN_VENDOR_DASHBOARD_FILE ) );
		define( 'DOKAN_VENDOR_DASHBOARD_TEMPLATE_PATH', DOKAN_VENDOR_DASHBOARD_DIR . '/templates/' );
		define( 'DOKAN_VENDOR_DASHBOARD_ASSETS', DOKAN_VENDOR_DASHBOARD_URL . '/assets' );
		define( 'DOKAN_VENDOR_DASHBOARD_BUILD', DOKAN_VENDOR_DASHBOARD_URL . '/build' );
	}

	/**
	 * Initiates the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_plugin() {
		// Check dokan minimum required version or return.
		if ( ! $this->dependency->verify_dependencies() ) {
			return;
		}

		new Hooks();

		// Check if new dashboard ui is on from dokan settings.
		if ( ! Settings::is_switched_new_dashboard() ) {
			return;
		}

		$this->include_classes();
	}

	/**
	 * Callback for activation hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function activate() {
		$installer = new \WeDevs\DokanVendorDashboard\Installer();
		$installer->run();
	}

	/**
	 * Include classes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function include_classes() {
		$this->container['assets']         = new WeDevs\DokanVendorDashboard\Assets();
		$this->container['menu']           = new WeDevs\DokanVendorDashboard\Menu();
		$this->container['page_templates'] = new WeDevs\DokanVendorDashboard\PageTemplates();
	}

	/**
	 * Initiates Appsero services.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_appsero_tracker() {
		if ( ! class_exists( '\Appsero\Client' ) ) {
            return;
        }

        $client   = new \Appsero\Client( 'Appsero key for vendor dashboard plugin', 'Dokan Vendor Dashboard', DOKAN_VENDOR_DASHBOARD_FILE );
        $insights = $client->insights();

		$insights->add_extra(
            function() {
                return array(
                    'dokan_vendor_dashboard_version' => DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION,
				);
            }
        );

        $insights->init();
	}
}

/**
* Load Vendor Dashboard plugin.
*
* @since 1.0.0
*
* @return self
*/
function dokan_vendor_dashboard() {
	return Dokan_Vendor_Dashboard::init();
}

// Lets Go....
dokan_vendor_dashboard();
