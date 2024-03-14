<?php
/**
 * Widget showed in WooCommerce order detail page
 */
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

require_once 'utility-functions.php';

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Lalamove_Widget_Actions' ) ) :
	class Lalamove_Widget_Actions {


		private static $instance;

		/**
		 * Singleton method
		 *
		 * @return Lalamove_Widget_Actions
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Add widget to WooCommerce order detail page
		 */
		public function add_meta_box() {
			if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
				if (strstr( $_SERVER['REQUEST_URI'],'wc-orders') !== false && strstr( $_SERVER['REQUEST_URI'],'edit') !== false) {
					$screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
					? wc_get_page_screen_id( 'shop_order' )
					: 'shop_order';
					add_meta_box( 'wc-llm-widget', __( 'Lalamove', 'llm-widget' ), array( $this, 'meta_box' ),  $screen , 'side', 'high' );
				} else {
					$array = explode( '/', esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
					if ( substr( end( $array ), 0, strlen( 'post-new.php' ) ) !== 'post-new.php') {
						add_meta_box( 'wc-llm-widget', __( 'Lalamove', 'llm-widget' ), array( $this, 'meta_box' ), 'shop_order', 'side', 'high' );
					}
				}
			}
		}

		/**
		 * Generate Widget UI
		 */
		public function meta_box($wc_post) {
			global $post;
			$wc_order_id            = isset($post) ? $post->ID : $wc_post->get_id();
			$lalamove_order_id      = lalamove_get_single_order_id( $wc_order_id );
			$send_again_with_status = lalamove_get_send_again_with_status();

			if ( is_null( $lalamove_order_id ) ) {
				$lalamove_order_status = null;
				$button_text           = 'Send with Lalamove';
				$button_background     = 'background: #F16622;';
				$delivery_status_text  = lalamove_get_order_status_string( -1 );
			} else {
				$order_detail              = lalamove_get_order_detail( $lalamove_order_id );
				$lalamove_order_display_id = $order_detail->order_display_id ?? null;
				$lalamove_order_status     = $order_detail->order_status ?? null;
				$lalamove_share_link       = $order_detail->share_link ?? '';

				$delivery_status_text = lalamove_get_order_status_string( $lalamove_order_status );

				if ( is_null( $lalamove_order_status ) ) {
					$button_text       = 'Send with Lalamove';
					$button_background = 'background: #F16622;';
				} elseif ( in_array( $lalamove_order_status, $send_again_with_status ) ) {
					$button_text       = 'Send Again with Lalamove';
					$button_background = 'background: #F16622;';
				} else {
					$button_text       = 'View Records';
					$button_background = 'background: #1228E9;';
				}
			}

			echo '<div class="delivery-status-container" style="margin-top: 10px;display: flex;align-items: center;"">';
			echo '<label style="font-size: 14px">Delivery Status:</label>';
			echo '<div id="delivery-status" style="margin-left:8px; padding: 4px 8px;background: #F7F7F7;border: 1px solid #000000;border-radius: 10px;font-size: 14px">' . esc_html( $delivery_status_text ) . '</div>';
			echo '</div>';

			if ( ! is_null( $lalamove_order_status ) && ! in_array( $lalamove_order_status, $send_again_with_status ) ) {
				if ( ! is_null( $lalamove_order_display_id ) ) {
					echo '<div style="display: flex;align-items: center;height: 30px;margin-top: 5px;" >
							<p style="font-size: 14px">Lalamove Order: ' . $lalamove_order_display_id . '</p>
					  	  </div>';
				}
				echo '<div style="display: flex;align-items: center;height: 30px" >
						<p style="font-size: 14px"> Track Your Order: </p>
						<a rel="noopener" target="_blank" style="line-height: 1.5;font-size: 14px;margin-left: 5px;" href="' . esc_url( $lalamove_share_link ) . '"> Lalamove Sharelink </a>
					  </div>';
			}
			echo '<div class="send-with-container" style="margin-top: 10px">';

			if ( is_null( $lalamove_order_status ) || in_array( $lalamove_order_status, $send_again_with_status ) ) {
				$cta_button_href = lalamove_get_current_admin_url() . '?page=Lalamove&sub-page=place-order&id=' . $wc_order_id;
			} else {
				$cta_button_href = Lalamove_App::$wc_llm_web_app_host . '/orders/' . $lalamove_order_id;
			}

			echo '<a href="' . esc_html( $cta_button_href ) . '"  target="_blank" class="button button-send-with" style="font-weight: bold;text-align: center;color: #FFFFFF;font-size: 14px;border-radius: 10px;display: block;line-height: 40px;height: 40px;' . esc_html( $button_background ) . ';" >
				' . esc_html( $button_text ) . '</a>';
			echo '</div>';
		}

		public function multi_stop_order( $action ) {
			$actions['multi_stop_order'] = __( 'Send with Lalamove (Multi-stop Order)', 'woocommerce' );
			return $actions;
		}

		public function multi_stop_order_action( $redirect_to, $action, $post_ids ) {
			if ( $action !== 'multi_stop_order' || empty( $post_ids ) ) {
				return $redirect_to;
			}
			return lalamove_get_current_admin_url() . '?page=Lalamove&sub-page=place-order&id=' . implode( ',', $post_ids );
		}
	}
endif;
