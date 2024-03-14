<?php
namespace bridge_models;
defined( 'ABSPATH' ) || exit;

use WC_Order;
use WP_Query;
use WC_Order_Item_Product;
use WC_Product_Variation;
use WC_Product;
use WC_Customer;
use WC_Order_Item_Fee;

/**
 * In this class we are perform operation ('s) on order ('s)
 */

class Pos_Bridge_Order
{

    function __construct()
    {
        # code...
    }

    /**
     * Get all remaining orders
     * @param int $remainig number how many customer need to get
     * @return array return array of remaining orders
     */
    public function oliver_pos_get_remainig_orders($remainig)
    {
        $all_orders = array();
        $orders = get_posts( array(
            'posts_per_page'   => $remainig,
            'orderby'          => 'id',
            'order'            => 'DESC',
            'post_status' => OP_ORDER_STATUS,
            'post_type' => OP_POST_TYPE,
        ) );

        foreach ($orders as $order) {
            $order_id = ( int ) $order->ID;
            array_push($all_orders, $this->oliver_pos_get_order( $order_id, null, array() ));
        }
        return $all_orders;
    }

    /**
     * Get orders (pagination)
     * @param int $page page number to get
     * @param int $limit how many records need to get
     * @return array return array of remaining orders
     */
    public function oliver_pos_get_paged_orders( $page, $limit)
    {
        $all_orders = array();
        $orders = get_posts( array(
            'posts_per_page'   => $limit,
            'orderby'          => 'post_date',
            'order'            => 'ASC',
            'post_status' => OP_ORDER_STATUS,
            'post_type' => OP_POST_TYPE,
            'paged' => $page,
        ) );

        foreach ($orders as $order) {
            $order_id = ( int ) $order->ID;
            array_push($all_orders, $this->oliver_pos_get_order( $order_id, null, array() ));
        }
        return $all_orders;
    }


    /**
     * Get the order for the given ID
     *
     * @param int $id the order ID
     * @param array $fields
     * @param array $filter
     * @return array|WP_Error
     */
    public function oliver_pos_get_order( $id, $fields, $filter ) {

        // ensure order ID is valid & user has permission to read
        //$id = $this->oliver_pos_validate_request( $id, $this->post_type, 'read' );

        if ( is_wp_error( $id ) ) {
            return $id;
        }

        // Get the decimal precession
        //$dp         = ( isset( $filter['dp'] ) ? intval( $filter['dp'] ) : 2 );
        $order      = wc_get_order( $id );
	    if ( ! $order ) return $id;
        $order_id   = $order->get_id();
	    $order_meta = get_post_meta($order_id);
	    $oliver_pos_receipt_id = esc_attr(isset($order_meta['_oliver_pos_receipt_id']) ?$order_meta['_oliver_pos_receipt_id'][0] : '');
        $order_data = array(
            'id'                        => $order_id,
            'order_number'              => $order->get_order_number(),
            'oliver_pos_receipt_id'     => $oliver_pos_receipt_id,
            'created_at'                => $order->get_date_created(), // API gives UTC times.
            'updated_at'                => $order->get_date_modified(), // API gives UTC times.
            'completed_at'              => $order->get_date_completed(), // API gives UTC times.
            'status'                    => $order->get_status(),
            'currency'                  => $order->get_currency(),
            'total'                     => $order->get_total(),
            'subtotal'                  => $order->get_subtotal(),
            'total_line_items_quantity' => $order->get_item_count(),
            'total_tax'                 => $order->get_total_tax(),
            'total_refunded'            => $order->get_total_refunded(),
            'total_refunded_tax'        => $order->get_total_tax_refunded(),
            'total_shipping'            => $order->get_shipping_total(),
            'cart_tax'                  => $order->get_cart_tax(),
            'shipping_tax'              => $order->get_shipping_tax(),
            'total_discount'            => $order->get_total_discount(),
            'shipping_methods'          => $order->get_shipping_method(),
            'payments'         			=> $this->oliver_pos_get_order_payments($order_id, $order_meta),
            'refund_payments'           => $this->oliver_pos_get_order_refund_payments($order_id, $order_meta),
            'get_refunds'           	=> $this->oliver_pos_get_order_refunds( $order->get_refunds() ),
            'payment_details' => array(
                'method_id'    => $order->get_payment_method(),
                'method_title' => $order->get_payment_method_title(),
                'paid'         => ! is_null( $order->get_date_paid() ),
            ),
            'billing_address' => array(
                'first_name' => $order->get_billing_first_name(),
                'last_name'  => $order->get_billing_last_name(),
                'company'    => $order->get_billing_company(),
                'address_1'  => $order->get_billing_address_1(),
                'address_2'  => $order->get_billing_address_2(),
                'city'       => $order->get_billing_city(),
                'state'      => $order->get_billing_state(),
                'postcode'   => $order->get_billing_postcode(),
                'country'    => $order->get_billing_country(),
                'email'      => $order->get_billing_email(),
                'phone'      => $order->get_billing_phone(),
            ),
            'shipping_address' => array(
                'first_name' => $order->get_shipping_first_name(),
                'last_name'  => $order->get_shipping_last_name(),
                'company'    => $order->get_shipping_company(),
                'address_1'  => $order->get_shipping_address_1(),
                'address_2'  => $order->get_shipping_address_2(),
                'city'       => $order->get_shipping_city(),
                'state'      => $order->get_shipping_state(),
                'postcode'   => $order->get_shipping_postcode(),
                'country'    => $order->get_shipping_country(),
            ),
            'customer_note'             => sanitize_text_field($order->get_customer_note()),
            'notes'                     => $this->oliver_pos_get_private_order_notes( $order_id ),
            'customer_ip'               => $order->get_customer_ip_address(),
            'customer_user_agent'       => $order->get_customer_user_agent(),
            'customer_id'               => $order->get_user_id(),
            'view_order_url'            => $order->get_view_order_url(),
            'line_items'                => array(),
            'shipping_lines'            => array(),
            'tax_lines'                 => array(),
            'fee_lines'                 => array(),
            'coupon_lines'              => array(),
            'meta_lines'                => $this->oliver_pos_get_custom_order_meta($order_id, $order_meta, $oliver_pos_receipt_id),
            'manager_id'                => esc_attr( isset($order_meta['manager_id']) ?$order_meta['manager_id'][0] : '' ),
            // 'manager_name'              => get_user_by('id', get_post_meta($order_id, 'manager_id', true))->display_name,
            'location_id'               => esc_attr( isset($order_meta['location_id']) ?$order_meta['location_id'][0] : '' ),
            'register_id'               => esc_attr( isset($order_meta['register_id']) ?$order_meta['register_id'][0] : '' ),
            'cash_rounding'             => esc_attr( isset($order_meta['cash_rounding']) ?$order_meta['cash_rounding'][0] : '' ),
            'refund_cash_rounding'      => esc_attr( isset($order_meta['refund_cash_rounding']) ?$order_meta['refund_cash_rounding'][0] : '' ),
            'manager_name'      		=> esc_attr( isset($order_meta['manager_name']) ?$order_meta['manager_name'][0] : '' ),
            'completed_date'            => isset($order_meta['_completed_date']) ?$order_meta['_completed_date'][0] : '',
            'paid_date'                 => isset($order_meta['_paid_date']) ?$order_meta['_paid_date'][0] : '',
            'date_completed'            => (int)(isset($order_meta['_date_completed']) ?$order_meta['_date_completed'][0] : "0"),
            'date_paid'                 => (int)(isset($order_meta['_date_paid']) ?$order_meta['_date_paid'][0] : "0"),
            'created_via'               => $order->get_created_via(),
            'order_key'                 => $order->get_order_key(),
            'cart_hash'                 => $order->get_cart_hash(),
            'download_permissions_granted'  => $order->get_download_permissions_granted(),
            'recorded_sales'             => $order->get_recorded_sales(),
            'recorded_coupon_usage_counts'  => $order->get_recorded_coupon_usage_counts(),
            'new_order_email_sent'      => $order->get_new_order_email_sent(),
            'order_stock_reduced'       => $order->get_order_stock_reduced(),
            'prices_include_tax'        => $order->get_prices_include_tax(),
            'cart_discount'             => esc_attr( isset($order_meta['_cart_discount']) ?$order_meta['_cart_discount'][0] : '' ),
            'cart_discount_tax'         => esc_attr( isset($order_meta['_cart_discount_tax']) ?$order_meta['_cart_discount_tax'][0] : '' ),
            'order_version'             => esc_attr( isset($order_meta['_order_version']) ?$order_meta['_order_version'][0] : '' ),
            'billing_address_index'     => esc_attr( isset($order_meta['_billing_address_index']) ?$order_meta['_billing_address_index'][0] : '' ),
            'shipping_address_index'    => esc_attr( isset($order_meta['_shipping_address_index']) ?$order_meta['_shipping_address_index'][0] : '' ),
            'is_vat_exempt'             => esc_attr( isset($order_meta['is_vat_exempt']) ?$order_meta['is_vat_exempt'][0] : '' ),
        );

        // add line items
        foreach ( $order->get_items() as $item_id => $item ) {
            $product    = $item->get_product();
            $hideprefix = ( isset( $filter['all_item_meta'] ) && 'true' === $filter['all_item_meta'] ) ? null : '_';
            $item_meta  = $item->get_formatted_meta_data( $hideprefix );

            foreach ( $item_meta as $key => $values ) {
                $item_meta[ $key ]->label = $values->display_key;
                unset( $item_meta[ $key ]->display_key );
                unset( $item_meta[ $key ]->display_value );
            }
            // Since 2.3.9.1 send booking details
            //$product = wc_get_product($item->get_product_id());
            if(!empty($product) && $product->get_type()=='booking')
            {
                oliver_log('product is booking type, Send booking details');
                $booking_args = array(
                    'meta_key' => '_booking_order_item_id',
                    'meta_value' => $item_id,
                    'post_type' => 'wc_booking',
                    'post_status' => 'any',
                    'posts_per_page' => -1
                );
                $booking_posts = get_posts($booking_args);
                $booking_meta = array();
                foreach($booking_posts as $booking_post)
                {
                    $booking_meta['key']='_booking_id';
                    $booking_meta['value']=$booking_post->ID;
                    $booking_meta['label']='booking id';
                    array_push($item_meta,$booking_meta);

                    $booking_all_day = get_post_meta( $booking_post->ID, '_booking_all_day', true );
                    if(!empty($booking_all_day)){
                        $booking_meta['key']='_booking_all_day';
                        $booking_meta['value']=$booking_all_day;
                        $booking_meta['label']='booking all day';
                        array_push($item_meta,$booking_meta);
                    }
                    $booking_start = get_post_meta( $booking_post->ID, '_booking_start', true );
                    if(!empty($booking_start)){
                        $booking_meta['key']='_booking_start';
                        $booking_meta['value']=strtotime($booking_start);
                        $booking_meta['label']='booking start';
                        array_push($item_meta,$booking_meta);
                    }
                    $booking_end = get_post_meta( $booking_post->ID, '_booking_end', true );
                    if(!empty($booking_end)){
                        $booking_meta['key']='_booking_end';
                        $booking_meta['value']=strtotime($booking_end);
                        $booking_meta['label']='booking end';
                        array_push($item_meta,$booking_meta);
                    }
                }
            }
	        $order_items = wc_get_order_item_meta($item_id, '', true);
            $order_data['line_items'][] = array(
                'id'           => $item_id,
                'subtotal'     => $order->get_line_subtotal( $item, false, false ),
                'subtotal_tax' => $item->get_subtotal_tax(),
                'total'        => $order->get_line_total( $item, false, false ),
                'total_refunded_amount' => $order->get_total_refunded_for_item( $item_id ,'line_item'),
                'total_tax'    => $item->get_total_tax(),
                'price'        => $order->get_item_total( $item, false, false ),
                'quantity'     => $item->get_quantity(),
                'refunded_quantity' => $order->get_qty_refunded_for_item( $item_id ,'line_item'),
                'tax_class'    => $item->get_tax_class(),
                'name'         => $item->get_name(),
                'product_id'   => $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id(),
                'sku'          => is_object( $product ) ? $product->get_sku() : null,
                'cost_per_item'=> $this->oliver_pos_get_cost_per_item($item->get_product_id(), $item->get_variation_id()),
                'meta'         => array_values( $item_meta ),
                // since 2.3.2.1
                'taxes'		   => $item->get_taxes(),
                // since 2.3.6.1
                'composite_product_key'	=>	isset($order_items['_composite_cart_key']) ?$order_items['_composite_cart_key'][0] : '', // composite item key
                'composite_parent_key'	=>	isset($order_items['_composite_parent']) ?$order_items['_composite_parent'][0] : '',	 // composite parent key
                'bundle_product_key'	=>	isset($order_items['_bundle_cart_key']) ?$order_items['_bundle_cart_key'][0] : '',	 // bundle item key
                'bundled_parent_key'	=>	isset($order_items['_bundled_by']) ?$order_items['_bundled_by'][0] : '',		 // bundle parent key
                'is_taxable'	        =>	isset($order_items['_is_taxable']) ?$order_items['_is_taxable'][0] : '',
                'item_tax_refunded'	    =>	$this->oliver_pos_get_item_tax_refunded($order, $item_id),
            );
        }

        // add shipping
        foreach ($order->get_shipping_methods() as $shipping_item_id => $shipping_item) {
            $order_data['shipping_lines'][] = array(
                'id'           => $shipping_item_id,
                'method_id'    => $shipping_item->get_method_id(),
                'method_title' => $shipping_item->get_name(),
                'total'        => $shipping_item->get_total(),
            );
        }

        // add taxes
	    foreach ( $order->get_items( 'tax' ) as $key => $tax ) {
		    $tax_line = array(
			    'id'            => $key,
			    'rate_id'       => $tax['rate_id'],
			    'code'          => $tax['name'],
			    'title'         => isset( $tax['label'] ) ? $tax['label'] : $tax['name'],
			    'total'         => (float)$tax['tax_amount'],
			    'compound'      => (bool) $tax['compound'],
			    'tax_refunded'  => $order->get_total_tax_refunded_by_rate_id( $tax['rate_id'] ),
		    );
		    $order_data['tax_lines'][] = $tax_line;
	    }

        // add fees
        foreach ( $order->get_fees() as $fee_item_id => $fee_item ) {
            $order_data['fee_lines'][] = array(
                'fee_id'    => $fee_item_id,
                'title'     => $fee_item->get_name(),
                'tax_class' => $fee_item->get_tax_class(),
                'total'     => $fee_item->get_total(),
                'taxes' 	=> $fee_item->get_taxes(),
                'total_tax' => $fee_item->get_total_tax(),
                'total_refunded'=> 	$order->get_total_refunded_for_item( $fee_item_id, 'fee' ),
                'tax_refunded'	=> 	$order->get_tax_refunded_for_item( $fee_item_id, 'fee' ),
            );
        }

        // add coupons
        foreach ( $order->get_items( 'coupon' ) as $coupon_item_id => $coupon_item ) {
            $order_data['coupon_lines'][] = array(
                'id'     => $coupon_item_id,
                'code'   => $coupon_item->get_code(),
                'amount' => $coupon_item->get_discount(),
            );
        }

        // return array( 'order' => apply_filters( 'woocommerce_api_order_response', $order_data, $order, $fields, $this->server ) );
        return $order_data;
    }

    /**
     * Get order custom meta data.
     * @since 2.1.3.2
     * @param int $order id
     * @return array Returns array of order meta data.
     */
    public function oliver_pos_get_custom_order_meta($order_id, $order_meta, $pos_receipt_id)
    {
	    $data = array();
	    if ( ! empty($order_meta)) {
		    foreach ($order_meta as $meta_key=>$meta_value) {
			    if(substr($meta_key, 0, 16) === "_order_oliverpos" || substr($meta_key, 0, 10) === "_wc_points"){
				    if(is_string($meta_value)){
					    $new_meta_value = unserialize($meta_value);
				    }else if(is_array($meta_value)){
					    foreach($meta_value as $row){
						    if($meta_key=='_order_oliverpos_product_discount_amount' || $meta_key=='_order_oliverpos_cash_change' || $meta_key=='_wc_points_logged_redemption'){
							    $new_meta_value = unserialize($row);
						    }
						    else{
							    $new_meta_value = $row;
						    }
					    }
				    }
				    array_push($data, array($meta_key =>$new_meta_value));
			    }
			    if(empty($pos_receipt_id) && $meta_key == "_prices_include_tax"){
				    array_push($data, array("_order_oliverpos_product_discount_amount" => array(["taxType" => (isset($order_meta['_prices_include_tax']) ?$order_meta['_prices_include_tax'][0] : "no")  == "yes" ? "incl" : "Tax",])));
			    }
		    }
	    }
	    return $data;
    }

    /**
     * Get order custom meta data.
     * @param int $id product id
     * @param int $v_id variation product id
     * @return float Returns cost price of line item.
     */
    private function oliver_pos_get_cost_per_item($id, $v_id)
    {
        if ($v_id > 0) {
            return esc_attr((!empty(get_post_meta( $v_id, 'var_product_cost', true ))) ? get_post_meta( $v_id, 'var_product_cost', true ) : 0 );
        } else {
            return esc_attr((!empty(get_post_meta( $id, 'product_cost', true ))) ? get_post_meta( $id, 'product_cost', true ) : 0 );
        }
    }
	/**
	 * Add Since 2.4.1.1
	 * Add line item refund tax amount
	 */
	private function oliver_pos_get_item_tax_refunded($order, $item_id){
		$total_item_refund_tax=0;
		$order_taxes = $order->get_taxes();
		foreach ( $order_taxes as $tax_item ) {
			$tax_item_id       = $tax_item->get_rate_id();
			$refunded = $order->get_tax_refunded_for_item( $item_id, $tax_item_id );
			$total_item_refund_tax = $total_item_refund_tax + $refunded;
		}
		return $total_item_refund_tax;
	}
    /**
     * Create new order.
     * @param array $param
     * @return array  Returns created order detail on success | otherwise Error.
     */
    public function oliver_pos_create_order( $params ) {
        global $wpdb;
        oliver_log( "Create order" );
        if ( ! isset( $params['order'] ) ) {
            oliver_log("Invalid Request Parameters");
	        return oliver_pos_api_response('Invalid Request Parameters', -1);
        }

        if (! is_array($params['order'])) {
            oliver_log("Invalid format expact an array");
	        return oliver_pos_api_response('Invalid format expact an array', -1);
        }
	    $data = $params['order'][0];

            if ( isset( $data['order_id'] ) && $data['order_id'] > 0) {
	            $order = wc_get_order($data['order_id']);
	            if(empty($order)){
		            return oliver_pos_api_response('Invalid order id', -1);
	            }
                oliver_log("You are in order edit mode");
                $this->oliver_pos_edit_order( $data['order_id'], $data );
	            return $this->oliver_pos_set_edit_order_status( $data );
            } else {

                /* ========================================
                 * Apply temp order check (oliver_pos_receipt_id) from version 2.1.3.3
                 * ========================================
                */
	            if (empty($data['oliver_pos_receipt_id']) || !isset($data['oliver_pos_receipt_id']) || (int) $data['oliver_pos_receipt_id'] <= 0) {
		            oliver_log("Parameter not found - oliver_pos_receipt_id");
		            return oliver_pos_api_response('Oliver pos receipt id missing', -1);
	            }
	            oliver_log("Check temp order id exist or not");
	            $get_oliver_pos_receipt_id = sanitize_text_field($data['oliver_pos_receipt_id']);

                // Check oliver pos receipt id exist or not
                $is_oliver_pos_receipt_id_exist = get_posts(array(
                    'posts_per_page'   => 1,
                    'post_status' 	   => OP_ORDER_STATUS,
                    'post_type' 	   => OP_POST_TYPE,
                    'meta_query'       => array(
                        array(
                            'key'       => '_oliver_pos_receipt_id',
                            'value'     => $get_oliver_pos_receipt_id,
                            'compare'   => '='
                        )
                    )
                ));

                if ( ! empty($is_oliver_pos_receipt_id_exist)) {
                    oliver_log("Yes, temp order id exist");
	                $oliver_pos_receipt_id_order = reset($is_oliver_pos_receipt_id_exist)->ID;
                    if ( ! empty($oliver_pos_receipt_id_order)) {
                        //If order id exist send order details
                        return $this->oliver_pos_get_order($oliver_pos_receipt_id_order, null, array());
                    } else {
                        return array();
                    }

                } else {
                    oliver_log("No, temp order id not exist we create new order");
                    // create the pending order
                    $order = wc_create_order();
					if(empty($order)){
						return oliver_pos_api_response('order not created', -1);
					}
	                $order_id = $order->get_id();
	                //Since 2.4.1.0
	                //Add back date order
	                if( isset($params['back-date-order']) && $params['back-date-order']==true ){
		                try {
			                oliver_log( 'back_date_order=' . $params['back-date-order'] );
			                $currentTime = $data['_currentTime'];
			                $offsetValue = $data['offsetValue'];
			                if ( ! empty( $currentTime ) ) {
				                $finaldate = $this->oliver_pos_convert_to_order_date_time( $currentTime, $offsetValue );
				                if ( $finaldate != null ) {
					                $assign_date = $finaldate->format( 'Y-m-d H:i:s' );
					                $order->set_date_created( $assign_date );
				                }
			                }
		                }
		                catch(Exception $e) {

		                }
	                }
                    try {
                        // set oliver pos receipt id to order
                        $oliver_pos_receipt_id = $data['oliver_pos_receipt_id'];
                        update_post_meta($order_id , '_oliver_pos_receipt_id', sanitize_text_field($oliver_pos_receipt_id));

                        oliver_log("set oliver pos receipt id");

                        //get customer id
                        $customer_id=0;
                        if (isset($data['customer_email']) && !empty($data['customer_email'])) {
                            $get_user_by_email = get_user_by('email', sanitize_email( $data['customer_email'] ));
                            $customer_id = (isset($get_user_by_email->ID)) ? $get_user_by_email->ID : $data['customer_id'];
                        }
                        oliver_log( "get customer id by email" );
                        $customer_id = is_integer($customer_id) ? $customer_id : (integer) $customer_id;

                        $order->add_order_note('POS Checkout');
                        //set order notes
                        if ( isset( $data['order_notes'] ) ) {
                            $this->oliver_pos_set_order_note( $order, $data['order_notes'] );
                        }
                        oliver_log("Add order notes");

                        //set order custom fees
                        if ( isset( $data['order_custom_fee'] ) ) {
                            $this->oliver_pos_set_order_custom_fee( $order_id, $data['order_custom_fee'] );
                        }
                        oliver_log("Add order custom fee");

                        //Since 2.3.8.5 for cost of goods
                        $wc_cog_order_total_cost = 0;
                        $cost_per_item = 0;
                        $yith_total_cost_of_good = 0;
                        //Since 2.4.0.1
                        // update inventry data
	                    $warehouse_id=0;
                        if ( isset( $data['order_meta'][0] ) && is_array( $data['order_meta'][0] ) ) {
                            $order_metas = $data['order_meta'][0];
                            $warehouse_id = $order_metas['warehouse_id'];
                            //coupon start
                            //Since 2.4.0.6
                            $wc_points = $order_metas['_wc_points_logged_redemption'];
                            if (!empty($wc_points)){
                                foreach ($wc_points as $wc_point) {
                                    $order_item_id = $this->oliver_pos_wc_add_order_item( $order_id, $wc_point['discount_code'], 'coupon' );
                                    if ($order_item_id) {
                                        wc_update_order_item_meta($order_item_id, 'discount_amount', $wc_point['amount']);
                                        wc_update_order_item_meta($order_item_id, 'discount_amount_tax', $wc_point['redeem_amount_tax']);
                                        wc_update_order_item_meta($order_item_id, 'coupon_data', $wc_point['amount']);
                                    }
                                }
                            }
	                        $oliverpos_coupons = $order_metas['_order_oliverpos_coupon'];
	                        if(!empty($oliverpos_coupons)){
		                        oliver_log('coupon code found');
	                            foreach($oliverpos_coupons as $oliverpos_coupon){
			                        oliver_log('coupon code ='.$oliverpos_coupon['coupon_code']);
			                        $order_item_id = $this->oliver_pos_wc_add_order_item( $order_id, $oliverpos_coupon['coupon_code'], 'coupon' );
			                        if( $order_item_id ) {
				                        wc_update_order_item_meta( $order_item_id, 'discount_amount', $oliverpos_coupon['amount'] );
				                        wc_update_order_item_meta( $order_item_id, 'discount_amount_tax', $oliverpos_coupon['coupon_tax'] );
				                        wc_update_order_item_meta( $order_item_id, 'coupon_data', $oliverpos_coupon['coupondetail'] );
			                        }
		                        }
	                        }//coupon end
                        } //order meta end
                        if ( isset($data['line_items']) && !empty($data['line_items']) ) {

                            // array of line products
                            $line_item_products = array();
                            foreach ($data['line_items'] as $key => $item) {

                                /* Tickera items data for ticket */
                                $is_ticket_meta = esc_attr( get_post_meta($item['product_id'], '_tc_is_ticket', true) );
                                $is_ticket = $is_ticket_meta == 'yes' ? true : false;

                                if ( $is_ticket ) {
                                    $tickera_key = ($item['variation_id'] > 0) ? $item['variation_id'] : $item['product_id'];
                                    $oliver_pos_generate_tickets_data[ $tickera_key ] = $item['ticket_info'];
                                }
                                /* Tickera items data for ticket */

                                /* set order items using add add_product() */
                                $line_item_product = wc_get_product( ($item['variation_id'] > 0) ? $item['variation_id'] : $item['product_id'] );

                                $item_id = $order->add_product( $line_item_product, $item['quantity'], array(
                                    'name'         => $line_item_product->get_name(),
                                    'tax_class'    => $line_item_product->get_tax_class(),
                                    'product_id'   => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_parent_id() : $line_item_product->get_id(),
                                    'variation_id' => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_id() : 0,
                                    'variation'    => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_attributes() : array(),
                                    'subtotal'     => sanitize_text_field($item['subtotal']),
                                    'total'        => sanitize_text_field($item['total']),
                                ) );
	                            //Since 2.4.0.5
	                            //Add is_taxable
	                            wc_update_order_item_meta( $item_id, '_is_taxable', $item['isTaxable'] );

                                //Since 2.4.0.1
                                //inventory update for warehouse in warehouse found and prevent quantity if park sale
                                if ( (! empty( $warehouse_id )) && ($data['status'] != 'wc-pending' )) {
                                    $isdefault = $this->oliver_pos_get_default_warehouse( $warehouse_id );
	                                if ( $isdefault == 0 ) {
                                        //work as warehouse
                                        if ( empty( $item['variation_id'] ) ) {
                                            $product_id = wc_get_order_item_meta( $item_id, '_product_id', true );
                                        } else {
                                            $product_id = wc_get_order_item_meta( $item_id, '_variation_id', true );
                                        }
                                        $total_quantity_oliver     = esc_attr( get_post_meta( $product_id, '_warehouse_' . $warehouse_id, true ) );
                                        $oliver_order_quantity     = $item['quantity'];
										if(!empty($total_quantity_oliver)){
											$oliver_warehouse_remanimg = (int)$total_quantity_oliver - $oliver_order_quantity;
											update_post_meta( $product_id, '_warehouse_' . $warehouse_id, $oliver_warehouse_remanimg );
											update_post_meta( $order_id, '_warehouse', sanitize_text_field( $oliver_warehouse_remanimg ) );
										}
                                        wc_update_order_item_meta($item_id, 'warehouse_' . $warehouse_id, $oliver_order_quantity );
		                                oliver_log("reduce quantity from warehouse");
                                    }
                                }

                                //Since 2.3.8.4
                                //Add meta data for price measurement
                                if ( ! empty( $item['pricing_item_meta_data'] ) ) {
	                                $this->oliver_pos_price_measurement( $item , $item_id );
                                }
                                //Since 2.3.8.5
                                //WooCommerce Cost of Goods
	                            if ( COST_OF_GOODS_FOR_WOO==true ) {
		                            if(metadata_exists('post', $item['product_id'], '_wc_cog_cost')) {
			                            $wc_cog_cost = get_post_meta($item['product_id'], '_wc_cog_cost', true);
			                            if(!empty($wc_cog_cost)){
				                            $wc_cog_order_total_cost = $wc_cog_order_total_cost+ $wc_cog_cost;
				                            wc_update_order_item_meta($item_id, '_wc_cog_item_cost', $wc_cog_cost);
				                            wc_update_order_item_meta($item_id, '_wc_cog_item_total_cost', $wc_cog_cost);
			                            }
		                            }
	                            }
                                //Since 2.3.9.8
                                //Yith Cost of Goods WooCommerce
	                            if( YITH_COST_OF_GOODS_FOR_WOO == true ) {
	                                if(metadata_exists('post', $item['product_id'], 'yith_cog_cost')) {
	                                    $product = wc_get_product($item['product_id']);
	                                    if( 'variable' == $product->get_type() ) {
	                                        $wc_cog_cost = get_post_meta( $item['variation_id'], 'yith_cog_cost', true );
	                                        wc_update_order_item_meta($item_id, '_yith_cog_item_cost', $wc_cog_cost);
	                                        $first_total_cost = $wc_cog_cost*$item['quantity'];
	                                        $yith_total_cost_of_good = $yith_total_cost_of_good+$first_total_cost;
	                                        $yith_cog_item_total_cost = ( $item['quantity']*$wc_cog_cost );
	                                        wc_update_order_item_meta( $item_id, '_yith_cog_item_total_cost', $yith_cog_item_total_cost );
	                                        wc_update_order_item_meta( $item_id, '_yith_cog_item_product_type', 'variation' );
	                                    }
	                                    if( 'simple' == $product->get_type() ) {

	                                        $wc_cog_cost = get_post_meta($item['product_id'], 'yith_cog_cost', true);
	                                        wc_update_order_item_meta($item_id, '_yith_cog_item_cost', $wc_cog_cost);
	                                        $first_total_cost = $wc_cog_cost*$item['quantity'];
	                                        $yith_total_cost_of_good = $yith_total_cost_of_good+$first_total_cost;
	                                        $yith_cog_item_total_cost = ( $item['quantity']*$wc_cog_cost );
	                                        wc_update_order_item_meta( $item_id, '_yith_cog_item_total_cost', $yith_cog_item_total_cost );
	                                        wc_update_order_item_meta( $item_id, '_yith_cog_item_product_type', 'simple' );
	                                    }
	                                    $line_item_total = ($item['subtotal']/$item['quantity']);
	                                    wc_update_order_item_meta( $item_id, '_yith_cog_item_price', $line_item_total);
	                                    $single_tax = ($item['subtotal_tax']/$item['quantity']);
	                                    wc_update_order_item_meta( $item_id, '_yith_cog_item_tax', $single_tax );
	                                    wc_update_order_item_meta( $item_id, '_yith_cog_item_name_sortable', $item['name'] );
	                                }
                                }
                                //Since 2.3.8.9
                                //update from 2.4.0.1
                                //Add addons meta data
                                oliver_log('before addons_meta_data');
	                            if(!empty($item['addons_meta_data']))
	                            {
		                            if(is_array($item['addons_meta_data'])){
			                            oliver_log('new addons aap');
			                            foreach($item['addons_meta_data'] as $addons_meta_data){
				                            $pos_addons= json_decode($addons_meta_data, true);
				                            if(empty($pos_addons['value']))
				                            {
					                            $pos_addons['value'] = $pos_addons['price'];
				                            }
				                            wc_update_order_item_meta($item_id, $pos_addons['name'] .'('. get_woocommerce_currency_symbol(). $pos_addons['price'] .')' , $pos_addons['value']);
			                            }
		                            }
		                            else{
			                            oliver_log('old addons aap');
			                            $pos_addons= json_decode($item['addons_meta_data'], true);
			                            foreach($pos_addons as $pos_addon)
			                            {
				                            if(empty($pos_addon['value']))
				                            {
					                            $pos_addon['value'] = $pos_addon['price'];
				                            }
				                            wc_update_order_item_meta($item_id, $pos_addon['name'] .'('. get_woocommerce_currency_symbol(). $pos_addon['price'] .')' , $pos_addon['value']);
			                            }
		                            }
	                            }
                                //Since 2.3.9.1
                                //Add meta data
                                if(!empty($item['meta_data']))
                                {
                                    oliver_log('meta date');
                                    $meta_datas= $item['meta_data'];
                                    foreach($meta_datas as $meta_data){
                                        foreach($meta_data as $key=>$meta){
                                            wc_update_order_item_meta($item_id, $key,  $meta);
                                        }
                                    }
                                }

                                // tax calculation for line items ((multiple tax) applied from version 2.1.2.1)
                                $item_subtotal_tax = [];
                                $item_total_tax = [];

                                foreach ($item['subtotal_taxes'] as $key => $l_sttl_tax) {
                                    $key = key( $l_sttl_tax );
                                    $item_subtotal_tax[$key] = $l_sttl_tax[ $key ];
                                }

                                foreach ($item['total_taxes'] as $key => $l_ttl_tax) {
                                    $key = key( $l_ttl_tax );
                                    $item_total_tax[$key] = $l_ttl_tax[ $key ];
                                }

                                // assign tax for items
                                $order_item_product = new WC_Order_Item_Product($item_id);
                                $order_item_product->set_subtotal_tax(array_sum( array_map(function($element){ return (float) $element; },$item_subtotal_tax) ));
                                $order_item_product->set_total_tax(array_sum( array_map(function($element){ return (float) $element; },$item_total_tax) ));
                                $order_item_product->set_taxes(array(
                                    'total' => $item_total_tax,
                                    'subtotal' => $item_subtotal_tax
                                ));
                                if ( COST_OF_GOODS_FOR_WOO==true ) {
                                    if(!empty($item['cost_per_item']))
                                    {
                                        $cog_cost = $item['cost_per_item'];
                                        $cost_per_item +=$cog_cost*$item['quantity'];
                                    }
                                }

                                $order_item_product->save();
                                /* set order items using add add_product() */
                            }
                        }
                        // since 2.3.8.5
                        if($wc_cog_order_total_cost !==0){
                            update_post_meta( $order_id, '_wc_cog_order_total_cost', sanitize_text_field($wc_cog_order_total_cost));
                        }
                        // since 2.3.9.8
                        if($yith_total_cost_of_good !==0){
                            update_post_meta( $order_id, '_yith_cog_order_total_cost', sanitize_text_field($yith_total_cost_of_good));
                        }
                        oliver_log("add order line items");

                        $order_tax = sanitize_text_field($data['order_tax']);
                        $order_total = sanitize_text_field($data['order_total']);
                        $order_discount = isset($data['order_discount']) ? sanitize_text_field($data['order_discount']) : 0;
                        $asp_order_id = isset($data['asp_order_id']) ? sanitize_text_field($data['asp_order_id']) : 0;
                        if(!empty($customer_id)) {
                            $order->set_customer_id($customer_id);
                        }
                        $order->set_total($order_tax, "tax");
                        $order->set_total($order_discount, "cart_discount");
                        $order->set_total($order_total, "total");

                        oliver_log("Set order totals");

                        // calculate totals and set them
                        // $order->calculate_totals();

                        // insert tax items (looping (multiple tax) applied from version 2.1.2.1)
                        foreach ($data['tax_ids'] as $key => $tax_id_value) {
                            $tax_id = key($tax_id_value);
                            $tax_amount = $tax_id_value[ $tax_id ];

                            // $tax_id = (isset($data['tax_id']) && $data['tax_id'] > 0) ? $data['tax_id'] : 0;
                            $tax_rate_query = $this->oliver_pos_get_tax_by_id((int) $tax_id);
                            $wpdb->insert($wpdb->prefix.'woocommerce_order_items', array(
                                'order_item_name' => $tax_rate_query->tax_rate_name,
                                'order_item_type' => 'tax',
                                'order_id' => $order_id
                            ));
                            $last_item_id = $wpdb->insert_id;
	                        wc_update_order_item_meta( $last_item_id, 'rate_id', sanitize_text_field($tax_id));
	                        wc_update_order_item_meta( $last_item_id, 'label', sanitize_text_field(!empty($tax_rate_query->tax_rate_name) ? $tax_rate_query->tax_rate_name : 'Tax'));
	                        wc_update_order_item_meta( $last_item_id, 'compound', sanitize_text_field(!empty($tax_rate_query) ? $tax_rate_query->tax_rate_compound  : ''));
                            // wc_add_order_item_meta( $last_item_id, 'tax_amount', ( ! wc_prices_include_tax() || ( ( get_option('woocommerce_tax_display_cart') === 'incl' || get_option('woocommerce_tax_display_cart') === 'excl') && wc_prices_include_tax() ) ) ? $order_tax : 0);
	                        wc_update_order_item_meta( $last_item_id, 'tax_amount', sanitize_text_field($tax_amount));
	                        wc_update_order_item_meta( $last_item_id, 'shipping_tax_amount', 0);
                        }
                        oliver_log("Set order taxes");

                        // billing/shipping addresses
                        $this->oliver_pos_set_order_addresses( $order, $data );
                        oliver_log("Set order addresses");

                        // set order meta
                        if ( isset( $data['order_meta'][0] ) && is_array( $data['order_meta'][0] ) ) {
                            $this->oliver_pos_set_order_meta($order_id, $data['order_meta'][0]);
                        }
                        oliver_log( 'Set order meta' );

                        // set order payments
                        if (isset($data['order_payments']) && is_array($data['order_payments'])) {
                            $this->oliver_pos_set_order_payments($order_id, $data['order_payments']);
                        }
                        oliver_log( 'Set order payments' );

                        $order->update_status( isset( $data['status'] ) ? sanitize_text_field($data['status']) : 'wc-pending', '', true);
                        oliver_log( 'Update order status' );

                        // run the action (for cost of goods plugin)
                        // do_action( 'woocommerce_api_create_order', $order->get_id(), false );
                        oliver_log( 'Execute cost of goods' );
                        // create ticket instance
                        if ( is_plugin_active( 'tickera/tickera.php' ) ) {
                            // run the action for book ticket
                            do_action( 'woocommerce_pos_process_payment',  $payment_details = array()  , wc_get_order($order_id) );
                            if ( !empty( $oliver_pos_generate_tickets_data ) ) {
                                $this->oliver_pos_generate_tickets( $order_id, $oliver_pos_generate_tickets_data );
                            }
                        }
                    } catch ( Exception $exception ) {
                        oliver_log( 'found exception ' . $exception->getMessage());
                    }
                    //Since 2.3.9.8
                    if ( ! empty( $cost_per_item ) ) {
                        $get_subtotal   = (float)$order->get_subtotal();
                        $order_profit   = $get_subtotal-$cost_per_item;
                        $profit_percent = ($order_profit/$cost_per_item)*100;
                        $profit_margin  = ($order_profit/$get_subtotal)*100;
                        update_post_meta( $order_id, '_alg_wc_cog_order_cost', $cost_per_item);
                        update_post_meta( $order_id, '_alg_wc_cog_order_profit', $order_profit);
                        update_post_meta( $order_id, '_alg_wc_cog_order_profit_percent', $profit_percent);
                        update_post_meta( $order_id, '_alg_wc_cog_order_profit_margin', $profit_margin);
                    }

                    oliver_log( 'Close order ' . $order_id );
                    // Warehouse Start
                    // since 2.4.0.1
	                $isdefault = $this->oliver_pos_get_default_warehouse( $warehouse_id );
	                if ( $isdefault == 0 ) {
		                $data_send = array();
		                $items     = $order->get_items();
		                foreach ( $items as $key => $item ) {
			                $order_item        = new WC_Order_Item_Product( $key );
			                $product_id        = $order_item->get_product_id();
			                $variation_id      = $order_item->get_variation_id();
			                $product = ( $variation_id > 0 ) ? wc_get_product( $variation_id ) : wc_get_product( $product_id );
			                array_push( $data_send, (object) array(
				                'WarehouseId' => $warehouse_id,
				                'id'		  => $product->get_id(),
				                'quantity'    => esc_attr( get_post_meta( $product->get_id(), '_warehouse_' . $warehouse_id, true ) ),
			                ));
		                }
		                oliver_log("post quantity for warehouse");
                        $this->oliver_pos_wp_post_warehouse_quantity( $data_send );
                    }
                    // Warehouse END

                    // for send email
                    return $this->oliver_pos_get_order( $order_id, null, array() );
                }
            }
    }
    // End Oliver pos create order
    /**
     * Set new customer on existing order.
     * @param string $email customer email address
     * @param int $order_id order id
     * @return array Returns success or error message.
     */
    public function oliver_pos_save_user_in_order( $email, $order_id ) {
        $user_id = 0;
        $order_id = is_int($order_id) ? $order_id : (int) $order_id;
        if ( email_exists( $email ) ) {
            $get_user_by_email = get_user_by('email', sanitize_email( $email ));
            if (! empty($get_user_by_email)) {
                $user_id = (integer) $get_user_by_email->ID;
            }
        } else {
            $random_password = wp_generate_password( 12, true, false );
            $wp_create_user_id = wc_create_new_customer( sanitize_email( $email ), $email, $random_password );

            if ( is_integer($wp_create_user_id)) {
                $user_id = (integer) $wp_create_user_id;
            }
        }

        if ($user_id > 0) {
            $order = new WC_Order( $order_id );
            $order->set_customer_id( $user_id );
            $order->set_billing_email( $email );
            $order->save();

            //send order email to new customer
            //Since 2.3.8.7 add new email check parameter to send email check from
            oliver_pos_send_order_email( $order_id, $email_check = true );
	        return oliver_pos_api_response('customer saved', 1);
        } else {
	        return oliver_pos_api_response('customer not saved', -1);
        }
    }

    /**
     * Set new customer on existing order by temprory order id.
     * @since 2.2.1.2
     * @param string $email customer email address
     * @param int|string $temp_order_id temp order id
     * @return array Returns success or error message.
     */
    public function oliver_pos_save_user_in_order_by_temp_order_id( $email, $temp_order_id ) {
        oliver_log( 'Start save user in order by temp id' );

        if (!empty($temp_order_id) && !empty($email)) {
            oliver_log( 'Find order id' );

            $is_oliver_pos_receipt_id_exist = get_posts(array(
                'posts_per_page'   => 1,
                'post_status' 	   => OP_ORDER_STATUS,
                'post_type' 	   => OP_POST_TYPE,
                'meta_query'       => array(
                    array(
                        'key'       => '_oliver_pos_receipt_id',
                        'value'     => $temp_order_id,
                        'compare'   => '='
                    )
                )
            ));

            if ( ! empty($is_oliver_pos_receipt_id_exist)) {
                $oliver_pos_receipt_id_order = reset($is_oliver_pos_receipt_id_exist)->ID;
                if ( ! empty($oliver_pos_receipt_id_order)) {
                    oliver_log("Order id found ".$oliver_pos_receipt_id_order);
                    return $this->oliver_pos_save_user_in_order($email, $oliver_pos_receipt_id_order);
                }

                oliver_log("Order id not found");
            }

            oliver_log("Invalid Request");
	        return oliver_pos_api_response('Order not exist', -1);
        } else {
            oliver_log("Invalid Request");
	        return oliver_pos_api_response('Invalid Request', -1);
        }
    }

	/**
	 * Since 2.4.0.2 add
	 * Set status of existing order.
	 * @param array $data customer email address
	 * @param int $order_id order id
	 * @return array Return array order details.
	 */
	public function oliver_pos_set_edit_order_status( $data ) {
		$id = (int) (isset($data['order_id']) ? $data['order_id'] : $data['id']);
		$status = sanitize_text_field(isset( $data['status'] ) ? $data['status'] : 'wc-pending');
		$order = wc_get_order($id);
		$order->set_status($status);
		$order->save();
		return $this->oliver_pos_get_order($id, null, array());
	}

    /**
     * Set status of existing order.
     * @param int $order_id order id
     * @return array Return array order details.
     */
    public function oliver_pos_set_order_status( $data ) {
	    $id = (int) (isset($data['order_id']) ? $data['order_id'] : $data['id']);
	    $warehouse_id = (int) (isset($data['warehouse_id']) ? $data['warehouse_id'] : 0);

	    $status = sanitize_text_field(isset( $data['status'] ) ? $data['status'] : 'wc-pending');
	    $order  = wc_get_order($id);
	    $order_status  = $order->get_status();
	    $items = $order->get_items();
	    $data_send = array();
	    if ( ! empty( $warehouse_id ) ) {
		    $isdefault = $this->oliver_pos_get_default_warehouse( $warehouse_id );
		    if ( $isdefault == 0 ) {
			    if(($status=='wc-cancelled' && $order_status=='completed') || ($status=='wc-pending' && $order_status=='completed')){
				    foreach ( $items as $item ) {
					    $product_id = $item->get_product_id();
					    $variation_id = $item->get_variation_id();
					    $product_id = ( $variation_id > 0 ) ?  $variation_id : $product_id ;
					    $total_quantity_oliver = esc_attr( get_post_meta( $product_id, '_warehouse_' . $warehouse_id, true ) );
					    $oliver_order_quantity = $item->get_quantity();
					    $total_warehouse_quantity = (int)$total_quantity_oliver	+ $oliver_order_quantity;
					    update_post_meta( $product_id, '_warehouse_' . $warehouse_id, $total_warehouse_quantity );
					    array_push( $data_send, (object) array(
						    'WarehouseId' => $warehouse_id,
						    'id'		  => $product_id,
						    'quantity'    => $total_warehouse_quantity,
					    ));
				    }
				    oliver_log("add quantity from warehouse");
				    $this->oliver_pos_wp_post_warehouse_quantity( $data_send );
			    }
			    if(($status=='wc-completed' && $order_status=='cancelled') || ($status=='wc-completed' && $order_status=='pending')){
				    foreach ( $items as $item ) {
					    $product_id = $item->get_product_id();
					    $variation_id = $item->get_variation_id();
					    $product_id = ( $variation_id > 0 ) ?  $variation_id : $product_id ;
					    $total_quantity_oliver = esc_attr( get_post_meta( $product_id, '_warehouse_' . $warehouse_id, true ) );
					    $oliver_order_quantity = $item->get_quantity();
					    $total_warehouse_quantity = (int)$total_quantity_oliver	- $oliver_order_quantity;
					    update_post_meta( $product_id, '_warehouse_' . $warehouse_id, $total_warehouse_quantity );
					    array_push( $data_send, (object) array(
						    'WarehouseId' => $warehouse_id,
						    'id'		  => $product_id,
						    'quantity'    => $total_warehouse_quantity,
					    ));
				    }
				    oliver_log("reduce quantity from warehouse");
				    $this->oliver_pos_wp_post_warehouse_quantity( $data_send );
			    }
			    wp_update_post( array( 'ID' => $id, 'post_status' => $status ) );
			    $note="Order status changed from $order_status to $status.";
			    $order->add_order_note( $note );
		    }
		    else{
			    $order->set_status($status);
		    }
	    }
	    else{
		    $order->set_status($status);
	    }
	    $order->save();
	    return $this->oliver_pos_get_order($id, null, array());
    }

    /**
     * Set order status cancel of existing order.
     * @param int $order_id order id
     * @return array Return array order details.
     */
    public function oliver_pos_cancel_order( $data ) {
	    $id = (int) (isset($data['order_id']) ? $data['order_id'] : $data['id']);
	    $warehouse_id = (int) (isset($data['warehouse_id']) ? $data['warehouse_id'] : 0);
	    $order = new WC_Order( $id );

	    $items = $order->get_items();
	    $data_send = array();
	    if ( ! empty( $warehouse_id ) ) {
		    $isdefault = $this->oliver_pos_get_default_warehouse( $warehouse_id );
		    if ( $isdefault == 0 ) {
			    foreach ( $items as $item ) {
				    $product_id = $item->get_product_id();
				    $variation_id = $item->get_variation_id();
				    $product_id = ( $variation_id > 0 ) ?  $variation_id : $product_id ;
				    $total_quantity_oliver = esc_attr( get_post_meta( $product_id, '_warehouse_' . $warehouse_id, true ) );
				    $oliver_order_quantity = $item->get_quantity();
				    $total_warehouse_quantity = (int)$total_quantity_oliver	+ $oliver_order_quantity;
				    update_post_meta( $product_id, '_warehouse_' . $warehouse_id, $total_warehouse_quantity );
				    array_push( $data_send, (object) array(
					    'WarehouseId' => $warehouse_id,
					    'id'		  => $product_id,
					    'quantity'    => $total_warehouse_quantity,
				    ));
			    }
			    wp_update_post( array( 'ID' => $id, 'post_status' => 'wc-cancelled' ) );
			    $order_status  = $order->get_status();
			    $note="Order status changed from $order_status to Cancelled.";
			    $order->add_order_note( $note );
			    oliver_log("add quantity from warehouse");
			    $this->oliver_pos_wp_post_warehouse_quantity( $data_send );
		    }
		    else{
			    $order->set_status( 'wc-cancelled' );
		    }
	    }
	    else{
		    $order->set_status( 'wc-cancelled' );
	    }
	    $order->save();
	    return $this->oliver_pos_get_order($id, null, array());
    }

    /**
     * Delete order.
     * @since 2.3.9.5
     * @return string|array order status
     */
    public function oliver_pos_delete_order( $id ) {
        $order = wc_get_order( $id );
        $items = $order->get_items();
        foreach ( $items as $item_id => $item ) {
            $product_id = $item->get_product_id();
            $Product = wc_get_product($product_id);
            if( $Product->get_type() == 'variable' ) {
                $variation_id = $item->get_variation_id();
                $manage_stock = get_post_meta( $variation_id, '_manage_stock', true );
                if($manage_stock !='no') {
                    $stock = get_post_meta( $variation_id, '_stock', true );
                    $productStock = $stock+$item->get_quantity();
                    update_post_meta($variation_id, '_stock', $productStock);
                    update_post_meta( $variation_id, '_stock_status', 'instock' );
                }
            } else {
                $manage_stock = get_post_meta( $product_id, '_manage_stock', true );
                if($manage_stock !='no') {
                    $stock = get_post_meta( $product_id, '_stock', true );
                    $productStock = $stock+$item->get_quantity();
                    update_post_meta($product_id, '_stock', $productStock);
                    update_post_meta( $product_id, '_stock_status', 'instock' );
                }
            }
        }
        wp_delete_post($id , true);
        return oliver_pos_api_response('Order deleted successfully', 1);
    }

    /**
     * Restock item quantity of order
     * @param object $order order instance
     * @return void Return void.
     */
    private function oliver_pos_get_item_product_quantity( $order, $warehouse_id ) {
	    $data_send = array();
        $items = $order->get_items();
        foreach ($items as $key => $item) {
            $order_item = new WC_Order_Item_Product( $key );
            $product_id = $order_item->get_product_id();
            $variation_id = $order_item->get_variation_id();
            $get_item_quantity = $order_item->get_quantity();
            $product = ( $variation_id > 0 ) ? wc_get_product( $variation_id ) : wc_get_product( $product_id );
            $set_qty = $product->get_stock_quantity() + (int) $get_item_quantity;
            $product->set_stock_quantity( $set_qty );
            $product->save();
            // Warehouse Start
            array_push( $data_send, array(
                'WarehouseId'  => $warehouse_id,
                'id'		   => $product->get_id(),
                'quantity'     => esc_attr( get_post_meta( $product->get_id(), '_warehouse_' . $warehouse_id, true ) ),
            ));
        }
        $isdefault = $this->oliver_pos_get_default_warehouse($warehouse_id);
	    if ( $isdefault == 0 ) {
		    oliver_log("add quantity from warehouse");
            $this->oliver_pos_wp_post_warehouse_quantity($data_send);
        }
        // Warehouse Start
        wp_remote_post( esc_url_raw( ASP_TRIGGER_UPDATE_PRODUCT_QUANTITY ), array(
            'timeout'   => 0.01,
            'blocking'  => false,
            'sslverify' => false,
            'body' => array(
                'udid' => ASP_DOT_NET_UDID,
                'productInfo' => $data_send
            ),
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ) );
    }

    /**
     * Get refund order id's
     * @param array $refunds order refund
     * @return array Return refund id's.
     */
    private function oliver_pos_get_order_refunds( $refunds ) {
        $data = array();
        foreach ($refunds as $refund) {
	        $data[] = $refund->get_id();
        }
        return $data;
    }

    /**
     * Generate tickets by order id
     * @param int $order_id order id
     * @param array $data order ticket data
     * @return void Return void.
     */
    private function oliver_pos_generate_tickets( $order_id, $data ) {
        $ticket_type_ids = array();
        $tickets = get_children(array(
            'post_parent' => $order_id,
            'post_type'   => 'tc_tickets_instances',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));

        if (! empty($tickets)) {
            foreach (array_keys($tickets) as $key => $ticket) {
                $ticket_type_ids[] = esc_attr(get_post_meta($ticket, 'ticket_type_id', true));
            }
        }

        if (! empty($ticket_type_ids)) {
            foreach (array_unique($ticket_type_ids) as $key => $ticket_type_id) {
                $get_posts = get_posts( array(
                    'post_parent' => $order_id,
                    'meta_query' => array(
                        array(
                            'key' => 'ticket_type_id',
                            'value' => $ticket_type_id
                        )
                    ),
                    'post_type' => 'tc_tickets_instances',
                    'posts_per_page' => -1
                ) );

                for ($i=1; $i <= count($get_posts); $i++) {
                    $array_keys = array_keys( $data[ $ticket_type_id ][ $i - 1 ] );
                    foreach ($array_keys as $key => $array_key) {
                        update_post_meta( $get_posts[$i - 1]->ID, $array_key, sanitize_text_field( $data[ $ticket_type_id ][ $i - 1 ][$array_key] ) );
                    }
                }
            }
        }
    }

    /**
     * Helper method to add/update order meta, with two restrictions:
     *
     * 1) Only non-protected meta (no leading underscore) can be set
     * 2) Meta values must be scalar (int, string, bool)
     *
     * @param int $order_id valid order ID
     * @param array $order_meta order meta in array( 'meta_key' => 'meta_value' ) format
     */
    protected function oliver_pos_set_order_meta( $order_id, $order_meta ) {

        foreach ( $order_meta as $meta_key => $meta_value ) {

            if ($meta_key == "_order_oliverpos_extension_data" || $this->oliver_pos_check_start_with_meta_key($meta_key,"_order_oliverpos_product")) {
                $this->oliver_pos_fire_extension_trigger($meta_value);
                // its take array as $meta value thats why we are not using sanitize_text_field
                update_post_meta( $order_id, $meta_key, $meta_value);
            } else {
                if ( is_string( $meta_key ) && is_scalar( $meta_value ) ) {
                    update_post_meta( $order_id, $meta_key, sanitize_text_field( $meta_value ) );
                }  else {
                    if($meta_key == "_wc_points_logged_redemption") {
                        update_post_meta( $order_id, $meta_key, $meta_value[0]);
                    } else {
                        update_post_meta( $order_id, $meta_key, $meta_value);
                    }
                }
            }
        }
    }

    /**
     * Check all meta key start with _order_oliverpos_product so this function can work dynamically.
     * @since 2.3.8.3
     * @param string meta key string and check prefix string
     * @return bool Return true or false.
     */
    public function oliver_pos_check_start_with_meta_key ($string, $startString) {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    /**
     * Fire trigger for extension.
     * @since 2.2.3.1
     * @param array $data wordpress trigger details
     * @return bool Return true.
     */
    public function oliver_pos_fire_extension_trigger( $data ) {
        oliver_log("Start fire extension trigger");

        if (isset($data['wordpress']) && !empty($data['wordpress'])) {
            $wordpress = $data['wordpress'];

            if (!empty($wordpress['action']) && !empty($wordpress['data'])) {
                $wordpress_action = $wordpress['action'];
                $wordpress_data   = $wordpress['data'];

                // Fire action
                do_action($wordpress_action, $wordpress_data);
                oliver_log("Do/Fire {$wordpress_action} action");
                return true;
            }
            oliver_log("Invalid client wordpress action and data");
        } else {
            oliver_log("Invalid client wordpress parameter");
        }
        return false;
    }


    /**
     * Fire trigger for extension.
     * @since 2.2.3.1
     * @param array $data wordpress trigger details
     * @return bool Return true.
     */
    public function fire_extension_trigger_multiple( $extension_data ) {
        oliver_log("Start fire extension trigger");

        if (isset($extension_data) && ! empty($extension_data)) {
            foreach ($extension_data as $key => $extension) {
                if (isset($extension['data']) && ! empty($extension['data'])) {
                    $data = $extension['data'];
                    if (isset($data['wordpress']) && ! empty($data['wordpress'])) {
                        $wordpress = $data['wordpress'];
                        if ((isset($wordpress['action']) && ! empty($wordpress['action'])) && (isset($wordpress['data']) && ! empty($wordpress['data']))) {
                            $wordpress_action = $wordpress['action'];
                            $wordpress_data   = $wordpress['data'];

                            // Fire action
                            do_action($wordpress_action, $wordpress_data);
                            oliver_log("Do/Fire {$wordpress_action} action");
                            return true;
                        }
                        $log = "Extension data-wordpress action or data not sent";
                    }
                    $log = "Extension data-wordpress not sent";
                }
                $log = "Extension data not sent";
            }
        }
        oliver_log($log);
        oliver_log("Stop fire extension trigger");
    }

    /**
     * Helper method to set/update the billing & shipping addresses for
     * an order
     *
     * @param \WC_Order $order
     * @param array $data
     */
    protected function oliver_pos_set_order_addresses( $order, $data ) {
        $address_fields = array(
            'first_name',
            'last_name',
            'company',
            'email',
            'phone',
            'address_1',
            'address_2',
            'city',
            'state',
            'postcode',
            'country',
        );
        $billing_address = $shipping_address = array();

        // billing address
        if ( isset( $data['billing_address'][0] ) && is_array( $data['billing_address'][0] ) ) {
            foreach ( $address_fields as $field ) {
                if ( isset( $data['billing_address'][0][ $field ] ) ) {
                    $billing_address[ $field ] = wc_clean( $data['billing_address'][0][ $field ] );
                }
            }
            unset( $address_fields['email'] );
            unset( $address_fields['phone'] );
        }

        // shipping address
        if ( isset( $data['shipping_address'][0] ) && is_array( $data['shipping_address'][0] ) ) {
            foreach ( $address_fields as $field ) {
                if ( isset( $data['shipping_address'][0][ $field ] ) ) {
                    $shipping_address[ $field ] = wc_clean( $data['shipping_address'][0][ $field ] );
                }
            }
        }
        $this->oliver_pos_update_address( $order, $billing_address, 'billing' );
        $this->oliver_pos_update_address( $order, $shipping_address, 'shipping' );
    }

    /**
     * Update address.
     *
     * @param WC_Order $order
     * @param array $posted
     * @param string $type
     */
    protected function oliver_pos_update_address( $order, $posted, $type ) {
        $order->set_address( $posted, $type );
        // foreach ( $posted as $key => $value ) {
        // 	if ( is_callable( array( $order, "set_{$type}_{$key}" ) ) ) {
        // 		$order->{"set_{$type}_{$key}"}( $value );
        // 	}
        // }
    }

    /**
     * Get orders count
     * @return int Orders count.
     */
    public static function oliver_pos_order_count() {
        $count = 0;
		global $wpdb;
		if(OP_HPOS_ENABLED=='yes'){
			$count = count($wpdb->get_results("SELECT id FROM {$wpdb->prefix}wc_orders WHERE type != 'shop_order_refund'"));
		}
		else{
			$status = OP_ORDER_STATUS;
			foreach (wc_get_order_statuses() as $key => $value) {
				if (in_array($key, $status)) {
					$count += (int) wp_count_posts( 'shop_order' )->$key;
				}
			}
		}
        return $count;
    }

    // /**
    //  * Get count the orders which creates by Oliver POS
    //  * @since 2.3.6.1
    //  * @return int count of orders
    //  */
    // public static function get_oliver_orders_count()
    // {
    // global $wpdb;
    // // // // // $count = $wpdb->get_var("SELECT COUNT(post.ID) FROM {$wpdb->prefix}posts post LEFT JOIN {$wpdb->prefix}postmeta m_post ON (post.ID = m_post.post_id) WHERE post.post_type = 'shop_order' AND post.post_status IN ('wc-pending', 'wc-completed', 'wc-cancelled', 'wc-refunded') AND (m_post.meta_key = '_oliver_pos_receipt_id' AND m_post.meta_value != '')");

    // return is_int($count) ? $count : absint($count);
    // }

    // /**
    //  * Get count the orders eiher which creates by Oliver POS or shop
    //  * @since 2.3.6.1
    //  * @return int count of orders
    //  */
    // public static function get_orders_count()
    // {
    // $total_order  = self::count();
    // $oliver_order = self::get_oliver_orders_count();

    // return array(
    // "total" 	=> $total_order,
    // "oliver" 	=> $oliver_order,
    // "shop" 		=> $total_order - $oliver_order
    // );
    // }

    /**
     * Create order refund
     * @param array $data order refund data
     * @return array Return order details.
     */
    public function oliver_pos_refund_order( $data ) {
        oliver_log( 'Start refund order trigger from model' );
        $warehouse_id  = 0;
        $isdefault     = 0;
	    $total_quantity = 0;
        $refund_amount = $data['refund_amount'];
        $refund_tax    = $data['refund_tax'];
        $order_id      = $data['order_id'];
        $items   	   = $data['RefundItems'];
        // Warehouse Start
        foreach ( $data['order_meta'] as $order_meta ) {
            $warehouse_id = $order_meta['warehouse_id'];
            oliver_log( 'warehouse_id = ' . $warehouse_id );
            foreach ( $order_meta as $meta_key => $meta_value ) {
	            $wp_version = (float)get_bloginfo( 'version' );
	            if($wp_version<5.9){
		            if (strpos($meta_key, '_order_oliverpos_refund') === 0) {
			            update_post_meta( $order_id, $meta_key, sanitize_text_field( $meta_value ) );
		            }
	            }
				else{
					if(str_starts_with($meta_key, "_order_oliverpos_refund")){
						update_post_meta( $order_id, $meta_key, sanitize_text_field( $meta_value ) );
					}
				}
            }
        }
        if ( $warehouse_id ) {
            $isdefault = $this->oliver_pos_get_default_warehouse( $warehouse_id );
        }
        // Warehouse End
        // since 2.3.2.1 ( type array )
        $refund_tax    = $data['refund_tax'];
        $order_id      = $data['order_id'];
        $items   	   = $data['RefundItems'];
        $data_send = array();
        foreach ( $items as $key => $item ) {
            $item_id  = $item['item_id'];
            $quantity = $item['Quantity'];
            $tax      = $item['tax'];
            $amount   = $item['amount'];
            // Refund warehouse 2.3.9.8
            $product_id = wc_get_order_item_meta( $item_id, '_product_id', true );
            $variation_id = wc_get_order_item_meta( $item_id, '_variation_id', true );
            $product_id = ( $variation_id > 0 ) ? $variation_id : $product_id ;
	        if ( $isdefault == 0 ) {
                $get_warehouse_quantity = esc_attr( get_post_meta( $product_id, '_warehouse_' . $warehouse_id, true ) );
				if(!empty($get_warehouse_quantity)){
					$total_quantity = (int)$get_warehouse_quantity + $quantity;
					update_post_meta( $product_id, '_warehouse_' . $warehouse_id, $total_quantity );
				}
            }
            //End of warehouse quantity;

            // since 2.3.2.1
            if (isset($item['taxes']) && is_array($item['taxes'])) {
                $refund_tax = array();
                $item_taxes = $item['taxes'];
                if ( ! empty($item_taxes) ) {
                    foreach ( $item_taxes as $tax_value ) {
                        $tax_key = key($tax_value);
                        $refund_tax[$tax_key] = $tax_value[$tax_key];
                    }
                }
                $tax = $refund_tax;
            }
            $line_items[$item_id] = array( 'qty' => $quantity, 'refund_total' => $amount, 'refund_tax' =>  $tax );
            array_push( $data_send, (object) array(
                'WarehouseId' => $warehouse_id,
                'id'		  => $product_id,
                'quantity'    => $total_quantity,
            ));
        }
        $order = new WC_Order( $order_id );
        if ( isset( $data['order_notes'] ) ) {
            $this->oliver_pos_set_order_note( $order, $data['order_notes'] );
        }
        $refund = wc_create_refund( array(
            'amount'         => $refund_amount,
            'reason'         => 'Oliver POS Refund',
            'order_id'       => $order_id,
            'line_items'     => $line_items,
            'restock_items'  => true, //used for reduce item stock automatically
        ));
        if (isset($data['order_refund_payments']) && !empty($data['order_refund_payments'])) {
            update_post_meta($order_id, 'refund_payments', sanitize_text_field( $data['order_refund_payments'] ) );
        }

        /**
         * @since 2.2.5.7
         */
        if (isset($data['refund_cash_rounding'])) {
            update_post_meta($order_id, 'refund_cash_rounding', sanitize_text_field($data['refund_cash_rounding']));
        }

        oliver_log( 'End refund order trigger from model' );
        // Warehouse
	    if ( $isdefault == 0 ) {
            $this->oliver_pos_wp_post_warehouse_quantity( $data_send );
        }
        return $this->oliver_pos_get_order( $order_id, null, array() );
    }

    /**
     * Set payments for order
     * @since 2.1.3.2
     * @param int $order_id
     * @param array $payments
     * @return void Returns void.
     */
    public function oliver_pos_set_order_payments( $order_id, $payments ) {
        if ( ! empty( $payments ) && $order_id>0 ) {
            update_post_meta( $order_id, '_oliver_order_payments', $payments );
            $payment_method ='';
            foreach($payments as $payment){
                $payment_method .=$payment['type'].',';
            }
            $payment_methods = rtrim($payment_method, ',');
            update_post_meta( $order_id, '_payment_method', $payment_methods . ' (POS)' );
            update_post_meta( $order_id, '_payment_method_title',  $payment_methods );
            // if (metadata_exists('post', $order_id, '_oliver_order_payments')) {
            // 	$e_payments = get_post_meta($order_id, '_oliver_order_payments');
            // 	if (is_array($e_payments) && is_array($payments)) {
            // 		$e_payments = reset($e_payments);
            // 		// print_r($e_payments); exit;
            // 		$payment_merge = array_merge($payments, $e_payments);
            // 		update_post_meta($order_id, '_oliver_order_payments', $payment_merge);
            // 	}
            // } else {
            // 	update_post_meta($order_id, '_oliver_order_payments', $payments);
            // }
	        $order_meta = get_post_meta($order_id);
            return $this->oliver_pos_get_order_payments($order_id, $order_meta);
        }
    }

    /**
     * get order payments
     * @since 2.1.3.2
     * @param int $order_id
     * @return array Returns array of payments.
     */
    public function oliver_pos_get_order_payments( $order_id, $order_meta ) {
	    if ( $order_id > 0 ) {
		    $payments = isset($order_meta['_oliver_order_payments']) ?$order_meta['_oliver_order_payments'][0] : '';
		    return empty($payments) ? array() : unserialize($payments);
	    }
    }

    /**
     * Set payments for refunding order
     * @since 2.1.3.2
     * @param int $order_id
     * @param array $payments
     * @return array Returns array of refund payments.
     */
    public function oliver_pos_set_order_refund_payments( $order_id, $payments ) {
        if ( ! empty( $payments ) && $order_id>0 ) {
            // update_post_meta($order_id, '_oliver_order_refund_payments', $payments);
            if ( metadata_exists( 'post', $order_id, '_oliver_order_refund_payments' ) ) {
                $e_payments = get_post_meta( $order_id, '_oliver_order_refund_payments', true );
                if ( is_array( $e_payments ) && is_array( $payments ) ) {
                    $payment_merge = array_merge( $payments, $e_payments );
                    update_post_meta( $order_id, '_oliver_order_refund_payments', $payment_merge );
                }
            } else {
                update_post_meta( $order_id, '_oliver_order_refund_payments', $payments );
            }
	        $order_meta = get_post_meta($order_id);
            return $this->oliver_pos_get_order_refund_payments( $order_id, $order_meta );
        }
    }

    /**
     * Get order refund payments
     * @since 2.1.3.2
     * @param int $order_id
     * @return array Returns array of refund payments.
     */
    public function oliver_pos_get_order_refund_payments( $order_id, $order_meta ) {
	    if ($order_id > 0) {
		    $payments = isset($order_meta['_oliver_order_refund_payments']) ?$order_meta['_oliver_order_refund_payments'][0] : '';
		    return empty($payments) ? array() : unserialize($payments);
	    }
    }

    /**
     * Get last temp order id.
     * @since 2.2.5.6
     * @return string|array Return last temp order id.
     */
    public function oliver_pos_get_last_temp_order_id() {
        // chnage since 2.3.4.1
        global $wpdb;
        $query = $wpdb->get_var( $wpdb->prepare( "SELECT MAX(CAST(`meta_value` AS unsigned)) AS `max_temp_id` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = '_oliver_pos_receipt_id'" ));
        if ( ! empty( $query ) ) {
            return $query;
        }
        return 'Temp order id not fount';
    }

    /**
     * Get tax by tax id
     * @param int $tax_id
     * @return object Return tax details.
     */
    private function oliver_pos_get_tax_by_id( $tax_id ) {
        global $wpdb;
        $query = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates WHERE tax_rate_id = %d",
            $tax_id
        ) );
        return $query;
    }

    /**
     * Set order meta data
     * @param object $order order instance
     * @param array $data meta data
     * @return void Return void.
     */
    private function oliver_pos_set_order_note( $order, $data ) {
        foreach ($data as $key => $value) {
            if ( !isset( $value['note_id'] ) || $value['note_id'] == 0 ) {
                $is_customer_note = isset($value['is_customer_note']) ? (int) $value['is_customer_note'] : 0;
                $order->add_order_note($value['note'], $is_customer_note);
            }
        }
    }

    /**
     * Set order custom fee's
     * @param object $order order instance
     * @param array $data fee's data
     * @return void Return void.
     */
    private function oliver_pos_set_order_custom_fee( $order_id, $data ) {
	    foreach ($data as $key => $fee) {
		    $_fee_amount = isset($fee['subtotal']) ? sanitize_text_field($fee['subtotal']) : 0;
		    $_fee_name = isset($fee['note']) ? sanitize_text_field($fee['note']) : '';
		    $total_tax = isset($fee['subtotal_tax']) ? sanitize_text_field($fee['subtotal_tax']) : '';
		    $total_taxes = isset($fee['total_taxes']) ? $fee['total_taxes'] : '';
		    if ( isset( $fee['fee_id'] ) && $fee['fee_id'] > 0 ) {
			    wc_update_order_item_meta( $fee['fee_id'], '_fee_amount', $_fee_amount, true );
			    wc_update_order_item_meta( $fee['fee_id'], '_line_total', $_fee_amount, true );
			    wc_update_order_item_meta( $fee['fee_id'], '_total_tax', $total_tax, true );
			    $custom_fee_order_item_id = $fee['fee_id'];
		    } else {
			    // add a fee order item
			    $custom_fee_order_item_id = $this->oliver_pos_wc_add_order_item( $order_id, $_fee_name, 'fee' );
			    wc_update_order_item_meta( $custom_fee_order_item_id, '_fee_amount', $_fee_amount, true );
			    wc_update_order_item_meta( $custom_fee_order_item_id, '_line_total', $_fee_amount, true );
			    wc_update_order_item_meta( $custom_fee_order_item_id, '_total_tax', $total_tax, true );
		    }
		    $item_total_tax    = [];
		    foreach ( $total_taxes as $key => $l_ttl_tax ) {
			    $key                  = key( $l_ttl_tax );
			    $item_total_tax[$key] = $l_ttl_tax[ $key ];
		    }
		    $line_tax_data = array(
			    'total' => $item_total_tax
		    );
		    wc_update_order_item_meta( $custom_fee_order_item_id, '_line_tax_data', $line_tax_data );
	    }
    }

    /**
     * Get order customer notes
     * @param int $order_id order id
     * @return array Return order customer notes.
     */
    private function oliver_pos_get_private_order_notes( $order_id ){
        global $wpdb;
        $order_note = array();
        $wc_table = $wpdb->prefix . 'comments';
        $wcm_table = $wpdb->prefix . 'commentmeta';
        $results = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $wc_table as wc
            LEFT JOIN $wcm_table as wcm on wc.comment_ID = wcm.comment_id
            WHERE  wc.comment_post_ID = %d
            AND  wc.comment_type LIKE  'order_note'
            AND wcm.meta_key = 'is_customer_note'
            AND wcm.meta_value = %d", 
            $order_id, 
            1
        ) );
        foreach($results as $note){
            $order_note[]  = array(
                'note_id'      => $note->comment_ID,
                'note_date'    => $note->comment_date,
                'note_author'  => $note->comment_author,
                'note_content' => $note->comment_content,
            );
        }
        return $order_note;
    }

    /**
     * Edit/Update an existing order
     * @param int $order_id order id
     * @param array $data order data
     * @return array Return order details.
     */
	private function oliver_pos_edit_order($order_id, $data) {
		if ( ! empty($order_id) ) {
			global $wpdb;
			$order_id = is_integer( $order_id ) ? $order_id : (integer) $order_id;
			$order = new WC_Order( $order_id );
			$order_status  = $order->get_status();
			// Since 2.3.8.3
			// Since 2.3.8.4 layaway
			if (isset($data['customer_email']) && !empty($data['customer_email'])) {
				$get_user_by_email = get_user_by('email', sanitize_email( $data['customer_email'] ));
				if (isset($get_user_by_email->ID) && !empty($get_user_by_email->ID)) {
					$order->set_customer_id( isset( $get_user_by_email->ID ) ? $get_user_by_email->ID : 0 );
				}
			}
			//set order notes
			if ( isset( $data['order_notes'] ) ) {
				$this->oliver_pos_set_order_note( $order, $data['order_notes'] );
			}

			//set order custom fees
			if ( isset( $data['order_custom_fee'] ) ) {
				$this->oliver_pos_set_order_custom_fee( $order_id, $data['order_custom_fee'] );
			}
			if($order_status == 'pending') {
				//Get old line item and compare with new
				$old_item_ids = $this->oliver_pos_get_order_old_line_item( $order_id, $data );

				// update inventry data
				$warehouse_id=0;
				if ( isset( $data['order_meta'][0] ) && is_array( $data['order_meta'][0] ) ) {
					$order_metas = $data['order_meta'][0];
					$warehouse_id = $order_metas['warehouse_id'];
				}
				if ( isset( $data['line_items'] ) && !empty($data['line_items'] ) ) {
					//Add New line items to order
					$wc_cog_order_total_cost = 0;
					$line_item_products = array();
					foreach ($data['line_items'] as $key => $item) {

						/* Tickera items data for ticket */
						$is_ticket_meta = esc_attr( get_post_meta($item['product_id'], '_tc_is_ticket', true) );
						$is_ticket = $is_ticket_meta == 'yes' ? true : false;

						if ( $is_ticket ) {
							$tickera_key = ($item['variation_id'] > 0) ? $item['variation_id'] : $item['product_id'];
							$oliver_pos_generate_tickets_data[ $tickera_key ] = $item['ticket_info'];
						}
						/* Tickera items data for ticket */
						/* set order items usind add	add_product() */

						if ( in_array( $item['line_item_id'], $old_item_ids ) ) {
							wc_update_order_item_meta( $item['line_item_id'], '_qty', $item['quantity'] );
							wc_update_order_item_meta( $item['line_item_id'], '_line_subtotal', sanitize_text_field( $item['subtotal'] ) );
							wc_update_order_item_meta( $item['line_item_id'], '_line_total', sanitize_text_field( $item['total'] ) );
							$item_subtotal_tax = [];
							$item_total_tax    = [];
							foreach ( $item['subtotal_taxes'] as $key => $l_sttl_tax ) {
								$key                     = key( $l_sttl_tax );
								$item_subtotal_tax[$key] = $l_sttl_tax[ $key ];
							}
							foreach ( $item['total_taxes'] as $key => $l_ttl_tax ) {
								$key                  = key( $l_ttl_tax );
								$item_total_tax[$key] = $l_ttl_tax[ $key ];
							}
							$line_tax_data = array(
								'total' => $item_total_tax,
								'subtotal' => $item_subtotal_tax
							);
							wc_update_order_item_meta( $item['line_item_id'], '_line_tax_data',      $line_tax_data );
						} else {
							//Add new item
							oliver_log('Match not found');
							$line_item_product = wc_get_product( ( $item['variation_id'] > 0 ) ? $item['variation_id'] : $item['product_id'] );
							$item_id = $order->add_product(
								$line_item_product,
								$item['quantity'],
								array(
									'name'         => $line_item_product->get_name(),
									'tax_class'    => $line_item_product->get_tax_class(),
									'product_id'   => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_parent_id() : $line_item_product->get_id(),
									'variation_id' => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_id() : 0,
									'variation'    => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_attributes() : array(),
									'subtotal'     => sanitize_text_field( $item['subtotal'] ),
									'total'        => sanitize_text_field( $item['total'] ),
								)
							);

							//Since 2.3.8.5
							//WooCommerce Cost of Goods
							if ( COST_OF_GOODS_FOR_WOO==true ) {
								if(metadata_exists('post', $item['product_id'], '_wc_cog_cost')) {
									$wc_cog_cost = get_post_meta($item['product_id'], '_wc_cog_cost', true);
									if(!empty($wc_cog_cost)){
										$wc_cog_order_total_cost = $wc_cog_order_total_cost+ $wc_cog_cost;
										wc_update_order_item_meta($item_id, '_wc_cog_item_cost', $wc_cog_cost);
										wc_update_order_item_meta($item_id, '_wc_cog_item_total_cost', $wc_cog_cost);
									}
								}
							}
							// tax calculation for line items ((multiple tax) applied from version 2.1.2.1)
							$item_subtotal_tax = [];
							$item_total_tax    = [];
							foreach ( $item['subtotal_taxes'] as $key => $l_sttl_tax ) {
								$key                     = key( $l_sttl_tax );
								$item_subtotal_tax[$key] = $l_sttl_tax[ $key ];
							}
							foreach ( $item['total_taxes'] as $key => $l_ttl_tax ) {
								$key                  = key( $l_ttl_tax );
								$item_total_tax[$key] = $l_ttl_tax[ $key ];
							}
							// assign test for items
							$order_item_product = new WC_Order_Item_Product($item_id);
							$order_item_product->set_subtotal_tax(array_sum( array_map(function($element){ return (float) $element; },$item_subtotal_tax) ));
							$order_item_product->set_total_tax(array_sum( array_map(function($element){ return (float) $element; },$item_total_tax) ));
							$order_item_product->set_taxes(array(
								'total' => $item_total_tax,
								'subtotal' => $item_subtotal_tax
							));
							$order_item_product->save();
							/* set order items usind add	add_product() */
						}
						// inventry update.
						if ( ! empty( $warehouse_id ) ) {
							$isdefault = $this->oliver_pos_get_default_warehouse( $warehouse_id );
							if ( $isdefault == 0 ) {
								if ( empty( $item['variation_id'] ) ) {
									$product_id = wc_get_order_item_meta( $item_id,	'_product_id', true );
								} else {
									$product_id = wc_get_order_item_meta( $item_id, '_variation_id', true );
								}
								$total_quantity_oliver = esc_attr( get_post_meta( $product_id, '_warehouse_' . $warehouse_id, true ) );
								$oliver_order_quantity = $item['quantity'];
								$oliver_warehouse_remanimg = (int)$total_quantity_oliver - $oliver_order_quantity;
								update_post_meta( $product_id, '_warehouse_' . $warehouse_id, $oliver_warehouse_remanimg );
								update_post_meta( $order->get_id(), '_warehouse', sanitize_text_field( $oliver_warehouse_remanimg ) );
								wc_update_order_item_meta( $item_id, 'warehouse_' . $warehouse_id, $oliver_order_quantity );
							}
						}
					}
				}
				// since 2.3.8.5
				if($wc_cog_order_total_cost !==0){
					update_post_meta( $order->get_id(), '_wc_cog_order_total_cost', sanitize_text_field($wc_cog_order_total_cost));
				}
				oliver_log("add order line items");
				$order_tax = sanitize_text_field($data['order_tax']);
				$order_total = sanitize_text_field($data['order_total']);
				$order_discount = isset($data['order_discount']) ? sanitize_text_field($data['order_discount']) : 0;
				$asp_order_id = isset($data['asp_order_id']) ? sanitize_text_field($data['asp_order_id']) : 0;
				$order->set_total($order_tax, "tax");
				$order->set_total($order_discount, "cart_discount");
				$order->set_total($order_total, "total");
				oliver_log("Set order totals");
				//Tax line item
				$this->oliver_pos_set_order_tax_item( $order_id, $data );
				oliver_log("Set order taxes");
				// billing/shipping addresses
				$this->oliver_pos_set_order_addresses( $order, $data );
				oliver_log("Set order addresses");
				// set order meta
				if ( isset( $data['order_meta'][0] ) && is_array( $data['order_meta'][0] ) ) {
					$this->oliver_pos_set_order_meta($order->get_id(), $data['order_meta'][0]);
				}
				oliver_log("Set order meta");

				// set order payments
				if (isset($data['order_payments']) && is_array($data['order_payments'])) {
					$this->oliver_pos_set_order_payments($order->get_id(), $data['order_payments']);
				}
				oliver_log("Set order payments");
				$order->update_status( isset( $data['status'] ) ? sanitize_text_field($data['status']) : 'wc-pending', '', true);
				oliver_log("Update order status");
				//return $this->oliver_pos_get_order( $order_id, null, array() );
			}
			elseif( $order_status == 'on-hold' )
			{
				// Inventry Start 2.4.0.1
				$items = $order->get_items();
				foreach ($items as $key => $item) {
					$order_item = new WC_Order_Item_Product( $key );
					$product_id = $order_item->get_product_id();
					$variation_id = $order_item->get_variation_id();
					$get_item_quantity = $order_item->get_quantity();
					$product = ( $variation_id > 0 ) ? wc_get_product( $variation_id ) : wc_get_product( $product_id );
					$set_qty = $product->get_stock_quantity() + (int) $get_item_quantity;
					$product->set_stock_quantity( $set_qty );
					$product->save();
				}
				// Inventry END
				//Get old line item and compare with new
				$old_item_ids = $this->oliver_pos_get_order_old_line_item( $order_id, $data );
				// update inventry data
				$warehouse_id=0;
				if ( isset( $data['order_meta'][0] ) && is_array( $data['order_meta'][0] ) ) {
					$order_metas = $data['order_meta'][0];
					$warehouse_id = $order_metas['warehouse_id'];
				}
				if ( isset( $data['line_items'] ) && !empty($data['line_items'] ) ) {
					//Add New line items to order
					$wc_cog_order_total_cost = 0;
					$line_item_products = array();
					foreach ($data['line_items'] as $key => $item) {

						/* Tickera items data for ticket */
						$is_ticket_meta = esc_attr( get_post_meta($item['product_id'], '_tc_is_ticket', true) );
						$is_ticket = $is_ticket_meta == 'yes' ? true : false;

						if ( $is_ticket ) {
							$tickera_key = ($item['variation_id'] > 0) ? $item['variation_id'] : $item['product_id'];
							$oliver_pos_generate_tickets_data[ $tickera_key ] = $item['ticket_info'];
						}
						/* Tickera items data for ticket */
						/* set order items usind add	add_product() */

						if ( in_array( $item['line_item_id'], $old_item_ids ) ) {
							wc_update_order_item_meta( $item['line_item_id'], '_qty', $item['quantity'] );
							wc_update_order_item_meta( $item['line_item_id'], '_line_subtotal', sanitize_text_field( $item['subtotal'] ) );
							wc_update_order_item_meta( $item['line_item_id'], '_line_total', sanitize_text_field( $item['total'] ) );
						} else {
							//Add new item
							oliver_log('Match not found');
							$line_item_product = wc_get_product( ( $item['variation_id'] > 0 ) ? $item['variation_id'] : $item['product_id'] );
							$item_id = $order->add_product(
								$line_item_product,
								$item['quantity'],
								array(
									'name'         => $line_item_product->get_name(),
									'tax_class'    => $line_item_product->get_tax_class(),
									'product_id'   => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_parent_id() : $line_item_product->get_id(),
									'variation_id' => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_id() : 0,
									'variation'    => $line_item_product->is_type( 'variation' ) ? $line_item_product->get_attributes() : array(),
									'subtotal'     => sanitize_text_field( $item['subtotal'] ),
									'total'        => sanitize_text_field( $item['total'] ),
								)
							);

							//Since 2.3.8.5
							//WooCommerce Cost of Goods
							if ( COST_OF_GOODS_FOR_WOO==true ) {
								if(metadata_exists('post', $item['product_id'], '_wc_cog_cost')) {
									$wc_cog_cost = get_post_meta($item['product_id'], '_wc_cog_cost', true);
									if(!empty($wc_cog_cost)){
										$wc_cog_order_total_cost = $wc_cog_order_total_cost+ $wc_cog_cost;
										wc_update_order_item_meta($item_id, '_wc_cog_item_cost', $wc_cog_cost);
										wc_update_order_item_meta($item_id, '_wc_cog_item_total_cost', $wc_cog_cost);
									}
								}
							}
							// tax calculation for line items ((multiple tax) applied from version 2.1.2.1)
							$item_subtotal_tax = [];
							$item_total_tax    = [];
							foreach ( $item['subtotal_taxes'] as $key => $l_sttl_tax ) {
								$key                     = key( $l_sttl_tax );
								$item_subtotal_tax[$key] = $l_sttl_tax[ $key ];
							}
							foreach ( $item['total_taxes'] as $key => $l_ttl_tax ) {
								$key                  = key( $l_ttl_tax );
								$item_total_tax[$key] = $l_ttl_tax[ $key ];
							}
							// assign test for items
							$order_item_product = new WC_Order_Item_Product($item_id);
							$order_item_product->set_subtotal_tax(array_sum( array_map(function($element){ return (float) $element; },$item_subtotal_tax) ));
							$order_item_product->set_total_tax(array_sum( array_map(function($element){ return (float) $element; },$item_total_tax) ));
							$order_item_product->set_taxes(array(
								'total' => $item_total_tax,
								'subtotal' => $item_subtotal_tax
							));
							$order_item_product->save();
							/* set order items usind add	add_product() */
						}
						// inventry update.
						if ( ! empty( $warehouse_id ) ) {
							$isdefault = $this->oliver_pos_get_default_warehouse( $warehouse_id );
							if ( $isdefault == 0 ) {
								if ( empty( $item['variation_id'] ) ) {
									$product_id = wc_get_order_item_meta( $item_id,	'_product_id', true );
								} else {
									$product_id = wc_get_order_item_meta( $item_id, '_variation_id', true );
								}
								$total_quantity_oliver = esc_attr( get_post_meta( $product_id, '_warehouse_' . $warehouse_id, true ) );
								$oliver_order_quantity = $item['quantity'];
								$oliver_warehouse_remanimg = (int)$total_quantity_oliver	- $oliver_order_quantity;
								update_post_meta( $product_id, '_warehouse_' . $warehouse_id, $oliver_warehouse_remanimg );
								update_post_meta( $order->get_id(), '_warehouse', sanitize_text_field( $oliver_warehouse_remanimg ) );
								wc_update_order_item_meta( $item_id, 'warehouse_' . $warehouse_id, $oliver_order_quantity );
							}
						}
					}
				}
				// since 2.3.8.5
				if($wc_cog_order_total_cost !==0){
					update_post_meta( $order->get_id(), '_wc_cog_order_total_cost', sanitize_text_field($wc_cog_order_total_cost));
				}
				oliver_log("add order line items");
				$order_tax = sanitize_text_field($data['order_tax']);
				$order_total = sanitize_text_field($data['order_total']);
				$order_discount = isset($data['order_discount']) ? sanitize_text_field($data['order_discount']) : 0;
				$asp_order_id = isset($data['asp_order_id']) ? sanitize_text_field($data['asp_order_id']) : 0;
				$order->set_total($order_tax, "tax");
				$order->set_total($order_discount, "cart_discount");
				$order->set_total($order_total, "total");
				oliver_log("Set order totals");

				//Tax line item
				$this->oliver_pos_set_order_tax_item( $order_id, $data );
				oliver_log("Set order taxes");
				// billing/shipping addresses
				$this->oliver_pos_set_order_addresses( $order, $data );
				oliver_log("Set order addresses");
				// set order meta
				if ( isset( $data['order_meta'][0] ) && is_array( $data['order_meta'][0] ) ) {
					$this->oliver_pos_set_order_meta($order->get_id(), $data['order_meta'][0]);
				}
				oliver_log("Set order meta");
				// set order payments
				if (isset($data['order_payments']) && is_array($data['order_payments'])) {
					$this->oliver_pos_set_order_payments($order->get_id(), $data['order_payments']);
				}
				oliver_log("Set order payments");
				$order->update_status( isset( $data['status'] ) ? sanitize_text_field($data['status']) : 'wc-pending', '', true);
				oliver_log("Update order status");

				// Inventry Start 2.4.0.1
				$items = $order->get_items();
				foreach ($items as $key => $item) {
					$order_item = new WC_Order_Item_Product( $key );
					$product_id = $order_item->get_product_id();
					$variation_id = $order_item->get_variation_id();
					$get_item_quantity = $order_item->get_quantity();

					$product = ( $variation_id > 0 ) ? wc_get_product( $variation_id ) : wc_get_product( $product_id );
					$set_qty = $product->get_stock_quantity() - (int) $get_item_quantity;
					$product->set_stock_quantity( $set_qty );
					$product->save();
				}
				// Inventry END
			}
			$order->save();
			// Warehouse Start
			// since 2.4.0.1
			$data_send = array();
			$items     = $order->get_items();
			foreach ( $items as $key => $item ) {
				$order_item        = new WC_Order_Item_Product( $key );
				$product_id        = $order_item->get_product_id();
				$variation_id      = $order_item->get_variation_id();
				$get_item_quantity = $order_item->get_quantity();
				$product = ( $variation_id > 0 ) ? wc_get_product( $variation_id ) : wc_get_product( $product_id );
				array_push( $data_send, (object) array(
					'WarehouseId' => $warehouse_id,
					'id'		  => $product->get_id(),
					'quantity'    => esc_attr( get_post_meta( $product->get_id(), '_warehouse_' . $warehouse_id, true ) ),
				));
			}
			if ( empty( $isdefault ) ) {
				$this->oliver_pos_wp_post_warehouse_quantity( $data_send );
			}
			// Warehouse END
			return $this->oliver_pos_get_order( $order_id, null, array() );
		}
	}
    /**
     * Get product details
     * @since 2.3.8.8
     */
    public function oliver_pos_post_productx_data( $object_id, $method ) {
        $product_data = $this->pos_bridge_product->oliver_pos_get_product_data( $object_id );
        wp_remote_post( esc_url_raw( $method ), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
				'Authorization' => AUTHORIZATION,
	            ),
            'body' => json_encode( $product_data ),
        ) );
    }
    /**
     * Get Order id through Oliver pos receipt id.
     * @since 2.3.8.3
     * @Add string Return order details
     */
    public function oliver_pos_get_order_details_by_oliver_receipt_id( $params ) {
        global $wpdb;
        if ( ! isset( $params['receipt_id'] ) ) {
            oliver_log( 'Invalid Request Parameters' );
	        return oliver_pos_api_response('Invalid Request Parameters', -1);
        }
        $params = $params['receipt_id'];
        $receipt_order_id = $wpdb->get_results( $wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value = %s", $params) );
	    if(!empty($receipt_order_id)){
		    $order_id =  $receipt_order_id[0]->post_id;
		    $order_data = $this->oliver_pos_get_order( $order_id, null, array());
		    return $order_data;
	    }
	    else{
		    oliver_log( 'Invalid receipt_id' );
		    return oliver_pos_api_response('Invalid receipt id', -1);
	    }
    }
    /*
    * Warehouse @since 2.4.0.1
    * Return Data Warehose
    */
    public function oliver_pos_get_default_warehouse( $warehouse_id ) {
        global $wpdb;
        $data_warehouse = $wpdb->get_results($wpdb->prepare("SELECT isdefault FROM {$wpdb->prefix}pos_warehouse WHERE oliver_warehouseid = %d", $warehouse_id), OBJECT);
        if(empty($data_warehouse)){
	        oliver_log('no warehouse found in warehouse table');
            //work as defoult
        	return 1;
        }
	    $data = $data_warehouse[0]->isdefault;
	    if( $data == 1 )
	    {
            //work as defoult
		    oliver_log('defoult warehouse found');
		    return 1;
	    }
	    else{
            //work as warehouse
		    oliver_log('warehouse found');
		    return 0;
	    }
    }
    public function oliver_pos_wp_post_warehouse_quantity( $data ) {
        $headers = array(
	        'Authorization' => AUTHORIZATION,
	        'Content-Type'  => 'application/json'
        );
        $fields = array(
            'body' => json_encode(
                array(
                    'udid' => ASP_DOT_NET_UDID,
                    'productInfo' => $data
                )
            ),
            'timeout'   => 0.01,
            'blocking'  => false,
            'sslverify' => false,
            'headers'     => $headers,
            'method'      => 'POST',
            'data_format' => 'body'
        );
        wp_remote_post( esc_url_raw( ASP_TRIGGER_UPDATE_PRODUCT_QUANTITY ), $fields );
    }
	public function oliver_pos_price_measurement ( $item , $item_id ) {

		if ( empty( $item['variation_id'] ) ) {
			$product_id = wc_get_order_item_meta( $item_id, '_product_id', true );
		} else {
			$product_id = wc_get_order_item_meta( $item_id, '_variation_id', true );
		}
		$product = wc_get_product( $product_id );
        if(!empty($product)) {
            $data = get_post_meta($product->get_id(), '_wc_price_calculator', true);
            $inventory = $data['dimension']['pricing']['inventory']['enabled'];
            if ($inventory == 'yes') {
                $set_qty = $product->get_stock_quantity() - $item['pricing_item_meta_data']['_measurement_needed'];
                $product->set_stock_quantity($set_qty);
                wc_update_order_item_meta($item_id, '_reduced_stock', $item['pricing_item_meta_data']['_measurement_needed']);

                wc_update_order_item_meta($item_id, '_qty', $item['pricing_item_meta_data']['_quantity']);
                $product->save();
            }
        }
		wc_update_order_item_meta( $item_id, 'Required Length' . ' ' . ucfirst( ( $item['pricing_item_meta_data']['_measurement_needed_unit'] ) ), $item['pricing_item_meta_data']['_measurement_needed'] );
	}
	public function oliver_pos_set_order_tax_item ( $order_id, $data ) {
		global $wpdb;
		$old_tax_item_ids = array();
		$old_tax_ids = array();
		$tax_items = $wpdb->get_results($wpdb->prepare("SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_id = '%d' AND order_item_type='tax'", $order_id));
		foreach($tax_items as $tax_item){
			oliver_log('tax order_item_id='.$tax_item->order_item_id);
			$rate_id = wc_get_order_item_meta( $tax_item->order_item_id, 'rate_id', true );
			oliver_log('rate_id='.$rate_id);
			$old_tax_ids[] = $rate_id;
			$old_tax_item_ids[$rate_id] = absint($tax_item->order_item_id);
		}
		foreach ( $data['tax_ids'] as $key => $tax_id_value ) {
			$tax_id = key($tax_id_value);
			$tax_amount = $tax_id_value[ $tax_id ];
			if ( in_array( $tax_id, $old_tax_ids ) ) {
				oliver_log('update tax amount for'.$old_tax_item_ids[$tax_id]);
				wc_update_order_item_meta( $old_tax_item_ids[$tax_id], 'tax_amount', $tax_amount );
			} else {
				oliver_log('create new tax item');
				$tax_rate_query = $this->oliver_pos_get_tax_by_id((int) $tax_id);
				$wpdb->insert($wpdb->prefix.'woocommerce_order_items', array(
					'order_item_name' => $tax_rate_query->tax_rate_name,
					'order_item_type' => 'tax',
					'order_id' => $order_id
				));
				$last_item_id = $wpdb->insert_id;
				wc_update_order_item_meta( $last_item_id, 'rate_id', sanitize_text_field($tax_id));
				wc_update_order_item_meta( $last_item_id, 'label', sanitize_text_field(!empty($tax_rate_query->tax_rate_name) ? $tax_rate_query->tax_rate_name : 'Tax'));
				wc_update_order_item_meta( $last_item_id, 'compound', sanitize_text_field(!empty($tax_rate_query) ? $tax_rate_query->tax_rate_compound  : ''));
				wc_update_order_item_meta( $last_item_id, 'tax_amount', sanitize_text_field($tax_amount));
				wc_update_order_item_meta( $last_item_id, 'shipping_tax_amount', 0);
			}
		}
	}
	public function oliver_pos_get_order_old_line_item ( $order_id, $data ) {
		global $wpdb;
		$old_item_ids = array();
		$items = $wpdb->get_results($wpdb->prepare("SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_id = '%d' AND order_item_type='line_item'", $order_id));
		// Since 2.3.8.6

		foreach($items as $item){
			oliver_log('old line item id='.$item->order_item_id);
			$old_item_ids[] = absint($item->order_item_id);
		}

		$new_item_ids = array();
		foreach ($data['line_items'] as $key => $item) {
			oliver_log('new line item id='.$item['line_item_id']);
			$new_item_ids[] = $item['line_item_id'];
		}
		//Remove not exists order item
		foreach ( $old_item_ids as $old_item_id ) {
			if ( ! in_array( $old_item_id, $new_item_ids ) ) {
				wc_delete_order_item( absint( $old_item_id ) );
				oliver_log('delete_id='.$old_item_id);
			}
		}
		update_post_meta( $order_id, '_order_tax', 0 );
		update_post_meta( $order_id, '_order_total', 0 );
		oliver_log( 'order item deleted' );
		return $old_item_ids;
	}
	//Since 2.4.1.0 add back date order
	public function oliver_pos_convert_to_order_date_time($incomming_date, $offset ){
		if($this->oliver_pos_validateDate($incomming_date)==false){
			return null;
		}
		if( empty($offset) || (!empty($offset) && is_int($offset)!=1)){
			//attached system time to date
			$finaldate= new \DateTime($incomming_date);
			return $finaldate->setTimezone(wp_timezone());
		}
		else{
			//$incomming_date = $incomming_date;
			$offset =-330;
			//keep sign
			$sign = $offset < 0 ? "+" : "-";
			//without sign offset
			$abs_offset = intval(abs($offset));
			//get hrs from offset
			$totalHrs = intdiv($abs_offset, 60);
			//create timezone string like +0530 or -0530
			$attachHrs = $sign.($totalHrs < 10 ? "0".$totalHrs : $totalHrs).($abs_offset % 60);
			//concate timezone string to date
			$IncomingFDTOVal = $incomming_date." ".$attachHrs;
			//create datetime obj from string
			$finaldate= new \DateTime($IncomingFDTOVal);
			return $finaldate->setTimezone(wp_timezone());
		}
	}
	//Since 2.4.1.0 add back date order
	public function oliver_pos_validateDate($date, $format = 'Y-m-d\TH:i:s'){
		$d = \DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
    //Since 2.4.1.8 wc add order item
	//return order_item_id
	public function oliver_pos_wc_add_order_item( $order_id, $item_name, $item_type ){
		$order_item_id = wc_add_order_item(
			$order_id,
			array(
				'order_item_name' => $item_name,
				'order_item_type' => $item_type,
			)
		);
		return $order_item_id;
	}
}
