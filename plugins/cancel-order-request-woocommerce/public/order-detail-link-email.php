<?php

class pisol_corw_order_detail_link_in_email{

    function __construct(){
        $position = apply_filters('pisol_cord_order_detail_link_position', 'woocommerce_email_after_order_table');
        add_action( $position, array(__CLASS__,'orderDetailLinkInRegularEmail'), 10000, 4 );
    }
  
    static function orderDetailLinkInRegularEmail( $order, $sent_to_admin, $plain_text, $email ) {
        $add_link_for = pisol_corw_get_option('pi_corw_order_detail_page_link',array('guest'));
        if(empty($add_link_for) || !is_array($add_link_for)) return;

        if ( in_array($email->id, array('customer_completed_order','customer_invoice','customer_on_hold_order', 'customer_processing_order', 'customer_on_hold_order', 'failed_order')) ) {
            
                $msg = self::orderDetailLink($order, __('Click to view order details', 'cancel-order-request-woocommerce'));

                if($order->get_user() === false && in_array('guest', $add_link_for)){
                    echo $msg;
                }elseif($order->get_user() !== false && in_array('registered', $add_link_for)){
                    echo $msg;
                }
        }
    }

    static function orderDetailLink($order, $msg_text = ""){
        if(empty($msg_text)){
            $msg_text = __('Click to view order details', 'cancel-order-request-woocommerce');
        }
        
        $return_url = $order->get_checkout_order_received_url();
        $msg = apply_filters('pisol_corw_view_order_detail_link', 
                            sprintf('<p><a href="%s">%s</a></p>', $return_url, $msg_text),
                            $return_url, $order
                        );
        return $msg;
    }
    
}

new pisol_corw_order_detail_link_in_email();