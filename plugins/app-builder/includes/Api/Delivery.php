<?php

/**
 * Class Delivery
 *
 * @link       https://appcheap.io
 * @since      2.5.1
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 *
 */

namespace AppBuilder\Api;

use WP_Error;
use WP_HTTP_Response;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

class Delivery extends Base {

	public string $cache_group = '';

	public function __construct() {
		$this->cache_group = 'wcfm-notification';
		$this->namespace   = APP_BUILDER_REST_BASE . '/v1';
	}

	/**
	 * Registers a REST API route
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {

		register_rest_route( $this->namespace, 'delivery-boy-delivery-stat', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'delivery_boy_delivery_stat' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( $this->namespace, 'messages-mark-read', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'messages_mark_read' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( $this->namespace, 'messages-delete', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'messages_delete' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( $this->namespace, 'mark-order-delivered', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'mark_order_delivered' ),
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 *
	 * Get delivery boy delivery stat
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function delivery_boy_delivery_stat( $request ) {
		$delivery_boy_id = (int) $request->get_param( 'delivery_boy_id' );

		if ( $delivery_boy_id != get_current_user_id() ) {
			return new WP_Error(
				'delivery_boy_delivery_stat',
				__( 'You do not have permission.', "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$data = array(
			"delivered" => function_exists( 'wcfm_get_delivery_boy_delivery_stat' ) ? wcfm_get_delivery_boy_delivery_stat( $delivery_boy_id, 'delivered' ) : 0,
			"pending"   => function_exists( 'wcfm_get_delivery_boy_delivery_stat' ) ? wcfm_get_delivery_boy_delivery_stat( $delivery_boy_id, 'pending' ) : 0,
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Handle Message mark as Read
	 *
	 * @since 2.5.1
	 */
	function messages_mark_read( $request ) {
		global $wpdb;

		$messageid  = absint( $request->get_param( 'message_id' ) );
		$message_to = get_current_user_id();
		$todate     = date( 'Y-m-d H:i:s' );

		if ( ! $message_to ) {
			return new WP_Error(
				'delivery_boy_delivery_stat',
				__( 'You do not have permission.', "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$wcfm_read_message = "INSERT into {$wpdb->prefix}wcfm_messages_modifier 
																(`message`, `is_read`, `read_by`, `read_on`)
																VALUES
																({$messageid}, 1, {$message_to}, '{$todate}')";
		$result            = $wpdb->query( $wcfm_read_message );

		if ( wcfm_is_vendor() || ( function_exists( 'wcfm_is_delivery_boy' ) && wcfm_is_delivery_boy() ) || ( function_exists( 'wcfm_is_affiliate' ) && wcfm_is_affiliate() ) ) {
			$cache_key = $this->cache_group . '-message-' . $message_to;
		} else {
			$cache_key = $this->cache_group . '-message-0';
		}
		delete_transient( $cache_key );

		return $result;
	}

	/**
	 * Handle delete message
	 *
	 * @since 2.5.1
	 */
	function messages_delete( $request ) {
		global $wpdb;

		$messageid = absint( $request->get_param( 'message_id' ) );

		if ( !current_user_can( 'manage_woocommerce' ) && !current_user_can( 'wcfm_vendor' ) && !current_user_can( 'seller' ) && !current_user_can( 'vendor' ) && !current_user_can( 'shop_staff' ) && !current_user_can( 'wcfm_delivery_boy' ) && !current_user_can( 'wcfm_affiliate' ) ) {
			return new WP_Error(
				'delivery_boy_delivery_stat',
				__( 'You do not have permission.', "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$result    = $wpdb->query( "DELETE FROM {$wpdb->prefix}wcfm_messages WHERE `ID` = {$messageid}" );
		$result2   = $wpdb->query( "DELETE FROM {$wpdb->prefix}wcfm_messages_modifier WHERE `message` = {$messageid}" );

		if ( wcfm_is_vendor() || ( function_exists( 'wcfm_is_delivery_boy' ) && wcfm_is_delivery_boy() ) || ( function_exists( 'wcfm_is_affiliate' ) && wcfm_is_affiliate() ) ) {
			$message_to = apply_filters( 'wcfm_message_author', get_current_user_id() );
			$cache_key  = $this->cache_group . '-message-' . $message_to;
		} else {
			$cache_key = $this->cache_group . '-message-0';
		}
		delete_transient( $cache_key );

		return $result;
	}

	/**
	 * Handle Message mark order delivered
	 *
	 * @since 2.5.1
	 */
	public function mark_order_delivered( $request ) {
		global $WCFM, $WCFMd, $wpdb;

		$delivery_ids = (int) $request->get_param( 'delivery_id' );

		$delivery_ids = explode( ",", $delivery_ids );

		$delivered_not_notified = false;

		if ( $delivery_ids ) {
			foreach ( $delivery_ids as $delivery_id ) {
				$sql              = "SELECT * FROM `{$wpdb->prefix}wcfm_delivery_orders`";
				$sql              .= " WHERE 1=1";
				$sql              .= " AND ID = {$delivery_id}";
				$delivery_details = $wpdb->get_results( $sql );

				if ( ! empty( $delivery_details ) ) {
					foreach ( $delivery_details as $delivery_detail ) {

						// Update Delivery Order Status Update
						$wpdb->update( "{$wpdb->prefix}wcfm_delivery_orders", array(
							'delivery_status' => 'delivered',
							'delivery_date'   => date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )
						), array( 'ID' => $delivery_id ), array( '%s', '%s' ), array( '%d' ) );

						$order                  = wc_get_order( $delivery_detail->order_id );
						$wcfm_delivery_boy_user = get_userdata( $delivery_detail->delivery_boy );

						if ( apply_filters( 'wcfm_is_show_marketplace_itemwise_orders', true ) ) {
							// Admin Notification
							$wcfm_messages = sprintf( __( 'Order <b>%s</b> item <b>%s</b> delivered by <b>%s</b>.', 'wc-frontend-manager-delivery' ), '#<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_wcfm_view_order_url( $delivery_detail->order_id ) . '">' . $order->get_order_number() . '</a>', get_the_title( $delivery_detail->product_id ), '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_wcfm_delivery_boys_stats_url( $delivery_detail->delivery_boy ) . '">' . $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name . '</a>' );
							$WCFM->wcfm_notification->wcfm_send_direct_message( - 2, 0, 0, 0, $wcfm_messages, 'delivery_complete' );

							// Vendor Notification
							if ( $delivery_detail->vendor_id ) {
								$WCFM->wcfm_notification->wcfm_send_direct_message( - 1, $delivery_detail->vendor_id, 1, 0, $wcfm_messages, 'delivery_complete' );
							}

							// Order Note
							$wcfm_messages = sprintf( __( 'Order <b>%s</b> item <b>%s</b> delivered by <b>%s</b>.', 'wc-frontend-manager-delivery' ), '#<span class="wcfm_dashboard_item_title">' . $order->get_order_number() . '</span>', get_the_title( $delivery_detail->product_id ), $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name );
							$comment_id    = $order->add_order_note( $wcfm_messages, apply_filters( 'wcfm_is_allow_delivery_note_to_customer', '1' ) );
						} elseif ( ! $delivered_not_notified ) {
							// Admin Notification
							$wcfm_messages = sprintf( __( 'Order <b>%s</b> delivered by <b>%s</b>.', 'wc-frontend-manager-delivery' ), '#<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_wcfm_view_order_url( $delivery_detail->order_id ) . '">' . $order->get_order_number() . '</a>', '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_wcfm_delivery_boys_stats_url( $delivery_detail->delivery_boy ) . '">' . $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name . '</a>' );
							$WCFM->wcfm_notification->wcfm_send_direct_message( - 2, 0, 0, 0, $wcfm_messages, 'delivery_complete' );

							// Vendor Notification
							if ( $delivery_detail->vendor_id ) {
								$WCFM->wcfm_notification->wcfm_send_direct_message( - 1, $delivery_detail->vendor_id, 1, 0, $wcfm_messages, 'delivery_complete' );
							}

							// Order Note
							$wcfm_messages = sprintf( __( 'Order <b>%s</b> delivered by <b>%s</b>.', 'wc-frontend-manager-delivery' ), '#<span class="wcfm_dashboard_item_title">' . $order->get_order_number() . '</span>', $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name );
							$comment_id    = $order->add_order_note( $wcfm_messages, apply_filters( 'wcfm_is_allow_delivery_note_to_customer', '1' ) );

							$delivered_not_notified = true;
						}
					}

					//if( defined('WCFM_REST_API_CALL') ) {
					//return '{"status": true, "message": "' . __( 'Delivery status updated.', 'wc-frontend-manager-delivery' ) . '"}';
					//}
				}
			}
		}

		return array(
			"status"  => true,
			"message" => __( 'Delivery status updated.', 'wc-frontend-manager-delivery' )
		);

	}
}