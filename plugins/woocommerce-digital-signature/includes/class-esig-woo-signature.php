<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class esig_woo_logic {

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->esig_sad = new esig_woocommerce_sad();
    }

    public static function is_product_logic($product_id, $is_true) {
        $logic = get_post_meta($product_id, '_esign_woo_sign_logic', true);
        if ($logic === $is_true) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_global_logic() {
        return get_option('esign_woo_logic');
    }

    public static function is_signature_required($product_id) {
        $product_agreement = get_post_meta($product_id, '_esig_woo_meta_product_agreement', true);

        if ($product_agreement) {
            return true;
        }
        return false;
    }

    public static function get_agreement_id($product_id) {
        $sad_page_id = get_post_meta($product_id, '_esig_woo_meta_sad_page', true);
        if($sad_page_id == "pleaseslc")
        {
            return false;
        }
        // check esignature stand alone document class exists
        if(!class_exists("esig_sad_document"))
        {
            return false;
        }
        
        $sad = new esig_sad_document();
        return $sad->get_sad_id($sad_page_id);
    }

    public static function get_sad_page_id($agreement_id) {
        $sad = new esig_sad_document();
        return $sad->get_sad_page_id($agreement_id);
    }

    public static function get_agreement_logic($product_id) {
        return get_post_meta($product_id, '_esign_woo_sign_logic', true);
    }

    public static function make_agreement_signed($cart_item_key, $document_id) {
        WC()->cart->cart_contents[$cart_item_key][ESIG_WOOCOMMERCE_Admin::PRODUCT_AGREEMENT]['signed'] = 'yes';
        WC()->cart->cart_contents[$cart_item_key][ESIG_WOOCOMMERCE_Admin::PRODUCT_AGREEMENT]['document_id'] = $document_id;
        WC()->cart->set_session();
    }

    public static function make_global_agreement_signed($document_id) {

        if (is_null(WC()->cart)) {
            return false;
        }

        if (WC()->cart->get_cart_contents_count() > 0) {

            foreach (WC()->cart->get_cart() as $cart_key => $cart_item) {
                if (isset($cart_item[ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT])) {
                    // $return= $cart_item[ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT];

                    WC()->cart->cart_contents[$cart_key][ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT]['document_id'] = $document_id;
                    WC()->cart->cart_contents[$cart_key][ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT]['signed'] = "yes";
                    WC()->cart->set_session();
                }
            }
        }

        //WC()->session->set(ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT, $agreements);
    }

    public static function is_global_agreement_enabled() {
        $esig_woo_agreement = get_option('esign_woo_agreement_setting');
        if ($esig_woo_agreement == "yes") {
            return true;
        }
        return false;
    }

    public static function get_global_agreement_id() {
        if (!class_exists('esig_sad_document')) {
            return false;
        }
        $esign_woo_sad_page = get_option('esign_woo_sad_page');
        $sad = new esig_sad_document();
        return $sad->get_sad_id($esign_woo_sad_page);
    }

    public static function set_global_agreement() {

        $isSadPageSelected  = get_option('esign_woo_sad_page');
        if($isSadPageSelected == "pleaseslc")
        {
            return false ; 
        }

        $global_id = self::get_global_agreement_id();

        $array = array(
            'agreement_id' => $global_id,
            'agreement_logic' => self::get_global_logic(),
            'signed' => 'no',
        );

        return $array;

        /* $global_agreement = WC()->session->get(ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT);
          if (!isset($global_agreement)) {
          $array = array(
          'agreement_id' => self::get_global_agreement_id(),
          'agreement_logic' => self::get_global_logic(),
          'signed' => 'no',
          );
          // WC()->session->set(ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT, $array);
          return $array;
          } else {
          $global_id = self::get_global_agreement_id();
          if (isset($global_agreement) && $global_agreement['agreement_id'] != $global_id) {
          $array = array(
          'agreement_id' => $global_id,
          'agreement_logic' => self::get_global_logic(),
          'signed' => 'no',
          );
          // WC()->session->set(ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT, $array);
          return $array;
          }
          } */
    }

    public static function get_global_agreement() {

        if (is_null(WC()->cart)) {
            return false;
        }
        $return = false;

        if (WC()->cart->get_cart_contents_count() > 0) {

            foreach (WC()->cart->get_cart() as $cart_item) {
                if (isset($cart_item[ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT])) {
                    $return = $cart_item[ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT];
                }
            }
        }
        /* $global_agreement = WC()->session->get(ESIG_WOOCOMMERCE_Admin::GLOBAL_AGREEMENT);
          if ($global_agreement) {
          return $global_agreement;
          } */

        return $return;
    }
    /**
     *  Get agreement id from a woocommrece session 
     * @return  array 
     */
    public static function get_global_doc_id_from_session($is_true) 
    {
        $global_settings = self::get_global_agreement();

        if (!empty($global_settings) && isset($global_settings) && is_array($global_settings)) 
        {
            if ($global_settings['signed'] == 'no' && $global_settings['agreement_logic'] === $is_true) {
                return $global_settings['agreement_id'];
            }
        }

        return false;
    }

    public static function save_temp_order_id($order_id) {
        if (is_null(WC()->session)) {
            return false;
        }
        WC()->session->set(ESIG_WOOCOMMERCE_Admin::TEMP_ORDER_ID, $order_id);
    }

    public static function get_temp_order_id() {
        if (is_null(WC()->session)) {
            return false;
        }
        $order_id = WC()->session->get(ESIG_WOOCOMMERCE_Admin::TEMP_ORDER_ID);
        if ($order_id) {
            return $order_id;
        }
        return false;
    }

    public static function save_document_meta($document_id, $order_id) {
        WP_E_Sig()->meta->add($document_id, 'esig-order_id', $order_id);
    }

    public static function checkData($data) {

        if (is_string($data)) {
            // maybe json?
            $is_json = json_decode($data, ARRAY_A);
            if (!empty($is_json) && is_array($is_json)) {
                $docList = $is_json;
            } else {
                $docList = esc_html(stripslashes_deep($field_value));
            }
        }
        if (is_array($data)) {
            return $data;
        }
    }

    public static function save_after_checkout_doc_list($order_id, $doc_list) {
        $orderId = self::orderIdValid($order_id);
        $docList = self::checkData($doc_list);
        update_post_meta($orderId, '_esig_after_checkout_doc_list', json_encode($docList));
    }

    public static function get_after_checkout_doc_list($order_id) {
        $orderId = self::orderIdValid($order_id);
        $listDoc = str_replace("\\", "", get_post_meta($orderId, '_esig_after_checkout_doc_list', true));
        $doc_list = json_decode(stripslashes_deep($listDoc), true);

        //$doc_list = json_decode(get_post_meta($order_id, '_esig_after_checkout_doc_list', true),true);
        return $doc_list;
    }

    public static function update_after_checkout_doc_list($order_id, $sad_doc_id, $document_id) {
        $orderId = self::orderIdValid($order_id);
        $doc_list = self::get_after_checkout_doc_list($orderId);

        $doc_list[$sad_doc_id] = 'yes';
        self::save_after_checkout_doc_list($orderId, $doc_list);
        self::save_document_meta($document_id, $orderId);
    }

    public static function is_after_checkout_enable($order_id) {
        $docList = self::get_after_checkout_doc_list($order_id);

        if (is_array($docList)) {
            return true;
        } else {
            return false;
        }
    }

    public static function save_after_checkout_order_id($order_id) {
        $orderId = self::orderIdValid($order_id);
        esig_setcookie('esig-aftercheckout-order-id', $orderId, 60 * 60 * 1);
    }

    public static function get_after_checkout_order_id() {
        if (ESIG_COOKIE('esig-aftercheckout-order-id')) {
            return ESIG_COOKIE('esig-aftercheckout-order-id');
        }
        return false;
    }

    public static function remove_after_checkout_order_id() {
        esig_unsetcookie('esig-aftercheckout-order-id', COOKIEPATH);
    }

    public static function orderDetails($orderId) {
        
         if ( !get_post_status ( $orderId ) ) {
               return false;
           }
         
        $order = new WC_Order($orderId);
       
        //$date_created = $order->get_date_created();

        $coupons_list = '';

        $coupon_used = $order->get_coupon_codes();
        if (is_array($coupon_used)) {
            $coupons_count = count($coupon_used);
            $i = 0;
            foreach ($coupon_used as $coupon) {
                $coupons_list .= $coupon;

                if ($i < $coupons_count)
                    $coupons_list .= ',';
                $i++;
            }
            $coupons_list = rtrim($coupons_list, ",");
        }


      
        $result = array(
            "billing_address_1" => $order->get_billing_address_1(),
            "billing_address_2" => $order->get_billing_address_2(),
            "billing_city" => $order->get_billing_city(),
            "billing_company" => $order->get_billing_company(),
            "billing_country" => $order->get_billing_country(),
            "billing_email" => $order->get_billing_email(),
            "billing_first_name" => $order->get_billing_first_name(),
            "billing_last_name" => $order->get_billing_last_name(),
            "billing_phone" => $order->get_billing_phone(),
            "billing_postcode" => $order->get_billing_postcode(),
            "billing_state" => $order->get_billing_state(),
            "cart_discount" => $order->get_discount_total(),
            "cart_discount_tax" => wc_price($order->get_cart_tax()),
            "customer_ip_address" => $order->get_customer_ip_address(),
            //"customer_message" => $order->get_customer_,
            "customer_note" => $order->get_customer_note(),
            //"customer_user"=>$order->customer_user,
            "customer_user_agent" => $order->get_customer_user_agent(),
            "display_cart_ex_tax" => $order->get_discount_to_display(),
            "display_totals_ex_tax" => wc_price($order->get_total_tax()),
            "order_id" => $order->get_order_number(),
            "order_currency" => $order->get_currency(),
            "order_date" => $order->get_date_completed(),
            "order_create_date" => WP_E_Sig()->document->esig_date_format($order->get_date_created()),
            "order_paid_date" => $order->get_date_paid(),
            "order_discount" => $order->get_total_discount(),
            "order_key" => $order->get_order_key(),
            "order_shipping" => $order->get_shipping_method(),
            "order_shipping_tax" => wc_price($order->get_shipping_tax()),
            "order_tax" => wc_price($order->get_total_tax()),
            "order_subtotal" => wc_price($order->get_subtotal()),
            "order_total" => wc_price($order->get_total()),
            "order_type" => $order->get_type(),
            "products_name_in_cart" => self::getProductsName($order,'name'),
            "products_quantity_in_cart" => self::getProductsName($order,'quantity'),
            "coupon_code" => $coupons_list,
            "payment_method" => $order->get_payment_method(),
            "payment_method_title" => $order->get_payment_method_title(),
            "shipping_address_1" => $order->get_shipping_address_1(),
            "shipping_address_2" => $order->get_shipping_address_2(),
            "shipping_city" => $order->get_shipping_city(),
            "shipping_company" => $order->get_shipping_company(),
            "shipping_country" => $order->get_shipping_country(),
            "shipping_first_name" => $order->get_shipping_first_name(),
            "shipping_last_name" => $order->get_shipping_last_name(),
            "shipping_method_title" => $order->get_shipping_method(),
            "shipping_postcode" => $order->get_shipping_postcode(),
            "shipping_state" => $order->get_shipping_state(),
        );
        // customer wordpress user details 
        if ($order->get_customer_id()) {
            $wpUser = get_userdata($order->get_customer_id());
            $result['customer_wp_username'] = $wpUser->user_login;
            $result['customer_wp_user_displayname'] = $wpUser->display_name;
            $result['customer_wp_user_email'] = $wpUser->user_email;
            $result['customer_wp_user_nicename'] = $wpUser->user_nicename;
            $result['customer_wp_user_firstname'] = $wpUser->first_name;
            $result['customer_wp_user_lastname'] = $wpUser->last_name;
        }

        // order product details . 
        $items = $order->get_items();
        if ($items) {

            foreach ($items as $itemId => $itemData) {

                $result['product_' . $itemData['product_id'] . '_name'] = $itemData['name'];
                $result['product_' . $itemData['product_id'] . '_quantity'] = $itemData['quantity'];
            }
        }


        $data = $order->get_meta_data();
        if (is_array($data)) {
            foreach ($data as $obj) {
                if (is_object($obj)) {
                    $result[$obj->key] = $obj->value;
                }
            }
        }


        return $result;
    }

    public static function getProductsName($order,$type) {

        $items = $order->get_items();        
        if ($items) {
            $itemName = '';
            $itemQuantity = '';
            foreach ($items as $itemId => $itemData) {
                $itemName .= $itemData['name'] . ', ';
                $itemQuantity .= $itemData['quantity'] . ', ';
            }

            if($type == 'name'){
                return substr($itemName, 0, -2);
            }elseif($type == 'quantity'){
                return substr($itemQuantity, 0, -2);
            }
            
        }        

    }

    public static function clone_document($sadDocId, $orderId) {

        if (!$orderId) {
            return false;
        }



        $old_doc = WP_E_Sig()->document->getDocument($sadDocId);

        if (empty($old_doc)) {
            return false;
        }
        if ($old_doc->document_status == "trash") {
            return false;
        }

        global $esig_woo_order_id;
        $esig_woo_order_id = $orderId;

        $docId = WP_E_Sig()->document->copy($sadDocId);
        WP_E_Sig()->meta->add($docId, "form-integration", "woo");
        
        $old_doc_timezone = WP_E_Sig()->document->esig_get_document_timezone($sadDocId);
        // save new doc timezone 
        WP_E_Sig()->meta->add($docId, 'esig-timezone-document', $old_doc_timezone);
        // Create the user

        $order = new WC_Order($orderId);
        $customerName = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
        $yith_quote_agreement = get_post_meta($orderId, 'yith_quote_agreement_generated', true);
        $customerEmail = (class_exists('YITH_Request_Quote') && !empty($yith_quote_agreement)) ? yit_get_prop($order, 'ywraq_customer_email', true) : $order->get_billing_email();
        $recipient = array(
            "user_email" => $customerEmail,
            "first_name" => $customerName,
            "document_id" => $docId,
            "last_name" => '',
            "company_name" => ''
        );

        $recipient['id'] = WP_E_Sig()->user->insert($recipient);

        $data = esig_woo_logic::orderDetails($orderId);

        $newDocTitle = WP_E_View::instance()->replace_variable($old_doc->document_title, $data)  . ' - ' . $recipient['first_name'];

        // Update the doc title
        WP_E_Sig()->document->updateTitle($docId, $newDocTitle);

        $doc = WP_E_Sig()->document->getDocument($docId);

        // Get Owner
        $owner = WP_E_Sig()->user->getUserByID($doc->user_id);

        // Create the invitation?
        $invitation = array(
            "recipient_id" => $recipient['id'],
            "recipient_email" => $recipient['user_email'],
            "recipient_name" => $recipient['first_name'],
            "document_id" => $docId,
            "document_title" => $doc->document_title,
            "sender_name" => $owner->first_name . ' ' . $owner->last_name,
            "sender_email" => esig_woocommerce_get("user_email", $owner),
            "sender_id" => 'stand alone',
            "document_checksum" => $doc->document_checksum,
            "sad_doc_id" => $sadDocId,
        );

        $invite_controller = new WP_E_invitationsController;
        $invitation_id = $invite_controller->save($invitation);
        $invite_hash = WP_E_Sig()->invite->getInviteHash($invitation_id);

        WP_E_Sig()->document->updateStatus($docId, "awaiting");
        WP_E_Sig()->document->updateType($docId, "normal");

        WP_E_Sig()->meta->add($docId, 'esig-woo-document-checksum', $doc->document_checksum);
        WP_E_Sig()->meta->add($docId, 'esig-woo-invite-hash', $invite_hash);
        WP_E_Sig()->meta->add($docId, 'esig-order_id', $orderId);

        // trigger an action after document save .
        do_action('esig_sad_document_invite_send', array(
            'document' => $doc,
            'old_doc_id' => $sadDocId,
            'signer_id' => $recipient['id'],
        ));

        return $docId;
    }

    public static function get_after_checkout_condition() {
        $after_checkout = get_option("esign_woo_logic");
        if ($after_checkout == "after_checkout") {
            $checkout_logic = get_option("esign_woo_after_checkout_logic");
            $condition_logic = ($checkout_logic) ? $checkout_logic : "on-hold";
            return $condition_logic;
        }
        return "on-hold";
    }

    public static function inviteLinkAfterCheckout($docId) {

        $docCheckSum = WP_E_Sig()->meta->get($docId, 'esig-woo-document-checksum');
        $inviteHash = WP_E_Sig()->meta->get($docId, 'esig-woo-invite-hash');
        if (empty($docCheckSum) && empty($inviteHash)) {
            return false;
        }
        $inviteUrl = WP_E_Sig()->invite->get_invite_url($inviteHash, $docCheckSum);
        return $inviteUrl;
    }

    /*     * *
     *   Feature for Plugin Name: YITH WooCommerce Request A Quote Premium 
     *   @since 1.5.5.5
     */

    public static function yith_quote_agreement($order_id, $logic_type) {

        $yith_quote_agreement =   get_post_meta($order_id,'yith_quote_agreement_generated',true);
        
        if($yith_quote_agreement == "yes"){
            return false;
        }
       
        $yith_agreement_list = array();
        $order = wc_get_order($order_id);
      
        $items = $order->get_items();
        foreach ($items as $item) {

            $product_id = $item->get_product_id();
            if (esig_woo_logic::is_signature_required($product_id)) {

                $agreement_id = esig_woo_logic::get_agreement_id($product_id);
               
                $agreement_logic = esig_woo_logic::get_agreement_logic($product_id);
               
                if ($agreement_logic == "after_checkout") {
                    $agreement_id = esig_woo_logic::clone_document($agreement_id, $order_id);
                    $yith_agreement_list[$agreement_id] = 'no';
                }
            }
        }
  //print_r($yith_agreement_list );
       
        $global_esig_logic = esig_woo_logic::get_global_logic();
        if (esig_woo_logic::is_global_agreement_enabled() && $global_esig_logic == "after_checkout") {
            $global_agreement_id = esig_woo_logic::get_global_agreement_id();
            $agreement_id = esig_woo_logic::clone_document($global_agreement_id, $order_id);
            $yith_agreement_list[$agreement_id] = 'no';
            // update_post_meta($order_id,'have_esig_agreement',esig_woo_logic::get_global_agreement_id());
        }

        if (!empty($yith_agreement_list)) {
            update_post_meta($order_id, 'yith_quote_agreement_generated', 'yes');
            self::save_after_checkout_doc_list($order_id, $yith_agreement_list);
        }

        /* $esig_agreement_id =   get_post_meta($order_id,'have_esig_agreement',true); 
          $esig_woo_logic_type = get_post_meta($order_id,'esig_agreement_type',true);
          $yith_quote_agreement =   get_post_meta($order_id,'yith_quote_agreement_generated',true);
          if(empty($yith_quote_agreement) && !empty($esig_agreement_id) && is_numeric($esig_agreement_id) && $esig_woo_logic_type==$logic_type){
          $agreement_id = esig_woo_logic::clone_document($esig_agreement_id, $order_id);
          $agreement_list= array($agreement_id => 'no');
          update_post_meta($order_id,'yith_quote_agreement_generated','yes');
          self::save_after_checkout_doc_list($order_id, $agreement_list);
          } */
    }
    
    public static function esigget($name, $array = null) {

            if (!isset($array) && function_exists('ESIG_GET')) {
                return ESIG_GET($name);
            }

            if (is_array($array)) {
                if (isset($array[$name])) {
                    return wp_unslash($array[$name]);
                }
                return false;
            }

            if (is_object($array)) {
                if (isset($array->$name)) {
                    return wp_unslash($array->$name);
                }
                return false;
            }

            return false;
        }

        /**
         *  return true if woocomerce order id has an agreement with after checkout logic  
         * @return bolean  
         */

         public static function orderHasAgreement($orderId)
         {
                  $hasAgreement =  get_post_meta($orderId, 'has_esig_agreement',true); 
                  if($hasAgreement)
                  {
                      update_option("rupom","working");
                      return true;
                  }
       
                  return false ; 
         }

         /**
          * Check for a subscription plugin exists 
          * @return bolean 
          */
         public static function subscriptionPluginExists()
         {
       
             // check YIth subscription plugin 
             if(function_exists("YITH_WC_Subscription"))
             {
                 return true; 
             }
             // check for WooCommerce subscription plugin  
             if (class_exists('WC_Subscriptions'))
             {
                 return true;
             }

             return false;

         }

         public static function renewalContractAllowed()
         {
            
            // check for renewal agreement enabled of not . 
            $subscriptionAllowed= get_option("esign_woo_agreement_in_product_renewal");
            
            if(self::subscriptionPluginExists() && $subscriptionAllowed != "no")
            {
                return true ; 
            }
       
            return false ; 
         }

         public static function isRenewal()
         {
            $contains_renewal = false;

            if (!empty(WC()->cart->cart_contents)) {
                foreach (WC()->cart->cart_contents as $cart_item) {
                    if (isset($cart_item['subscription_renewal'])) {
                        $contains_renewal = $cart_item;
                        break;
                    }
                }
            }

            if($contains_renewal) return true;   

         }

         /**
          * Check for order id valid. Becuase some plugin change order id like woocommerce deposit plugin
          *@return bool
          */

         public static function orderIdValid($orderId)
         {
                $post = get_post($orderId);
                
                if($post->post_type == "shop_order")
                {
                    return $orderId;
                } else {
                    
                    if($post->post_parent) 
                    {
                        $post = get_post($post->post_parent);
                        return $post->ID ; 
                    }
                }

                return $orderId;
         }

}
