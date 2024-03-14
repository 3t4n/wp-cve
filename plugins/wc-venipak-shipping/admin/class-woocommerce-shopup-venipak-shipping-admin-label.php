<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://shopup.lt/
 * @since      1.7.0
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/admin
 * @author     ShopUp <info@shopup.lt>
 */
class Woocommerce_Shopup_Venipak_Shipping_Admin_Label {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	private $venipak_username;

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	private $venipak_password;

	/**
	 *
	 *
	 * @since    1.11.0
	 */
	private $label_format;

	/**
   *
   *
   * @since    1.0.0
   */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings = $settings;
		$this->venipak_username = $settings->get_option_by_key('shopup_venipak_shipping_field_username');
		$this->venipak_password = $settings->get_option_by_key('shopup_venipak_shipping_field_password');
		$this->label_format = $settings->get_option_by_key('shopup_venipak_shipping_field_labelformat');

	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_bulk_admin_notice() {
		if ( ! empty( $_REQUEST['venipak_labels_link'] ) ) {
			// Sanitize the URL to remove any malicious code
			$processed_result = esc_url( $_REQUEST['venipak_labels_link'] );
	
			// Use printf with a placeholder for the sanitized URL
			printf( '<div class="notice notice-success fade is-dismissible"><p><a target="_blank" href="%s">Venipak PDF</a></p></div>', $processed_result );
		}
	}	

	public function add_venipak_shipping_bulk_action_process( $redirect_to, $action, $post_ids ) {
		if ( $action === 'shopup_venipak_shipping_labels' ) {
			$pack_no_collection = [];
			foreach ($post_ids as $post_id) {
				$order = wc_get_order($post_id);
				$venipak_shipping_order_data = json_decode($order->get_meta('venipak_shipping_order_data', true), true);
				$pack_numbers = $venipak_shipping_order_data['pack_numbers'];

				// Add pack numbers to the collection while maintaining their sequence
				foreach ($pack_numbers as $pack_number) {
					$pack_no_collection[] = $pack_number;
				}
			}
			$url = 'https://go.venipak.lt/ws/print_label';
			$body = array(
				'user' => $this->venipak_username,
				'pass' => $this->venipak_password,
				'pack_no' => $pack_no_collection,
				'format' => $this->label_format,
				'carrier' => 'all'
			);
			$args = array(
				'body' => $body,
				'headers' => array(
					'Referer' => 'https://woocommerce.com/'
				)
			);
			error_log(json_encode($args));
			$response = wp_remote_post( $url, $args );
			header("Content-type:application/pdf");
			echo wp_remote_retrieve_body( $response );
			die();
		}
		return $redirect_to;
	}
	
		

	public function get_label_pdf() {
		$order_id = $_GET['order_id'];
		if (trim($order_id) === '') {
			wp_die();
		}
		$order = wc_get_order($_GET['order_id']);
		$venipak_shipping_order_data = json_decode($order->get_meta('venipak_shipping_order_data', true), true);
		$pack_numbers = $venipak_shipping_order_data['pack_numbers'];

		$url = 'https://go.venipak.lt/ws/print_label';
		$body = array(
			'user' => $this->venipak_username,
			'pass' => $this->venipak_password,
			'pack_no' => $pack_numbers,
			'format' => $this->label_format,
			'carrier' => 'all'
		);
		$args = array(
			'body' => $body,
			'headers' => array(
        		'Referer' => 'https://woocommerce.com/'
    		)
		);
		$response = wp_remote_post( $url, $args );
		header("Content-type:application/pdf");
		echo wp_remote_retrieve_body( $response );
		wp_die();
	}


	public function get_manifest_pdf() {
		$order_id = $_GET['order_id'];
		if (trim($order_id) === '') {
			wp_die();
		}
		$order = wc_get_order($_GET['order_id']);
		$venipak_shipping_order_data = json_decode($order->get_meta('venipak_shipping_order_data', true), true);
		$manifest = $venipak_shipping_order_data['manifest'];

		$url = 'https://go.venipak.lt/ws/print_list';
		$body = array(
			'user' => $this->venipak_username,
			'pass' => $this->venipak_password,
			'code' => $manifest
		);
		$args = array(
			'body' => $body,
			'headers' => array(
        		'Referer' => 'https://woocommerce.com/'
    		)
		);
		$response = wp_remote_post( $url, $args );
		header("Content-type:application/pdf");
		echo wp_remote_retrieve_body( $response );
		wp_die();
	}
}
