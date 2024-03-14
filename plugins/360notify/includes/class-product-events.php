<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_Product_Events {
	private $enable_notification = false;
	private $enable_super_admin_360Messenger = false;
	private $enable_product_admin_360Messenger = false;
	public function __construct() {
		$this->enable_notification      = WooNotify()->Options( 'enable_notif_360Messenger_main' );
		$this->enable_super_admin_360Messenger   = WooNotify()->Options( 'enable_super_admin_360Messenger' );
		$this->enable_product_admin_360Messenger = WooNotify()->Options( 'enable_product_admin_360Messenger' );
		if ( $this->enable_notification || $this->enable_super_admin_360Messenger || $this->enable_product_admin_360Messenger ) {
			add_action( 'init', [ $this, 'init' ] );
		}
	}

	public function init() {

		$action = ! empty( $_POST['action'] ) ? str_ireplace( 'woocommerce_', '', esc_attr(sanitize_text_field( $_POST['action'] ) )) : '';
		if ( in_array( $action, [ 'add_variation', 'link_all_variations' ] ) ) {
			return;
		}

		/*onSale*/
		add_action( 'woocommerce_process_product_meta', [ $this, 'woonotify_360MessengerIsOnSale' ], 9999, 1 );
		add_action( 'woocommerce_update_product_variation', [ $this, 'woonotify_360MessengerIsOnSale' ], 9999, 1 );
		add_action( 'woocommerce_360Messenger_send_onsale_event', [ $this, 'woonotify_360MessengerIsOnSale' ] );
		/*inStock*/
		add_action( 'woocommerce_product_set_stock_status', [ $this, 'woonotify_360MessengerInStock' ] );
		add_action( 'woocommerce_variation_set_stock_status', [ $this, 'woonotify_360MessengerInStock' ] );
		/*outStock*/
		add_action( 'woocommerce_product_set_stock_status', [ $this, 'woonotify_360MessengerOutStock' ] );
		add_action( 'woocommerce_variation_set_stock_status', [ $this, 'woonotify_360MessengerOutStock' ] );
		/*lowStock*/
		add_action( 'woocommerce_low_stock', [ $this, 'woonotify_360MessengerIsLowStock' ] );
		add_action( 'woocommerce_product_set_stock', [ $this, 'woonotify_360MessengerIsLowStock' ] );
		add_action( 'woocommerce_variation_set_stock', [ $this, 'woonotify_360MessengerIsLowStock' ] );
	}

	// وقتی محصول فروش ویژه شد : کاربر
	public function woonotify_360MessengerIsOnSale( int $product_id ) {

		$product_id = WooNotify()->MayBeVariable( $product_id );
		if ( is_array( $product_id ) ) {
			return array_map( [ $this, __FUNCTION__ ], $product_id );
		}

		$product           = wc_get_product( $product_id );
		$parent_product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
		/*-----------------------------------------------------------------*/

		$post_meta   = '_onsale_send';
		$schedule    = 'woocommerce_360Messenger_send_onsale_event';
		$sale_price  = $product->get_sale_price();
		$is_schedule = current_action() == $schedule;

		if ( $sale_price === get_post_meta( $parent_product_id, $post_meta, true ) ) {
			return false;
		} elseif ( ! $is_schedule ) {
			delete_post_meta( $parent_product_id, $post_meta );
		}

		if ( WooNotify()->hasNotifCond( 'enable_onsale', $parent_product_id ) ) {

			if ( ! $product->is_on_sale() ) {

				if ( ! $is_schedule ) {
					$date_from = WooNotify()->ProductSalePriceTime( $product_id, 'from' );
					if ( ! empty( $date_from ) && $date_from > strtotime( 'NOW', current_time( 'timestamp' ) ) ) {
						wp_schedule_single_event( $date_from + 3600, $schedule, [ $product_id ] );
					}
				}

				return delete_post_meta( $parent_product_id, $post_meta );
			}

			wp_clear_scheduled_hook( $schedule );

			$data = [
				'post_id' => absint(sanitize_text_field($parent_product_id)),
				'type'    => 9,
				'mobile'  => WooNotify_360Messenger_Contacts::getContactsMobiles( $parent_product_id, '_onsale' ),
				'message' => WooNotify()->ReplaceTags( 'notif_onsale_360Messenger', $product_id, $parent_product_id ),
			];

			if ( WooNotify()->Send360Messenger( $data ) === true ) {
				//return update_post_meta( $product_id, $post_meta, $sale_price );
				//} else {
				//return delete_post_meta( $product_id, $post_meta );
			}

			return update_post_meta( $parent_product_id, $post_meta, $sale_price );
		}
	}

	// وقتی محصول موجود شد : کاربر
	public function woonotify_360MessengerInStock( $product_id ) {

		$product           = wc_get_product( $product_id );
		$parent_product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
		/*-----------------------------------------------------------------*/

		$post_meta = '_in_stock_send';

		if ( ! $product->is_in_stock() ) {
			return delete_post_meta( $parent_product_id, $post_meta );
		}

		if ( WooNotify()->maybeBool( get_post_meta( $parent_product_id, $post_meta, true ) ) ) {
			return false;
		}

		if ( WooNotify()->hasNotifCond( 'enable_notif_no_stock', $parent_product_id ) ) {

			$data = [
				'post_id' => $parent_product_id,
				'type'    => 11,
				'mobile'  => WooNotify_360Messenger_Contacts::getContactsMobiles( $parent_product_id, '_in' ),
				'message' => WooNotify()->ReplaceTags( 'notif_no_stock_360Messenger', $product_id, $parent_product_id ),
			];

			if ( WooNotify()->Send360Messenger( $data ) === true ) {
				//return update_post_meta( $product_id, $post_meta, 'yes' );
				//} else {
				//return delete_post_meta( $product_id, $post_meta );
			}

			return update_post_meta( $parent_product_id, $post_meta, 'yes' );
		}
	}

	// وقتی محصول ناموجود شد : مدیران کل و مدیران محصول
	public function woonotify_360MessengerOutStock( $product_id ) {

		$product           = wc_get_product( $product_id );
		$parent_product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
		/*-----------------------------------------------------------------*/

		$post_meta = '_out_stock_send_360Messenger';

		if ( $product->is_in_stock() ) {
			return delete_post_meta( $parent_product_id, $post_meta );
		}

		if ( WooNotify()->maybeBool( get_post_meta( $parent_product_id, $post_meta, true ) ) ) {
			return false;
		}

		if ( $this->woonotify_360MessengerAdminsStocks( $product_id, $parent_product_id, 'out', 7 ) ) {
			//return update_post_meta( $product_id, $post_meta, 'yes' );
			//} else {
			//return delete_post_meta( $product_id, $post_meta );
		}

		return update_post_meta( $parent_product_id, $post_meta, 'yes' );
	}

	// محصول رو به اتمام است : مدیر و کاربر

	private function woonotify_360MessengerAdminsStocks( $product_id, $parent_product_id, $status, $type ) {

		$mobiles = [];
		if ( $this->enable_super_admin_360Messenger ) {
			if ( in_array( $status, (array) WooNotify()->Options( 'super_admin_order_status' ) ) ) {
				$mobiles = array_merge( $mobiles, explode( ',', WooNotify()->Options( 'super_admin_phone' ) ) );
			}
		}
		if ( $this->enable_product_admin_360Messenger ) {
			$mobiles = array_merge( $mobiles, array_keys( WooNotify()->ProductAdminMobiles( $parent_product_id, $status ) ) );
		}

		$mobiles = array_map( 'trim', $mobiles );
		$mobiles = array_unique( array_filter( $mobiles ) );

		if ( ! empty( $mobiles ) ) {
			$data = [
				'post_id' => absint(sanitize_text_field($parent_product_id)),
				'type'    => esc_html(sanitize_text_field($type)),
				'mobile'  => esc_html(sanitize_text_field($mobiles)),
				'message' => WooNotify()->ReplaceTags( "admin_{$status}_stock", $product_id, $parent_product_id ),
			];

			return WooNotify()->Send360Messenger( $data ) === true;
		}

		return false;
	}

	public function woonotify_360MessengerIsLowStock( $product_id ) {

		if ( 'yes' !== get_option( 'woocommerce_manage_stock' ) ) {
			return false;
		}

		$product           = wc_get_product( $product_id );
		$parent_product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
		/*-----------------------------------------------------------------*/

		if ( ! WooNotify()->IsStockManaging( $product ) ) {
			return false;
		}

		$post_meta = '_low_stock_send';

		$quantity = WooNotify()->ProductStockQty( $product );
		if ( $quantity > get_option( 'woocommerce_notify_low_stock_amount' ) || $quantity <= get_option( 'woocommerce_notify_no_stock_amount' ) ) {
			return delete_post_meta( $parent_product_id, $post_meta );
		}

		if ( WooNotify()->maybeBool( get_post_meta( $parent_product_id, $post_meta, true ) ) ) {
			return false;
		}

		//کاربر
		if ( WooNotify()->hasNotifCond( 'enable_notif_low_stock', $parent_product_id ) ) {
			$data         = [
				'post_id' => absint(sanitize_text_field($parent_product_id)),
				'type'    => 13,
				'mobile'  => WooNotify_360Messenger_Contacts::getContactsMobiles( $parent_product_id, '_low' ),
				'message' => WooNotify()->ReplaceTags( 'notif_low_stock_360Messenger', $product_id, $parent_product_id ),
			];
			$result_users = WooNotify()->Send360Messenger( $data ) === true;
		}

		//مدیر
		if ( $this->woonotify_360MessengerAdminsStocks( $product_id, $parent_product_id, 'low', 8 ) || ! empty( $result_users ) ) {
			//return update_post_meta( $product_id, $post_meta, 'yes' );
			//} else {
			//return delete_post_meta( $product_id, $post_meta );
		}

		return update_post_meta( $parent_product_id, $post_meta, 'yes' );
	}
}

new WooNotify_360Messenger_Product_Events();