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

namespace Automattic\WooCommerce\PayPal_Here\Admin;

defined( 'ABSPATH' ) or exit;

/**
 * Meta Box Handler class.
 *
 * Handles adding and removal of meta boxes on the add/edit order screen.
 *
 * @since 1.0.0
 */
class Meta_Boxes {


	/** @var array instantiated meta boxes */
	protected $meta_boxes = array();


	/**
	 * Constructs the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'current_screen', array( $this, 'initialize_meta_boxes' ) );

		// technically `add_meta_boxes_shop_order` would be best practice, but
		// since WC uses `add_meta_boxes` which is called earlier than the
		// dynamic version, we have to use the generic version as well so that
		// we can place our meta boxes at the correct spot on the page
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'add_meta_boxes_shop_order', array( $this, 'remove_meta_boxes' ) );
	}


	/**
	 * Initializes the meta boxes that will be inserted on the current screen.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Screen $current_screen the current screen object
	 */
	public function initialize_meta_boxes( $current_screen ) {

		$current_screen_id     = $current_screen->id;
		$is_paypal_here_screen = wc_paypal_here()->get_admin_handler()->is_paypal_here_order_screen();
		$meta_box_definitions  = $this->get_meta_box_definitions();

		foreach( $meta_box_definitions as $id => $definition ) {

			$display_metabox = true;

			// check paypal here page requirements
			if ( isset( $definition['paypal_here'] ) && $is_paypal_here_screen !== (bool) $definition['paypal_here'] ) {
				$display_metabox = false;
			}

			// don't display the meta box if we aren't on an allowed screen
			if ( ! in_array( $current_screen_id, $definition['screens'], true ) ) {
				$display_metabox = false;
			}

			// check new screen requirement
			if (    'add' === $current_screen->action
			     && isset( $definition['new_screens'] )
			     && false === $definition['new_screens'] ) {

				$display_metabox = false;
			}

			// check order status requirement
			if ( isset( $definition['paid_status'], $_GET['post'] ) && false === $definition['paid_status'] ) {

				$order = wc_get_order( (int) $_GET['post'] );

				if ( $order && ! $order->needs_payment() ) {
					$display_metabox = false;
				}
			}

			/**
			 * Filters whether or not to display a metabox on the current admin page.
			 *
			 * @since 1.0.1
			 *
			 * @param bool $display_metabox whether to display the metabox or not
			 * @param string $id the metabox ID
			 * @param array $definition the metabox definition array
			 * @param \WP_Screen $current_screen the current screen
			 */
			$display_metabox = apply_filters( 'wc_gateway_paypal_here_display_meta_box', $display_metabox, $id, $definition, $current_screen );

			if ( $display_metabox ) {
				// instantiate the meta box class and store a reference
				$this->meta_boxes[ $id ] = new $definition['class']();
			}
		}
	}


	/**
	 * Gets an array of keys for the meta boxes that are active on the current screen.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	public function get_active_meta_box_keys() {

		return array_keys( $this->meta_boxes );
	}


	/**
	 * Gets an array with meta box definitions used to instantiate them.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] meta box definitions, meta box ID is the array key {
	 *     @type string $class the fully-qualified class name for the meta box class
	 *     @type bool $paypal_here whether to display on paypal here pages or not
	 *     @type bool $paid_status whether to display on orders that have a 'paid' status
	 *     @type bool $new_screens whether to display on admin screens that are for new orders
	 *     @type string[] $screens an array of screen IDs this may appear on
	 * }
	 */
	protected function get_meta_box_definitions() {

		// the array keys are the meta box IDs, but also correspond with the
		// javascript file that will get automatically loaded and instantiated
		return array(
			'wc-paypal-here-meta-box-paypal-here' => array(
				'class'       => '\Automattic\WooCommerce\PayPal_Here\Admin\Meta_Boxes\PayPal_Here',
				'paypal_here' => false,
				'paid_status' => false,
				'new_screens' => false,
				'screens'     => array( 'shop_order' ),
			),
			'wc-paypal-here-meta-box-order-data' => array(
				'class'       => '\Automattic\WooCommerce\PayPal_Here\Admin\Meta_Boxes\Order_Data',
				'paypal_here' => true,
				'screens'     => array( 'shop_order' ),
			),
			'wc-paypal-here-meta-box-order-actions' => array(
				'class'       => '\Automattic\WooCommerce\PayPal_Here\Admin\Meta_Boxes\Order_Actions',
				'paypal_here' => true,
				'screens'     => array( 'shop_order' ),
			),
		);
	}


	/**
	 * Removes unnecessary meta boxes.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function remove_meta_boxes() {

		// only remove meta boxes on paypal here order screens
		if ( wc_paypal_here()->get_admin_handler()->is_paypal_here_order_screen() ) {

			remove_meta_box( 'woocommerce-order-actions',   'shop_order', 'side' );
			remove_meta_box( 'woocommerce-order-notes',     'shop_order', 'side' );
			remove_meta_box( 'woocommerce-order-data',      'shop_order', 'normal' );
			remove_meta_box( 'woocommerce-order-downloads', 'shop_order', 'normal' );
			remove_meta_box( 'postcustom',                  'shop_order', 'normal' );
		}
	}


	/**
	 * Adds custom meta boxes to the screen.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {

		foreach( $this->meta_boxes as $meta_box_id => $meta_box ) {

			add_meta_box(
				$meta_box_id,
				$meta_box->get_title(),
				array( $meta_box, 'output' ),
				get_current_screen(),
				$meta_box->get_context(),
				$meta_box->get_priority()
			);
		}
	}


}
