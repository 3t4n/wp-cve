<?php
defined( 'ABSPATH' ) || exit;
use Automattic\WooCommerce\Utilities\OrderUtil;
include_once OLIVER_POS_ABSPATH . 'includes/models/class-pos-bridge-order.php';
use bridge_models\Pos_Bridge_Order as Order;
/**
 *
 */
class Pos_Bridge_Order
{

    private $pos_bridge_order;

    function __construct()
    {
        $this->pos_bridge_order = new Order;
    }

    public function oliver_pos_orders( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['page'] ) && isset( $parameters['per_page'] ) ) {
            $data = $this->pos_bridge_order->oliver_pos_get_paged_orders( sanitize_text_field( $parameters['page'] ), sanitize_text_field( $parameters['per_page'] ) );
        } else {
            $data = $this->pos_bridge_order->oliver_pos_get_paged_orders( 1 , 10 );
        }
        return $data;
    }
    /**
     * Add check for order id exists or not
     * @since 2.3.8.7
     */
    public function oliver_pos_order( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['id'] ) || !empty( $parameters['id'] ) ) {
            $id = sanitize_text_field( $parameters['id'] );
            if($this->oliver_pos_check_order_type($id))
            {
                $data = $this->pos_bridge_order->oliver_pos_get_order( $id, null, array() );
                return $data;
            }
	        return oliver_pos_api_response('Invalid order id', -1);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_get_remainig_orders( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['remaining'] ) && !empty( $parameters['remaining'] ) ) {
            $data = $this->pos_bridge_order->oliver_pos_get_remainig_orders( sanitize_text_field( $parameters['remaining'] ) );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_create_order( $request_data ) {
        if ( !empty( $request_data ) ) {
	        $parameters = $request_data->get_params();
            $data = $this->pos_bridge_order->oliver_pos_create_order( $parameters );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_set_order_status( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['id'] ) || !empty( $parameters['id'] ) ) {
            $data = $this->pos_bridge_order->oliver_pos_set_order_status( $request_data );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_cancel_order( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['order_id'] ) || !empty( $parameters['order_id'] ) ) {
            $data = $this->pos_bridge_order->oliver_pos_cancel_order($parameters);
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }
    /**
     * Delete order.
     * @since 2.3.9.5
     * @return string|array order status
     */
    public function oliver_pos_delete_order( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['id'] ) || !empty( $parameters['id'] ) ) {
            $order_id = sanitize_text_field( $parameters['id'] );
            if($this->oliver_pos_check_order_type($order_id))
            {
                $data = $this->pos_bridge_order->oliver_pos_delete_order($order_id);
                $this->oliver_pos_asp_dot_net_sync_order( $order_id, esc_url_raw( ASP_TRIGGER_REMOVE_ORDER ), false);
                return $data;
            }
	        return oliver_pos_api_response('Invalid order id', -1);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_save_user_in_order( $request_data ) {
        $parameters = $request_data->get_params();
        if ( ! empty( $parameters['email'] ) && ! empty( $parameters['order_id'] ) ) {
            $data = $this->pos_bridge_order->oliver_pos_save_user_in_order( sanitize_text_field( $parameters['email'] ), sanitize_text_field( $parameters['order_id'] ) );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Set new customer on existing order by temprory order id.
     * @since 2.2.1.2
     * @param array $request_data request parameters
     * @return array Returns success or error message.
     */
    public function oliver_pos_save_user_in_order_by_temp_order_id( $request_data ) {
        $parameters = $request_data->get_params();
        if ( !empty($parameters['email']) && !empty($parameters['order_id'])) {
            $email = sanitize_email($parameters['email']);
            $temp_order_id = sanitize_text_field($parameters['order_id']);
            $data = $this->pos_bridge_order->oliver_pos_save_user_in_order_by_temp_order_id($email, $temp_order_id);
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Set payments for order
     * @since 2.1.3.2
     * @param int $request_data
     * @return array Returns order payments on succees.
     */
    public function oliver_pos_set_order_payments( $request_data ) {
        $parameters = $request_data->get_params();
        if ( ! empty($parameters['order_id']) && ! empty($parameters['payments'])) {
            $order_id = sanitize_text_field($parameters['order_id']);
            $payments = $parameters['payments'];
            return $this->pos_bridge_order->oliver_pos_set_order_payments($order_id, $payments);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Get payments for order
     * @since 2.1.3.2
     * @param int $request_data
     * @return array Returns order payments on succees.
     */
    public function oliver_pos_get_order_payments( $request_data ) {
        $parameters = $request_data->get_params();
        if ( ! empty($parameters['order_id'])) {
            $order_id = sanitize_text_field($parameters['order_id']);
            return $this->pos_bridge_order->oliver_pos_get_order_payments($order_id);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Set refund payments for order
     * @since 2.1.3.2
     * @param int $request_data
     * @return array Returns order refund payments on succees.
     */
    public function oliver_pos_set_order_refund_payments( $request_data ) {
        $parameters = $request_data->get_params();
        if ( ! empty($parameters['order_id']) && ! empty($parameters['payments'])) {
            $order_id = sanitize_text_field($parameters['order_id']);
            $payments = $parameters['payments'];
            return $this->pos_bridge_order->oliver_pos_set_order_refund_payments($order_id, $payments);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Get refund payments for order
     * @since 2.1.3.2
     * @param int $request_data
     * @return array Returns order refund payments on succees.
     */
    public function oliver_pos_get_order_refund_payments( $request_data ) {
        $parameters = $request_data->get_params();
        if ( ! empty($parameters['order_id'])) {
            $order_id = sanitize_text_field($parameters['order_id']);
            return $this->pos_bridge_order->oliver_pos_get_order_refund_payments($order_id);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Get last temp order id.
     * @since 2.2.5.6
     * @return string|array Return last temp order id.
     */
    public function oliver_pos_get_last_temp_order_id() {
        return $this->pos_bridge_order->oliver_pos_get_last_temp_order_id();
    }
    //Since version 2.3.8.1
    //Modify this function and add a checkpoint for refund order
    private function oliver_pos_asp_dot_net_sync_order( $id, $url, $email, $from_refund=false ) {
        $udid = ASP_DOT_NET_UDID;
        $remote_url = "{$url}?udid={$udid}&wpid={$id}";
        if ( $email ) {
            $user = wp_get_current_user()->user_email;
            $remote_url .= "&email={$user}";
        }
        if( true == $from_refund )
        {
            wp_remote_get( esc_url_raw($remote_url), array(
                'headers' => array(
	                'Authorization' => AUTHORIZATION,
                ),
            ));
        }
        else{
            wp_remote_get( esc_url_raw($remote_url), array(
                'timeout'   => 0.01,
                'blocking'  => false,
                'sslverify' => false,
                'headers' => array(
	                'Authorization' => AUTHORIZATION,
                ),
            ));
        }
    }
    //Since 2.3.8.8
    private function oliver_pos_post_dot_net_sync_order( $id, $method ) {
        $order_data = $this->pos_bridge_order->oliver_pos_get_order( $id, null, array() );
        wp_remote_post( esc_url_raw($method), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
				'Authorization' => AUTHORIZATION,
            ),
            'body' => json_encode($order_data),
        ));
    }
    /*
     * Since 2.3.9.8 comment
    private function post_dot_net_sync_order_refund( $id, $method ) {
        $refund_data = $this->pos_bridge_order->oliver_pos_get_order_refund_payments($id);
        wp_remote_post( esc_url_raw($method), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => 'Basic ' . base64_encode( get_option( 'oliver_pos_subscription_client_id' ).":".get_option( 'oliver_pos_subscription_token' ))
            ),
            'body' => json_encode($refund_data),
        ) );
    }
     */

    /*
     * This function used if we use a save_post hooks
    */
    public function oliver_pos_untrash_order_listener( $id ) {
        if($this->oliver_pos_check_order_type($id)) {
            oliver_log('start restore order trigger');
            $this->oliver_pos_asp_dot_net_sync_order( $id, esc_url_raw( ASP_TRIGGER_ROLLBACK_ORDER ), false);
            oliver_log('End restore order trigger');
        }
        if( 'product' == get_post_type($id) ) {
            oliver_log('Start restore product trigger');
            $this->oliver_pos_asp_dot_net_sync_order( $id, esc_url_raw( ASP_TRIGGER_ROLLBACK_PRODUCT ), false);
            oliver_log('End restore product trigger');
        }
    }

    public function oliver_pos_delete_order_listener( $id ) {
        if($this->oliver_pos_check_order_type($id)) {
            oliver_log('Start delete order trigger');
            $this->oliver_pos_asp_dot_net_sync_order( $id, esc_url_raw( ASP_TRIGGER_REMOVE_ORDER ), true);
        }
    }
	//Since 2.4.1.0 Add
	public function oliver_pos_delete_permanent_order_listener( $id ) {
		if($this->oliver_pos_check_order_type($id)) {
			oliver_log('Start permanent delete order trigger');
			$this->oliver_pos_asp_dot_net_sync_order( $id, esc_url_raw( ASP_TRIGGER_PERMANENT_REMOVE_ORDER ), true);
		}
	}

    public function oliver_pos_new_order_listener( $id ) {
        oliver_log('New order trigger');
        $this->oliver_pos_schedule_event( $id);
    }

    public function oliver_pos_update_order_listener( $id ) {
        oliver_log('Update order trigger' . $id );
        $this->oliver_pos_schedule_event( $id);
    }
	//Since version 2.4.1.0 Add
	public function oliver_pos_woo_order_status_change( $id, $old_status, $new_status ) {
		oliver_log('change order status');
		$this->oliver_pos_post_dot_net_sync_order( $id, esc_url_raw( ASP_TRIGGER_ORDER ), false);
	}
    //Since version 2.3.8.1 Add
    public function oliver_pos_update_order_listener_delay_call( $id ) {
        oliver_log('update order trigger delay');
        delete_option("op_schedule_event_$id");
        $this->oliver_pos_post_dot_net_sync_order( $id, esc_url_raw( ASP_TRIGGER_ORDER ), false);
    }

    public function oliver_pos_refund_order_listener( $order_id, $refund_id ) {
        oliver_log('Start refund order trigger');
        //$this->asp_dot_net_sync_order( $order_id, esc_url_raw( ASP_TRIGGER_REFUND_ORDER ), false, true);
        //$this->post_dot_net_sync_order_refund( $order_id, esc_url_raw( ASP_TRIGGER_REFUND_ORDER ), false, true);
        wp_schedule_single_event(  time() + 5, 'woocommerce_order_refunded_delay', array($order_id, $refund_id));
        oliver_log('End refund order trigger');
    }
    //Since version 2.3.8.1 Add
    public function oliver_pos_refund_order_listener_delay_call( $order_id, $refund_id ) {
        oliver_log('Start refund order trigger delay');
        $this->oliver_pos_asp_dot_net_sync_order( $order_id, esc_url_raw( ASP_TRIGGER_REFUND_ORDER ), false, true);
        //$this->post_dot_net_sync_order_refund( $order_id, esc_url_raw( ASP_TRIGGER_REFUND_ORDER ), false, true);
        oliver_log('End refund order trigger delay');
    }

    /**
     * Fire while line item stock reduce after refund
     *
     * @since 2.2.0.1
     * @param int $product_id
     * @param int $old_stock
     * @param int $new_stock
     * @param array $order
     * @param array $product
     * @return void Return void.
     */
    public function oliver_pos_restock_refunded_item( $product_id, $old_stock, $new_stock, $order, $product ) {
        oliver_log("Start refunded order line_item trigger {$product_id}");
        $udid = ASP_DOT_NET_UDID;
        $url = ASP_TRIGGER_REFUND_ORDER_ITEM;
        $remote_url = "{$url}?udid={$udid}&wpid={$product_id}&stock={$new_stock}";

        // $this->asp_dot_net_sync_order( $order_id, esc_url_raw( ASP_TRIGGER_REFUND_ORDER_ITEM ), false);
        wp_remote_get(esc_url_raw($remote_url), array(
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ));
        oliver_log("End refunded order line_item trigger {$product_id}");
    }

    public function oliver_pos_refund_order( $request_data ) {
        $parameters = $request_data->get_params();

        if ( !empty( $request_data ) ) {
            $data = $this->pos_bridge_order->oliver_pos_refund_order( $parameters );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public static function oliver_pos_order_count() {
        return Order::oliver_pos_order_count();
    }

    /**
     * Get count the orders eiher which creates by Oliver POS or shop
     *
     * @since 2.3.6.1
     * @return int count of orders
     */
    public static function oliver_pos_get_orders_count() {
        return Order::oliver_pos_get_orders_count();
    }

    /**
     * Unlock or Remove email triggers admin
     *
     * @since 2.3.3.2
     * update 2.3.8.7
     * @return admin recipients email
     */
    public function oliver_pos_unhook_order_emails_admin( $recipient, $order ) {
    	if(empty($order)){
    		return $recipient;
	    }
        $order_id = $order->get_id();
        oliver_log("unhook_oliver_order_emails_admin = " . $order_id);

        if (metadata_exists( 'post', $order_id, '_oliver_pos_receipt_id' )) {
            if (get_option('send_order_email_to_admin') == 1) {
                oliver_log("email sent to admin");
                return $recipient;
            } else {
                $recipient = '';
                oliver_log("email not send to admin");
                return $recipient;
            }
        } else {
            return $recipient; // in case of wc order
        }
    }
    /**
     * Unlock or Remove email triggers customer
     *
     * @since 2.3.8.7
     * @return customer recipients email
     */
    public function oliver_pos_unhook_order_emails_customer( $recipient, $order ) {
        $order_id = $order->get_id();
        oliver_log('unhook_oliver_order_emails_customer = ' . $order_id);

        if (metadata_exists( 'post', $order_id, '_oliver_pos_receipt_id' )) {
            if (get_option('send_order_email_to_customer') == 1) {
                oliver_log("email sent to customer");
                return $recipient;
            } else {
                $recipient = '';
                oliver_log("email not send to customer");
                return $recipient;
            }
        } else {
            return $recipient; // in case of wc order
        }
    }
    /**
     * By pass all check when email from register order
     *
     * @since 2.3.8.7
     * @return array Return customer recipient email
     */
    public function oliver_pos_unhook_order_emails_customer_from_register( $recipient, $order ) {
        $order_id = $order->get_id();
        oliver_log('unhook_oliver_order_emails_customer = ' . $order->get_billing_email());

        if (metadata_exists( 'post', $order_id, '_oliver_pos_receipt_id' )) {
            if (get_option('send_order_email_to_customer') == 1) {
                oliver_log("email sent to customer");
                return $recipient;
            } else {
                $recipient = $order->get_billing_email();
                oliver_log("email set again send to customer");
                return $recipient;
            }
        } else {
            return $recipient; // in case of wc order
        }
    }
    /**
     * Get Order details through Oliver Pos receipt id.
     *
     * @since 2.3.8.3
     * @Add string return order details
     */
    public function oliver_pos_get_order_details_by_oliver_receipt_id( $request_data ) {
        if ( !empty( $request_data ) ) {
	        $parameters = $request_data->get_params();
            $data = $this->pos_bridge_order->oliver_pos_get_order_details_by_oliver_receipt_id( $parameters );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }
    /**
     * Get all order status.
     *
     * @since 2.3.8.5
     * @Add string return order status
     */
    public function oliver_pos_get_orders_status() {
        $order_status = get_option('_transient_orders-all-statuses');
        $all_status = array();
        foreach ( $order_status as $status ) {
            $all_status[] = $status;
        }
        return $all_status;
    }
    public function oliver_pos_schedule_event($id) {
	    //Add check for stop multiple update order trigger
	    $oldTime = get_option("op_schedule_event_$id");
	    $current_time = $differenceTime = strtotime(date('Y-m-d h:i:s'));
	    if(!empty($oldTime)){
		    $differenceTime = $current_time - $oldTime;
	    }
	    oliver_log('order update difference time=' . $differenceTime);
	    if($differenceTime>=6){
		    oliver_log('try post');
		    update_option("op_schedule_event_$id", $current_time);
		    wp_schedule_single_event(  time() + 5, 'woocommerce_update_order_delay', array($id,));
	    }
    }
    /**
    * Get all order date details
    * @since 2.4.0.9
    */
    public function oliver_pos_orders_with_time( $request_data ) {
        $parameters = $request_data->get_params();
        $all_orders = array();
        if ( isset( $parameters['page'] ) && isset( $parameters['per_page'] ) ) {
            $orders = get_posts( array(
                'posts_per_page'   => $parameters['per_page'],
                'orderby'          => 'post_date',
                'order'            => 'ASC',
                'post_status'      => OP_ORDER_STATUS,
                'post_type'        => OP_POST_TYPE,
                'paged'            => $parameters['page'],
            ) );

            foreach ($orders as $order) {
                $all_orders[] = array(
                    'id'             => $order->ID,
                    'post_date'     => $order->post_date,
                    'post_date_gmt'     => $order->post_date_gmt,
                    'completed_date'   => get_post_meta($order->ID, '_completed_date', true),
                    'paid_date'   => get_post_meta($order->ID, '_paid_date', true),
                    'date_completed'   => (int)(get_post_meta($order->ID, '_date_completed', true)?:"0"),
                    'date_paid'   => (int)(get_post_meta($order->ID, '_date_paid', true)?:"0"),
                );
            }
            return $all_orders;
        }
        else{
            return oliver_pos_api_response('Invalid Request', -1);
        }
    }
    /**
     * Count all orders.
     * @since 2.4.0.5
     * @return string|array order status
     */
    public function oliver_pos_order_counts() {
        global $wpdb;
        return (int)($wpdb->get_var( "SELECT count(ID) as order_count FROM {$wpdb->prefix}posts WHERE post_type in ('shop_order', 'shop_order_placehold') "));
    }
	/**
	*  Action : - Stop reduce main quantity if warehouse found.
	*/
	public function oliver_pos_stock_reduced_based_on_warehouse( $reduce_stock, $order ) {
		$warehouse_id = get_post_meta( $order->get_id(), 'warehouse_id', true );
		global $wpdb;
		$query_ware = "SELECT isdefault FROM {$wpdb->prefix}pos_warehouse WHERE oliver_warehouseid = %d";
		$data_warehouse = $wpdb->get_results($wpdb->prepare( $query_ware, $warehouse_id) );
        if(empty($data_warehouse)){
			oliver_log('reduce stock from default');
			return $reduce_stock;
		}
		$data = $data_warehouse[0]->isdefault;
		if( $data == 1 )
		{
			oliver_log('reduce stock from default');
			return $reduce_stock;
		}
		else{
			oliver_log('reduce stock from warehouse');
			return false;
		}
	}
    public function oliver_pos_check_order_type($id){
		return ('shop_order' === OrderUtil::get_order_type( $id )) ? true : false;
	}
}