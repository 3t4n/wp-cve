<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class WFTY_Woo_Order_Data
 * @package WFTY
 * @author XlPlugins
 */
if ( ! class_exists( 'WFTY_Woo_Order_Data' ) ) {
	#[AllowDynamicProperties]

  class WFTY_Woo_Order_Data {

		private static $ins = null;

		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_first_name( $order ) {

			$customer_first_name = $order->get_billing_first_name();
			if ( ! empty( $customer_first_name ) ) {
				return $customer_first_name;
			}
			$customer_first_name = $order->get_shipping_first_name();
			if ( ! empty( $customer_first_name ) ) {
				return $customer_first_name;
			}

		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_last_name( $order ) {
			$customer_last_name = $order->get_billing_last_name();
			if ( ! empty( $customer_last_name ) ) {
				return $customer_last_name;
			}
			$customer_last_name = $order->get_shipping_last_name();
			if ( ! empty( $customer_last_name ) ) {
				return $customer_last_name;
			}
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_email( $order ) {
			$customer_email = $order->get_billing_email();
			if ( ! empty( $customer_email ) ) {
				return $customer_email;
			}
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_phone( $order ) {
			$customer_phone = $order->get_billing_phone();
			if ( ! empty( $customer_phone ) ) {
				return $customer_phone;
			}
		}

		/**
		 * @param int $order_id
		 *
		 * @return mixed
		 */
		public static function get_order_details( $order, $args ) {
			$order_details_component = new WFTY_Order_Details_Component( $args );
			$order_details_component->load_order( $order );
			ob_start();
			if ( $order ) {
				?>
                <div class="wffn_order_details_table">
					<?php
					$order_details_component->render();
					?>
                </div> <?php
			}

			return ob_get_clean();
		}

		/**
		 * @return false|string
		 */
		public static function get_dummy_order_details( $args ) {
			$order_details_component = new WFTY_Order_Details_Component( $args );
			ob_start(); ?>
            <div class="wffn_order_details_table">
				<?php $order_details_component->render_dummy(); ?>
            </div> <?php
			return ob_get_clean();
		}


		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_details( $order, $args ) {
			$customer_info_component = new WFTY_Customer_Info_Component( $args );
			$customer_info_component->load_order( $order );
			ob_start();
			if ( $order ) {
				$customer_info_component->render();
			}

			return ob_get_clean();
		}

		/**
		 * @return false|string
		 */
		public static function get_dummy_customer_details( $dummy_data, $args ) {
			$customer_info_component = new WFTY_Customer_Info_Component( $args );
			ob_start();
			$customer_info_component->render_dummy( $dummy_data );

			return ob_get_clean();
		}
	}
}
