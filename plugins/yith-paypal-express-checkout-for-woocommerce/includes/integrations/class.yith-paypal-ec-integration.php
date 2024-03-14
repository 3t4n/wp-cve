<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since  1.0.0
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_PayPal_EC_Integration' ) ) {
	/**
	 * Class YITH_PayPal_EC_Integration
	 */
	class YITH_PayPal_EC_Integration {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_PayPal_EC_Integration
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_PayPal_EC_Integration
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used.
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			// Load the integration with YITH WooCommerce Subscription Premium.
			if ( defined( 'YITH_YWSBS_PREMIUM' ) && version_compare( YITH_YWSBS_VERSION, '1.4.5', '>' ) ) {
				require_once YITH_PAYPAL_EC_INC . 'integrations/class.yith-paypal-ec-subscription.php';
				YITH_PayPal_EC_Subscription(); //phpcs:ignore
			}

		}

	}

	/**
	 * Unique access to instance of YITH_PayPal_EC_Subscription class
	 *
	 * @return \YITH_PayPal_EC_Subscription
	 */
	function YITH_PayPal_EC_Integration() { //phpcs:ignore
		return YITH_PayPal_EC_Integration::get_instance();
	}
}
