<?php
/**
 * WooCommerce PayPal Here Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce PayPal Here Gateway to newer
 * versions in the future. If you wish to customize WooCommerce PayPal Here Gateway for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-gateway-paypal-here/
 *
 * @author    WooCommerce
 * @copyright Copyright (c) 2018-2020, Automattic, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace Automattic\WooCommerce\PayPal_Here;

use Automattic\WooCommerce\PayPal_Here\Admin\Admin;
use SkyVerge\WooCommerce\PluginFramework\v5_6_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * WooCommerce PayPal Here Gateway main plugin class.
 *
 * @since 1.0.0
 */
class Plugin extends Framework\SV_WC_Payment_Gateway_Plugin {


	/** @var Plugin single instance of this plugin */
	protected static $instance;

	/** @var Admin instance of the admin handler */
	public $admin_handler;

	/** string version number */
	const VERSION = '1.1.3';

	/** string the plugin ID */
	const PLUGIN_ID = 'paypal_here';

	/** string credit card gateway class name */
	const GATEWAY_CLASS_NAME = '\\Automattic\\WooCommerce\\PayPal_Here\\Gateway';

	/** string credit card gateway id */
	const GATEWAY_ID = 'paypal_here';


	/**
	 * Constructs the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			array(
				'currencies'  => array( 'USD', 'GBP', 'AUD' ),
				'gateways'    => array( self::GATEWAY_ID => self::GATEWAY_CLASS_NAME ),
				'require_ssl' => true,
				'text_domain' => 'woocommerce-gateway-paypal-here',
			)
		);
	}


	/**
	 * Initializes the admin handler.
	 *
	 * @since 1.0.0
	 */
	public function init_admin() {

		parent::init_admin();

		$this->admin_handler = new Admin();
	}


	/**
	 * Gets the admin handler instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Admin
	 */
	public function get_admin_handler() {

		return $this->admin_handler;
	}


	/**
	 * Gets the main PayPal Here instance.
	 *
	 * Ensures only one instance is/can be loaded.
	 *
	 * @see wc_paypal_here()
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin
	 */
	public static function instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Gets the plugin name.
	 *
	 * @see SV_WC_Payment_Gateway::get_plugin_name()
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_plugin_name() {
		return __( 'WooCommerce PayPal Here Gateway', 'woocommerce-gateway-paypal-here' );
	}


	/**
	 * Gets the plugin documentation URL.
	 *
	 * @see Framework\SV_WC_Plugin::get_documentation_url()
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_documentation_url() {

		return 'https://docs.woocommerce.com/document/woocommerce-gateway-paypal-here/';
	}


	/**
	 * Gets the plugin support URL.
	 *
	 * @see Framework\SV_WC_Plugin::get_support_url()
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_support_url() {

		return 'https://woocommerce.com/my-account/create-a-ticket/';
	}


	/**
	 * Gets the plugin sales page URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_sales_page_url() {

		return 'https://woocommerce.com/products/paypal-here/';
	}


	/**
	 * Returns __DIR__
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_file() {

		return __DIR__;
	}


}
