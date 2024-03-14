<?php
defined( 'ABSPATH' ) || exit;

require plugin_dir_path( __FILE__ ) . '/class-wfty-woo-order-data.php';

/**
 * Class WFTY_Data
 * @package WFTY
 * @author XlPlugins
 */
if ( ! class_exists( 'WFTY_Data' ) ) {
	#[AllowDynamicProperties]

  class WFTY_Data {

		private static $ins = null;
		public $page_id = false;
		public $page_link = false;
		private $order_id = false;
		private $order = false;
		private $error_showed = null;
		public $component_order_details = false;

		private $dummy_order_data = array(
			"name"       => "John Doe",
			"email"      => "john.doe@example.com",
			"first_name" => "John",
			"last_name"  => "Doe",
			"phone"      => "(999) 999-9999",
			'address_1'  => '711-2880 Nulla St',
			'address_2'  => '',
			'city'       => 'New York',
			'state'      => 'NY',
			'postcode'   => '10001',
			'country'    => 'US'
		);

		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}


			return self::$ins;
		}

		public function __construct() {

		}

		/**
		 * Setup Thank you post and return the WFTY_Data Singleton Instance
		 *
		 * @param $order_id
		 *
		 * @return mixed | WFTY_Data
		 */
		public function setup_thankyou_post( $order_id = 0 ) {

			if ( ! is_numeric( $order_id ) ) {
				return false;
			}

			$this->load_order( $order_id );

			$contents = apply_filters( 'wffn_wfty_filter_page_ids', [], $this->get_order() );


			$get_the_decided_page = is_array( $contents ) && count( $contents ) > 0 ? $contents[0] : false;


			if ( false !== $get_the_decided_page ) {
				$this->page_id   = $this->maybe_custom_thankyou( $get_the_decided_page );
				$this->page_link = get_permalink( $this->page_id );
				$this->page_link = add_query_arg( array( 'wfty_source' => $this->page_id ), get_permalink( $this->page_id ) );
			}

			return $this;
		}

		public function load_order( $order_id = 0 ) {
			if ( $order_id instanceof WP ) {
				$order_id = 0;
			}

			if ( $order_id instanceof WC_Order ) {
				$this->order_id = $order_id->get_id();
				$this->order    = $order_id;
			} else {
				if ( $order_id < 1 ) {
					$order_id = ( isset( $_GET['order_id'] ) && ( ! empty( $_GET['order_id'] ) ) ) ? wffn_clean( $_GET['order_id'] ) : 0; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				}
				if ( $order_id > 0 ) {
					$this->order_id = $order_id;
					$this->order    = wc_get_order( $order_id );
				}
			}
		}

		/**
		 * @param int $id
		 *
		 * @return WC_Order|bool
		 */
		public function get_order( $id = 0 ) {
			if ( $id > 0 ) {
				$this->load_order( $id );
			}

			return $this->order;
		}

		public function get_page_link() {
			return $this->page_link;
		}

		public function get_order_id() {
			return ( false === $this->get_order() ) ? 0 : $this->get_order()->get_order_number();
		}

		public function get_order_key() {
			if ( false === $this->get_order() ) {
				return 'wc-demo-order-key-' . wp_rand();
			}
			if ( $this->order ) {
				return $this->order->get_order_key();
			}
		}

		public function reset_order( $order = 0 ) {
			if ( $order < 1 ) {
				$this->order = false;
			}

			$this->order = $order;
		}

		public function load_order_wp( $order_id = 0 ) {

			if ( function_exists( 'is_order_received_page' ) && ! is_order_received_page() ) {
				return;
			}

			if ( $order_id instanceof WP ) {
				$order_id = 0;
			}

			if ( $order_id < 1 ) {
				$order_id = ( isset( $_GET['order_id'] ) && ( ! empty( $_GET['order_id'] ) ) ) ? wffn_clean( $_GET['order_id'] ) : 0; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( $order_id > 0 ) {
				$this->order_id = $order_id;
				$this->order    = wc_get_order( $order_id );
			}
		}

		public function get_customer_first_name() {
			if ( false === $this->get_order() ) {
				return $this->dummy_order_data['first_name'];
			}
			if ( $this->order_id && $this->order ) {
				return WFTY_Woo_Order_Data::get_customer_first_name( $this->order );
			}
		}

		public function get_customer_last_name() {
			if ( false === $this->get_order() ) {
				return $this->dummy_order_data['last_name'];
			}
			if ( $this->order_id && $this->order ) {
				return WFTY_Woo_Order_Data::get_customer_last_name( $this->order );
			}
		}

		public function get_customer_email() {
			if ( false === $this->get_order() ) {
				return $this->dummy_order_data['email'];
			}
			if ( $this->order_id && $this->order ) {
				return WFTY_Woo_Order_Data::get_customer_email( $this->order );
			}
		}

		public function get_customer_phone() {
			if ( false === $this->get_order() ) {
				return $this->dummy_order_data['phone'];
			}
			if ( $this->order_id && $this->order ) {
				return WFTY_Woo_Order_Data::get_customer_phone( $this->order );
			}
		}

		public function get_order_details( $args ) {
			if ( is_string( $args ) ) {
				$args = [];
			}
			$user = WFFN_Core()->role->user_access( 'funnel', 'read' );

			//Unable to get Order & User or Guest doesn't have funnel read permission.
			if ( false === $this->get_order() ) {
				if ( ( false === $user ) && ( false === apply_filters( 'wffn_show_dummy_ty_order_data', false ) ) ) {
					return $this->show_error_message();

				}

				return WFTY_Woo_Order_Data::get_dummy_order_details( $args );
			}
			if ( $this->order ) {
				return WFTY_Woo_Order_Data::get_order_details( $this->order, $args );
			}
		}

		public function get_customer_info( $args ) {
			if ( is_string( $args ) ) {
				$args = [];
			}

			$user = WFFN_Core()->role->user_access( 'funnel', 'read' );

			if ( false === $this->get_order() ) {
				if ( ( false === $user ) && ( false === apply_filters( 'wffn_show_dummy_ty_order_data', false ) ) ) {
					return $this->show_error_message();

				}

				return WFTY_Woo_Order_Data::get_dummy_customer_details( $this->dummy_order_data, $args );
			}

			if ( $this->order && ( true === apply_filters( 'wffn_show_customer_info_non_logged_user', true, $this->order ) ) ) {
				return WFTY_Woo_Order_Data::get_customer_details( $this->get_order(), $args );
			}
		}

		/**
		 * if thankyou page set custom redirect
		 */
		public function maybe_custom_thankyou( $id ) {
			$type = get_post_type( $id );

			if ( 'wffn_ty' !== $type ) {
				return $id;
			}

			$data = get_post_meta( $id, 'wffn_step_custom_settings', true );

			if ( ! isset( $data['custom_redirect_page'] ) || ( isset( $data['custom_redirect_page'] ) && $data['custom_redirect'] !== 'true' ) ) {
				return $id;
			}

			if ( is_array( $data['custom_redirect_page'] ) && count( $data['custom_redirect_page'] ) > 0 ) {
				$custom_page = get_post( $data['custom_redirect_page']['id'] );
				if ( ! is_null( $custom_page ) && $custom_page instanceof WP_Post && 'publish' === $custom_page->post_status ) {
					return $data['custom_redirect_page']['id'];
				}
			}

			return $id;
		}

		public function show_error_message( $force = false ) {
			if ( $force || null === $this->error_showed ) {
				$this->error_showed = true;

				return '<p class="woocommerce-notice">' . __( 'Order not found. You cannot access this page directly.', 'funnel-builder' ) . '</p>';

			}

		}

		public function get_order_details_current_component() {
			return $this->component_order_details;
		}

		public function get_order_total( $atts ) {

			$atts = shortcode_atts( array(
				'order_id' => 0,
			), $atts );

			$order_id = absint( $atts['order_id'] );
			if ( $order_id == 0 ) {
				if ( isset( $_REQUEST['order_id'] ) && $_REQUEST['order_id'] > 0 ) {
					$order_id = absint( $_REQUEST['order_id'] );
				}
			}
			$order_id = apply_filters( 'wfty_custom_field_order_id', $order_id );
			if ( $order_id == 0 ) {
				return '';
			}
			$metadata = BWF_WC_Compatibility::get_order_meta( wc_get_order( $order_id ), '_order_total' );
			if ( is_string( $metadata ) ) {
				return $metadata;
			}

			return '';
		}
	}
}
