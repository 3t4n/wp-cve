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

use Automattic\WooCommerce\PayPal_Here\Plugin;

defined( 'ABSPATH' ) or exit;

/**
 * WooCommerce PayPal Here Gateway Admin handler class.
 *
 * @since 1.0.0
 */
class Admin {


	/** @var Meta_Boxes Order Meta Box Handler instance */
	protected $meta_box_handler;


	/**
	 * Constructs the admin class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->meta_box_handler = new Meta_Boxes();

		// add body class we can use to identify paypal here screens in css
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ), 10, 1 );

		// enqueue styles and scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );

		// add data to the get_customer_details request on paypal here screens
		add_filter( 'woocommerce_ajax_get_customer_details', array( $this, 'add_customer_details_to_ajax_request' ), 10, 3 );
	}


	/**
	 * Adds a PayPal Here body class on the order listing and detail pages.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 *
	 * @param string $body_class the current body class
	 * @return string
	 */
	public function add_admin_body_class( $body_class ) {

		if ( $this->is_paypal_here_order_screen() ) {
			$body_class = trim( rtrim( $body_class ) . ' paypal-here-order' );
		}

		return $body_class;
	}


	/**
	 * Returns whether the current admin page is a PayPal Here order screen.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_paypal_here_order_screen() {

		return    isset( $_GET['paypal_here'] )
		       && '1' === $_GET['paypal_here']
		       && ( $screen = get_current_screen() )
		       && 'shop_order' === $screen->id;
	}


	/**
	 * Enqueues admin scripts and styles.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts_and_styles() {

		$this->enqueue_styles();
		$this->enqueue_scripts();
	}


	/**
	 * Enqueues admin styles.
	 *
	 * @since 1.0.0
	 */
	private function enqueue_styles() {

		$path    = wc_paypal_here()->get_plugin_url() . '/assets/css/admin/';
		$version = Plugin::VERSION;

		wp_enqueue_style( 'wc-paypal-here-admin-order', $path . 'wc-paypal-here-admin-order.min.css', array(), $version );
	}


	/**
	 * Enqueues admin scripts.
	 *
	 * @since 1.0.0
	 */
	private function enqueue_scripts() {

		$path = wc_paypal_here()->get_plugin_url() . '/assets/js/admin/';
		$version  = Plugin::VERSION;

		// meta box scripts
		wp_register_script( 'wc-paypal-here-meta-box', $path . 'wc-paypal-here-meta-box.min.js', array(), $version );

		$registered_meta_box_handles = $this->register_meta_box_scripts();
		$active_meta_box_keys        = array_intersect( $registered_meta_box_handles, $this->meta_box_handler->get_active_meta_box_keys() );
		$js_only_meta_box_handles    = array_intersect( $registered_meta_box_handles, array( 'wc-paypal-here-meta-box-order-items' ) );
		$meta_box_dependencies       = array_unique( array_merge( $active_meta_box_keys, $js_only_meta_box_handles ) );
		$current_order               = wc_get_order();

		// main admin script
		wp_enqueue_script( 'wc-paypal-here-admin', $path . 'wc-paypal-here-admin.min.js', $meta_box_dependencies, $version );

		wp_localize_script( 'wc-paypal-here-admin', 'wc_paypal_here_admin', array(
			'ajax_url'                    => admin_url( 'admin-ajax.php' ),
			'order_id'                    => $current_order ? $current_order->get_id() : '',
			'get_customer_details_nonce'  => wp_create_nonce( 'get-customer-details' ),
			'generate_qr_code_nonce'      => wp_create_nonce( 'generate-qr-code' ),
			'is_paypal_here_order_screen' => $this->is_paypal_here_order_screen(),
			'custom_new_order_url'        => esc_url( admin_url( 'post-new.php?post_type=shop_order&paypal_here=1' ) ),
			'i18n'                        => array(
				'email_address'            => __( 'Email Address', 'woocommerce-gateway-paypal-here' ),
				'phone_number'             => __( 'Phone Number', 'woocommerce-gateway-paypal-here' ),
				'title_action_button_text' => _x( 'PayPal Here', 'button', 'woocommerce-gateway-paypal-here' ),
				'add_items_button_text'    => _x( '+ item(s)', 'button', 'woocommerce-gateway-paypal-here' ),
				'add_coupon_button_text'   => _x( '+ coupon', 'button', 'woocommerce-gateway-paypal-here' ),
				'edit_item_button_text'    => _x( 'Edit', 'button', 'woocommerce-gateway-paypal-here' ),
				'delete_item_button_text'  => _x( 'Delete', 'button', 'woocommerce-gateway-paypal-here' ),
			),
		) );
	}


	/**
	 * Registers any scripts found in the meta box directory.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] array of registered handles
	 */
	private function register_meta_box_scripts() {

		$meta_boxes_path    = wc_paypal_here()->get_plugin_path() . '/assets/js/admin/meta_boxes/';
		$meta_boxes_url     = wc_paypal_here()->get_plugin_url() . '/assets/js/admin/meta_boxes/';
		$registered_handles = array();
		$version            = Plugin::VERSION;
		$meta_box_js_files  = glob( $meta_boxes_path . '*.min.js' );

		foreach ( $meta_box_js_files as $filename ) {

			if ( is_readable( $filename ) ) {

				$key = basename( $filename, '.min.js' );

				wp_register_script( $key, $meta_boxes_url . basename( $filename ), array( 'wc-paypal-here-meta-box' ), $version );

				$registered_handles[] = $key;
			}
		}

		return $registered_handles;
	}


	/**
	 * Adds more customer data to the default get_customer_details AJAX request.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data customer data to return
	 * @param \WC_Customer $customer the customer object
	 * @param int $user_id the customer ID
	 * @return array
	 */
	public function add_customer_details_to_ajax_request( $data, $customer, $user_id ) {

		$modify = isset( $_POST['paypal_here'] ) && 1 === (int) $_POST['paypal_here'];

		if ( $modify ) {

			$data['billing_formatted']       = wp_kses( wc_get_account_formatted_address( 'billing', $user_id ), array( 'br' => array() ) );
			$data['shipping_formatted']      = wp_kses( wc_get_account_formatted_address( 'shipping', $user_id ), array( 'br' => array() ) );

			if ( isset( $data['billing']['phone'] ) ) {

				$data['billing_phone_formatted'] = wp_kses(
					wc_make_phone_clickable( $data['billing']['phone'] ),
					array( 'a' => array( 'href' => array() ) ),
					array( 'tel' )
				);
			}

			if ( isset( $data['billing']['email'] ) ) {

				$data['billing_email_formatted'] = wp_kses(
					make_clickable( $data['billing']['email'] ),
					array( 'a' => array( 'href' => array() ) ),
					array( 'mailto' )
				);
			}
		}

		return $data;
	}


}
