<?php

class pisol_corw_cancel_request{

    function __construct(){
        add_filter( 'woocommerce_my_account_my_orders_actions', array($this, 'orderCancelRequestButton'),9999,2);

        $position_of_text_link = apply_filters('pisol_cancel_order_request_text_link_position', 'woocommerce_order_details_after_order_table');

        add_action($position_of_text_link , array(__CLASS__, 'orderCancelRequestText'),9999,1);

        add_action('wp_ajax_pi_order_cancel_request_form', array($this, 'getOrderCancelForm'));

        add_action('wp_ajax_nopriv_pi_order_cancel_request_form', array($this, 'getOrderCancelForm'));

        add_action('admin_post_pi_cancellation_request', array($this, 'cancellationRequest'));
        add_action('admin_post_nopriv_pi_cancellation_request', array($this, 'cancellationRequest'));

        add_action('wp_loaded', array($this, 'showMessage'));

         /**
         * This is needed as wc session is not created for non-loged in users
         */
        add_action( 'woocommerce_init',  array($this, 'startSession') );
    }

    function startSession(){
        if(function_exists('WC') && isset(WC()->session)){
            if ( !is_admin() && !WC()->session->has_session() ) {
                WC()->session->set_customer_session_cookie( true );
            }
        }
    } 

    function orderCancelRequestButton($actions, $order){
        
        if(self::allowOrderCancelRequest($order)){
            $order_id = version_compare( WC_VERSION, '3.0.0', '<' ) ? $order->id : $order->get_id();

            $actions['pi_cancel_request_form'] = array(
                    'url'  => admin_url("admin-ajax.php?action=pi_order_cancel_request_form&order_id={$order_id}"),
                    'name' => __( 'Cancel Request', 'cancel-order-request-woocommerce' )
            );

            unset($actions['cancel']);
        }

        return $actions;
    }

    static function allowOrderCancelRequest($order){
        $cancel_request_for = pisol_corw_get_option('pi_corw_order_status_allow_cancel_request', array());

        if(empty($cancel_request_for) || !is_array($cancel_request_for)) return apply_filters('pisol_corw_allow_order_cancellation',false, $order);

        if(!self::orderWithinCancellationTimeLimit($order)) return apply_filters('pisol_corw_allow_order_cancellation',false, $order);

        $order_status = $order->get_status();

        if(in_array($order_status, $cancel_request_for)) return apply_filters('pisol_corw_allow_order_cancellation',true, $order);

        return apply_filters('pisol_corw_allow_order_cancellation',false, $order);
    }

    static function orderWithinCancellationTimeLimit($order){

        $force_hiding_showing = apply_filters('pi_corw_force_hiding_showing_by_time', null, $order);
        if($force_hiding_showing !== null){
            return $force_hiding_showing;
        }

        $hide_after = pisol_corw_get_option('pi_corw_hide_button_after_time', '');

        if(empty($hide_after)) return true;

        $present = current_time('Y/m/d H:i');

        $order_placement_time_obj = $order->get_date_created();

        $order_placement_time = $order_placement_time_obj->date('Y/m/d H:i');

        $cancellation_allowed_till = date('Y/m/d H:i', strtotime($order_placement_time. "+ {$hide_after} minutes"));

        if(strtotime($present) > strtotime($cancellation_allowed_till)) return false;

        return true;
    }

    function getOrderCancelForm(){
        $redirect_url = wp_get_referer() ? wp_get_referer() : get_permalink(get_option('woocommerce_myaccount_page_id'));
        $order_id = filter_input(INPUT_GET, 'order_id');

        $admin_notice = pisol_corw_get_option('pi_corw_order_admin_notice', '');

        $admin_message = apply_filters('pisol_corw_filter_admin_notice_text', $admin_notice, $order_id);

        $predefined_reasons = $this->predefinedReasonRadioButton($order_id);

        $order = wc_get_order($order_id);
        $order_key = $order->get_order_key();
        
        include 'partials/cancel-order-request-form.php';
        die;
    }

    function predefinedReasonArray($order_id){
        $predefined_reasons = pisol_corw_get_option('pi_corw_predefined_reason_for_cancellation','');
        $reasons = "";
        if(!empty($predefined_reasons)){
            $reasons = explode(PHP_EOL, $predefined_reasons);
        }
        return apply_filters('pisol_corw_filter_reasons', $reasons, $order_id);
    }

    function predefinedReasonRadioButton($order_id){
        $reasons = $this->predefinedReasonArray($order_id);

        if(!is_array($reasons) || empty($reasons)) return;

        $html = "";
        foreach($reasons as $reason){
            if(!empty($reason)){
            $html .= sprintf('<label class="pi-cord-reason-label"><input type="radio" name="predefined_reason" value="%s"><span class="pi-cord-reason">%s</span></label>', esc_attr($reason), esc_html($reason));
            }
        }
        return $html;
    }

    function cancellationRequest(){
        $order_id = filter_input(INPUT_POST, 'order_id');
        $reason = filter_input(INPUT_POST, 'order_cancel_reason');

        $redirect = filter_input(INPUT_POST, 'redirect_url');

        $predefined_reason = filter_input(INPUT_POST, 'predefined_reason');

        if(!self::reasonDescriptionGiven($reason, $predefined_reason)){
            $data['pi_corw_msg']['error'] = sprintf(__('Please specify a order cancellation reason for order no. #%s', 'cancel-order-request-woocommerce'), $order_id);

            if(function_exists('WC')){
                WC()->session = new WC_Session_Handler();
                WC()->session->init();
                WC()->session->set( 'pi_crow_data', $data);
            }
           
            wp_safe_redirect($redirect);
            return;
        }

        if(self::userCanRequestCancellation($order_id)){
            self::recordCancellationRequest($order_id, $reason, $predefined_reason);
            wp_safe_redirect($redirect);
        }else{
            wp_die(__('You do not have permissions to request cancellation for this order.', 'cancel-order-request-woocommerce'), '', array('response' => 403));
        }
    }

    function showMessage(){
        
        if(function_exists('WC')){
            /**
             * don't want to run this WC_Session_Handler() during login as it causes issue in session transfer 
             * Have commented it out 
             */
            /*
            if(!isset(WC()->session) || !WC()->session->has_session()){
                WC()->session = new WC_Session_Handler();
                WC()->session->init();
            }
            */
            
            if(!isset(WC()->session)) return;

            $session_data = WC()->session->get('pi_crow_data');
            if(isset($session_data['pi_corw_msg']) && !empty($session_data['pi_corw_msg'])){
                foreach($session_data['pi_corw_msg'] as $type => $msg){
                    if(function_exists('wc_add_notice')){
                        wc_add_notice($msg, $type);
                    }
                }
                WC()->session->__unset('pi_crow_data');
            }
        }
    }

    static function reasonDescriptionGiven($reason, $predefined_reason){
        $desc_is_required = get_option('pi_corw_make_reason_required', 0);
        if(!$desc_is_required) return true;

        if(!empty($reason) || !empty($predefined_reason)) return true;
        
        return false;

    }

    static function userCanRequestCancellation($order_id){
        $order = wc_get_order($order_id);

        // if it is guest order then we dont check anything and directly allow cancellation
        if($order->get_user() === false && self::allowOrderCancelRequest($order)){
            if(isset($_POST['order_key']) && $_POST['order_key'] == $order->get_order_key()) return true; 
        }

        if(!self::currentCustomerOrder($order)) return false;
        
        if(self::allowOrderCancelRequest($order)) return true;

        return false;
    }

    static function currentCustomerOrder($order){
        if(!is_user_logged_in()) return false;
        
        $user = wp_get_current_user();
        $current_user_id = isset( $user->ID ) ? (int) $user->ID : 0;

        $order_user_id = $order->get_user_id();
        if($current_user_id == $order_user_id) return true;

        return false;
    }

    static function recordCancellationRequest($order_id, $reason, $predefined_reason){
        $order = wc_get_order($order_id);
        $order->update_meta_data( 'order_cancel_reason', $reason);
        $order->update_meta_data( 'cancellation_date', current_time('Y/m/d H:i'));
        $order->update_meta_data( 'predefined_reason', $predefined_reason);

        /**
         * Adding to order note
         */
        self::addCancelReasonInOrderNote($order_id, $reason, $predefined_reason);

        /** this will allow us to directly cancel the order instead of waiting for admin approval */
        $new_status = apply_filters('pisol_corw_cancel_request_new_status','cancel-request' );
        $order->update_status($new_status);
        if(function_exists('WC')){
            WC()->session = new WC_Session_Handler();
            WC()->session->init();
            $data['pi_corw_msg']['success'] = sprintf(__('Order cancellation request submitted for order no. #%s', 'cancel-order-request-woocommerce'),$order_id);
            WC()->session->set( 'pi_crow_data', $data);
        }
    }

    static function addCancelReasonInOrderNote($order_id, $reason, $predefined_reason){
       
        $notes = array();
        if(!empty($reason)){
            $notes[] = __('Cancellation reason: ','cancel-order-request-woocommerce').'<br>'.$reason;
        }
            
        if(!empty($predefined_reason)){
            $notes[] = __('Selected reason for cancellation: ','cancel-order-request-woocommerce').'<br>'.$predefined_reason;
        }
        
        $order = wc_get_order($order_id);

        $notes = apply_filters('pisol_corw_order_note_filter',$notes, $order_id, $reason, $predefined_reason); 

        if(!empty($notes)){
            if(is_array($notes)){
                foreach($notes as $note){
                    $order->add_order_note( $note );
                }
            }else{
                $order->add_order_note( $notes );
            }
        }
    }

    static function orderCancelRequestText($order){
        
        if(self::allowOrderCancelRequest($order)){
            $order_id = version_compare( WC_VERSION, '3.0.0', '<' ) ? $order->id : $order->get_id();

            $url = admin_url("admin-ajax.php?action=pi_order_cancel_request_form&order_id={$order_id}");
            $order_status = $order->get_status();
            echo '<span class="pi-corw-text">'.__('Want to cancel this order? ', 'cancel-order-request-woocommerce').'</span>';
            echo '<a href="'.esc_url($url).'" class="pi_cancel_request_form">';
            _e('Click here','cancel-order-request-woocommerce');
            echo '</a>';
        }else{
            $order_status = $order->get_status();
            if($order_status == 'cancel-request'){
                _e('Your order cancellation request is submitted', 'cancel-order-request-woocommerce');
            }
        }
    }

}

new pisol_corw_cancel_request();