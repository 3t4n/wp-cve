<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\Includes
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

if ( ! class_exists( 'YITH_Pre_Order_My_Account' ) ) {
	/**
	 * Class YITH_Pre_Order_My_Account
	 */
	class YITH_Pre_Order_My_Account {

		/**
		 * Main Instance
		 *
		 * @var YITH_Pre_Order_My_Account
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_Pre_Order_My_Account
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Construct
		 */
		public function __construct() {
			add_action( 'woocommerce_my_account_my_orders_column_order-status', array( $this, 'add_pre_order_button_on_orders_page' ) );
			add_action( 'woocommerce_order_item_meta_start', array( $this, 'add_pre_order_info_on_single_order_page' ), 10, 3 );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
			add_action( 'woocommerce_account_my-pre-orders_endpoint', array( $this, 'endpoint_content' ) );
			add_filter( 'the_title', array( $this, 'endpoint_title' ) );
		}

		/**
		 * Add pre-order flag on Orders page (My account).
		 *
		 * @param WC_Order $order The WC_Order object.
		 */
		public function add_pre_order_button_on_orders_page( $order ) {
			if ( $order instanceof WC_Order ) {
				if ( ywpo_order_has_pre_order( $order ) ) {
					echo wp_kses_post( wc_get_order_status_name( $order->get_status() ) );
					$label  = apply_filters( 'ywpo_pre_order_flag_my_account_orders_label', __( 'Has pre-order item(s)', 'yith-pre-order-for-woocommerce' ), $order );
					$output = apply_filters( 'ywpo_pre_order_flag_my_account_orders_output', '<br><mark>' . $label . '</mark>', $order, $label );
					echo wp_kses_post( $output );
				} else {
					echo wp_kses_post( wc_get_order_status_name( $order->get_status() ) );
				}
			}
		}

		/**
		 * Add pre-order info on single order page (My account).
		 *
		 * @param int                   $item_id The item ID.
		 * @param WC_Order_Item_Product $item    The WC_Order_Item_Product object.
		 * @param WC_Order              $order   The WC_Order object.
		 */
		public function add_pre_order_info_on_single_order_page( $item_id, $item, $order ) {
			$is_pre_order = ! empty( $item['ywpo_item_preorder'] ) ? $item['ywpo_item_preorder'] : '';
			$timestamp    = ! empty( $item['ywpo_item_for_sale_date'] ) ? $item['ywpo_item_for_sale_date'] : '';
			if ( 'yes' === $is_pre_order ) {
				do_action( 'ywpo_order_item_before_pre_order_label_output', $item, $order );

				$label = apply_filters(
					'yith_ywpo_pre_order_product_label_single_order_page',
					__( 'Pre-order product', 'yith-pre-order-for-woocommerce' ),
					$item,
					$item_id,
					$order
				);

				$output = apply_filters(
					'ywpo_order_item_pre_order_label_output',
					'<div>' . $label . '</div>',
					$item,
					$item_id,
					$order,
					$label
				);
				echo wp_kses_post( $output );

				do_action( 'ywpo_order_item_after_pre_order_label_output', $item, $order );

				$release_date_label = apply_filters(
					'ywpo_order_item_release_date_label',
					__( 'Availability date: ', 'yith-pre-order-for-woocommerce' ),
					$item,
					$order
				);

				$release_date_label_output = apply_filters(
					'ywpo_order_item_release_date_label_output',
					'<div class="ywpo_release_date">' . $release_date_label . '</div>',
					$item,
					$order
				);
				echo wp_kses_post( $release_date_label_output );

				do_action( 'ywpo_order_item_before_release_date_output', $item, $order );

				if ( $timestamp ) {
					$date_output = apply_filters(
						'ywpo_order_item_date_output',
						'<span class="preorder-date">' . ywpo_print_date( $timestamp ) . '</span>',
						$timestamp,
						$item,
						$order
					);

					$time_output = apply_filters(
						'ywpo_order_item_time_output',
						'<span class="preorder-time">' . ywpo_print_time( $timestamp ) . '</span>',
						$timestamp,
						$item,
						$order
					);

					$datetime_output = apply_filters(
						'ywpo_order_item_datetime_output',
						$date_output . '<span>&nbsp;&mdash;&nbsp;</span>' . $time_output,
						$timestamp,
						$date_output,
						$time_output,
						$item,
						$order
					);

					$class = 'yes' === get_option( 'yith_wcpo_enable_automatic_date_formatting', 'yes' ) ?
						'preorder-my-account' :
						'preorder-my-account-no-auto';

					$release_date_output = apply_filters(
						'ywpo_order_item_release_date_output',
						'<div class="' . $class . '" data-time="' . $timestamp . '">' . $datetime_output . '</div>',
						$timestamp,
						$date_output,
						$time_output,
						$datetime_output,
						$item,
						$order
					);
					echo wp_kses_post( $release_date_output );
				} else {
					$no_date_label = apply_filters(
						'ywpo_order_item_no_date_label',
						__( 'N/A', 'yith-pre-order-for-woocommerce' ),
						$item,
						$order
					);

					$no_date_label_output = apply_filters(
						'ywpo_order_item_no_date_label_output',
						'<div class="preorder-my-account-no-date">' . $no_date_label . '</div>',
						$item,
						$order
					);
					echo wp_kses_post( $no_date_label_output );
				}

				do_action( 'ywpo_order_item_after_release_date_output', $item, $order );
			}
		}

		/**
		 * Display the endpoint content.
		 */
		public function endpoint_content() {
			$orders = ywpo_get_orders_by_customer( get_current_user_id() );

			do_action( 'ywpo_my_account_my_pre_orders_before_content', $orders );

			wc_get_template(
				'myaccount/ywpo-my-pre-orders.php',
				array( 'orders' => $orders ),
				'',
				YITH_WCPO_TEMPLATE_PATH
			);

			do_action( 'ywpo_my_account_my_pre_orders_after_content', $orders );
		}

		/**
		 * Set the endpoint in the menu list from My account dashboard.
		 *
		 * @param array $items List of My account menu items.
		 *
		 * @return array
		 */
		public function new_menu_items( $items ) {
			if ( count( ywpo_get_orders_by_customer( get_current_user_id() ) ) > 0 ) {
				// Remove the logout menu item.
				$logout = $items['customer-logout'];
				unset( $items['customer-logout'] );

				// Insert your custom endpoint.
				$items['my-pre-orders'] = __( 'My pre-orders', 'yith-pre-order-for-woocommerce' );

				// Insert back the logout item.
				$items['customer-logout'] = $logout;
			}
			return $items;
		}

		/**
		 * Set endpoint title.
		 *
		 * @param string $title Endpoint title.
		 * @return string
		 */
		public function endpoint_title( $title ) {
			global $wp_query;

			$is_endpoint = isset( $wp_query->query_vars['my-pre-orders'] );

			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = esc_html__( 'My pre-orders', 'yith-pre-order-for-woocommerce' );

				remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
			}

			return $title;
		}
	}
}

/**
 * Unique access to instance of YITH_Pre_Order_My_Account class
 *
 * @return YITH_Pre_Order_My_Account
 */
function YITH_Pre_Order_My_Account() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_Pre_Order_My_Account::get_instance();
}
