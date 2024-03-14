<?php

class pisol_corw_detect_status_change{

    function __construct(){
        add_action('woocommerce_order_status_changed', array($this, 'orderStatusChange'),10,4);
    }

    function orderStatusChange($order_id, $previous_status, $new_status, $order){

        if($new_status == 'cancel-request'){
            self::orderCancellationRequestEmailToAdmin($order_id);
            self::orderCancellationRequestReceivedConfermationEmail($order_id);
        }

        if($previous_status == 'cancel-request' && $new_status == 'cancelled'){
            self::orderCancelledEmail($order_id);
        }

        if($previous_status == 'cancel-request' && ($new_status == 'processing' || $new_status == 'completed')){
            if(apply_filters('pi_cord_disable_request_rejected_email', false, $order_id, $previous_status, $new_status)){
                return;
            }
            self::orderCancellationRequestRejected($order_id);
        }
    }

    static function orderCancellationRequestEmailToAdmin($order_id){
        $mails = WC()->mailer()->get_emails();
        $mails['WC_Email_Admin_Order_Cancel_Request']->trigger($order_id);
    }

    static function orderCancellationRequestReceivedConfermationEmail($order_id){
        $mails = WC()->mailer()->get_emails();
        $mails['WC_Email_Customer_Order_Cancel_Request']->trigger($order_id);
    }

    static function orderCancelledEmail($order_id){
        $mails = WC()->mailer()->get_emails();
        $mails['WC_Email_Customer_Order_Cancelled']->trigger($order_id);
        
        /**
         * Admin order canceled email is only send when status changes from processing or on hold to canceled state, but since we want it to be send even when status changes from cancel request to cancel we are adding this code
         */
        $mails['WC_Email_Cancelled_Order']->trigger($order_id);
    }

    static function orderCancellationRequestRejected($order_id){
        $mails = WC()->mailer()->get_emails();
        $mails['WC_Email_Customer_Order_Cancellation_Request_Rejected']->trigger($order_id);
    }
}

new pisol_corw_detect_status_change();